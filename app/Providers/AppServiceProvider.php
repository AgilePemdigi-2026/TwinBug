<?php

namespace App\Providers;

use App\Models\DetectionResult;
use App\Policies\DetectionResultPolicy;
use App\Services\DuplicateDetectionClient;
use App\Services\ScreenshotAnalysisClient;
use Illuminate\Support\Facades\Gate;
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
    }
}
