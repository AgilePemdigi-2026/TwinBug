<?php

namespace App\Http\Controllers;

use App\Enums\BugSeverity;
use App\Enums\BugStatus;
use App\Enums\UserRole;
use App\Models\BugReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for the authenticated user's role.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        return $user->role === UserRole::Developer
            ? $this->developerDashboard($user)
            : $this->reporterDashboard($user);
    }

    /**
     * Display the reporter dashboard focused on personal reports.
     */
    private function reporterDashboard(User $user): View
    {
        $stats = [
            'my_total' => BugReport::where('user_id', $user->id)->count(),
            'my_open' => BugReport::where('user_id', $user->id)->where('status', BugStatus::Open)->count(),
            'my_in_progress' => BugReport::where('user_id', $user->id)->where('status', BugStatus::InProgress)->count(),
            'my_resolved' => BugReport::where('user_id', $user->id)->where('status', BugStatus::Resolved)->count(),
        ];

        $recentReports = BugReport::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.reporter', compact('user', 'stats', 'recentReports'));
    }

    /**
     * Display the developer dashboard focused on the global bug queue.
     */
    private function developerDashboard(User $user): View
    {
        $stats = [
            'total' => BugReport::count(),
            'open' => BugReport::where('status', BugStatus::Open)->count(),
            'in_progress' => BugReport::where('status', BugStatus::InProgress)->count(),
            'critical' => BugReport::where('severity', BugSeverity::Critical)->count(),
        ];

        $recentReports = BugReport::with('user')
            ->latest()
            ->take(8)
            ->get();

        return view('dashboard.developer', compact('user', 'stats', 'recentReports'));
    }
}
