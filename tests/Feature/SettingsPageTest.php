<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_from_settings_page(): void
    {
        $response = $this->get(route('settings'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_manage_settings_page(): void
    {
        $user = User::factory()->create([
            'name' => 'Sample User',
            'email' => 'sample@twinbug.test',
            'role' => UserRole::Developer,
            'language' => 'en',
        ]);

        $response = $this->actingAs($user)->get(route('settings'));

        $response->assertOk();
        $response->assertSee('Manage Settings');
        $response->assertSee('General settings');
        $response->assertSee('Account preferences');
        $response->assertSee('Notification settings');
        $response->assertSee('Security settings');
        $response->assertSee('Appearance');
        $response->assertSee('Sample User');
        $response->assertSee('sample@twinbug.test');
        $response->assertSee('Not provided');
        $response->assertSee('Developer');
    }

    public function test_user_can_update_language_to_indonesian_and_enable_dark_theme(): void
    {
        $user = User::factory()->create([
            'language' => 'en',
            'theme' => 'light',
        ]);

        $response = $this->actingAs($user)->patch(route('settings.update'), [
            'language' => 'id',
            'timezone' => 'Asia/Jakarta',
            'date_format' => 'YYYY-MM-DD',
            'theme' => 'dark',
            'active_tab' => 'appearance',
            'notifications' => [
                'submission_confirmation' => '1',
                'detection_completed' => '0',
                'duplicate_results' => '1',
                'status_updates' => '1',
                'developer_reviews' => '0',
            ],
        ]);

        $response->assertRedirect(route('settings', ['tab' => 'appearance']));

        $user->refresh();
        $this->assertSame('id', $user->language);
        $this->assertSame('dark', $user->theme);
        $this->assertSame('YYYY-MM-DD', $user->date_format);
        $this->assertTrue($user->notification_preferences['submission_confirmation']);
        $this->assertFalse($user->notification_preferences['detection_completed']);

        $rendered = $this->actingAs($user)->get(route('settings'));
        $rendered->assertOk();
        $rendered->assertSee('lang="id"', false);
        $rendered->assertSee('data-theme="dark"', false);
        $rendered->assertSee('Kelola Pengaturan');
        $rendered->assertSee('Pengaturan umum');
    }

    public function test_user_can_reset_preferences_to_defaults(): void
    {
        $user = User::factory()->create([
            'language' => 'id',
            'theme' => 'dark',
            'date_format' => 'YYYY-MM-DD',
        ]);

        $response = $this->actingAs($user)->post(route('settings.reset'), [
            'active_tab' => 'preferences',
        ]);

        $response->assertRedirect(route('settings', ['tab' => 'preferences']));

        $user->refresh();
        $this->assertSame('en', $user->language);
        $this->assertSame('light', $user->theme);
        $this->assertSame('DD MMM YYYY', $user->date_format);
    }
}
