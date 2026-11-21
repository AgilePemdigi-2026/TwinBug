<?php

namespace App\Jobs;

use App\Enums\OcrStatus;
use App\Models\BugReport;
use App\Services\DuplicateDetectionClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ExtractScreenshotText implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 2;

    /**
     * The maximum time (in seconds) the job can run.
     */
    public $timeout = 90;

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
    public function handle(DuplicateDetectionClient $client): void
    {
        $this->bugReport->refresh();

        if (! $this->bugReport->hasScreenshot()) {
            $this->bugReport->update([
                'ocr_status' => OcrStatus::Skipped->value,
                'ocr_error' => null,
            ]);

            return;
        }

        $storedPath = $this->bugReport->screenshot_path;
        $disk = Storage::disk('public');

        if (! $disk->exists($storedPath)) {
            $this->bugReport->update([
                'ocr_status' => OcrStatus::Skipped->value,
                'ocr_error' => null,
            ]);

            return;
        }

        $mimeType = $disk->mimeType($storedPath) ?? '';
        if (! str_starts_with($mimeType, 'image/')) {
            $this->bugReport->update([
                'ocr_status' => OcrStatus::Skipped->value,
                'ocr_error' => null,
            ]);

            return;
        }

        $this->bugReport->update([
            'ocr_status' => OcrStatus::Processing->value,
            'ocr_error' => null,
        ]);

        try {
            $text = $client->extractText($storedPath);

            $this->bugReport->update([
                'ocr_text' => $text,
                'ocr_status' => OcrStatus::Completed->value,
                'ocr_error' => null,
            ]);
        } catch (Throwable $e) {
            $this->bugReport->update([
                'ocr_status' => OcrStatus::Failed->value,
                'ocr_error' => $e->getMessage(),
            ]);

            Log::warning('OCR extraction failed for bug report {id}', [
                'id' => $this->bugReport->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        $this->bugReport->refresh();

        $this->bugReport->update([
            'ocr_status' => OcrStatus::Failed->value,
            'ocr_error' => $exception->getMessage(),
        ]);

        Log::warning('OCR job failed for bug report {id}', [
            'id' => $this->bugReport->id,
            'error' => $exception->getMessage(),
        ]);
    }

    /**
     * Get the display name for the job.
     */
    public function displayName(): string
    {
        return 'Extract screenshot text (OCR)';
    }
}
