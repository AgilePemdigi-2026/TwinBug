<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\DetectionStatus;
use App\Enums\OcrStatus;
use App\Jobs\AnalyzeBugReport;
use App\Jobs\ExtractScreenshotText;
use App\Models\Application;
use App\Models\BugReport;
use App\Models\User;
use App\Services\DuplicateDetectionClient;
use App\Services\ScreenshotAnalysisClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class OcrTest extends TestCase
{
    use RefreshDatabase;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';
    private const TEXT_CONTENT = 'this is not an image file';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        config([
            'services.ml.url' => 'http://ml.test',
            'services.ml.key' => null,
            'services.ml.api_key' => null,
            'services.ml.timeout' => 60,
            'services.ml.top_k' => 5,
            'services.ml.max_candidates' => 500,
            'services.ml.model' => 'paraphrase-multilingual-MiniLM-L12-v2',
            'services.vision.url' => 'http://vision.test/v1/chat/completions',
            'services.vision.key' => null,
            'services.vision.model' => 'gpt-4o-mini',
            'services.vision.timeout' => 30,
        ]);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createBugReport(Application $application, array $overrides = []): BugReport
    {
        return BugReport::create(array_merge([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Checkout gagal',
            'description' => 'Tombol checkout tidak merespons setelah diklik.',
            'severity' => BugSeverity::Medium->value,
            'detection_status' => 'pending',
        ], $overrides));
    }

    /* ------------------------------------------------------------------
     * DuplicateDetectionClient::extractText tests
     * ------------------------------------------------------------------ */

    public function test_extract_text_sends_file_as_multipart_and_returns_text(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function () {
            return Http::response(['text' => 'Teks hasil OCR', 'lines' => 3, 'truncated' => false], 200);
        });

        $client = DuplicateDetectionClient::fromConfig();
        $text = $client->extractText('screenshots/checkout.png');

        $this->assertSame('Teks hasil OCR', $text);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'ml.test/ocr')
                && $request->hasFile('file')
                && ! $request->header('X-API-Key');
        });
    }

    public function test_extract_text_sends_x_api_key_when_configured(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        config(['services.ml.api_key' => 'secret-key']);

        Http::fake(function () {
            return Http::response(['text' => 'OCR teks'], 200);
        });

        $client = DuplicateDetectionClient::fromConfig();
        $client->extractText('screenshots/checkout.png');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'ml.test/ocr')
                && $request->header('X-API-Key') !== null;
        });
    }

    public function test_extract_text_does_not_send_x_api_key_when_empty(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        config(['services.ml.api_key' => null]);

        Http::fake(function () {
            return Http::response(['text' => 'OCR teks'], 200);
        });

        $client = DuplicateDetectionClient::fromConfig();
        $client->extractText('screenshots/checkout.png');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'ml.test/ocr')
                && ! $request->header('X-API-Key');
        });
    }

    public function test_extract_text_throws_on_415(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function () {
            return Http::response(['error' => 'unsupported media type'], 415);
        });

        $client = DuplicateDetectionClient::fromConfig();

        $this->expectException(\RuntimeException::class);
        $client->extractText('screenshots/checkout.png');
    }

    public function test_extract_text_throws_on_413(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function () {
            return Http::response(['error' => 'file too large'], 413);
        });

        $client = DuplicateDetectionClient::fromConfig();

        $this->expectException(\RuntimeException::class);
        $client->extractText('screenshots/checkout.png');
    }

    public function test_extract_text_throws_on_401(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function () {
            return Http::response(['error' => 'unauthorized'], 401);
        });

        $client = DuplicateDetectionClient::fromConfig();

        $this->expectException(\RuntimeException::class);
        $client->extractText('screenshots/checkout.png');
    }

    public function test_extract_text_throws_when_file_missing(): void
    {
        $client = DuplicateDetectionClient::fromConfig();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessageMatches('/OCR source file not found/');
        $client->extractText('screenshots/missing.png');
    }

    /* ------------------------------------------------------------------
     * ExtractScreenshotText job tests
     * ------------------------------------------------------------------ */

    public function test_ocr_job_completed_when_success(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function () {
            return Http::response(['text' => 'Teks diekstrak', 'lines' => 2, 'truncated' => false], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
            'ocr_status' => OcrStatus::Pending->value,
        ]);

        $job = new ExtractScreenshotText($report);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $report->refresh();

        $this->assertSame(OcrStatus::Completed, $report->ocr_status);
        $this->assertSame('Teks diekstrak', $report->ocr_text);
        $this->assertNull($report->ocr_error);
    }

    public function test_ocr_job_skipped_when_no_screenshot(): void
    {
        Http::fake(function () {
            return Http::response(['text' => 'should not be called'], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'ocr_status' => OcrStatus::Pending->value,
        ]);

        $job = new ExtractScreenshotText($report);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $report->refresh();

        $this->assertSame(OcrStatus::Skipped, $report->ocr_status);
        $this->assertNull($report->ocr_error);
        Http::assertNotSent(fn ($request) => str_contains($request->url(), 'ml.test/ocr'));
    }

    public function test_ocr_job_skipped_when_file_missing(): void
    {
        Http::fake(function () {
            return Http::response(['text' => 'should not be called'], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/missing.png',
            'ocr_status' => OcrStatus::Pending->value,
        ]);

        $job = new ExtractScreenshotText($report);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $report->refresh();

        $this->assertSame(OcrStatus::Skipped, $report->ocr_status);
        $this->assertNull($report->ocr_error);
    }

    public function test_ocr_job_skipped_when_non_image_file(): void
    {
        Storage::disk('public')->put('screenshots/document.txt', self::TEXT_CONTENT);

        Http::fake(function () {
            return Http::response(['text' => 'should not be called'], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/document.txt',
            'ocr_status' => OcrStatus::Pending->value,
        ]);

        $job = new ExtractScreenshotText($report);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $report->refresh();

        $this->assertSame(OcrStatus::Skipped, $report->ocr_status);
        $this->assertNull($report->ocr_error);
    }

    public function test_ocr_job_failed_when_ml_error(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function () {
            return Http::response(['error' => 'ocr service error'], 500);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
            'ocr_status' => OcrStatus::Pending->value,
        ]);

        $job = new ExtractScreenshotText($report);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $report->refresh();

        $this->assertSame(OcrStatus::Failed, $report->ocr_status);
        $this->assertNotNull($report->ocr_error);
    }

    public function test_ocr_failure_does_not_block_detection(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function ($request) {
            if (str_contains($request->url(), 'ml.test/ocr')) {
                return Http::response(['error' => 'ocr failed'], 500);
            }

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
            'ocr_status' => OcrStatus::Pending->value,
            'detection_status' => DetectionStatus::Pending->value,
        ]);

        $job = new AnalyzeBugReport($report);
        $job->handle(DuplicateDetectionClient::fromConfig(), ScreenshotAnalysisClient::fromConfig());

        $report->refresh();

        // OCR failed but detection still completed
        $this->assertSame(OcrStatus::Failed, $report->ocr_status);
        $this->assertSame(DetectionStatus::Completed, $report->detection_status);
    }

    public function test_ocr_skipped_when_already_completed(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        $ocrCalled = false;
        Http::fake(function ($request) use (&$ocrCalled) {
            if (str_contains($request->url(), 'ml.test/ocr')) {
                $ocrCalled = true;
            }

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
            'ocr_status' => OcrStatus::Completed->value,
            'ocr_text' => 'Teks OCR yang sudah ada.',
            'detection_status' => DetectionStatus::Pending->value,
        ]);

        $job = new AnalyzeBugReport($report);
        $job->handle(DuplicateDetectionClient::fromConfig(), ScreenshotAnalysisClient::fromConfig());

        $report->refresh();

        $this->assertFalse($ocrCalled, 'OCR should not be re-run when already completed');
        $this->assertSame(DetectionStatus::Completed, $report->detection_status);
        $this->assertSame('Teks OCR yang sudah ada.', $report->ocr_text);
    }

    public function test_ocr_skipped_when_already_skipped(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        $ocrCalled = false;
        Http::fake(function ($request) use (&$ocrCalled) {
            if (str_contains($request->url(), 'ml.test/ocr')) {
                $ocrCalled = true;
            }

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
            'ocr_status' => OcrStatus::Skipped->value,
            'detection_status' => DetectionStatus::Pending->value,
        ]);

        $job = new AnalyzeBugReport($report);
        $job->handle(DuplicateDetectionClient::fromConfig(), ScreenshotAnalysisClient::fromConfig());

        $report->refresh();

        $this->assertFalse($ocrCalled, 'OCR should not be re-run when already skipped');
        $this->assertSame(DetectionStatus::Completed, $report->detection_status);
    }

    public function test_ocr_text_appended_to_detection_description(): void
    {
        $captured = null;
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function ($request) use (&$captured) {
            if (str_contains($request->url(), 'ml.test/ocr')) {
                return Http::response(['text' => 'Teks OCR diekstrak'], 200);
            }

            $captured = json_decode($request->body(), true);

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'title' => 'Laporan dengan screenshot',
            'description' => 'Deskripsi asli.',
            'screenshot_path' => 'screenshots/checkout.png',
            'ocr_status' => OcrStatus::Pending->value,
            'detection_status' => DetectionStatus::Pending->value,
        ]);

        $job = new AnalyzeBugReport($report);
        $job->handle(DuplicateDetectionClient::fromConfig(), ScreenshotAnalysisClient::fromConfig());

        $report->refresh();

        $this->assertSame(OcrStatus::Completed, $report->ocr_status);
        $this->assertSame('Teks OCR diekstrak', $report->ocr_text);

        // The description sent to /detect should include OCR text
        $this->assertStringContainsString('Deskripsi asli.', $captured['description']);
        $this->assertStringContainsString('Teks OCR diekstrak', $captured['description']);
    }
}
