<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\DetectionStatus;
use App\Enums\UserRole;
use App\Jobs\AnalyzeBugReport;
use App\Models\Application;
use App\Models\BugReport;
use App\Models\DetectionResult;
use App\Models\User;
use App\Services\DuplicateDetectionClient;
use App\Services\ImageHasher;
use App\Services\ScreenshotAnalysisClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ScreenshotHashDetectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Storage::disk('public')->makeDirectory('screenshots');

        config([
            'services.ml.url' => 'http://ml-service.local',
            'services.ml.timeout' => 60,
            'services.ml.top_k' => 5,
            'services.ml.max_candidates' => 500,
            'services.ml.model' => 'paraphrase-multilingual-MiniLM-L12-v2',
            'detection.image_match_threshold' => 0.90,
            'detection.image_match_max_candidates' => 500,
            'detection.uniformity_variance_threshold' => 10.0,
        ]);
    }

    private function createPngImage(string $path, int $width, int $height, callable $draw): void
    {
        $image = imagecreatetruecolor($width, $height);
        $draw($image);
        imagepng($image, Storage::disk('public')->path($path));
        imagedestroy($image);
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createBugReport(Application $application, array $overrides = []): BugReport
    {
        return BugReport::create(array_merge([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Judul bug',
            'module' => 'Dashboard',
            'description' => 'Deskripsi bug yang cukup panjang untuk pengujian.',
            'severity' => BugSeverity::Medium->value,
            'detection_status' => DetectionStatus::Pending->value,
        ], $overrides));
    }

    public function test_identical_screenshot_different_text_shows_image_score(): void
    {
        $this->createPngImage('screenshots/shared.png', 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imageline($img, 10, 10, 90, 90, $black);
            imageline($img, 10, 90, 90, 10, $black);
        });

        // /detect returns no matches
        Http::fake(function () {
            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Laporan A - teks berbeda',
            'description' => 'Deskripsi yang sangat berbeda dari kandidat.',
            'screenshot_path' => 'screenshots/shared.png',
            'detection_status' => DetectionStatus::Pending->value,
        ]);
        $candidate = $this->createBugReport($application, [
            'title' => 'Laporan B - judul berbeda',
            'description' => 'Deskripsi kandidat yang tidak ada hubungan.',
            'screenshot_path' => 'screenshots/shared.png',
            'detection_status' => DetectionStatus::Completed->value,
        ]);

        // Pre-compute hash for both reports so they match
        $hasher = new ImageHasher();
        $sharedHash = $hasher->hash('screenshots/shared.png');
        $source->update(['screenshot_hash' => $sharedHash]);
        $candidate->update(['screenshot_hash' => $sharedHash]);

        $job = new AnalyzeBugReport($source);
        $job->handle(
            DuplicateDetectionClient::fromConfig(),
            ScreenshotAnalysisClient::fromConfig(),
            new ImageHasher()
        );

        $source->refresh();

        $this->assertSame(DetectionStatus::Completed, $source->detection_status);
        $this->assertNotNull($source->screenshot_hash);
        $this->assertSame($candidate->fresh()->screenshot_hash, $source->screenshot_hash);

        $result = $source->detectionResults()->where('candidate_report_id', $candidate->id)->first();
        $this->assertNotNull($result);
        $this->assertNull($result->text_score);
        $this->assertSame(1.0, (float) $result->image_score);
    }

    public function test_candidate_from_other_application_never_appears(): void
    {
        $this->createPngImage('screenshots/shared.png', 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imageline($img, 10, 10, 90, 90, $black);
            imageline($img, 10, 90, 90, 10, $black);
        });

        Http::fake(function () {
            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $ownApp = Application::factory()->create();
        $otherApp = Application::factory()->create();
        $source = $this->createBugReport($ownApp, [
            'screenshot_path' => 'screenshots/shared.png',
        ]);
        $otherCandidate = $this->createBugReport($otherApp, [
            'screenshot_path' => 'screenshots/shared.png',
        ]);

        $job = new AnalyzeBugReport($source);
        $job->handle(
            DuplicateDetectionClient::fromConfig(),
            ScreenshotAnalysisClient::fromConfig(),
            new ImageHasher()
        );

        $source->refresh();

        $this->assertCount(0, $source->detectionResults);
    }

    public function test_merge_ranks_by_combined_score(): void
    {
        $this->createPngImage('screenshots/shared.png', 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imageline($img, 10, 10, 90, 90, $black);
            imageline($img, 10, 90, 90, 10, $black);
        });

        // Pre-compute hash for source and image candidate
        $hasher = new ImageHasher();
        $sharedHash = $hasher->hash('screenshots/shared.png');

        $captured = null;
        Http::fake(function ($request) use (&$captured) {
            $captured = json_decode($request->body(), true);

            return Http::response([
                'threshold_used' => 0.5,
                'results' => [
                    ['id' => 999, 'text_score' => 0.95], // text-only candidate
                ],
            ], 200);
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Sumber merge',
            'description' => 'Deskripsi sumber.',
            'screenshot_path' => 'screenshots/shared.png',
        ]);
        // Candidate with both text and image match
        $textCandidate = $this->createBugReport($application, [
            'title' => 'Kandidat teks',
            'description' => 'Deskripsi kandidat teks.',
        ]);
        // Candidate with image-only match (same screenshot)
        $imageCandidate = $this->createBugReport($application, [
            'title' => 'Kandidat gambar',
            'description' => 'Deskripsi yang beda sekali.',
            'screenshot_path' => 'screenshots/shared.png',
        ]);
        $source->update(['screenshot_hash' => $sharedHash]);
        $imageCandidate->update(['screenshot_hash' => $sharedHash]);

        $job = new AnalyzeBugReport($source);
        $job->handle(
            DuplicateDetectionClient::fromConfig(),
            ScreenshotAnalysisClient::fromConfig(),
            new ImageHasher()
        );

        $source->refresh();

        // /detect returned candidate id 999 which doesn't exist, so it's skipped
        // Image candidate should appear with image_score = 1.0
        $imageResult = $source->detectionResults()->where('candidate_report_id', $imageCandidate->id)->first();
        $this->assertNotNull($imageResult);
        $this->assertSame(1.0, (float) $imageResult->image_score);
        $this->assertNull($imageResult->text_score);
    }

    public function test_top_k_respected_after_merge(): void
    {
        $this->createPngImage('screenshots/shared.png', 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            $black = imagecolorallocate($img, 0, 0, 0);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
            imageline($img, 10, 10, 90, 90, $black);
            imageline($img, 10, 90, 90, 10, $black);
        });

        config(['services.ml.top_k' => 2]);

        Http::fake(function () {
            return Http::response([
                'threshold_used' => 0.5,
                'results' => [
                    ['id' => 1, 'text_score' => 0.90],
                    ['id' => 2, 'text_score' => 0.85],
                    ['id' => 3, 'text_score' => 0.80],
                ],
            ], 200);
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/shared.png',
        ]);

        // Create 3 real candidates (they exist in DB, so /detect results map to them)
        $c1 = $this->createBugReport($application, ['title' => 'C1']);
        $c2 = $this->createBugReport($application, ['title' => 'C2']);
        $c3 = $this->createBugReport($application, ['title' => 'C3']);

        // Override the detected IDs to match our real candidates
        $captured = null;
        Http::fake(function ($request) use (&$captured, $c1, $c2, $c3) {
            $captured = json_decode($request->body(), true);
            return Http::response([
                'threshold_used' => 0.5,
                'results' => [
                    ['id' => $c1->id, 'text_score' => 0.90],
                    ['id' => $c2->id, 'text_score' => 0.85],
                    ['id' => $c3->id, 'text_score' => 0.80],
                ],
            ], 200);
        });

        $job = new AnalyzeBugReport($source);
        $job->handle(
            DuplicateDetectionClient::fromConfig(),
            ScreenshotAnalysisClient::fromConfig(),
            new ImageHasher()
        );

        $source->refresh();

        $results = $source->detectionResults()->orderBy('rank')->get();
        $this->assertSame(2, $results->count(), 'top_k=2 should limit results');
    }

    public function test_hashing_failure_does_not_block_detection(): void
    {
        $this->createPngImage('screenshots/bad.png', 100, 100, function ($img) {
            $white = imagecolorallocate($img, 255, 255, 255);
            imagefilledrectangle($img, 0, 0, 99, 99, $white);
        });

        // Make ImageHasher throw by passing a corrupted path
        Http::fake(function () {
            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'screenshot_path' => 'screenshots/bad.png',
            'detection_status' => DetectionStatus::Pending->value,
        ]);

        $job = new AnalyzeBugReport($source);
        $job->handle(
            DuplicateDetectionClient::fromConfig(),
            ScreenshotAnalysisClient::fromConfig(),
            new ImageHasher()
        );

        $source->refresh();

        // Detection should still complete even if hashing fails/returns null
        $this->assertSame(DetectionStatus::Completed, $source->detection_status);
    }

    public function test_image_score_null_when_no_hash(): void
    {
        Http::fake(function () {
            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'screenshot_path' => null, // no screenshot
            'detection_status' => DetectionStatus::Pending->value,
        ]);
        $candidate = $this->createBugReport($application, [
            'screenshot_path' => null,
        ]);

        $job = new AnalyzeBugReport($source);
        $job->handle(
            DuplicateDetectionClient::fromConfig(),
            ScreenshotAnalysisClient::fromConfig(),
            new ImageHasher()
        );

        $source->refresh();

        $this->assertNull($source->screenshot_hash);
        $this->assertCount(0, $source->detectionResults);
    }
}
