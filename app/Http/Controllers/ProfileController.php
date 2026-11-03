<?php

namespace App\Http\Controllers;

use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the profile page with role-aware statistics.
     */
    public function show(Request $request): View
    {
        $user = $request->user();

        $stats = match ($user->role) {
            UserRole::Reporter => $this->reporterStats($user),
            UserRole::Developer => $this->developerStats($user),
            UserRole::Admin => $this->adminStats(),
        };

        return view('profile.show', compact('user', 'stats'));
    }

    /**
     * Update the user's profile information (name only).
     */
    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $request->user()->update($request->validated());

        return to_route('profile')
            ->with('success', 'Informasi profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Kata sandi lama yang Anda masukkan tidak sesuai.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return to_route('profile')
            ->with('success', 'Kata sandi berhasil diperbarui. Gunakan kata sandi baru Anda saat login berikutnya.');
    }

    /**
     * Statistics for Reporter role.
     */
    private function reporterStats(User $user): array
    {
        $reports = $user->bugReports();

        return [
            'type' => 'reporter',
            'total' => $reports->count(),
            'open' => (clone $reports)->where('status', BugStatus::Open)->count(),
            'in_progress' => (clone $reports)->where('status', BugStatus::InProgress)->count(),
            'resolved' => (clone $reports)->where('status', BugStatus::Resolved)->count(),
            'closed' => (clone $reports)->where('status', BugStatus::Closed)->count(),
        ];
    }

    /**
     * Statistics for Developer role.
     */
    private function developerStats(User $user): array
    {
        $reports = BugReport::where('application_id', $user->application_id);

        return [
            'type' => 'developer',
            'total_system' => (clone $reports)->count(),
            'open' => (clone $reports)->where('status', BugStatus::Open)->count(),
            'in_progress' => (clone $reports)->where('status', BugStatus::InProgress)->count(),
            'resolved' => (clone $reports)->where('status', BugStatus::Resolved)->count(),
        ];
    }

    /**
     * Statistics for Admin role.
     */
    private function adminStats(): array
    {
        return [
            'type' => 'admin',
            'total_users' => User::count(),
            'total_bugs' => BugReport::count(),
            'open_bugs' => BugReport::where('status', BugStatus::Open)->count(),
            'resolved_bugs' => BugReport::where('status', BugStatus::Resolved)->count(),
        ];
    }
}
