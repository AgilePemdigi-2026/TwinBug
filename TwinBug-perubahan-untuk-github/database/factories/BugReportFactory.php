<?php

namespace Database\Factories;

use App\Enums\AnalysisStatus;
use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BugReport>
 */
class BugReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(5),
            'module' => 'Checkout & Pembayaran',
            'description' => fake()->paragraph(4),
            'severity' => BugSeverity::Medium,
            'status' => BugStatus::Open,
            'analysis_status' => AnalysisStatus::Completed,
        ];
    }

    public function duplicate(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BugStatus::Duplicate,
        ]);
    }

    public function processing(): static
    {
        return $this->state(fn (array $attributes) => [
            'analysis_status' => AnalysisStatus::Processing,
        ]);
    }
}
