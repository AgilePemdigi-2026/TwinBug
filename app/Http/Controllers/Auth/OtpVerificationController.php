<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class OtpVerificationController extends Controller
{
    public function create(): View
    {
        return view('auth.otp-verify');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
        ]);

        $email = $request->session()->get('pending_otp_email');

        $otpToken = DB::table('otp_tokens')->where('email', $email)->first();

        if (! $otpToken || ! Hash::check($request->otp_code, $otpToken->otp_code)) {
            return back()
                ->withInput()
                ->withErrors(['otp_code' => 'Kode OTP salah atau sudah expired.']);
        }

        if ($otpToken->expires_at < now()) {
            DB::table('otp_tokens')->where('email', $email)->delete();

            return back()
                ->withInput()
                ->withErrors(['otp_code' => 'Kode OTP telah expired.']);
        }

        // Create the user account
        User::create($request->session()->get('pending_user_data'));

        // Delete the used OTP token
        DB::table('otp_tokens')->where('email', $email)->delete();

        return to_route('otp.success')
            ->with('status', 'Akun berhasil dibuat dan diverifikasi. Selamat datang!');
    }
}
