<?php

namespace App\Http\Controllers;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Http\Requests\StoreBugReportRequest;
use App\Models\BugReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BugReportController extends Controller
{
    /**
     * Display a listing of bug reports with search and filter capabilities.
     */
    public function index(Request $request): View
    {
        $filters = [
            'q' => $request->query('q'),
            'severity' => $request->query('severity', 'all'),
            'status' => $request->query('status', 'all'),
            'module' => $request->query('module', 'all'),
            'has_screenshot' => $request->query('has_screenshot', 'all'),
            'scope' => $request->query('scope', 'all'),
        ];

        $reports = BugReport::query()
            ->with('user')
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $modules = $this->availableModules();
        $severities = BugSeverity::cases();
        $statuses = BugStatus::cases();

        $hasActiveFilter = filled($filters['q'])
            || ($filters['severity'] !== 'all')
            || ($filters['status'] !== 'all')
            || ($filters['module'] !== 'all')
            || ($filters['has_screenshot'] !== 'all')
            || ($filters['scope'] !== 'all');

        $stats = [
            'total' => BugReport::count(),
            'open' => BugReport::where('status', BugStatus::Open)->count(),
            'in_progress' => BugReport::where('status', BugStatus::InProgress)->count(),
            'resolved' => BugReport::where('status', BugStatus::Resolved)->count(),
            'critical' => BugReport::where('severity', BugSeverity::Critical)->count(),
        ];

        return view('bug-reports.index', compact(
            'reports',
            'modules',
            'severities',
            'statuses',
            'filters',
            'hasActiveFilter',
            'stats'
        ));
    }

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
