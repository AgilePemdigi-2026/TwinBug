<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BugReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\EnsureUserHasApplication;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? to_route('dashboard') : view('auth.login');
});

Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('register.store');

    Route::get('/otp/verify', [OtpVerificationController::class, 'create'])
        ->name('otp.verify');
    Route::post('/otp/verify', [OtpVerificationController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('otp.verify.submit');
    Route::get('/otp/success', function () {
        return view('auth.otp-success');
    })->name('otp.success');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/application/onboarding', [ApplicationController::class, 'create'])->name('applications.onboarding');
    Route::post('/application/onboarding', [ApplicationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('applications.store');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::middleware(EnsureUserHasApplication::class)->group(function () {
        // Profile
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Bug Reports
        Route::get('/bug-reports', [BugReportController::class, 'index'])->name('bug-reports.index');
        Route::get('/bug-reports/create', [BugReportController::class, 'create'])->name('bug-reports.create');
        Route::post('/bug-reports', [BugReportController::class, 'store'])->name('bug-reports.store');
        Route::get('/bug-reports/{bugReport}', [BugReportController::class, 'show'])->name('bug-reports.show');
    });
});
