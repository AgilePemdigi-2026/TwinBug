<?php

namespace App\Console\Commands;

use App\Models\BugReport;
use App\Services\ImageHasher;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('bug-reports:backfill-screenshot-hashes {--dry-run : Perform a dry run without saving hashes} {--limit=0 : Maximum number of reports to process (0 = all)}')]
#[Description('Compute perceptual hashes (dHash) for existing bug report screenshots.')]
class BackfillScreenshotHashes extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(ImageHasher $hasher): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $limit = max(0, (int) $this->option('limit'));

        if ($dryRun) {
            $this->warn('Dry run enabled - no hashes will be saved.');
        }

        $query = BugReport::whereNotNull('screenshot_path')
            ->whereNull('screenshot_hash')
            ->orderBy('id');

        $attempted = 0;
        $processed = 0;
        $failed = 0;
        $skipped = 0;

        $query->chunkById(50, function ($reports) use ($hasher, $dryRun, $limit, &$attempted, &$processed, &$failed, &$skipped) {
            foreach ($reports as $report) {
                if ($limit > 0 && $attempted >= $limit) {
                    return false;
                }

                $attempted++;

                try {
                    $hash = $hasher->hash($report->screenshot_path);

                    if ($hash === null) {
                        $skipped++;
                        $this->warn("Laporan #{$report->id}: tidak dapat menghasilkan hash (gambar seragam atau tidak dapat dibaca).");
                        continue;
                    }

                    if ($dryRun) {
                        $this->info("Laporan #{$report->id}: hash {$hash} (dry-run, tidak disimpan).");
                    } else {
                        $report->update(['screenshot_hash' => $hash]);
                        $this->info("Laporan #{$report->id}: hash {$hash} disimpan.");
                    }

                    $processed++;
                } catch (\Throwable $e) {
                    $failed++;
                    $this->error("Laporan #{$report->id}: gagal - {$e->getMessage()}");
                }
            }
        });

        $this->info("");
        $this->info("Selesai. {$attempted} dicoba, {$processed} berhasil, {$skipped} dilewati, {$failed} gagal.");

        return self::SUCCESS;
    }
}
