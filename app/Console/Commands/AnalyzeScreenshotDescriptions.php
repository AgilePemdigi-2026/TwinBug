<?php

namespace App\Console\Commands;

use App\Models\BugReport;
use App\Services\ScreenshotAnalysisClient;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Throwable;

#[Signature('bug-reports:analyze-screenshots {--limit=0 : Maximum number of reports to process (0 = all)}')]
#[Description('Generate AI descriptions for bug report screenshots used in duplicate detection.')]
class AnalyzeScreenshotDescriptions extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ScreenshotAnalysisClient $vision): int
    {
        if (! $vision->configured()) {
            $this->error('Layanan analisis visual belum dikonfigurasi (VISION_SERVICE_KEY).');

            return self::FAILURE;
        }

        $limit = max(0, (int) $this->option('limit'));
        $attempted = 0;
        $processed = 0;
        $failed = 0;

        BugReport::query()
            ->whereNotNull('screenshot_path')
            ->whereNull('image_description')
            ->orderBy('id')
            ->chunkById(50, function ($reports) use ($vision, $limit, &$attempted, &$processed, &$failed) {
                foreach ($reports as $report) {
                    if ($limit > 0 && $attempted >= $limit) {
                        return false;
                    }

                    $attempted++;

                    try {
                        $report->update([
                            'image_description' => $vision->analyze($report),
                            'image_analyzed_at' => now(),
                        ]);

                        $processed++;
                        $this->info("Laporan #{$report->id}: deskripsi visual disimpan.");
                    } catch (Throwable $e) {
                        $failed++;
                        $this->warn("Laporan #{$report->id}: gagal - {$e->getMessage()}");
                    }
                }
            });

        $this->info("Selesai. {$processed} berhasil, {$failed} gagal.");

        return self::SUCCESS;
    }
}
