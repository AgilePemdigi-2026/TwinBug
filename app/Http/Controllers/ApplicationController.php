<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ApplicationController extends Controller
{
    /**
     * Show the onboarding form for users without an application.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if ($request->user()->application_id !== null) {
            return to_route('dashboard');
        }

        return view('applications.onboarding', ['user' => $request->user()]);
    }

    /**
     * Create an application for developers or join one for reporters.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->application_id !== null) {
            return to_route('dashboard');
        }

        if ($user->role === UserRole::Developer) {
            $validated = $request->validate([
                'application_name' => ['required', 'string', 'max:255', 'unique:applications,name'],
            ]);

            $application = Application::create(['name' => $validated['application_name']]);
        } else {
            $validated = $request->validate([
                'application_join' => ['required', 'string', 'max:255'],
            ]);

            $application = Application::where('name', $validated['application_join'])
                ->orWhere('code', strtoupper($validated['application_join']))
                ->first();

            if ($application === null) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'application_join' => 'Aplikasi tidak ditemukan. Periksa kembali nama atau kode dari developer.',
                    ]);
            }
        }

        $user->update(['application_id' => $application->id]);

        return to_route('dashboard')
            ->with('success', 'Akun Anda berhasil terhubung ke aplikasi '.$application->name.'.');
    }
}
