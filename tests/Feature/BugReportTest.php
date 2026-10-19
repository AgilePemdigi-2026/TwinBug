<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $response->assertRedirect(route('bug-reports.create'));
        $this->assertDatabaseHas('bug_reports', [
            'user_id' => $user->id,
            'title' => 'Tombol checkout tidak dapat diklik pada browser Safari',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::High->value,
        ]);
    }
}
