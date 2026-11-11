<?php

namespace App\Http\Controllers;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Http\Requests\StoreBugReportRequest;
use App\Jobs\AnalyzeBugReport;
use App\Models\BugReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

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

        $applicationId = $request->user()->application_id;

        $reports = BugReport::query()
            ->where('application_id', $applicationId)
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
            'total' => BugReport::where('application_id', $applicationId)->count(),
            'open' => BugReport::where('application_id', $applicationId)->where('status', BugStatus::Open)->count(),
            'in_progress' => BugReport::where('application_id', $applicationId)->where('status', BugStatus::InProgress)->count(),
            'resolved' => BugReport::where('application_id', $applicationId)->where('status', BugStatus::Resolved)->count(),
            'critical' => BugReport::where('application_id', $applicationId)->where('severity', BugSeverity::Critical)->count(),
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
    public function create(Request $request): View
    {
        $application = $request->user()->application;
        $totalReports = BugReport::where('application_id', $application->id)->count();
        $modules = $this->availableModules();
        $severities = BugSeverity::cases();

        return view('bug-reports.create', compact('application', 'totalReports', 'modules', 'severities'));
    }

    /**
     * Display the specified bug report.
     */
    public function show(Request $request, BugReport $bugReport): View
    {
        abort_unless($bugReport->application_id === $request->user()->application_id, 404);

        $bugReport->load(['user', 'application']);

        return view('bug-reports.show', ['report' => $bugReport]);
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

        $bugReport = BugReport::create([
            'user_id' => $request->user()->id,
            'application_id' => $request->user()->application_id,
            'title' => $data['title'],
            'module' => $data['module'] ?? null,
            'description' => $data['description'],
            'severity' => $data['severity'],
            'screenshot_path' => $screenshotPath,
            'screenshot_name' => $screenshotName,
            'screenshot_size' => $screenshotSize,
            'screenshot_dimensions' => $screenshotDimensions,
        ]);

        try {
            AnalyzeBugReport::dispatch($bugReport)->afterCommit();
        } catch (Throwable $e) {
            Log::warning('Failed to dispatch duplicate detection job', [
                'error' => $e->getMessage(),
            ]);
        }

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
