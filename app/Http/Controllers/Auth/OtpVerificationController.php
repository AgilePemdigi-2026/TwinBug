<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\OTPToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('pending_user_data')) {
            return to_route('register')
                ->withErrors(['email' => 'Silakan daftar terlebih dahulu.']);
        }

        return view('auth.otp-verify');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        $email = $request->session()->get('pending_otp_email');
        $pendingUserData = $request->session()->get('pending_user_data');

        if (! $email || ! $pendingUserData) {
            return to_route('register')
                ->withErrors(['email' => 'Sesi verifikasi telah berakhir. Silakan daftar ulang.']);
        }

        $otpToken = OTPToken::where('email', $email)->first();

        if (! $otpToken || ! Hash::check($request->input('otp_code'), $otpToken->otp_code)) {
            return back()
                ->withInput()
                ->withErrors(['otp_code' => 'Kode OTP salah atau sudah kedaluwarsa.']);
        }

        if ($otpToken->expires_at->isPast()) {
            $otpToken->delete();

            return back()
                ->withInput()
                ->withErrors(['otp_code' => 'Kode OTP telah kedaluwarsa.']);
        }

        User::create($pendingUserData);

        $otpToken->delete();

        $request->session()->forget(['pending_user_data', 'pending_otp_email']);

        return to_route('otp.success')
            ->with('status', 'Akun berhasil dibuat dan diverifikasi. Selamat datang!');
    }
}
