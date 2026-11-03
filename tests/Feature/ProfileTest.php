<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_profile_page(): void
    {
        $response = $this->get(route('profile'));

        $response->assertRedirect(route('login'));
    }

    public function test_reporter_can_view_profile_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee($user->name);
        $response->assertSee($user->email);
        $response->assertSee('Reporter');
        $response->assertSee('Statistik Laporan Saya');
    }

    public function test_developer_can_view_profile_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::Developer]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('Developer');
        $response->assertSee('Statistik Bug Sistem');
    }

    public function test_admin_can_view_profile_page(): void
    {
        $user = User::factory()->create(['role' => UserRole::Admin]);

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertStatus(200);
        $response->assertSee('Administrator');
        $response->assertSee('Statistik Platform');
    }

    public function test_user_can_update_their_name(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => 'Nama Baru Saya',
        ]);

        $response->assertRedirect(route('profile'));
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nama Baru Saya',
        ]);
    }

    public function test_user_cannot_update_name_with_empty_value(): void
    {
        $user = User::factory()->create(['role' => UserRole::Reporter]);

        $response = $this->actingAs($user)->patch(route('profile.update'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_user_can_change_password_with_correct_current_password(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.password'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewPassword456!',
            'password_confirmation' => 'NewPassword456!',
        ]);

        $response->assertRedirect(route('profile'));
        $response->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('NewPassword456!', $user->fresh()->password));
    }

    public function test_user_cannot_change_password_with_wrong_current_password(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
            'password' => Hash::make('CorrectPassword123!'),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.password'), [
            'current_password' => 'WrongPassword!',
            'password' => 'NewPassword456!',
            'password_confirmation' => 'NewPassword456!',
        ]);

        $response->assertSessionHasErrors('current_password');
    }

    public function test_user_cannot_change_password_when_confirmation_does_not_match(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Reporter,
            'password' => Hash::make('OldPassword123!'),
        ]);

        $response = $this->actingAs($user)->patch(route('profile.password'), [
            'current_password' => 'OldPassword123!',
            'password' => 'NewPassword456!',
            'password_confirmation' => 'TidakSama999!',
        ]);

        $response->assertSessionHasErrors('password');
    }
}
