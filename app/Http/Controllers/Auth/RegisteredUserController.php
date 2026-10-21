<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Mail\OtpVerificationCode;
use App\Models\OTPToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Throwable;

class RegisteredUserController extends Controller
{
    private const OTP_LIFETIME_MINUTES = 10;

    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'email', 'role', 'password']);

        $otpCode = (string) random_int(100000, 999999);

        OTPToken::updateOrCreate(
            ['email' => $data['email']],
            [
                'otp_code' => Hash::make($otpCode),
                'expires_at' => now()->addMinutes(self::OTP_LIFETIME_MINUTES),
            ],
        );

        $data['password'] = Hash::make($data['password']);

        Session::put('pending_user_data', $data);
        Session::put('pending_otp_email', $data['email']);

        try {
            Mail::to($data['email'])->send(new OtpVerificationCode(
                otp: $otpCode,
                expires: self::OTP_LIFETIME_MINUTES.' menit',
            ));
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput($request->only('name', 'email', 'role'))
                ->withErrors([
                    'email' => 'Kode OTP gagal dikirim. Silakan coba lagi.',
                ]);
        }

        return to_route('otp.verify')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }
}
