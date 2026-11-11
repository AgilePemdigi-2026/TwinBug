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
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class DetectionPageTest extends TestCase
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
            'detection_status' => DetectionStatus::Pending->value,
        ], $overrides));
    }

    public function test_developer_can_view_detection_results(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $report));

        $response->assertStatus(200);
        $response->assertSee($report->title);
    }

    public function test_reporter_gets_403_on_detection_page(): void
    {
        $application = Application::factory()->create();
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application);

        $this->actingAs($reporter)
            ->get(route('bug-reports.detection', $report))
            ->assertStatus(403);
    }

    public function test_developer_from_another_application_gets_403(): void
    {
        $ownApplication = Application::factory()->create();
        $otherApplication = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $ownApplication->id,
        ]);
        $report = $this->createBugReport($otherApplication);

        $this->actingAs($developer)
            ->get(route('bug-reports.detection', $report))
            ->assertStatus(403);
    }

    public function test_pending_state_renders_start_button(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Pending->value,
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $report));

        $response->assertStatus(200);
        $response->assertSee('Menunggu Analisis');
        $response->assertSee('Mulai Analisis');
    }

    public function test_processing_state_renders_refresh_button(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Processing->value,
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $report));

        $response->assertStatus(200);
        $response->assertSee('Sedang Menganalisis');
        $response->assertSee('Segarkan Halaman');
    }

    public function test_completed_with_results_renders_ranked_list(): void
    {
        Http::fake(function () {
            return Http::response([
                'threshold_used' => 0.5097,
                'results' => [],
            ], 200);
        });

        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $source = $this->createBugReport($application, [
            'title' => 'Laporan Sumber',
            'detection_status' => DetectionStatus::Completed->value,
            'detection_threshold' => 0.5097,
            'detected_at' => now(),
        ]);
        $candidate1 = $this->createBugReport($application, [
            'title' => 'Kandidat Tertinggi',
            'detection_status' => DetectionStatus::Completed->value,
        ]);
        $candidate2 = $this->createBugReport($application, [
            'title' => 'Kandidat Kedua',
            'detection_status' => DetectionStatus::Completed->value,
        ]);

        DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $candidate1->id,
            'rank' => 1,
            'text_score' => 0.92,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);
        DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $candidate2->id,
            'rank' => 2,
            'text_score' => 0.85,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $source));

        $response->assertStatus(200);
        $response->assertSee('Laporan Kandidat Mirip');
        $response->assertSee('Kandidat Tertinggi');
        $response->assertSee('Kandidat Kedua');
        $response->assertSee('92.0%');
        $response->assertSee('85.0%');
        // Rank order: candidate1 (rank 1) appears before candidate2 (rank 2)
        $response->assertSeeInOrder(['Kandidat Tertinggi', 'Kandidat Kedua']);
    }

    public function test_completed_empty_renders_no_duplicates_message(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Completed->value,
            'detection_threshold' => 0.5097,
            'detected_at' => now(),
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $report));

        $response->assertStatus(200);
        $response->assertSee('Tidak Ada Laporan Serupa');
    }

    public function test_failed_state_renders_error_and_rerun_button(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Failed->value,
            'detection_error' => 'Koneksi ke layanan ML gagal.',
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $report));

        $response->assertStatus(200);
        $response->assertSee('Analisis Gagal');
        $response->assertSee('Koneksi ke layanan ML gagal.');
        $response->assertSee('Analisis Ulang');
    }

    public function test_rerun_dispatches_job_and_resets_status(): void
    {
        Queue::fake();

        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Failed->value,
            'detection_error' => 'Error lama',
        ]);

        $response = $this->actingAs($developer)
            ->post(route('bug-reports.detection.rerun', $report));

        $response->assertRedirect(route('bug-reports.detection', $report));
        $this->assertSame(DetectionStatus::Pending, $report->fresh()->detection_status);
        $this->assertNull($report->fresh()->detection_error);
        Queue::assertPushed(AnalyzeBugReport::class);
    }

    public function test_rerun_is_blocked_for_reporter(): void
    {
        $application = Application::factory()->create();
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application);

        $this->actingAs($reporter)
            ->post(route('bug-reports.detection.rerun', $report))
            ->assertStatus(403);
    }

    public function test_badge_levels_follow_config_values(): void
    {
        config(['detection.badge_high.min' => 0.80, 'detection.badge_medium.min' => 0.65]);

        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $source = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Completed->value,
            'detection_threshold' => 0.50,
            'detected_at' => now(),
        ]);

        // High: >= 0.80
        $highCandidate = $this->createBugReport($application, ['title' => 'High Candidate']);
        DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $highCandidate->id,
            'rank' => 1,
            'text_score' => 0.85,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        // Medium: 0.65 - 0.80
        $mediumCandidate = $this->createBugReport($application, ['title' => 'Medium Candidate']);
        DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $mediumCandidate->id,
            'rank' => 2,
            'text_score' => 0.70,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        // Low: threshold - 0.65
        $lowCandidate = $this->createBugReport($application, ['title' => 'Low Candidate']);
        DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $lowCandidate->id,
            'rank' => 3,
            'text_score' => 0.55,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $source));

        $response->assertStatus(200);
        $response->assertSee('Tinggi');
        $response->assertSee('Sedang');
        $response->assertSee('Rendah');
    }

    public function test_suggestion_note_is_visible(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Completed->value,
            'detected_at' => now(),
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $report));

        $response->assertStatus(200);
        $response->assertSee('Hasil deteksi adalah saran saja');
        $response->assertSee('Keputusan akhir diambil oleh Developer');
    }

    public function test_candidate_descriptions_are_escaped(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $source = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Completed->value,
            'detected_at' => now(),
        ]);
        $candidate = $this->createBugReport($application, [
            'title' => '<script>alert("xss")</script>',
            'description' => '<b>Bold description</b>',
        ]);
        DetectionResult::create([
            'bug_report_id' => $source->id,
            'candidate_report_id' => $candidate->id,
            'rank' => 1,
            'text_score' => 0.90,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        $response = $this->actingAs($developer)->get(route('bug-reports.detection', $source));

        $response->assertStatus(200);
        // Description should be escaped, not rendered as HTML
        $response->assertDontSee('<b>Bold description</b>', false);
    }

    public function test_detection_link_visible_on_show_page_for_developer(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application);

        $response = $this->actingAs($developer)->get(route('bug-reports.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Deteksi Duplikasi');
        $response->assertSee(route('bug-reports.detection', $report));
    }

    public function test_detection_link_hidden_on_show_page_for_reporter(): void
    {
        $application = Application::factory()->create();
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application);

        $response = $this->actingAs($reporter)->get(route('bug-reports.show', $report));

        $response->assertStatus(200);
        $response->assertDontSee('Deteksi Duplikasi');
    }

    public function test_detection_badge_shown_on_developer_dashboard(): void
    {
        $application = Application::factory()->create();
        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $application->id,
        ]);
        $report = $this->createBugReport($application, [
            'detection_status' => DetectionStatus::Completed->value,
        ]);
        $candidate = $this->createBugReport($application);
        DetectionResult::create([
            'bug_report_id' => $report->id,
            'candidate_report_id' => $candidate->id,
            'rank' => 1,
            'text_score' => 0.90,
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ]);

        $response = $this->actingAs($developer)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Deteksi Duplikasi');
    }
}
