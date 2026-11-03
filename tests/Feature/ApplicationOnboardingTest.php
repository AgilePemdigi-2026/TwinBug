<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Mail\OtpVerificationCode;
use App\Models\Application;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ApplicationOnboardingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_application_is_redirected_to_onboarding(): void
    {
        $user = User::factory()->create(['application_id' => null]);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertRedirect(route('applications.onboarding'));
    }

    public function test_reporter_can_join_using_application_name(): void
    {
        $application = Application::factory()->create(['name' => 'Kampus App']);
        $user = User::factory()->create(['application_id' => null]);

        $this->actingAs($user)
            ->post(route('applications.store'), ['application_join' => 'Kampus App'])
            ->assertRedirect(route('dashboard'));

        $this->assertSame($application->id, $user->fresh()->application_id);
    }

    public function test_reporter_can_join_using_invitation_code(): void
    {
        $application = Application::factory()->create(['code' => 'ABC12345']);
        $user = User::factory()->create(['application_id' => null]);

        $this->actingAs($user)
            ->post(route('applications.store'), ['application_join' => 'abc12345'])
            ->assertRedirect(route('dashboard'));

        $this->assertSame($application->id, $user->fresh()->application_id);
    }

    public function test_invalid_application_is_rejected(): void
    {
        $user = User::factory()->create(['application_id' => null]);

        $this->actingAs($user)
            ->post(route('applications.store'), ['application_join' => 'Tidak Ada'])
            ->assertSessionHasErrors('application_join');

        $this->assertNull($user->fresh()->application_id);
    }

    public function test_developer_can_create_application_from_onboarding(): void
    {
        $user = User::factory()->create([
            'role' => UserRole::Developer,
            'application_id' => null,
        ]);

        $this->actingAs($user)
            ->post(route('applications.store'), ['application_name' => 'Aplikasi Baru'])
            ->assertRedirect(route('dashboard'));

        $application = Application::where('name', 'Aplikasi Baru')->first();

        $this->assertNotNull($application);
        $this->assertNotEmpty($application->code);
        $this->assertSame($application->id, $user->fresh()->application_id);
    }

    public function test_linked_user_cannot_access_onboarding_again(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('applications.onboarding'))
            ->assertRedirect(route('dashboard'));
    }

    public function test_registration_requires_existing_application_for_reporter(): void
    {
        $this->post(route('register.store'), $this->registrationPayload())
            ->assertSessionHasErrors('application_join');

        $this->assertDatabaseMissing('users', ['email' => 'budi@example.com']);
    }

    public function test_reporter_is_linked_to_application_after_otp_verification(): void
    {
        Mail::fake();

        $application = Application::factory()->create(['name' => 'Kampus App']);

        $this->post(route('register.store'), $this->registrationPayload([
            'application_join' => 'Kampus App',
        ]));

        $this->post(route('otp.verify.submit'), ['otp_code' => $this->sentOtp()])
            ->assertRedirect(route('otp.success'));

        $user = User::where('email', 'budi@example.com')->first();

        $this->assertNotNull($user);
        $this->assertSame($application->id, $user->application_id);
        $this->assertSame(UserRole::Reporter, $user->role);
    }

    public function test_developer_registration_creates_application_after_otp_verification(): void
    {
        Mail::fake();

        $this->post(route('register.store'), $this->registrationPayload([
            'role' => UserRole::Developer->value,
            'application_name' => 'Aplikasi Developer',
        ]));

        $this->post(route('otp.verify.submit'), ['otp_code' => $this->sentOtp()])
            ->assertRedirect(route('otp.success'));

        $application = Application::where('name', 'Aplikasi Developer')->first();
        $user = User::where('email', 'budi@example.com')->first();

        $this->assertNotNull($application);
        $this->assertNotEmpty($application->code);
        $this->assertNotNull($user);
        $this->assertSame($application->id, $user->application_id);
        $this->assertSame(UserRole::Developer, $user->role);
    }

    /**
     * @param  array<string, mixed>  $overrides
     * @return array<string, mixed>
     */
    private function registrationPayload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => UserRole::Reporter->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ], $overrides);
    }

    private function sentOtp(): string
    {
        $otp = null;

        Mail::assertSent(OtpVerificationCode::class, function (OtpVerificationCode $mail) use (&$otp): bool {
            $otp = $mail->otp;

            return true;
        });

        return $otp;
    }
}
