<?php

namespace Tests\Feature;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BugReportStatusAndTwinTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_bug_report_with_rich_metadata(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $response = $this->actingAs($user)->post(route('bug-reports.store'), [
            'title' => 'Gagal checkout saat menggunakan kupon diskon',
            'module' => 'Checkout & Pembayaran',
            'severity' => BugSeverity::Critical->value,
            'description' => 'Aplikasi error 500 saat mencoba klik checkout setelah input kupon promo diskon.',
            'environment' => 'staging',
            'steps_to_reproduce' => "1. Masukkan kupon PROMO50\n2. Klik Checkout",
            'expected_result' => 'Diskon terpotong dan pembayaran berlanjut.',
            'actual_result' => 'Muncul toast error 500 server error.',
            'url' => 'https://staging.app.test/checkout',
            'device_metadata' => [
                'browser' => 'Chrome',
                'os' => 'Windows',
                'screen' => '1920×1080',
            ],
        ]);

        $response->assertRedirect(route('bug-reports.create'));

        $this->assertDatabaseHas('bug_reports', [
            'user_id' => $user->id,
            'title' => 'Gagal checkout saat menggunakan kupon diskon',
            'environment' => 'staging',
            'url' => 'https://staging.app.test/checkout',
        ]);

        $report = BugReport::where('title', 'Gagal checkout saat menggunakan kupon diskon')->firstOrFail();
        $this->assertSame('Chrome', $report->device_metadata['browser']);
        $this->assertSame("1. Masukkan kupon PROMO50\n2. Klik Checkout", $report->steps_to_reproduce);
    }

    public function test_check_similar_endpoint_returns_potential_duplicates(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        BugReport::create([
            'user_id' => $user->id,
            'application_id' => $user->application_id,
            'title' => 'Gagal bayar dengan metode QRIS mandiri',
            'module' => 'Checkout & Pembayaran',
            'description' => 'QRIS tidak responsif saat transaksi dilakukan malam hari.',
            'severity' => BugSeverity::Medium->value,
            'status' => BugStatus::Open->value,
        ]);

        $response = $this->actingAs($user)->getJson(route('bug-reports.check-similar', [
            'title' => 'Gagal bayar QRIS di checkout',
            'module' => 'Checkout & Pembayaran',
        ]));

        $response->assertOk();
        $response->assertJsonStructure([
            'similar' => [
                '*' => ['id', 'title', 'module', 'status', 'status_label'],
            ],
        ]);

        $data = $response->json('similar');
        $this->assertNotEmpty($data);
        $this->assertSame('Gagal bayar dengan metode QRIS mandiri', $data[0]['title']);
    }

    public function test_find_similar_reports_identifies_matching_bugs(): void
    {
        $user = User::factory()->create(['role' => UserRole::Developer]);

        $report1 = BugReport::create([
            'user_id' => $user->id,
            'application_id' => $user->application_id,
            'title' => 'Metode QRIS gagal memuat kode bayar',
            'module' => 'Checkout & Pembayaran',
            'description' => 'Deskripsi panjang bug pertama mengenai kendala QRIS.',
            'severity' => BugSeverity::High->value,
            'status' => BugStatus::Open->value,
        ]);

        $report2 = BugReport::create([
            'user_id' => $user->id,
            'application_id' => $user->application_id,
            'title' => 'Kode bayar QRIS blank putih',
            'module' => 'Checkout & Pembayaran',
            'description' => 'Deskripsi kedua tentang masalah serupa pada QRIS.',
            'severity' => BugSeverity::High->value,
            'status' => BugStatus::InProgress->value,
        ]);

        $similar = $report1->findSimilarReports();

        $this->assertTrue($similar->contains('id', $report2->id));
        $this->assertGreaterThanOrEqual(30, $similar->first()->similarity_score);
    }

    public function test_detail_page_displays_metadata(): void
    {
        $user = User::factory()->create(['role' => UserRole::Developer]);

        $report = BugReport::create([
            'user_id' => $user->id,
            'application_id' => $user->application_id,
            'title' => 'Metode QRIS gagal memuat kode bayar',
            'module' => 'Checkout & Pembayaran',
            'description' => 'Deskripsi panjang bug pertama mengenai kendala QRIS.',
            'severity' => BugSeverity::High->value,
            'status' => BugStatus::Open->value,
            'environment' => 'staging',
            'steps_to_reproduce' => '1. Buka kasir, 2. Pilih QRIS',
            'expected_result' => 'QR Code tampil',
            'actual_result' => 'Layar blank',
            'url' => 'https://app.test/checkout',
            'device_metadata' => [
                'browser' => 'Chrome',
                'os' => 'Windows',
            ],
        ]);

        $response = $this->actingAs($user)->get(route('bug-reports.show', $report));

        $response->assertOk();
        $response->assertSee('Env: staging');
        $response->assertSee('Rincian Teknis &amp; Reproduksi', false);
        $response->assertSee('1. Buka kasir, 2. Pilih QRIS');
        $response->assertSee('https://app.test/checkout');
    }
}
