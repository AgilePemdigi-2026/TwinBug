<?php

namespace App\Jobs;

use App\Enums\DetectionStatus;
use App\Enums\OcrStatus;
use App\Models\BugReport;
use App\Models\DetectionResult;
use App\Jobs\ExtractScreenshotText;
use App\Services\DuplicateDetectionClient;
use App\Services\ImageHasher;
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
    public function handle(DuplicateDetectionClient $client, ScreenshotAnalysisClient $vision, ImageHasher $hasher): void
    {
        $this->bugReport->refresh();

        // --- OCR step (best-effort, never blocks duplicate detection) ---
        if ($this->bugReport->ocrIsPending()) {
            $this->bugReport->update([
                'ocr_status' => OcrStatus::Processing->value,
            ]);

            try {
                (new ExtractScreenshotText($this->bugReport))->handle($client);
            } catch (Throwable $e) {
                // Safety net: ExtractScreenshotText handles its own failures,
                // but we guard here so detection always proceeds.
                $this->bugReport->update([
                    'ocr_status' => OcrStatus::Failed->value,
                    'ocr_error' => $e->getMessage(),
                ]);
            }
        }

        // --- Screenshot hash step (best-effort, never blocks detection) ---
        if ($this->bugReport->hashIsPending()) {
            try {
                $hash = $hasher->hash($this->bugReport->screenshot_path);
                if ($hash !== null) {
                    $this->bugReport->update([
                        'screenshot_hash' => $hash,
                    ]);
                }
            } catch (Throwable $e) {
                Log::warning('Screenshot hashing failed for bug report {id}', [
                    'id' => $this->bugReport->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        $this->bugReport->update([
            'detection_status' => DetectionStatus::Processing->value,
            'detection_error' => null,
        ]);

        $this->ensureImageDescription($vision);

        $applicationId = $this->bugReport->application_id;

        // --- Text-based candidates (existing flow) ---
        $textCandidates = BugReport::where('application_id', $applicationId)
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

        $result = $client->detect($source, $textCandidates);

        // --- Image-only candidates (screenshot hash match) ---
        $imageMatches = [];
        if ($this->bugReport->hasScreenshotHash()) {
            $imageThreshold = (float) config('detection.image_match_threshold', 0.90);
            $imageMaxCandidates = (int) config('detection.image_match_max_candidates', 500);

            $imageCandidates = BugReport::where('application_id', $applicationId)
                ->where('id', '!=', $this->bugReport->id)
                ->whereNotNull('screenshot_hash')
                ->latest('id')
                ->limit($imageMaxCandidates)
                ->get();

            foreach ($imageCandidates as $candidate) {
                $distance = $hasher->hammingDistance(
                    $this->bugReport->screenshot_hash,
                    $candidate->screenshot_hash
                );
                $imageScore = 1.0 - ($distance / 64.0);

                if ($imageScore >= $imageThreshold) {
                    $imageMatches[$candidate->id] = [
                        'id' => $candidate->id,
                        'image_score' => $imageScore,
                        'text_score' => null,
                    ];
                }
            }
        }

        // --- Merge results by candidate id ---
        $merged = [];

        // Add text results from /detect
        foreach ($result['results'] as $rank => $match) {
            $candidateId = (int) $match['id'];
            $textScore = (float) $match['text_score'];

            $merged[$candidateId] = [
                'id' => $candidateId,
                'text_score' => $textScore,
                'image_score' => $imageMatches[$candidateId]['image_score'] ?? null,
            ];
        }

        // Add image-only matches not already in text results
        foreach ($imageMatches as $candidateId => $imageMatch) {
            if (! isset($merged[$candidateId])) {
                $merged[$candidateId] = [
                    'id' => $candidateId,
                    'text_score' => null,
                    'image_score' => $imageMatch['image_score'],
                ];
            }
        }

        // Compute combined score and rank
        $ranked = [];
        foreach ($merged as $candidateId => $data) {
            $combined = max($data['text_score'] ?? 0, $data['image_score'] ?? 0);
            $ranked[$candidateId] = array_merge($data, ['combined' => $combined]);
        }

        // Sort by combined desc, ties broken by text_score desc
        uasort($ranked, function ($a, $b) {
            if ($a['combined'] !== $b['combined']) {
                return $b['combined'] <=> $a['combined'];
            }

            return ($b['text_score'] ?? 0) <=> ($a['text_score'] ?? 0);
        });

        // Apply top_k
        $topK = (int) config('services.ml.top_k', 5);
        $ranked = array_slice($ranked, 0, $topK, true);

        $thresholdUsed = (float) ($result['threshold_used'] ?? 0);

        DB::transaction(function () use ($ranked, $thresholdUsed, $client): void {
            // Delete old results first so re-analysis never violates the unique constraint.
            $this->bugReport->detectionResults()->delete();

            $rank = 1;
            foreach ($ranked as $candidateId => $data) {
                $candidateReport = BugReport::find($candidateId);

                if ($candidateReport === null) {
                    continue;
                }

                DetectionResult::create([
                    'bug_report_id' => $this->bugReport->id,
                    'candidate_report_id' => $candidateReport->id,
                    'rank' => $rank++,
                    'text_score' => $data['text_score'],
                    'image_score' => $data['image_score'],
                    'model' => $client->config()['model'],
                ]);
            }

            $this->bugReport->update([
                'detection_status' => DetectionStatus::Completed->value,
                'detection_threshold' => $thresholdUsed,
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
     * Appends OCR text (if available) to the description.
     */
    private function similarityText(BugReport $report): string
    {
        $description = $report->description ?? '';

        if ($report->hasOcrText()) {
            $description = trim($description.' '.$report->ocr_text);
        }

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
