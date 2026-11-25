<x-layouts.app title="My Bug Reports" meta-description="Track, filter, and review every bug report you have submitted on TwinBug.">

    <div class="workspace-reports-page mx-auto max-w-[1540px] p-8 space-y-[26px]">

        {{-- ======================== PAGE HEADING ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">My Bug Reports</h1>
                <p class="pt-[7px] text-[13px] leading-5 text-[#67736D]">
                    Track, filter, and review every bug report you have submitted.
                </p>
            </div>

            <a href="{{ route('bug-reports.create') }}"
                class="inline-flex h-10 shrink-0 items-center justify-center gap-[7px] rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#0D2D22]">
                <span>+ Create new report</span>
            </a>
        </div>

        {{-- ======================== TABLE CARD ======================== --}}
        <div class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white shadow-[0_1px_2px_rgba(12,35,25,0.024)]">

            {{-- Filter Toolbar --}}
            <form method="GET" action="{{ route('bug-reports.index') }}" class="flex flex-wrap items-center gap-[9px] border-b-[0.8px] border-[#E2E8E5] px-[18px] py-[14px]">
                <label class="flex h-10 min-w-[260px] flex-1 items-center gap-2 rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px]">
                    <x-auth.icon name="search" class="size-4 shrink-0 text-[#87928C]" />
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] ?? '' }}"
                        placeholder="Search by bug title or report ID"
                        class="w-full bg-transparent text-[11.5px] text-[#24322B] placeholder:text-[#A4ADA8] focus:outline-none">
                </label>

                <select name="status" aria-label="Filter by status"
                    class="h-[41px] w-[150px] rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-3 text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                    <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>All statuses</option>
                    @foreach ($statuses as $stat)
                        <option value="{{ $stat->value }}" @selected(($filters['status'] ?? '') === $stat->value)>{{ $stat->label() }}</option>
                    @endforeach
                </select>

                <select name="module" aria-label="Filter by module"
                    class="h-[41px] w-[150px] rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-3 text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                    <option value="all" @selected(($filters['module'] ?? 'all') === 'all')>All modules</option>
                    @foreach ($modules as $mod)
                        <option value="{{ $mod }}" @selected(($filters['module'] ?? '') === $mod)>{{ $mod }}</option>
                    @endforeach
                </select>

                <button type="submit" id="filter-submit-btn"
                    class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                    <x-auth.icon name="filter" class="size-4" />
                    <span>Apply filter</span>
                </button>
            </form>

            {{-- Table --}}
            @if ($reports->isEmpty())
                <div class="px-6 py-12 text-center">
                    <p class="text-[13px] font-semibold text-[#142019]">No bug reports found</p>
                    <p class="mt-1 text-[11.5px] text-[#67736D]">
                        @if ($hasActiveFilter)
                            Try adjusting your search or filter criteria.
                        @else
                            Submit your first bug report to get started.
                        @endif
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[850px] border-collapse text-left">
                        <thead>
                            <tr class="h-[37.4px] border-b-[0.8px] border-[#E2E8E5] bg-[#FAFBFA] text-[10px] font-semibold uppercase tracking-[0.35px] text-[#77837D]">
                                <th class="px-[15px]">Report ID</th>
                                <th class="px-[15px]">Bug title</th>
                                <th class="px-[15px]">Module</th>
                                <th class="px-[15px]">Submission date</th>
                                <th class="px-[15px]">Status</th>
                                <th class="px-[15px]">Screenshot</th>
                                <th class="px-[15px]"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                @php
                                    if ($report->duplicate_of_id !== null) {
                                        $pillBg = 'border-[#F2E4BD] bg-[#FBF4E3] text-[#8B6418]';
                                        $pillDot = 'bg-[#8B6418]';
                                        $pillLabel = 'Duplicate';
                                    } elseif ($report->status === App\Enums\BugStatus::InProgress) {
                                        $pillBg = 'border-[#D9E9F2] bg-[#EEF5F9] text-[#356C8C]';
                                        $pillDot = 'bg-[#356C8C]';
                                        $pillLabel = 'Processing';
                                    } elseif ($report->status === App\Enums\BugStatus::Resolved) {
                                        $pillBg = 'border-[#D9EEE3] bg-[#EDF7F2] text-[#26714F]';
                                        $pillDot = 'bg-[#26714F]';
                                        $pillLabel = 'Resolved';
                                    } else {
                                        $pillBg = 'border-[#E0E5E2] bg-[#F1F4F2] text-[#58665F]';
                                        $pillDot = 'bg-[#58665F]';
                                        $pillLabel = $report->status->label();
                                    }
                                @endphp
                                <tr class="h-[59.58px] border-b-[0.8px] border-[#EDF0EE] last:border-b-0 hover:bg-[#FAFBFA]/70">
                                    <td class="px-[15px]">
                                        <a href="{{ route('bug-reports.show', $report) }}" class="text-[11.5px] font-semibold leading-[17px] text-[#236B51] hover:underline">
                                            BUG-{{ $report->id }}
                                        </a>
                                    </td>
                                    <td class="px-[15px]">
                                        <p class="max-w-[260px] truncate text-[11.5px] font-semibold leading-4 text-[#26322C]">{{ $report->title }}</p>
                                        <p class="pt-[3px] text-[9.5px] leading-[14px] text-[#929B96]">Last updated {{ $report->updated_at->diffForHumans() }}</p>
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
                                        <span class="inline-flex items-center gap-[5px] rounded-full border-[0.8px] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] {{ $pillBg }}">
                                            <span class="size-[5px] rounded-full {{ $pillDot }}"></span>
                                            <span>{{ $pillLabel }}</span>
                                        </span>
                                    </td>
                                    <td class="px-[15px]">
                                        @if ($report->hasScreenshot())
                                            <span class="inline-flex items-center gap-[5px] text-[11.5px] leading-[17px] text-[#65736C]">
                                                <x-auth.icon name="eye" class="size-[15px]" />
                                                <span>Attached</span>
                                            </span>
                                        @else
                                            <span class="text-[11.5px] leading-[17px] text-[#A4ADA8]">None</span>
                                        @endif
                                    </td>
                                    <td class="px-[15px] text-right">
                                        <a href="{{ route('bug-reports.show', $report) }}" class="inline-flex items-center gap-1 text-[10.5px] font-semibold leading-4 text-[#236B51] hover:underline">
                                            <span>View details</span>
                                            <span aria-hidden="true">›</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Footer --}}
                <div class="flex flex-wrap items-center justify-between gap-4 border-t-[0.8px] border-[#E2E8E5] px-[18px] py-[14px]">
                    <p class="text-[10px] leading-[15px] text-[#67736D]">
                        Showing {{ $reports->firstItem() ?? 0 }}–{{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} reports
                    </p>
                    @if ($reports->hasPages())
                        <div class="flex items-center gap-1">
                            {{ $reports->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

</x-layouts.app>
