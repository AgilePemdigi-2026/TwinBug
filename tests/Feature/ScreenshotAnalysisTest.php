<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\DetectionStatus;
use App\Jobs\AnalyzeBugReport;
use App\Models\Application;
use App\Models\BugReport;
use App\Models\User;
use App\Services\DuplicateDetectionClient;
use App\Services\ImageHasher;
use App\Services\ScreenshotAnalysisClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScreenshotAnalysisTest extends TestCase
{
    use RefreshDatabase;

    private const PNG = 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        config([
            'services.ml.url' => 'http://ml.test',
            'services.ml.timeout' => 60,
            'services.ml.top_k' => 5,
            'services.ml.max_candidates' => 500,
            'services.ml.model' => 'paraphrase-multilingual-MiniLM-L12-v2',
            'services.vision.url' => 'http://vision.test/v1/chat/completions',
            'services.vision.key' => 'test-key',
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
            'severity' => BugSeverity::High->value,
            'detection_status' => DetectionStatus::Pending->value,
        ], $overrides));
    }

    private function runJob(BugReport $report): void
    {
        $job = new AnalyzeBugReport($report);
        $job->handle(DuplicateDetectionClient::fromConfig(), ScreenshotAnalysisClient::fromConfig(), new ImageHasher());
    }

    public function test_detection_job_analyzes_screenshot_and_stores_description(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function ($request) {
            if (str_contains($request->url(), 'vision.test')) {
                return Http::response([
                    'choices' => [
                        ['message' => ['content' => 'Halaman checkout menampilkan dialog error pembayaran.']],
                    ],
                ], 200);
            }

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
        ]);

        $this->runJob($report);

        $report->refresh();

        $this->assertSame('Halaman checkout menampilkan dialog error pembayaran.', $report->image_description);
        $this->assertNotNull($report->image_analyzed_at);
        $this->assertSame(DetectionStatus::Completed, $report->detection_status);

        Http::assertSent(function ($request) {
            if (! str_contains($request->url(), 'vision.test')) {
                return false;
            }

            $body = $request->data();
            $imageUrl = $body['messages'][1]['content'][1]['image_url']['url'] ?? '';

            return $body['model'] === 'gpt-4o-mini'
                && str_starts_with($imageUrl, 'data:image/png;base64,');
        });
    }

    public function test_reports_without_screenshot_skip_vision_analysis(): void
    {
        Http::fake(function () {
            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application);

        $this->runJob($report);

        $this->assertNull($report->fresh()->image_description);
        Http::assertNotSent(fn ($request) => str_contains($request->url(), 'vision.test'));
    }

    public function test_vision_failure_does_not_fail_detection(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function ($request) {
            if (str_contains($request->url(), 'vision.test')) {
                return Http::response(['error' => ['message' => 'Vision unavailable']], 500);
            }

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
        ]);

        $this->runJob($report);

        $report->refresh();

        $this->assertNull($report->image_description);
        $this->assertSame(DetectionStatus::Completed, $report->detection_status);
        $this->assertNull($report->detection_error);
    }

    public function test_stored_description_is_not_analyzed_again(): void
    {
        Storage::disk('public')->put('screenshots/checkout.png', base64_decode(self::PNG));

        Http::fake(function () {
            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $report = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/checkout.png',
            'image_description' => 'Deskripsi visual yang sudah tersimpan.',
            'image_analyzed_at' => now(),
        ]);

        $this->runJob($report);

        $this->assertSame('Deskripsi visual yang sudah tersimpan.', $report->fresh()->image_description);
        Http::assertNotSent(fn ($request) => str_contains($request->url(), 'vision.test'));
    }
}
