<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BugReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_link_to_create_bug_report_page(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee(route('bug-reports.create'));
        $response->assertSee('Buat Laporan Bug Baru');
    }

    public function test_guest_is_redirected_from_create_bug_report_page(): void
    {
        $response = $this->get(route('bug-reports.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_create_bug_report_page(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->get(route('bug-reports.create'));

        $response->assertStatus(200);
        $response->assertSee('Formulir Pelaporan Bug');
        $response->assertSee('Identitas Permasalahan');
        $response->assertSee('Lampiran Screenshot');
        $response->assertSee('File terlampir');
        $response->assertSee('id="drop-zone"', false);
        $response->assertSee('id="screenshot"', false);
        $response->assertSee("addEventListener('paste'", false);
    }

    public function test_authenticated_user_can_submit_bug_report(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->post(route('bug-reports.store'), [
            'title' => 'Tombol checkout tidak dapat diklik pada browser Safari',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
            'description' => 'Langkah reproduksi: Masuk ke keranjang belanja, klik checkout, tombol loading terus menerus.',
        ]);

        $response->assertRedirect(route('report.history'));
        $response->assertSessionHas('success', 'Laporan bug berhasil dikirim!');
        $this->assertDatabaseHas('bug_reports', [
            'user_id' => $user->id,
            'title' => 'Tombol checkout tidak dapat diklik pada browser Safari',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
        ]);
    }

    public function test_authenticated_user_can_submit_bug_report_without_optional_fields(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->post(route('bug-reports.store'), [
            'title' => 'Halaman gagal dimuat',
            'description' => 'Halaman dashboard menampilkan blank screen setelah login',
        ]);

        $response->assertRedirect(route('report.history'));
        $response->assertSessionHas('success', 'Laporan bug berhasil dikirim!');

        $this->assertDatabaseHas('bug_reports', [
            'user_id' => $user->id,
            'title' => 'Halaman gagal dimuat',
            'severity' => BugSeverity::Medium->value,
            'status' => BugStatus::Open->value,
        ]);
    }

    public function test_missing_title_and_description_show_bdd_validation_message(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->post(route('bug-reports.store'), [
            'title' => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors([
            'title' => 'Laporan gagal dikirim. Judul dan deskripsi harus diisi!',
            'description' => 'Laporan gagal dikirim. Judul dan deskripsi harus diisi!',
        ]);
        $this->assertDatabaseMissing('bug_reports', [
            'user_id' => $user->id,
        ]);
    }

    public function test_authenticated_user_can_attach_screenshot_to_bug_report(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->post(route('bug-reports.store'), [
            'title' => 'Tombol checkout tidak dapat diklik pada browser Safari',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
            'description' => 'Langkah reproduksi: Masuk ke keranjang belanja, klik checkout, tombol loading terus menerus.',
            'screenshot' => UploadedFile::fake()->image('checkout-error.png', 800, 600),
        ]);

        $response->assertRedirect(route('report.history'));
        $response->assertSessionHasNoErrors();

        $report = BugReport::query()->where('user_id', $user->id)->firstOrFail();

        $this->assertTrue($report->hasScreenshot());
        $this->assertSame('checkout-error.png', $report->screenshot_name);
        $this->assertSame('800×600', $report->screenshot_dimensions);
        $this->assertGreaterThan(0, $report->screenshot_size);
        Storage::disk('public')->assertExists($report->screenshot_path);
    }

    public function test_screenshot_must_use_a_supported_image_format(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->post(route('bug-reports.store'), [
            'title' => 'Tombol checkout tidak dapat diklik pada browser Safari',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
            'description' => 'Langkah reproduksi: Masuk ke keranjang belanja, klik checkout, tombol loading terus menerus.',
            'screenshot' => UploadedFile::fake()->create('catatan.pdf', 100, 'application/pdf'),
        ]);

        $response->assertSessionHasErrors([
            'screenshot' => 'Laporan gagal dikirim. Format file tidak didukung. Gunakan JPG atau PNG.',
        ]);
        $this->assertDatabaseMissing('bug_reports', [
            'user_id' => $user->id,
        ]);
    }

    public function test_screenshot_cannot_exceed_five_megabytes(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->post(route('bug-reports.store'), [
            'title' => 'Tombol checkout tidak dapat diklik pada browser Safari',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
            'description' => 'Langkah reproduksi: Masuk ke keranjang belanja, klik checkout, tombol loading terus menerus.',
            'screenshot' => UploadedFile::fake()->image('screenshot-besar.png')->size(5121),
        ]);

        $response->assertSessionHasErrors('screenshot');
        $this->assertDatabaseMissing('bug_reports', [
            'user_id' => $user->id,
        ]);
    }

    public function test_dashboard_shows_attached_screenshot_thumbnail(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        Storage::disk('public')->put('screenshots/checkout-error.png', 'screenshot');

        BugReport::create([
            'user_id' => $user->id,
            'title' => 'Tombol checkout tidak dapat diklik pada browser Safari',
            'module' => 'Checkout & Pembayaran',
            'description' => 'Langkah reproduksi: Masuk ke keranjang belanja, klik checkout, tombol loading terus menerus.',
            'severity' => BugSeverity::High,
            'screenshot_path' => 'screenshots/checkout-error.png',
            'screenshot_name' => 'checkout-error.png',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee(Storage::disk('public')->url('screenshots/checkout-error.png'), false);
        $response->assertSee('Lihat');
    }
}
