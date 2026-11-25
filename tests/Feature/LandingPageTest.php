<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_access_landing_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('TwinBug');
        $response->assertSee('Detect Duplicate Bugs. Simplify Bug Management.');
        $response->assertSee('Login');
        $response->assertSee('Register');
        $response->assertSee('What is TwinBug?');
        $response->assertSee('How TwinBug Works');
        $response->assertSee('Frequently Asked Questions');
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
    }

    public function test_authenticated_user_redirected_to_dashboard(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
        ]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_landing_page_has_links_to_login_and_register(): void
    {
        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
    }
}
