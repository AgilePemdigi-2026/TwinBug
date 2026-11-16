<?php

namespace App\Http\Controllers;

use App\Enums\DetectionStatus;
use App\Enums\UserRole;
use App\Jobs\AnalyzeBugReport;
use App\Models\BugReport;
use App\Models\DetectionResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DetectionController extends Controller
{
    /**
     * Show the duplicate detection results for a bug report.
     */
    public function show(Request $request, BugReport $bugReport): View
    {
        abort_unless(
            $request->user()->role === UserRole::Developer
            && $bugReport->application_id === $request->user()->application_id,
            403
        );

        $bugReport->loadMissing(['user', 'application']);

        $results = DetectionResult::where('bug_report_id', $bugReport->id)
            ->with('candidateReport')
            ->orderBy('rank')
            ->get();

        $selectedCandidateId = $request->query('candidate');
        $selectedResult = $selectedCandidateId
            ? $results->firstWhere('candidate_report_id', (int) $selectedCandidateId)
            : null;

        if ($selectedResult === null && $results->isNotEmpty()) {
            $selectedResult = $results->first();
        }

        $selectedCandidate = $selectedResult?->candidateReport;

        $config = config('detection', []);

        return view('bug-reports.detection', compact(
            'bugReport',
            'results',
            'selectedResult',
            'selectedCandidate',
            'config'
        ));
    }

    /**
     * Re-run the duplicate detection analysis for a bug report.
     */
    public function rerun(BugReport $bugReport): RedirectResponse
    {
        abort_unless(
            $bugReport->application_id === request()->user()->application_id
            && request()->user()->role === UserRole::Developer,
            403
        );

        $bugReport->update([
            'detection_status' => DetectionStatus::Pending->value,
            'detection_error' => null,
            'detected_at' => null,
        ]);

        AnalyzeBugReport::dispatch($bugReport)->afterCommit();

        return redirect()
            ->route('bug-reports.detection', $bugReport)
            ->with('success', 'Analisis duplikasi telah dijalankan ulang. Tunggu beberapa saat hingga selesai.');
    }
}
