<?php

namespace App\Providers;

use App\Models\DetectionResult;
use App\Policies\DetectionResultPolicy;
use App\Services\DuplicateDetectionClient;
use App\Services\ScreenshotAnalysisClient;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(DuplicateDetectionClient::class, function () {
            return DuplicateDetectionClient::fromConfig();
        });

        $this->app->singleton(ScreenshotAnalysisClient::class, function () {
            return ScreenshotAnalysisClient::fromConfig();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(DetectionResult::class, DetectionResultPolicy::class);

        RateLimiter::for('otp.resend.email', function (Request $request) {
            return Limit::perHour((int) config('auth.otp.max_resends_per_hour', 5))
                ->by($request->input('email', $request->ip()));
        });

        RateLimiter::for('otp.resend.ip', function (Request $request) {
            return Limit::perHour((int) config('auth.otp.resend_limit_per_ip', 5))
                ->by($request->ip());
        });
    }
}
