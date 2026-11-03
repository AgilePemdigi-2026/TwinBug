<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\Application;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_dashboard_only_shows_reports_from_own_application(): void
    {
        [$developer, $ownReport, $otherReport] = $this->twoApplications();

        $response = $this->actingAs($developer)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee($ownReport->title);
        $response->assertDontSee($otherReport->title);
        $this->assertSame(1, $response->viewData('stats')['total']);
    }

    public function test_report_index_only_lists_own_application_reports(): void
    {
        [$developer, $ownReport, $otherReport] = $this->twoApplications();

        $response = $this->actingAs($developer)->get(route('bug-reports.index'));

        $response->assertOk();
        $response->assertSee($ownReport->title);
        $response->assertDontSee($otherReport->title);
        $this->assertSame(1, $response->viewData('stats')['total']);
    }

    public function test_report_from_another_application_returns_not_found(): void
    {
        [$developer, , $otherReport] = $this->twoApplications();

        $this->actingAs($developer)
            ->get(route('bug-reports.show', $otherReport))
            ->assertNotFound();
    }

    public function test_submitted_report_is_linked_to_reporter_application(): void
    {
        $application = Application::factory()->create();
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
            'application_id' => $application->id,
        ]);

        $this->actingAs($reporter)->post(route('bug-reports.store'), [
            'title' => 'Tombol checkout tidak dapat diklik',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
            'description' => 'Langkah reproduksi: buka keranjang, klik checkout, tombol tetap loading.',
        ])->assertRedirect(route('bug-reports.create'));

        $this->assertDatabaseHas('bug_reports', [
            'user_id' => $reporter->id,
            'application_id' => $application->id,
        ]);
    }

    /**
     * @return array{User, BugReport, BugReport}
     */
    private function twoApplications(): array
    {
        $ownApplication = Application::factory()->create();
        $otherApplication = Application::factory()->create();

        $developer = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => $ownApplication->id,
        ]);

        $ownReporter = User::factory()->create(['application_id' => $ownApplication->id]);
        $otherReporter = User::factory()->create(['application_id' => $otherApplication->id]);

        $ownReport = $this->createReport($ownReporter, 'Bug dari aplikasi sendiri', $ownApplication);
        $otherReport = $this->createReport($otherReporter, 'Bug dari aplikasi lain', $otherApplication);

        return [$developer, $ownReport, $otherReport];
    }

    private function createReport(User $reporter, string $title, Application $application): BugReport
    {
        return BugReport::create([
            'user_id' => $reporter->id,
            'application_id' => $application->id,
            'title' => $title,
            'module' => 'Dashboard',
            'description' => 'Deskripsi bug yang cukup panjang untuk memenuhi validasi.',
            'severity' => BugSeverity::Medium,
            'status' => BugStatus::Open,
        ]);
    }
}
