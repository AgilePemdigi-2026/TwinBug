<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_reporter_dashboard_only_shows_personal_reports(): void
    {
        $reporter = User::factory()->create(['name' => 'Reporter Utama', 'role' => UserRole::Reporter]);
        $otherReporter = User::factory()->create(['name' => 'Reporter Lain', 'role' => UserRole::Reporter]);

        $this->createReport($reporter, 'Bug milik reporter utama');
        $this->createReport($otherReporter, 'Bug milik reporter lain');

        $response = $this->actingAs($reporter)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Histori Laporan Saya');
        $response->assertSee('Laporan Saya');
        $response->assertSee('Bug milik reporter utama');
        $response->assertDontSee('Bug milik reporter lain');
        $response->assertDontSee('Antrean Bug Terbaru');
        $response->assertSee('Buat Laporan Bug Baru');
    }

    public function test_developer_dashboard_shows_global_bug_queue(): void
    {
        $developer = User::factory()->create(['name' => 'Developer Utama', 'role' => UserRole::Developer]);
        $reporter = User::factory()->create(['name' => 'Reporter Lapangan', 'role' => UserRole::Reporter]);

        $this->createReport($reporter, 'Bug kritis pada pembayaran', [
            'severity' => BugSeverity::Critical->value,
        ]);
        $this->createReport($reporter, 'Bug minor pada profil', [
            'severity' => BugSeverity::Low->value,
            'status' => BugStatus::InProgress->value,
        ]);

        $response = $this->actingAs($developer)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Antrean Bug Terbaru');
        $response->assertSee('Total Laporan');
        $response->assertSee('Bug kritis pada pembayaran');
        $response->assertSee('Bug minor pada profil');
        $response->assertSee('Reporter Lapangan');
        $response->assertSee(route('bug-reports.index', ['status' => 'open']));
        $response->assertDontSee('Histori Laporan Saya');
    }

    public function test_developer_dashboard_shows_empty_queue_state(): void
    {
        $developer = User::factory()->create(['role' => UserRole::Developer]);

        $response = $this->actingAs($developer)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Antrean Masih Kosong');
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createReport(User $user, string $title, array $overrides = []): BugReport
    {
        return BugReport::create(array_merge([
            'user_id' => $user->id,
            'title' => $title,
            'module' => 'Dashboard',
            'description' => 'Deskripsi bug untuk pengujian dashboard.',
            'severity' => BugSeverity::Medium->value,
            'status' => BugStatus::Open->value,
        ], $overrides));
    }
}
