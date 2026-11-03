<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugReportFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_bug_reports_index_page(): void
    {
        $response = $this->get(route('bug-reports.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_bug_reports_index_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $response = $this->actingAs($user)->get(route('bug-reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar &amp; Filter Laporan Bug', false);
        $response->assertSee('Pusat Penelusuran');
    }

    public function test_can_filter_bug_reports_by_search_query(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Gagal checkout pada metode pembayaran QRIS',
            'module' => 'Checkout & Pembayaran',
            'description' => 'QRIS code tidak muncul saat klik tombol bayar.',
            'severity' => BugSeverity::High->value,
            'status' => BugStatus::Open->value,
        ]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Font avatar user terlalu kecil',
            'module' => 'Profil Pengguna',
            'description' => 'Tampilan inisial nama terpotong di layar mobile.',
            'severity' => BugSeverity::Low->value,
            'status' => BugStatus::Open->value,
        ]);

        $response = $this->actingAs($user)->get(route('bug-reports.index', ['q' => 'QRIS']));

        $response->assertStatus(200);
        $response->assertSee('Gagal checkout pada metode pembayaran QRIS');
        $response->assertDontSee('Font avatar user terlalu kecil');
    }

    public function test_can_filter_bug_reports_by_severity(): void
    {
        $user = User::factory()->create(['role' => UserRole::Developer]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Server database down saat lonjakan traffic',
            'module' => 'API & Integrasi',
            'description' => 'Connection pool overload.',
            'severity' => BugSeverity::Critical->value,
            'status' => BugStatus::Open->value,
        ]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Warna footer kurang kontras',
            'module' => 'Dashboard',
            'description' => 'Perlu sedikit digelapkan.',
            'severity' => BugSeverity::Low->value,
            'status' => BugStatus::Open->value,
        ]);

        $response = $this->actingAs($user)->get(route('bug-reports.index', ['severity' => 'critical']));

        $response->assertStatus(200);
        $response->assertSee('Server database down saat lonjakan traffic');
        $response->assertDontSee('Warna footer kurang kontras');
    }

    public function test_can_filter_bug_reports_by_status(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Bug login dengan Google OAuth',
            'module' => 'Autentikasi & Akun',
            'description' => 'Error 500 saat callback.',
            'severity' => BugSeverity::High->value,
            'status' => BugStatus::Resolved->value,
        ]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Notifikasi email delayed',
            'module' => 'Notifikasi',
            'description' => 'Antrean email lambat terproses.',
            'severity' => BugSeverity::Medium->value,
            'status' => BugStatus::Open->value,
        ]);

        $response = $this->actingAs($user)->get(route('bug-reports.index', ['status' => 'resolved']));

        $response->assertStatus(200);
        $response->assertSee('Bug login dengan Google OAuth');
        $response->assertDontSee('Notifikasi email delayed');
    }

    public function test_can_filter_bug_reports_by_module(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Keranjang kosong setelah refresh',
            'module' => 'Keranjang Belanja',
            'description' => 'Session keranjang terhapus.',
            'severity' => BugSeverity::Medium->value,
            'status' => BugStatus::Open->value,
        ]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Pencarian nama produk tidak case-insensitive',
            'module' => 'Pencarian & Filter',
            'description' => 'Huruf besar tidak menampilkan hasil.',
            'severity' => BugSeverity::Low->value,
            'status' => BugStatus::Open->value,
        ]);

        $response = $this->actingAs($user)->get(route('bug-reports.index', ['module' => 'Keranjang Belanja']));

        $response->assertStatus(200);
        $response->assertSee('Keranjang kosong setelah refresh');
        $response->assertDontSee('Pencarian nama produk tidak case-insensitive');
    }

    public function test_can_filter_bug_reports_by_screenshot_existence(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Tampilan crash pada halaman login',
            'module' => 'Autentikasi & Akun',
            'description' => 'Tangkapan layar terlampir.',
            'severity' => BugSeverity::High->value,
            'status' => BugStatus::Open->value,
            'screenshot_path' => 'screenshots/sample.png',
            'screenshot_name' => 'sample.png',
            'screenshot_dimensions' => '1920×1080',
        ]);

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Typo pada tombol submit',
            'module' => 'Lainnya',
            'description' => 'Teks tombol salah ketik.',
            'severity' => BugSeverity::Low->value,
            'status' => BugStatus::Open->value,
            'screenshot_path' => null,
        ]);

        $responseWithScreenshot = $this->actingAs($user)->get(route('bug-reports.index', ['has_screenshot' => 'yes']));
        $responseWithScreenshot->assertStatus(200);
        $responseWithScreenshot->assertSee('Tampilan crash pada halaman login');
        $responseWithScreenshot->assertDontSee('Typo pada tombol submit');

        $responseWithoutScreenshot = $this->actingAs($user)->get(route('bug-reports.index', ['has_screenshot' => 'no']));
        $responseWithoutScreenshot->assertStatus(200);
        $responseWithoutScreenshot->assertSee('Typo pada tombol submit');
        $responseWithoutScreenshot->assertDontSee('Tampilan crash pada halaman login');
    }

    public function test_can_filter_by_scope_my_reports(): void
    {
        $userA = User::factory()->create(['name' => 'User A', 'email' => 'a@example.com']);
        $userB = User::factory()->create(['name' => 'User B', 'email' => 'b@example.com']);

        BugReport::create([
            'user_id' => $userA->id,
            'title' => 'Laporan dari User A',
            'module' => 'Dashboard',
            'description' => 'Deskripsi laporan milik User A.',
            'severity' => BugSeverity::Medium->value,
            'status' => BugStatus::Open->value,
        ]);

        BugReport::create([
            'user_id' => $userB->id,
            'title' => 'Laporan dari User B',
            'module' => 'Dashboard',
            'description' => 'Deskripsi laporan milik User B.',
            'severity' => BugSeverity::Medium->value,
            'status' => BugStatus::Open->value,
        ]);

        $response = $this->actingAs($userA)->get(route('bug-reports.index', ['scope' => 'my']));

        $response->assertStatus(200);
        $response->assertSee('Laporan dari User A');
        $response->assertDontSee('Laporan dari User B');
    }
}
