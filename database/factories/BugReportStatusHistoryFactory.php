<?php

namespace Database\Factories;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Models\BugReport;
use App\Models\BugReportStatusHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BugReportStatusHistory>
 */
class BugReportStatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bug_report_id' => function (): int {
                $reporter = User::factory()->create();

                return BugReport::create([
                    'user_id' => $reporter->id,
                    'title' => fake()->sentence(),
                    'description' => fake()->paragraph(),
                    'severity' => BugSeverity::Medium,
                    'status' => BugStatus::InProgress,
                ])->id;
            },
            'changed_by' => User::factory()->developer(),
            'from_status' => BugStatus::Open,
            'to_status' => BugStatus::InProgress,
        ];
    }
}
