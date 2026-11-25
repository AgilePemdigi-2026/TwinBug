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

        return view('dashboard.reporter', [
            'user' => $user,
            'stats' => $this->statsFor($baseQuery),
            'recentReports' => (clone $baseQuery)->with('user')->withCount('detectionResults')->latest()->take(6)->get(),
            'topModules' => $this->topModulesFor($baseQuery),
        ]);
    }

    /**
     * Display the developer dashboard focused on the application bug queue.
     */
    private function developerDashboard(User $user): View
    {
        $baseQuery = BugReport::where('application_id', $user->application_id);
        $queueQuery = (clone $baseQuery)->whereNull('duplicate_of_id');

        return view('dashboard.developer', [
            'user' => $user,
            'stats' => $this->statsFor($baseQuery, $queueQuery),
            'recentReports' => (clone $queueQuery)->with('user')->withCount('detectionResults')->latest()->take(8)->get(),
            'topModules' => $this->topModulesFor($baseQuery),
        ]);
    }

    /**
     * @param  Builder<BugReport>  $baseQuery
     * @param  Builder<BugReport>|null  $queueQuery
     * @return array<string, int>
     */
    private function statsFor($baseQuery, $queueQuery = null): array
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
    private function topModulesFor($baseQuery)
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
