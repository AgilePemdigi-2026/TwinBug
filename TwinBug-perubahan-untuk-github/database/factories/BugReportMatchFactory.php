<?php

namespace Database\Factories;

use App\Models\BugReport;
use App\Models\BugReportMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BugReportMatch>
 */
class BugReportMatchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'bug_report_id' => BugReport::factory(),
            'matched_report_id' => BugReport::factory(),
            'text_similarity' => fake()->randomFloat(2, 70, 99),
            'image_similarity' => fake()->randomFloat(2, 60, 99),
        ];
    }

    public function textOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_similarity' => null,
        ]);
    }
}
