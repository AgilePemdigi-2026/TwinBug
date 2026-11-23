<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Mail\OtpVerificationCode;
use App\Models\Application;
use App\Models\OTPToken;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpResendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        Carbon::setTestNow(null);
    }

    private function registerPayload(): array
    {
        $application = Application::factory()->create(['name' => 'TwinBug Test']);

        return [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'role' => UserRole::Reporter->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'application_join' => $application->name,
            'terms' => 'on',
        ];
    }

    private function getSentOtp(): ?string
    {
        $otp = null;

        Mail::assertSent(OtpVerificationCode::class, function (OtpVerificationCode $mail) use (&$otp): bool {
            $otp = $mail->otp;

            return true;
        });

        return $otp;
    }

    public function test_resend_creates_new_otp_and_invalidates_old(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-01-01 10:00:00'));

        // Register to create initial OTP (sets cooldown in cache)
        $response = $this->post(route('register.store'), $this->registerPayload());
        $response->assertSessionHas('pending_otp_email');

        $oldOtp = $this->getSentOtp();
        $this->assertNotNull($oldOtp);

        // Clear cooldown set during registration
        Cache::forget('otp.resend.cooldown:budi@example.com');

        // Resend OTP - reset mail fake to count only resend mail
        Mail::fake();
        $response = $this->from(route('otp.verify'))->post(route('otp.resend'));

        $response->assertRedirect(route('otp.verify'));
        $response->assertSessionHas('status', 'Kode OTP baru telah dikirim ke email Anda.');

        // Exactly one mail sent for resend
        Mail::assertSentCount(1, OtpVerificationCode::class);

        $newOtp = $this->getSentOtp();
        $this->assertNotNull($newOtp);
        $this->assertNotSame($oldOtp, $newOtp);

        // Old OTP should fail
        $oldResponse = $this->from(route('otp.verify'))->post(route('otp.verify.submit'), [
            'otp_code' => $oldOtp,
        ]);
        $oldResponse->assertRedirect(route('otp.verify'));
        $oldResponse->assertSessionHasErrors('otp_code');

        // New OTP should work
        $newResponse = $this->from(route('otp.verify'))->post(route('otp.verify.submit'), [
            'otp_code' => $newOtp,
        ]);
        $newResponse->assertRedirect(route('otp.success'));

        Carbon::setTestNow(null);
    }

    public function test_resend_blocks_within_cooldown(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-01-01 10:00:00'));

        $this->post(route('register.store'), $this->registerPayload());

        // Clear cooldown set during registration
        Cache::forget('otp.resend.cooldown:budi@example.com');

        // First resend should succeed (no cooldown in cache)
        $response1 = $this->from(route('otp.verify'))->post(route('otp.resend'));
        $response1->assertRedirect(route('otp.verify'));

        // Second resend immediately should be blocked by cooldown
        $response2 = $this->from(route('otp.verify'))->post(route('otp.resend'));
        $response2->assertRedirect(route('otp.verify'));
        $response2->assertSessionHasErrors('otp_resend');
        $this->assertStringContainsString('detik', $response2->getSession()->get('errors')->first('otp_resend'));

        Carbon::setTestNow(null);
    }

    public function test_resend_blocks_after_hourly_limit(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-01-01 10:00:00'));

        $this->post(route('register.store'), $this->registerPayload());

        // Clear cooldown set during registration
        Cache::forget('otp.resend.cooldown:budi@example.com');

        // Send 5 resends (the limit), advancing time past cooldown each time
        for ($i = 0; $i < 5; $i++) {
            Mail::fake(); // Reset mail fake for each resend
            $response = $this->from(route('otp.verify'))->post(route('otp.resend'));
            $response->assertRedirect(route('otp.verify'));
            $response->assertSessionHas('status', 'Kode OTP baru telah dikirim ke email Anda.');

            // Advance time past cooldown (61 seconds) - but NOT after the last resend
            if ($i < 4) {
                Carbon::setTestNow(Carbon::now()->addSeconds(61));
            }
        }

        // 6th resend should be blocked by cooldown (still within 60s window)
        $response = $this->from(route('otp.verify'))->post(route('otp.resend'));
        $response->assertRedirect(route('otp.verify'));
        $response->assertSessionHasErrors('otp_resend');

        Carbon::setTestNow(null);
    }

    public function test_resend_without_session_redirects_to_register(): void
    {
        Mail::fake();
        Carbon::setTestNow(null);

        $response = $this->from(route('otp.verify'))->post(route('otp.resend'));

        $response->assertRedirect(route('register'));
        $response->assertSessionHasErrors('email');
    }

    public function test_resend_updates_otp_sent_at(): void
    {
        Mail::fake();
        Carbon::setTestNow(Carbon::parse('2026-01-01 10:00:00'));

        $this->post(route('register.store'), $this->registerPayload());

        $tokenBefore = OTPToken::where('email', 'budi@example.com')->first();
        $this->assertNotNull($tokenBefore->otp_sent_at);

        // Clear cooldown set during registration
        Cache::forget('otp.resend.cooldown:budi@example.com');

        // Advance time past cooldown
        Carbon::setTestNow(Carbon::parse('2026-01-01 10:01:01'));

        $response = $this->from(route('otp.verify'))->post(route('otp.resend'));
        $response->assertRedirect(route('otp.verify'));

        $tokenAfter = OTPToken::where('email', 'budi@example.com')->first();
        $this->assertNotNull($tokenAfter->otp_sent_at);
        $this->assertTrue($tokenAfter->otp_sent_at->greaterThan($tokenBefore->otp_sent_at));

        Carbon::setTestNow(null);
    }
}
