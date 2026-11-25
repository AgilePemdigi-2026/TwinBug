@php
    $myTotal = (int) ($stats['my_total'] ?? 0);
    $myOpen = (int) ($stats['my_open'] ?? 0);
    $myInProgress = (int) ($stats['my_in_progress'] ?? 0);
    $myResolved = (int) ($stats['my_resolved'] ?? 0);
    $myPending = (int) ($stats['my_pending'] ?? 0);
    $myThisMonth = (int) ($stats['my_this_month'] ?? 0);
    $resolutionRate = $myTotal > 0 ? (int) round(($myResolved / $myTotal) * 100) : 0;

    $chartTotal = max(1, $myResolved + $myOpen + $myInProgress + $myPending);
    $resolvedStop = round(($myResolved / $chartTotal) * 100, 1);
    $openStop = round($resolvedStop + ($myOpen / $chartTotal) * 100, 1);
    $processingStop = round($openStop + ($myInProgress / $chartTotal) * 100, 1);
    $donutGradient = $myTotal > 0
        ? "conic-gradient(#2E7A5C 0% {$resolvedStop}%, #D0A247 {$resolvedStop}% {$openStop}%, #5485A3 {$openStop}% {$processingStop}%, #C7CFCB {$processingStop}% 100%)"
        : 'conic-gradient(#E2E8E5 0% 100%)';

    $topModule = $topModules->first();
@endphp

