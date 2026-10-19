<?php

namespace App\Http\Controllers;

use App\Enums\BugSeverity;
use App\Http\Requests\StoreBugReportRequest;
use App\Models\BugReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BugReportController extends Controller
{
    /**
     * Show the create bug report form.
     */
    public function create(): View
    {
        $totalReports = BugReport::count();
        $modules = $this->availableModules();
        $severities = BugSeverity::cases();

        return view('bug-reports.create', compact('totalReports', 'modules', 'severities'));
    }

    /**
     * Store a newly created bug report.
     */
    public function store(StoreBugReportRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $screenshotPath = null;
        $screenshotName = null;
        $screenshotSize = null;
        $screenshotDimensions = null;

        if ($request->hasFile('screenshot')) {
            $file = $request->file('screenshot');
            $screenshotPath = $file->store('screenshots', 'public');
            $screenshotName = $file->getClientOriginalName();
            $screenshotSize = $file->getSize();

            [$width, $height] = getimagesize($file->getRealPath());
            $screenshotDimensions = "{$width}×{$height}";
        }

        BugReport::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'module' => $data['module'] ?? null,
            'description' => $data['description'],
            'severity' => $data['severity'],
            'screenshot_path' => $screenshotPath,
            'screenshot_name' => $screenshotName,
            'screenshot_size' => $screenshotSize,
            'screenshot_dimensions' => $screenshotDimensions,
        ]);

        return to_route('bug-reports.create')
            ->with('success', 'Laporan bug berhasil dikirim dan sedang dianalisis oleh sistem TwinBug.');
    }

    /**
     * Get the list of available modules.
     *
     * @return array<string>
     */
    private function availableModules(): array
    {
        return [
            'Autentikasi & Akun',
            'Keranjang Belanja',
            'Checkout & Pembayaran',
            'Profil Pengguna',
            'Pencarian & Filter',
            'Notifikasi',
            'Dashboard',
            'API & Integrasi',
            'Laporan & Analitik',
            'Lainnya',
        ];
    }
}
