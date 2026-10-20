<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Models\OTPToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['name', 'email', 'role', 'password']);

        // Generate 6-digit OTP
        $otpCode = Str::random(6);

        // Store OTP in database
        OTPToken::updateOrCreate(
            ['email' => $data['email']],
            ['otp_code' => $otpCode, 'expires_at' => now()->addMinutes(10)]
        );

        // Store pending user data in session for later creation
        Session::put('pending_user_data', $data);

        // Store email for OTP verification
        Session::put('pending_otp_email', $data['email']);

        // Send OTP via email
        Mail::send('emails.otp', [
            'title' => 'Kode OTP Verifikasi TwinBug',
            'otp' => $otpCode,
            'expires' => '10 menit',
        ], function ($message) use ($data) {
            $message->to($data['email'])
                    ->subject('Kode OTP Verifikasi TwinBug');
            $message->from('no-reply@twinbug.local', 'TwinBug');
        });

        return to_route('otp.verify')
            ->with('status', 'Kode OTP telah dikirim ke email Anda.');
    }
}