<x-layouts.app title="Reporter Dashboard" meta-description="Reporter dashboard for TwinBug to monitor personal bug reports and their review status.">

    <div class="mx-auto max-w-[1540px] p-8 space-y-[26px]">

        {{-- Page Heading --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">Welcome back</h1>
                <p class="mt-[7px] text-[13px] leading-5 text-[#67736D]">
                    Here’s what’s happening with your bug reports in {{ $user->application?->name ?? 'this workspace' }}.
                </p>
            </div>

            <a href="{{ route('bug-reports.create') }}"
                id="btn-create-bug-report-hero"
                class="inline-flex h-10 shrink-0 items-center justify-center gap-[7px] rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#194E3C]">
                <svg class="size-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.42" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14" />
                    <path d="M12 5v14" />
                </svg>
                <span>Create new report</span>
            </a>
        </div>

        {{-- 3 Stat Cards --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            {{-- Card 1: Total reports --}}
            <div class="flex items-start gap-[14px] rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[18px] shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                <div class="grid size-[39px] shrink-0 place-items-center rounded-[9px] bg-[#E9F4EF] text-[#277253]">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                        <path d="M10 9H8" />
                        <path d="M16 13H8" />
                        <path d="M16 17H8" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11.5px] font-medium leading-[17px] text-[#67736D]">Total reports</p>
                    <p class="pt-[5px] pb-[3px] text-[24px] font-bold leading-9 tracking-[-0.6px] text-[#09090B]">{{ number_format($myTotal) }}</p>
                    <p class="text-[10.5px] leading-4 text-[#84908A]">{{ number_format($myThisMonth) }} submitted this month</p>
                </div>
            </div>

            {{-- Card 2: Pending review --}}
            <div class="flex items-start gap-[14px] rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[18px] shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                <div class="grid size-[39px] shrink-0 place-items-center rounded-[9px] bg-[#FAF2DF] text-[#997121]">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11.5px] font-medium leading-[17px] text-[#67736D]">Pending review</p>
                    <p class="pt-[5px] pb-[3px] text-[24px] font-bold leading-9 tracking-[-0.6px] text-[#09090B]">{{ number_format($myOpen) }}</p>
                    <p class="text-[10.5px] leading-4 text-[#84908A]">{{ number_format($myInProgress) }} currently processing</p>
                </div>
            </div>

            {{-- Card 3: Resolved / validated --}}
            <div class="flex items-start gap-[14px] rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[18px] shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                <div class="grid size-[39px] shrink-0 place-items-center rounded-[9px] bg-[#E9F1F6] text-[#3D6E8D]">
                    <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11.5px] font-medium leading-[17px] text-[#67736D]">Resolved / validated</p>
                    <p class="pt-[5px] pb-[3px] text-[24px] font-bold leading-9 tracking-[-0.6px] text-[#09090B]">{{ number_format($myResolved) }}</p>
                    <p class="text-[10.5px] leading-4 text-[#84908A]">{{ $resolutionRate }}% resolution rate</p>
                </div>
            </div>
        </div>

        {{-- 2-Column Content Grid --}}
        <div class="grid grid-cols-1 gap-5 xl:grid-cols-[minmax(0,1fr)_285px]">

            {{-- Left: Recent Activity Table Card --}}
            <section class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b-[0.8px] border-[#E9EEEB] px-5 py-[18px]">
                    <div>
                        <h2 class="text-[14px] font-normal leading-[21px] tracking-[-0.15px] text-[#09090B]">Recent activity</h2>
                        <p class="pt-1 text-[11px] leading-4 text-[#67736D]">Your latest submitted bug reports and their status</p>
                    </div>
                    <a href="{{ route('bug-reports.index') }}"
                        class="inline-flex h-[34px] items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[11px] text-[11px] font-semibold leading-4 text-[#35433C] transition hover:bg-[#F7F9F8]">
                        View all reports
                    </a>
                </div>

                @if ($recentReports->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-[13px] font-semibold text-[#142019]">No reports submitted yet</p>
                        <p class="mt-1 text-[11.5px] text-[#67736D]">Submit your first bug report to start tracking its status.</p>
                        <a href="{{ route('bug-reports.create') }}"
                            class="mt-4 inline-flex h-9 items-center justify-center rounded-[8px] bg-[#123C2E] px-4 text-[11.5px] font-semibold text-white transition hover:bg-[#194E3C]">
                            Create new report
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[740px] border-collapse text-left">
                            <thead>
                                <tr class="h-[37.4px] border-b-[0.8px] border-[#E2E8E5] bg-[#FAFBFA] text-[10px] font-semibold uppercase tracking-[0.35px] text-[#77837D]">
                                    <th class="px-[15px]">Report ID</th>
                                    <th class="px-[15px]">Bug title</th>
                                    <th class="px-[15px]">Module</th>
                                    <th class="px-[15px]">Submitted</th>
                                    <th class="px-[15px]">Status</th>
                                    <th class="px-[15px]"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentReports as $report)
                                    @php
                                        $topResult = $report->detectionResults->first();
                                        $topScore = $topResult ? max((float) ($topResult->text_score ?? 0), (float) ($topResult->image_score ?? 0)) : null;
                                        $isDup = $report->duplicate_of_id !== null || ($topScore !== null && $topScore >= 0.65);
                                        $isProcessing = $report->status === App\Enums\BugStatus::InProgress
                                            || in_array($report->detection_status, [App\Enums\DetectionStatus::Pending, App\Enums\DetectionStatus::Processing]);

                                        if ($isDup) {
                                            $badgeClasses = 'bg-[#FBF4E3] border-[#F2E4BD] text-[#8B6418]';
                                            $dotClasses = 'bg-[#8B6418]';
                                            $statusText = 'Duplicate';
                                        } elseif ($isProcessing) {
                                            $badgeClasses = 'bg-[#EEF5F9] border-[#D9E9F2] text-[#356C8C]';
                                            $dotClasses = 'bg-[#356C8C]';
                                            $statusText = 'Processing';
                                        } elseif (in_array($report->status, [App\Enums\BugStatus::Resolved, App\Enums\BugStatus::Closed])) {
                                            $badgeClasses = 'bg-[#EDF7F2] border-[#D9EEE3] text-[#26714F]';
                                            $dotClasses = 'bg-[#26714F]';
                                            $statusText = 'Resolved';
                                        } else {
                                            $badgeClasses = 'bg-[#F1F4F2] border-[#E0E5E2] text-[#58665F]';
                                            $dotClasses = 'bg-[#58665F]';
                                            $statusText = 'Under Review';
                                        }
                                    @endphp
                                    <tr class="h-[59.58px] border-b-[0.8px] border-[#EDF0EE] last:border-b-0 hover:bg-[#FAFBFA]/70">
                                        <td class="px-[15px]">
                                            <a href="{{ route('bug-reports.show', $report) }}" class="text-[11.5px] font-semibold leading-[17px] text-[#236B51] hover:underline">
                                                BUG-{{ $report->id }}
                                            </a>
                                        </td>
                                        <td class="px-[15px]">
                                            <p class="max-w-[230px] truncate text-[11.5px] font-semibold leading-4 text-[#26322C]">{{ $report->title }}</p>
                                            <p class="pt-[3px] text-[9.5px] leading-[14px] text-[#929B96]">Updated {{ $report->updated_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-[15px]">
                                            <span class="inline-block rounded-[5px] border-[0.8px] border-[#E0E6E3] bg-[#FAFBFA] px-2 py-[3px] text-[10px] leading-[15px] text-[#53615A]">
                                                {{ $report->module ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="px-[15px] text-[11.5px] leading-[17px] text-[#536059]">
                                            {{ $report->created_at->format('d M Y') }}
                                        </td>
                                        <td class="px-[15px]">
                                            <span class="inline-flex items-center gap-[5px] rounded-full border-[0.8px] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] {{ $badgeClasses }}">
                                                <span class="size-[5px] rounded-full {{ $dotClasses }}"></span>
                                                <span>{{ $statusText }}</span>
                                            </span>
                                        </td>
                                        <td class="px-[15px] text-right">
                                            <a href="{{ route('bug-reports.show', $report) }}" class="inline-flex items-center gap-1 text-[10.5px] font-semibold leading-4 text-[#236B51] hover:underline">
                                                <span>View</span>
                                                <span aria-hidden="true">›</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>

            {{-- Right: Status Overview Sidebar Card --}}
            <aside class="flex flex-col justify-between rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white pb-4 shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                <div>
                    <div class="flex items-center justify-between px-5 py-[18px]">
                        <div>
                            <h2 class="text-[14px] font-normal leading-[21px] tracking-[-0.15px] text-[#09090B]">Status overview</h2>
                            <p class="pt-1 text-[11px] leading-4 text-[#67736D]">All reports</p>
                        </div>
                        <span class="text-[#09090B]" aria-hidden="true">•••</span>
                    </div>

                    {{-- Donut Chart --}}
                    <div class="flex justify-center py-2">
                        <div class="relative grid size-[130px] place-items-center rounded-full" style="background: {{ $donutGradient }};">
                            <div class="grid size-[91px] place-items-center rounded-full bg-white text-center">
                                <div>
                                    <p class="text-[23px] font-bold leading-[34px] text-[#09090B]">{{ number_format($myTotal) }}</p>
                                    <p class="text-[10px] leading-[15px] text-[#67736D]">reports</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="mt-4 space-y-[10px] px-5 text-[10.5px] leading-4">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-[#637069]">
                                <span class="size-[7px] rounded-full bg-[#2E7A5C]"></span>
                                <span>Resolved</span>
                            </span>
                            <span class="font-bold text-[#26342D]">{{ number_format($myResolved) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-[#637069]">
                                <span class="size-[7px] rounded-full bg-[#D0A247]"></span>
                                <span>Under review</span>
                            </span>
                            <span class="font-bold text-[#26342D]">{{ number_format($myOpen) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-[#637069]">
                                <span class="size-[7px] rounded-full bg-[#5485A3]"></span>
                                <span>Processing</span>
                            </span>
                            <span class="font-bold text-[#26342D]">{{ number_format($myInProgress) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-[#637069]">
                                <span class="size-[7px] rounded-full bg-[#C7CFCB]"></span>
                                <span>Pending</span>
                            </span>
                            <span class="font-bold text-[#26342D]">{{ number_format($myPending) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Most active module --}}
                <div class="mt-5 px-4">
                    <div class="flex items-start gap-[9px] rounded-[8px] bg-[#F5F8F6] p-[11px]">
                        <x-auth.icon name="sparkles" class="mt-0.5 size-[17px] shrink-0 text-[#236B51]" />
                        <div>
                            <p class="text-[10.5px] font-bold leading-4 text-[#35443C]">Most active module</p>
                            <p class="pt-[3px] text-[9.5px] leading-[14px] text-[#67736D]">
                                @if ($topModule)
                                    {{ $topModule->module }} · {{ $topModule->count }} {{ Str::plural('report', $topModule->count) }}
                                @else
                                    No module activity yet
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    </div>

</x-layouts.app>
