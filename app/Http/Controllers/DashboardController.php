<?php

namespace App\Http\Controllers;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for the authenticated user's role.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $user->loadMissing('application');

        return $user->role === UserRole::Developer
            ? $this->developerDashboard($user)
            : $this->reporterDashboard($user);
    }

    /**
     * Display the reporter dashboard focused on personal reports.
     */
    private function reporterDashboard(User $user): View
    {
        $baseQuery = BugReport::where('user_id', $user->id);
        $stats = $this->statsFor($baseQuery);
        $stats['my_total'] = $stats['total'];
        $stats['my_open'] = $stats['open'];
        $stats['my_in_progress'] = $stats['in_progress'];
        $stats['my_resolved'] = $stats['resolved'] + $stats['closed'];

        return view('dashboard.reporter', [
            'user' => $user,
            'stats' => $stats,
            'recentReports' => (clone $baseQuery)->with('user')->withCount('detectionResults')->latest()->take(6)->get(),
            'topModules' => $this->topModulesFor($baseQuery),
        ]);
    }

    /**
     * Display the developer dashboard focused on the application bug queue.
     */
    private function developerDashboard(User $user): View
    {
        $applicationId = $user->application_id;
        $request = request();

        $filters = [
            'q' => $request->query('q'),
            'module' => $request->query('module', 'all'),
            'status' => $request->query('status', 'all'),
            'period' => $request->query('period', '30'),
        ];

        $baseQuery = BugReport::where('application_id', $applicationId);
        $total = (clone $baseQuery)->count();
        $open = (clone $baseQuery)->where('status', BugStatus::Open)->count();
        $inProgress = (clone $baseQuery)->where('status', BugStatus::InProgress)->count();
        $critical = (clone $baseQuery)->where('severity', BugSeverity::Critical)->count();
        $potentialDuplicates = (clone $baseQuery)
            ->where(function ($query): void {
                $query->whereNotNull('duplicate_of_id')
                    ->orWhereHas('detectionResults', function ($subQuery): void {
                        $subQuery->where('text_score', '>=', 0.65)
                            ->orWhere('image_score', '>=', 0.65);
                    });
            })
            ->count();

        $stats = [
            'total' => $total,
            'open' => $open,
            'in_progress' => $inProgress,
            'critical' => $critical,
            'potential_duplicates' => $potentialDuplicates,
            'unique' => max(0, $total - $potentialDuplicates),
        ];

        $recentReports = BugReport::where('application_id', $applicationId)
            ->whereNull('duplicate_of_id')
            ->with(['user', 'detectionResults' => fn ($query) => $query->orderBy('rank')])
            ->withCount('detectionResults')
            ->when(filled($filters['q']), function ($query) use ($filters): void {
                $search = trim((string) $filters['q']);
                $numericId = preg_replace('/^BUG-/i', '', $search);

                $query->where(function ($subQuery) use ($search, $numericId): void {
                    $subQuery->where('title', 'like', "%{$search}%");
                    if (is_numeric($numericId)) {
                        $subQuery->orWhere('id', (int) $numericId);
                    }
                });
            })
            ->when(($filters['module'] ?? 'all') !== 'all', fn ($query) => $query->where('module', $filters['module']))
            ->when(($filters['status'] ?? 'all') !== 'all', fn ($query) => $query->where('status', $filters['status']))
            ->when(($filters['period'] ?? '30') !== 'all' && is_numeric($filters['period']), fn ($query) => $query->where('created_at', '>=', now()->subDays((int) $filters['period'])))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $modules = BugReport::where('application_id', $applicationId)
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        $statuses = BugStatus::cases();

        $topModules = $this->topModulesFor($baseQuery);

        return view('dashboard.developer', compact('user', 'stats', 'recentReports', 'topModules', 'modules', 'statuses', 'filters'));
    }

    /**
     * @param  Builder<BugReport>  $baseQuery
     * @param  Builder<BugReport>|null  $queueQuery
     * @return array<string, int>
     */
    private function statsFor(Builder $baseQuery, ?Builder $queueQuery = null): array
    {
        $queueQuery ??= $baseQuery;

        return [
            'total' => (clone $baseQuery)->count(),
            'open' => (clone $queueQuery)->where('status', BugStatus::Open)->count(),
            'in_progress' => (clone $baseQuery)->where('status', BugStatus::InProgress)->count(),
            'resolved' => (clone $baseQuery)->where('status', BugStatus::Resolved)->count(),
            'closed' => (clone $baseQuery)->where('status', BugStatus::Closed)->count(),
            'critical' => (clone $queueQuery)->where('status', BugStatus::Open)->where('severity', BugSeverity::Critical)->count(),
        ];
    }

    /**
     * @param  Builder<BugReport>  $baseQuery
     * @return Collection<int, BugReport>
     */
    private function topModulesFor(Builder $baseQuery): Collection
    {
        return (clone $baseQuery)
            ->whereNotNull('module')
            ->selectRaw('module, count(*) as count')
            ->groupBy('module')
            ->orderByDesc('count')
            ->take(4)
            ->get();
    }
}
