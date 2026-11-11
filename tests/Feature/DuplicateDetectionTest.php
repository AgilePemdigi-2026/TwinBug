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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DuplicateDetectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.ml.url' => 'http://ml-service.local',
            'services.ml.timeout' => 60,
            'services.ml.top_k' => 5,
            'services.ml.max_candidates' => 500,
            'services.ml.model' => 'paraphrase-multilingual-MiniLM-L12-v2',
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
            'title' => 'Judul bug',
            'module' => 'Dashboard',
            'description' => 'Deskripsi bug yang cukup panjang untuk pengujian.',
            'severity' => BugSeverity::Medium->value,
        ], $overrides));
    }

    public function test_store_dispatches_analysis_job_and_saves_report(): void
    {
        Queue::fake();

        $application = Application::factory()->create();
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
            'application_id' => $application->id,
        ]);

        $response = $this->actingAs($reporter)->post(route('bug-reports.store'), [
            'title' => 'Tombol checkout error',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
            'description' => 'Deskripsi error yang cukup panjang untuk melewati validasi minimal karakter.',
        ]);

        $response->assertRedirect(route('bug-reports.create'));
        $this->assertDatabaseHas('bug_reports', [
            'title' => 'Tombol checkout error',
            'application_id' => $application->id,
        ]);
        Queue::assertPushed(AnalyzeBugReport::class);
    }

    public function test_existing_bug_report_defaults_to_pending_status(): void
    {
        $application = Application::factory()->create();
        $report = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Tanpa status deteksi',
            'description' => 'Deskripsi laporan bug.',
            'severity' => BugSeverity::Medium->value,
        ]);

        $this->assertSame(DetectionStatus::Pending, $report->fresh()->detection_status);
    }

    public function test_analyze_job_success_stores_top_k_with_rank(): void
    {
        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Laporan sumber',
            'description' => 'Deskripsi laporan sumber.',
        ]);
        $candidate1 = $this->createBugReport($application, [
            'title' => 'Kandidat 1',
            'description' => 'Deskripsi kandidat 1.',
        ]);
        $candidate2 = $this->createBugReport($application, [
            'title' => 'Kandidat 2',
            'description' => 'Deskripsi kandidat 2.',
        ]);

        $otherApplication = Application::factory()->create();
        $this->createBugReport($otherApplication, [
            'title' => 'Kandidat aplikasi lain',
            'description' => 'Deskripsi.',
        ]);

        Http::fake(function () use ($candidate1, $candidate2) {
            return Http::response([
                'threshold_used' => 0.5097,
                'results' => [
                    ['id' => $candidate1->id, 'text_score' => 0.92],
                    ['id' => $candidate2->id, 'text_score' => 0.85],
                ],
            ], 200);
        });

        $job = new AnalyzeBugReport($source);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $this->assertSame(DetectionStatus::Completed, $source->fresh()->detection_status);
        $this->assertSame(0.5097, $source->fresh()->detection_threshold);
        $this->assertNotNull($source->fresh()->detected_at);

        $results = $source->detectionResults()->orderBy('rank')->get();
        $this->assertCount(2, $results);
        $this->assertSame(1, $results->first()->rank);
        $this->assertSame($candidate1->id, $results->first()->candidate_report_id);
        $this->assertSame(0.92, (float) $results->first()->text_score);
        $this->assertSame(2, $results->last()->rank);
        $this->assertSame($candidate2->id, $results->last()->candidate_report_id);
        $this->assertSame(0.85, (float) $results->last()->text_score);
        $this->assertSame('paraphrase-multilingual-MiniLM-L12-v2', $results->first()->model);
    }

    public function test_analyze_job_empty_results_is_completed(): void
    {
        Http::fake(function () {
            return Http::response(['threshold_used' => 0.50, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Laporan tanpa duplikat',
            'description' => 'Deskripsi.',
        ]);

        $job = new AnalyzeBugReport($source);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $this->assertSame(DetectionStatus::Completed, $source->fresh()->detection_status);
        $this->assertSame(0.50, $source->fresh()->detection_threshold);
        $this->assertNotNull($source->fresh()->detected_at);
        $this->assertCount(0, $source->detectionResults);
    }

    public function test_analyze_job_service_500_sets_failed(): void
    {
        Http::fake(function () {
            throw new \RuntimeException('HTTP 500');
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Laporan gagal',
            'description' => 'Deskripsi.',
        ]);

        $job = new AnalyzeBugReport($source);

        try {
            $job->handle(DuplicateDetectionClient::fromConfig());
        } catch (\Throwable $e) {
            $job->failed($e);
        }

        $this->assertSame(DetectionStatus::Failed, $source->fresh()->detection_status);
        $this->assertNotNull($source->fresh()->detection_error);
        $this->assertStringContainsString('500', $source->fresh()->detection_error);
    }

    public function test_analyze_job_timeout_sets_failed(): void
    {
        Http::fake(function () {
            throw new \RuntimeException('Connection timed out');
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Laporan timeout',
            'description' => 'Deskripsi.',
        ]);

        $job = new AnalyzeBugReport($source);

        try {
            $job->handle(DuplicateDetectionClient::fromConfig());
        } catch (\Throwable $e) {
            $job->failed($e);
        }

        $this->assertSame(DetectionStatus::Failed, $source->fresh()->detection_status);
        $this->assertNotNull($source->fresh()->detection_error);
        $this->assertStringContainsString('timed out', $source->fresh()->detection_error);
    }

    public function test_payload_contains_no_threshold_and_description_is_never_null(): void
    {
        $captured = null;
        Http::fake(function ($request) use (&$captured) {
            $captured = json_decode($request->body(), true);

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $source = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Sumber',
            'description' => '',
            'severity' => BugSeverity::Medium->value,
        ]);
        $candidate = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Kandidat',
            'description' => '',
            'severity' => BugSeverity::Medium->value,
        ]);

        $job = new AnalyzeBugReport($source);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $this->assertArrayNotHasKey('threshold', $captured);
        $this->assertSame('', $captured['description']);
        foreach ($captured['candidates'] as $candidatePayload) {
            $this->assertIsString($candidatePayload['description']);
        }
    }

    public function test_candidates_only_from_same_application_and_not_self(): void
    {
        $captured = null;
        Http::fake(function ($request) use (&$captured) {
            $captured = json_decode($request->body(), true);

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $otherApplication = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Sumber',
            'description' => 'Deskripsi sumber.',
        ]);
        $this->createBugReport($application, [
            'title' => 'Kandidat sama aplikasi',
            'description' => 'Deskripsi.',
        ]);
        $this->createBugReport($otherApplication, [
            'title' => 'Kandidat aplikasi lain',
            'description' => 'Deskripsi.',
        ]);

        $job = new AnalyzeBugReport($source);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $candidateIds = array_map(fn (array $c) => (int) $c['id'], $captured['candidates']);
        $this->assertNotContains($source->id, $candidateIds);
        foreach ($candidateIds as $candidateId) {
            $this->assertDatabaseHas('bug_reports', [
                'id' => $candidateId,
                'application_id' => $application->id,
            ]);
        }
    }

    public function test_max_candidates_limit_is_respected(): void
    {
        $captured = null;
        config(['services.ml.max_candidates' => 3]);
        Http::fake(function ($request) use (&$captured) {
            $captured = json_decode($request->body(), true);

            return Http::response(['threshold_used' => 0.5, 'results' => []], 200);
        });

        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Sumber',
            'description' => 'Deskripsi.',
        ]);
        foreach (range(1, 5) as $i) {
            $this->createBugReport($application, [
                'title' => "Kandidat {$i}",
                'description' => "Deskripsi kandidat {$i}.",
            ]);
        }

        $job = new AnalyzeBugReport($source);
        $job->handle(DuplicateDetectionClient::fromConfig());

        $this->assertSame(3, count($captured['candidates']));
    }

    public function test_reanalysis_does_not_duplicate_rows(): void
    {
        $application = Application::factory()->create();
        $source = $this->createBugReport($application, [
            'title' => 'Sumber re-analisis',
            'description' => 'Deskripsi.',
        ]);
        $candidate = $this->createBugReport($application, [
            'title' => 'Kandidat',
            'description' => 'Deskripsi.',
        ]);

        Http::fake(function () use ($candidate) {
            return Http::response([
                'threshold_used' => 0.5097,
                'results' => [
                    ['id' => $candidate->id, 'text_score' => 0.92],
                ],
            ], 200);
        });

        $job = new AnalyzeBugReport($source);
        $job->handle(DuplicateDetectionClient::fromConfig());
        $job->handle(DuplicateDetectionClient::fromConfig());

        $this->assertCount(1, $source->fresh()->detectionResults);
        $this->assertSame(DetectionStatus::Completed, $source->fresh()->detection_status);
    }

    public function test_report_is_saved_even_when_service_is_down(): void
    {
        Queue::fake();

        $application = Application::factory()->create();
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
            'application_id' => $application->id,
        ]);

        $response = $this->actingAs($reporter)->post(route('bug-reports.store'), [
            'title' => 'Laporan meski service down',
            'module' => 'Dashboard',
            'severity' => BugSeverity::Low->value,
            'description' => 'Deskripsi yang cukup panjang untuk melewati validasi minimal karakter.',
        ]);

        $response->assertRedirect(route('bug-reports.create'));
        $this->assertDatabaseHas('bug_reports', [
            'title' => 'Laporan meski service down',
        ]);
        Queue::assertPushed(AnalyzeBugReport::class);
    }

    public function test_reporter_is_denied_by_policy(): void
    {
        $application = Application::factory()->create();
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
            'application_id' => $application->id,
        ]);
        $source = BugReport::create([
            'user_id' => $reporter->id,
            'application_id' => $application->id,
            'title' => 'Sumber',
            'description' => 'Deskripsi sumber.',
            'severity' => BugSeverity::Medium->value,
        ]);
        $candidate = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Kandidat',
            'description' => 'Deskripsi kandidat.',
            'severity' => BugSeverity::Medium->value,
        ]);
        $detectionResult = DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $candidate->id,
            'rank' => 1,
            'text_score' => 0.9,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        $this->assertFalse(Gate::forUser($reporter)->allows('view', $detectionResult));
        $this->assertTrue(Gate::forUser($reporter)->denies('view', $detectionResult));
    }

    public function test_developer_from_same_application_can_view(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $source = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Sumber',
            'description' => 'Deskripsi sumber.',
            'severity' => BugSeverity::Medium->value,
        ]);
        $candidate = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $application->id])->id,
            'application_id' => $application->id,
            'title' => 'Kandidat',
            'description' => 'Deskripsi kandidat.',
            'severity' => BugSeverity::Medium->value,
        ]);
        $detectionResult = DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $candidate->id,
            'rank' => 1,
            'text_score' => 0.9,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        $this->assertTrue(Gate::forUser($developer)->allows('view', $detectionResult));
    }

    public function test_developer_from_another_application_is_denied(): void
    {
        $ownApplication = Application::factory()->create();
        $otherApplication = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $ownApplication->id,
        ]);
        $source = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $otherApplication->id])->id,
            'application_id' => $otherApplication->id,
            'title' => 'Sumber',
            'description' => 'Deskripsi sumber.',
            'severity' => BugSeverity::Medium->value,
        ]);
        $candidate = BugReport::create([
            'user_id' => User::factory()->create(['application_id' => $otherApplication->id])->id,
            'application_id' => $otherApplication->id,
            'title' => 'Kandidat',
            'description' => 'Deskripsi kandidat.',
            'severity' => BugSeverity::Medium->value,
        ]);
        $detectionResult = DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $candidate->id,
            'rank' => 1,
            'text_score' => 0.9,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        $this->assertFalse(Gate::forUser($developer)->allows('view', $detectionResult));
        $this->assertTrue(Gate::forUser($developer)->denies('view', $detectionResult));
    }
}
