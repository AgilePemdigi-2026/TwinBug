<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationCode;
use App\Models\OTPToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Throwable;

class OtpResendController extends Controller
{
    private const OTP_LIFETIME_MINUTES = 10;

    /**
     * Resend OTP code for registration verification.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $email = $request->session()->get('pending_otp_email');

        if (! $email) {
            return to_route('register')
                ->withErrors(['email' => 'Sesi verifikasi telah berakhir. Silakan daftar ulang.']);
        }

        // If the account is already verified, do not resend.
        $user = User::where('email', $email)->first();
        if ($user && ! is_null($user->email_verified_at)) {
            return to_route('login')
                ->with('status', 'Akun telah terverifikasi. Silakan masuk.');
        }

        // Apply rate limiting: per email and per IP.
        $emailKey = 'otp.resend.email:' . $email;
        $ipKey = 'otp.resend.ip:' . $request->ip();

        $maxEmail = (int) config('auth.otp.max_resends_per_hour', 5);
        $maxIp = (int) config('auth.otp.resend_limit_per_ip', 5);

        if (RateLimiter::tooManyAttempts($emailKey, $maxEmail)) {
            $retryAfter = RateLimiter::availableIn($emailKey);

            return back()
                ->withErrors([
                    'otp_resend' => 'Terlalu banyak permintaan. Coba lagi dalam ' . $retryAfter . ' detik.',
                ]);
        }

        if (RateLimiter::tooManyAttempts($ipKey, $maxIp)) {
            $retryAfter = RateLimiter::availableIn($ipKey);

            return back()
                ->withErrors([
                    'otp_resend' => 'Terlalu banyak permintaan dari IP ini. Coba lagi dalam ' . $retryAfter . ' detik.',
                ]);
        }

        // Cooldown check: minimum 60 seconds between sends per email.
        $cooldownSeconds = (int) config('auth.otp.resend_cooldown', 60);
        $cooldownKey = 'otp.resend.cooldown:' . $email;

        if (Cache::has($cooldownKey)) {
            $remaining = (int) Cache::get($cooldownKey);
            return back()
                ->withErrors([
                    'otp_resend' => 'Tunggu ' . $remaining . ' detik sebelum mengirim ulang kode OTP.',
                ]);
        }

        $otpCode = (string) random_int(100000, 999999);

        OTPToken::updateOrCreate(
            ['email' => $email],
            [
                'otp_code' => Hash::make($otpCode),
                'expires_at' => now()->addMinutes(self::OTP_LIFETIME_MINUTES),
                'otp_sent_at' => now(),
            ],
        );

        // Increment rate limiter counters.
        RateLimiter::hit($emailKey);
        RateLimiter::hit($ipKey);

        try {
            Mail::to($email)->send(new OtpVerificationCode(
                otp: $otpCode,
                expires: self::OTP_LIFETIME_MINUTES . ' menit',
            ));
        } catch (Throwable $exception) {
            report($exception);
            Log::warning('OTP resend mail failed for email {email}', [
                'email' => $email,
            ]);

            return back()
                ->withErrors([
                    'otp_resend' => 'Kode OTP gagal dikirim. Silakan coba lagi.',
                ]);
        }

        // Set cooldown in cache
        Cache::put($cooldownKey, $cooldownSeconds, now()->addSeconds($cooldownSeconds));

        return to_route('otp.verify')
            ->with('status', 'Kode OTP baru telah dikirim ke email Anda.');
    }
}
