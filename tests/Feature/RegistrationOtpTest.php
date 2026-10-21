<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Mail\OtpVerificationCode;
use App\Models\OTPToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Mail\Markdown;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class RegistrationOtpTest extends TestCase
{
    use RefreshDatabase;

    private function registerPayload(): array
    {
        return [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => UserRole::Reporter->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => 'on',
        ];
    }

    public function test_otp_email_renders_without_view_namespace_error(): void
    {
        $html = (new OtpVerificationCode(otp: '123456'))->render();

        $this->assertStringContainsString('123456', $html);

        $text = app(Markdown::class)->renderText('emails.otp', [
            'title' => 'Kode OTP Verifikasi TwinBug',
            'otp' => '123456',
            'expires' => '10 menit',
        ]);

        $this->assertStringContainsString('123456', (string) $text);
    }

    public function test_registration_sends_email_through_real_transport_without_error(): void
    {
        $response = $this->post(route('register.store'), $this->registerPayload());

        $response->assertRedirect(route('otp.verify'));
        $response->assertSessionHasNoErrors();
    }

    public function test_registration_sends_otp_and_redirects_to_verification(): void
    {
        Mail::fake();

        $response = $this->post(route('register.store'), $this->registerPayload());

        $response->assertRedirect(route('otp.verify'));
        $this->assertDatabaseHas('otp_tokens', ['email' => 'budi@example.com']);
        $this->assertDatabaseMissing('users', ['email' => 'budi@example.com']);

        Mail::assertSent(OtpVerificationCode::class, function (OtpVerificationCode $mail): bool {
            return $mail->hasTo('budi@example.com') && preg_match('/^\d{6}$/', $mail->otp) === 1;
        });
    }

    public function test_user_can_verify_otp_and_account_is_created(): void
    {
        Mail::fake();

        $this->post(route('register.store'), $this->registerPayload());

        $otpCode = null;

        Mail::assertSent(OtpVerificationCode::class, function (OtpVerificationCode $mail) use (&$otpCode): bool {
            $otpCode = $mail->otp;

            return true;
        });

        $response = $this->post(route('otp.verify.submit'), ['otp_code' => $otpCode]);

        $response->assertRedirect(route('otp.success'));

        $user = User::where('email', 'budi@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password123', $user->password));
        $this->assertDatabaseMissing('otp_tokens', ['email' => 'budi@example.com']);
    }

    public function test_invalid_otp_is_rejected(): void
    {
        Mail::fake();

        $this->post(route('register.store'), $this->registerPayload());

        $response = $this->from(route('otp.verify'))->post(route('otp.verify.submit'), [
            'otp_code' => '000000',
        ]);

        $response->assertRedirect(route('otp.verify'));
        $response->assertSessionHasErrors('otp_code');
        $this->assertDatabaseMissing('users', ['email' => 'budi@example.com']);
    }

    public function test_expired_otp_is_rejected(): void
    {
        Mail::fake();

        $this->post(route('register.store'), $this->registerPayload());

        $otpCode = null;

        Mail::assertSent(OtpVerificationCode::class, function (OtpVerificationCode $mail) use (&$otpCode): bool {
            $otpCode = $mail->otp;

            return true;
        });

        OTPToken::where('email', 'budi@example.com')->update([
            'expires_at' => now()->subMinute(),
        ]);

        $response = $this->from(route('otp.verify'))->post(route('otp.verify.submit'), [
            'otp_code' => $otpCode,
        ]);

        $response->assertRedirect(route('otp.verify'));
        $response->assertSessionHasErrors('otp_code');
        $this->assertDatabaseMissing('users', ['email' => 'budi@example.com']);
    }
}
