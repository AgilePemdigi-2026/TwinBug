<?php

namespace App\Jobs;

use App\Enums\DetectionStatus;
use App\Models\BugReport;
use App\Models\DetectionResult;
use App\Services\DuplicateDetectionClient;
use App\Services\ScreenshotAnalysisClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class AnalyzeBugReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public $backoff = 60;

    /**
     * The maximum time (in seconds) the job can run.
     */
    public $timeout = 180;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public BugReport $bugReport,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(DuplicateDetectionClient $client, ScreenshotAnalysisClient $vision): void
    {
        $this->bugReport->refresh();

        $this->bugReport->update([
            'detection_status' => DetectionStatus::Processing->value,
            'detection_error' => null,
        ]);

        $this->ensureImageDescription($vision);

        $applicationId = $this->bugReport->application_id;
        $candidates = BugReport::where('application_id', $applicationId)
            ->where('id', '!=', $this->bugReport->id)
            ->latest('id')
            ->limit(config('services.ml.max_candidates', 500))
            ->get()
            ->map(fn (BugReport $report) => [
                'id' => $report->id,
                'summary' => $report->title,
                'description' => $this->similarityText($report),
            ])
            ->values()
            ->all();

        $source = [
            'summary' => $this->bugReport->title,
            'description' => $this->similarityText($this->bugReport),
        ];

        $result = $client->detect($source, $candidates);

        DB::transaction(function () use ($result, $client): void {
            // Delete old results first so re-analysis never violates the unique constraint.
            $this->bugReport->detectionResults()->delete();

            foreach ($result['results'] as $rank => $match) {
                $candidateReport = BugReport::find($match['id']);

                if ($candidateReport === null) {
                    continue;
                }

                DetectionResult::create([
                    'bug_report_id' => $this->bugReport->id,
                    'candidate_report_id' => $candidateReport->id,
                    'rank' => $rank + 1,
                    'text_score' => (float) $match['text_score'],
                    'model' => $client->config()['model'],
                ]);
            }

            $this->bugReport->update([
                'detection_status' => DetectionStatus::Completed->value,
                'detection_threshold' => (float) ($result['threshold_used'] ?? 0),
                'detected_at' => now(),
            ]);
        });
    }

    /**
     * Ensure the source report has an AI-generated screenshot description.
     */
    private function ensureImageDescription(ScreenshotAnalysisClient $vision): void
    {
        if (! $this->bugReport->hasScreenshot() || $this->bugReport->hasImageDescription()) {
            return;
        }

        if (! $vision->configured()) {
            return;
        }

        try {
            $description = $vision->analyze($this->bugReport);

            $this->bugReport->update([
                'image_description' => $description,
                'image_analyzed_at' => now(),
            ]);
        } catch (Throwable $e) {
            Log::warning('Screenshot analysis failed for bug report {id}', [
                'id' => $this->bugReport->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Build the text sent to the similarity model, including visual context.
     */
    private function similarityText(BugReport $report): string
    {
        $description = $report->description ?? '';

        if (! $report->hasImageDescription()) {
            return $description;
        }

        return "Konteks visual screenshot: {$report->image_description}\n\n{$description}";
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->bugReport->refresh();

        $this->bugReport->update([
            'detection_status' => DetectionStatus::Failed->value,
            'detection_error' => $exception->getMessage(),
        ]);

        Log::warning('Duplicate detection failed for bug report {id}', [
            'id' => $this->bugReport->id,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Get the display name for the job.
     */
    public function displayName(): string
    {
        return 'Analyze bug report (duplicate detection)';
    }
}
