<?php

namespace Database\Factories;

use App\Models\BugReport;
use App\Models\DetectionResult;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DetectionResult>
 */
class DetectionResultFactory extends Factory
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
            'candidate_report_id' => BugReport::factory(),
            'rank' => fake()->randomDigit(),
            'text_score' => fake()->randomFloat(6, 0, 1),
            'model' => 'paraphrase-multilingual-MiniLM-L12-v2',
        ];
    }
}
