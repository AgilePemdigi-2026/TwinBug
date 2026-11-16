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

class BugReportStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_developer_can_advance_status_sequentially_and_history_is_recorded(): void
    {
        $report = $this->createReport();
        $developer = $this->developerFor($report);

        $transitions = [
            BugStatus::Open->value => BugStatus::InProgress,
            BugStatus::InProgress->value => BugStatus::Resolved,
            BugStatus::Resolved->value => BugStatus::Closed,
        ];

        foreach ($transitions as $target) {
            $this->actingAs($developer)
                ->patch(route('bug-reports.status.update', $report), [
                    'status' => $target->value,
                ])
                ->assertRedirect()
                ->assertSessionHasNoErrors();

            $this->assertSame($target, $report->fresh()->status);
        }

        $this->assertDatabaseCount('bug_report_status_histories', 3);
        $this->assertDatabaseHas('bug_report_status_histories', [
            'bug_report_id' => $report->id,
            'changed_by' => $developer->id,
            'from_status' => BugStatus::Open->value,
            'to_status' => BugStatus::InProgress->value,
        ]);
        $this->assertDatabaseHas('bug_report_status_histories', [
            'bug_report_id' => $report->id,
            'changed_by' => $developer->id,
            'from_status' => BugStatus::Resolved->value,
            'to_status' => BugStatus::Closed->value,
        ]);
    }

    public function test_developer_sees_success_message_after_updating_status(): void
    {
        $report = $this->createReport();
        $developer = $this->developerFor($report);

        $this->actingAs($developer)
            ->patch(route('bug-reports.status.update', $report), [
                'status' => BugStatus::InProgress->value,
            ])
            ->assertSessionHas('success', 'Status laporan berhasil diperbarui menjadi Diproses.');
    }

    public function test_status_cannot_skip_stages(): void
    {
        $report = $this->createReport();
        $developer = $this->developerFor($report);

        $this->actingAs($developer)
            ->patch(route('bug-reports.status.update', $report), [
                'status' => BugStatus::Resolved->value,
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame(BugStatus::Open, $report->fresh()->status);
        $this->assertDatabaseCount('bug_report_status_histories', 0);
    }

    public function test_status_cannot_move_backward(): void
    {
        $report = $this->createReport(BugStatus::InProgress);
        $developer = $this->developerFor($report);

        $this->actingAs($developer)
            ->patch(route('bug-reports.status.update', $report), [
                'status' => BugStatus::Open->value,
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame(BugStatus::InProgress, $report->fresh()->status);
        $this->assertDatabaseCount('bug_report_status_histories', 0);
    }

    public function test_closed_report_cannot_change_status(): void
    {
        $report = $this->createReport(BugStatus::Closed);
        $developer = $this->developerFor($report);

        $this->actingAs($developer)
            ->patch(route('bug-reports.status.update', $report), [
                'status' => BugStatus::Closed->value,
            ])
            ->assertSessionHasErrors('status');

        $this->assertSame(BugStatus::Closed, $report->fresh()->status);
        $this->assertDatabaseCount('bug_report_status_histories', 0);
    }

    public function test_invalid_status_is_rejected(): void
    {
        $report = $this->createReport();
        $developer = $this->developerFor($report);

        $this->actingAs($developer)
            ->patch(route('bug-reports.status.update', $report), [
                'status' => 'invalid-status',
            ])
            ->assertSessionHasErrors('status');
    }

    public function test_non_developer_roles_cannot_change_status(): void
    {
        foreach ([UserRole::Reporter, UserRole::Admin] as $role) {
            $report = $this->createReport();
            $viewer = User::factory()->create([
                'role' => $role,
                'application_id' => $report->application_id,
            ]);

            $this->actingAs($viewer)
                ->patch(route('bug-reports.status.update', $report), [
                    'status' => BugStatus::InProgress->value,
                ])
                ->assertForbidden();

            $this->assertSame(BugStatus::Open, $report->fresh()->status);
        }

        $this->assertDatabaseCount('bug_report_status_histories', 0);
    }

    public function test_developer_from_another_application_gets_not_found(): void
    {
        $report = $this->createReport();
        $otherApplication = Application::factory()->create();
        $intruder = User::factory()->developer()->create([
            'application_id' => $otherApplication->id,
        ]);

        $this->actingAs($intruder)
            ->patch(route('bug-reports.status.update', $report), [
                'status' => BugStatus::InProgress->value,
            ])
            ->assertNotFound();

        $this->assertSame(BugStatus::Open, $report->fresh()->status);
    }

    public function test_detail_page_shows_the_next_status_action_for_developer(): void
    {
        $actions = [
            BugStatus::Open->value => 'Tandai Diproses',
            BugStatus::InProgress->value => 'Tandai Diselesaikan',
            BugStatus::Resolved->value => 'Tandai Ditutup',
        ];

        foreach ($actions as $status => $label) {
            $report = $this->createReport(BugStatus::from($status));
            $developer = $this->developerFor($report);

            $this->actingAs($developer)
                ->get(route('bug-reports.show', $report))
                ->assertOk()
                ->assertSee($label);
        }

        $closedReport = $this->createReport(BugStatus::Closed);
        $developer = $this->developerFor($closedReport);

        $this->actingAs($developer)
            ->get(route('bug-reports.show', $closedReport))
            ->assertOk()
            ->assertSee('Laporan sudah ditutup. Tidak ada tahap status berikutnya.')
            ->assertDontSee('Tandai ');
    }

    public function test_detail_page_hides_status_action_for_non_developers(): void
    {
        foreach ([UserRole::Reporter, UserRole::Admin] as $role) {
            $report = $this->createReport();
            $viewer = User::factory()->create([
                'role' => $role,
                'application_id' => $report->application_id,
            ]);

            $this->actingAs($viewer)
                ->get(route('bug-reports.show', $report))
                ->assertOk()
                ->assertSee('Status Penanganan')
                ->assertDontSee('Tandai Diproses');
        }
    }

    public function test_detail_page_shows_status_history(): void
    {
        $report = $this->createReport(BugStatus::InProgress);
        $developer = $this->developerFor($report);

        $report->statusHistories()->create([
            'changed_by' => $developer->id,
            'from_status' => BugStatus::Open,
            'to_status' => BugStatus::InProgress,
        ]);

        $this->actingAs($developer)
            ->get(route('bug-reports.show', $report))
            ->assertOk()
            ->assertSee('Riwayat Status')
            ->assertSee('Terbuka')
            ->assertSee('Diproses')
            ->assertSee($developer->name);
    }

    private function createReport(BugStatus $status = BugStatus::Open): BugReport
    {
        $reporter = User::factory()->create();

        return BugReport::create([
            'user_id' => $reporter->id,
            'title' => 'Checkout gagal saat memilih metode QRIS',
            'module' => 'Checkout & Pembayaran',
            'description' => 'Kode QRIS tidak muncul setelah tombol bayar diklik.',
            'severity' => BugSeverity::High,
            'status' => $status,
        ]);
    }

    private function developerFor(BugReport $report): User
    {
        return User::factory()->developer()->create([
            'application_id' => $report->application_id,
        ]);
    }
}
