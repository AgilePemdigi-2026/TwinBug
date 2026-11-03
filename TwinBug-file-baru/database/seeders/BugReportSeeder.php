<?php

namespace Database\Seeders;

use App\Enums\AnalysisStatus;
use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Models\BugReport;
use App\Models\BugReportMatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class BugReportSeeder extends Seeder
{
    /**
     * Seed sample bug reports and their similarity results.
     */
    public function run(): void
    {
        $reporter = User::query()->where('email', 'reporter.demo@gmail.com')->first();

        if (! $reporter) {
            return;
        }

        $checkoutScreenshot = $this->copyScreenshot(
            'sample-screenshots/checkout-timeout.png',
            'screenshots/sample-checkout-timeout.png',
        );

        $paymentScreenshot = $this->copyScreenshot(
            'sample-screenshots/payment-error.png',
            'screenshots/sample-payment-error.png',
        );

        $reports = [
            101 => [
                'title' => 'Tombol submit tidak berfungsi',
                'module' => 'Checkout & Pembayaran',
                'description' => 'Tombol submit tidak merespon saat diklik pada halaman checkout.',
                'severity' => BugSeverity::High,
                'status' => BugStatus::Open,
                'analysis_status' => AnalysisStatus::Completed,
                'screenshot_path' => $checkoutScreenshot['path'],
                'screenshot_name' => $checkoutScreenshot['name'],
                'screenshot_size' => $checkoutScreenshot['size'],
                'screenshot_dimensions' => $checkoutScreenshot['dimensions'],
                'created_at' => now()->subDays(3),
            ],
            102 => [
                'title' => 'Halaman gagal dimuat',
                'module' => 'Dashboard',
                'description' => 'Halaman dashboard menampilkan blank screen setelah login.',
                'severity' => BugSeverity::Medium,
                'status' => BugStatus::InProgress,
                'analysis_status' => AnalysisStatus::Completed,
                'created_at' => now()->subDays(2),
            ],
            103 => [
                'title' => 'Error saat upload file',
                'module' => 'Lainnya',
                'description' => 'Sistem menampilkan pesan error saat mengunggah dokumen.',
                'severity' => BugSeverity::Low,
                'status' => BugStatus::Open,
                'analysis_status' => AnalysisStatus::Processing,
                'created_at' => now()->subDay(),
            ],
            104 => [
                'title' => 'Tombol checkout tidak merespon',
                'module' => 'Checkout & Pembayaran',
                'description' => 'Tombol checkout tidak merespon saat diklik pada halaman pembayaran.',
                'severity' => BugSeverity::High,
                'status' => BugStatus::Duplicate,
                'analysis_status' => AnalysisStatus::Completed,
                'screenshot_path' => $paymentScreenshot['path'],
                'screenshot_name' => $paymentScreenshot['name'],
                'screenshot_size' => $paymentScreenshot['size'],
                'screenshot_dimensions' => $paymentScreenshot['dimensions'],
                'created_at' => now()->subDays(6),
            ],
            105 => [
                'title' => 'Pembayaran gagal setelah klik bayar',
                'module' => 'Checkout & Pembayaran',
                'description' => 'Status pesanan tidak berubah setelah pengguna menekan tombol bayar.',
                'severity' => BugSeverity::Medium,
                'status' => BugStatus::InProgress,
                'analysis_status' => AnalysisStatus::Completed,
                'created_at' => now()->subDays(8),
            ],
        ];

        foreach ($reports as $id => $attributes) {
            $report = BugReport::query()->find($id) ?? new BugReport;

            $report->forceFill($attributes + [
                'id' => $id,
                'user_id' => $reporter->id,
            ])->save();
        }

        BugReportMatch::query()->updateOrCreate(
            [
                'bug_report_id' => 101,
                'matched_report_id' => 104,
            ],
            [
                'text_similarity' => 92.50,
                'image_similarity' => 88.00,
            ],
        );

        BugReportMatch::query()->updateOrCreate(
            [
                'bug_report_id' => 101,
                'matched_report_id' => 105,
            ],
            [
                'text_similarity' => 84.20,
                'image_similarity' => null,
            ],
        );
    }

    /**
     * Copy a sample screenshot into the public storage disk.
     *
     * @return array{path: ?string, name: ?string, size: ?int, dimensions: ?string}
     */
    private function copyScreenshot(string $source, string $target): array
    {
        $absolutePath = public_path($source);

        if (! is_file($absolutePath)) {
            return ['path' => null, 'name' => null, 'size' => null, 'dimensions' => null];
        }

        Storage::disk('public')->put($target, file_get_contents($absolutePath));

        $imageSize = @getimagesize($absolutePath);

        return [
            'path' => $target,
            'name' => basename($target),
            'size' => Storage::disk('public')->size($target),
            'dimensions' => is_array($imageSize) ? "{$imageSize[0]}×{$imageSize[1]}" : null,
        ];
    }
}
