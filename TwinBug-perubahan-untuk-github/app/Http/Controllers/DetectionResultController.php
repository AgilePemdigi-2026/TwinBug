<?php

namespace App\Http\Controllers;

use App\Models\BugReport;
use App\Models\BugReportMatch;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DetectionResultController extends Controller
{
    /**
     * Show the similarity detection result for a bug report.
     */
    public function show(BugReport $bugReport): View
    {
        Gate::authorize('view', $bugReport);

        $matches = $bugReport->matches()
            ->with('matchedReport')
            ->orderByDesc('text_similarity')
            ->get();

        return view('reports.detection', [
            'report' => $bugReport,
            'matches' => $matches,
        ]);
    }

    /**
     * Show the detailed comparison between two similar reports.
     */
    public function comparison(BugReport $bugReport, BugReportMatch $match): View
    {
        Gate::authorize('view', $bugReport);

        abort_unless($match->bug_report_id === $bugReport->id, 404);

        $match->load('matchedReport');

        return view('reports.comparison', [
            'report' => $bugReport,
            'match' => $match,
            'matchedReport' => $match->matchedReport,
        ]);
    }
}
