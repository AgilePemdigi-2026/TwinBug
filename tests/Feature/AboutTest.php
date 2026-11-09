<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_page_can_be_accessed_by_guests(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();
        $response->assertSee('Membangun Ekosistem Pelaporan Bug');
        $response->assertSee('Universitas Airlangga');
        $response->assertSee('Masuk ke Sistem');
    }

    public function test_about_page_can_be_accessed_by_authenticated_users(): void
    {
        $reporter = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($reporter)->get(route('about'));

        $response->assertOk();
        $response->assertSee('Membangun Ekosistem Pelaporan Bug');
        $response->assertSee('+ Buat Laporan Bug');
        $response->assertSee($reporter->name);
    }

    public function test_about_page_displays_all_four_developer_profiles(): void
    {
        $response = $this->get(route('about'));

        $response->assertOk();

        // Developer names
        $response->assertSee('Abdul Hakim Fatur Rochman');
        $response->assertSee('Ivan Raditya Prisetyo');
        $response->assertSee('Isa Jahwa Fatimiyah');
        $response->assertSee('Muhammad Fikhlas Hakim Arya Maulana');

        // NIMs
        $response->assertSee('187241111');
        $response->assertSee('187241072');
        $response->assertSee('187241029');
        $response->assertSee('187241034');

        // GitHub profiles
        $response->assertSee('https://github.com/AbdulHakimFaturR');
        $response->assertSee('https://github.com/Ivan-Raditya');
        $response->assertSee('https://github.com/isajahwa');
        $response->assertSee('https://github.com/fikhlassm');

        // Roles
        $response->assertSee('Scrum Master / Project Lead');
        $response->assertSee('Fullstack Developer & CI/CD');
        $response->assertSee('Frontend & Auth Developer');
        $response->assertSee('Backend & Security Developer');
    }
}
