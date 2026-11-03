<?php

namespace App\Http\Controllers;

use App\Enums\BugStatus;
use App\Models\BugReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ReportHistoryController extends Controller
{
    /**
     * Show the authenticated user's bug report history.
     */
    public function index(Request $request): View
    {
        $statusQuery = $request->query('status');

        $selectedStatus = is_string($statusQuery) ? BugStatus::tryFrom($statusQuery) : null;

        $reports = $request->user()
            ->bugReports()
            ->when($selectedStatus, fn ($query) => $query->where('status', $selectedStatus->value))
            ->latest()
            ->get();

        return view('reports.history', [
            'reports' => $reports,
            'statuses' => BugStatus::cases(),
            'selectedStatus' => $selectedStatus,
        ]);
    }

    /**
     * Show the detail of a submitted bug report.
     */
    public function show(BugReport $bugReport): View
    {
        Gate::authorize('view', $bugReport);

        return view('reports.show', [
            'report' => $bugReport,
        ]);
    }
}
