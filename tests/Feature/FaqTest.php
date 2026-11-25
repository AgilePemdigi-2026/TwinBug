<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FaqTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_page_can_be_accessed_by_guests(): void
    {
        $response = $this->get(route('faq'));

        $response->assertOk();
        $response->assertSee('Frequently Asked Questions');
        $response->assertSee('Help center');
        $response->assertSee('Akun &amp; OTP', false);
        $response->assertSee('Pelaporan Bug');
        $response->assertSee('Deteksi Duplikasi');
        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
    }

    public function test_faq_page_can_be_accessed_by_authenticated_users(): void
    {
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($reporter)->get(route('faq'));

        $response->assertOk();
        $response->assertSee('Frequently Asked Questions');
        $response->assertSee('All Questions');
        $response->assertSee($reporter->name);
    }

    public function test_faq_page_renders_key_technical_topics(): void
    {
        $response = $this->get(route('faq'));

        $response->assertOk();
        $response->assertSee('Bagaimana alur pendaftaran akun di TwinBug?');
        $response->assertSee('Mengapa saya belum menerima kode OTP verifikasi?');
        $response->assertSee('Format dan batas ukuran file screenshot apa saja yang diterima?');
        $response->assertSee('Apa itu fitur deteksi duplikasi (Twin Bug Detection)?');
        $response->assertSee('Apa arti status pada setiap laporan bug?');
    }
}
