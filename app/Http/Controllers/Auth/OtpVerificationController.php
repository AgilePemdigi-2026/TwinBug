<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Application;
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
        $pendingApplication = $request->session()->get('pending_application', []);

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

        $application = $this->resolveApplication($pendingUserData, $pendingApplication);

        if ($application === null) {
            $request->session()->forget(['pending_user_data', 'pending_otp_email', 'pending_application']);

            return to_route('register')
                ->withErrors(['application_join' => 'Aplikasi tidak ditemukan. Silakan daftar ulang dengan nama atau kode yang benar.']);
        }

        $pendingUserData['application_id'] = $application->id;

        User::create($pendingUserData);

        $otpToken->delete();

        $request->session()->forget(['pending_user_data', 'pending_otp_email', 'pending_application']);

        return to_route('otp.success')
            ->with('status', 'Akun berhasil dibuat dan diverifikasi. Selamat datang!');
    }

    /**
     * Resolve the application chosen during registration.
     *
     * @param  array<string, mixed>  $pendingUserData
     * @param  array<string, mixed>  $pendingApplication
     */
    private function resolveApplication(array $pendingUserData, array $pendingApplication): ?Application
    {
        if (($pendingUserData['role'] ?? null) === UserRole::Reporter->value) {
            $join = (string) ($pendingApplication['application_join'] ?? '');

            return Application::where('name', $join)
                ->orWhere('code', strtoupper($join))
                ->first();
        }

        return Application::firstOrCreate([
            'name' => (string) ($pendingApplication['application_name'] ?? ''),
        ]);
    }
}
