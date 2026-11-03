<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugReportDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_bug_report_detail_page(): void
    {
        $report = $this->createReport();

        $response = $this->get(route('bug-reports.show', $report));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_bug_report_detail(): void
    {
        $report = $this->createReport();
        $viewer = User::factory()->create(['role' => UserRole::Developer]);

        $response = $this->actingAs($viewer)->get(route('bug-reports.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Detail Laporan');
        $response->assertSee('Checkout gagal saat memilih metode QRIS');
        $response->assertSee('Kode QRIS tidak muncul setelah tombol bayar diklik.');
        $response->assertSee('Checkout &amp; Pembayaran', false);
        $response->assertSee('Reporter Satu');
        $response->assertSee('Tinggi');
        $response->assertSee('Terbuka');
    }

    public function test_dashboard_history_displays_link_to_report_detail(): void
    {
        $report = $this->createReport();

        $response = $this->actingAs($report->user)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee(route('bug-reports.show', $report));
        $response->assertSee('Lihat Detail');
        $response->assertSee('Checkout gagal saat memilih metode QRIS');
        $response->assertSee('Kode QRIS tidak muncul setelah tombol bayar diklik.');
    }

    public function test_detail_page_renders_screenshot_metadata(): void
    {
        $report = $this->createReport([
            'screenshot_path' => 'screenshots/bukti-qris.png',
            'screenshot_name' => 'bukti-qris.png',
            'screenshot_size' => 204800,
            'screenshot_dimensions' => '1280×720',
        ]);

        $response = $this->actingAs($report->user)->get(route('bug-reports.show', $report));

        $response->assertStatus(200);
        $response->assertSee('Lampiran Screenshot');
        $response->assertSee(asset('storage/screenshots/bukti-qris.png'));
        $response->assertSee('bukti-qris.png');
        $response->assertSee('200 KB');
        $response->assertSee('1280×720');
    }

    public function test_missing_bug_report_returns_not_found(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $response = $this->actingAs($user)->get(route('bug-reports.show', 999));

        $response->assertNotFound();
    }

    /**
     * @param  array<string, mixed>  $overrides
     */
    private function createReport(array $overrides = []): BugReport
    {
        $reporter = User::factory()->create([
            'name' => 'Reporter Satu',
            'role' => UserRole::Reporter,
        ]);

        return BugReport::create(array_merge([
            'user_id' => $reporter->id,
            'title' => 'Checkout gagal saat memilih metode QRIS',
            'module' => 'Checkout & Pembayaran',
            'description' => 'Kode QRIS tidak muncul setelah tombol bayar diklik.',
            'severity' => BugSeverity::High->value,
            'status' => BugStatus::Open->value,
        ], $overrides));
    }
}
