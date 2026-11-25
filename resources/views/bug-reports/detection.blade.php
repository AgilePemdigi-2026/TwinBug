<x-layouts.app title="Detection Results — BUG-{{ $bugReport->id }}" meta-description="Duplicate bug report detection results and similarity evidence on TwinBug.">

    @php
        $overallSimilarity = $results->isNotEmpty()
            ? $results->max(fn ($result) => max((float) ($result->text_score ?? 0), (float) ($result->image_score ?? 0)))
            : null;
        $overallPercent = $overallSimilarity !== null ? (int) round($overallSimilarity * 100) : null;
    @endphp

    <div class="workspace-detection-page mx-auto max-w-[1540px] p-8 space-y-5">

        @if ($selectedCandidate && request()->has('candidate'))
            @php
                $compTextPct = $selectedResult?->text_score !== null ? (int) round($selectedResult->text_score * 100) : 0;
                $compImagePct = $selectedResult?->image_score !== null ? (int) round($selectedResult->image_score * 100) : null;
                $compFinalPct = (int) round(max((float) ($selectedResult?->text_score ?? 0), (float) ($selectedResult?->image_score ?? 0)) * 100);
                $isHighSim = $compFinalPct >= 65;
            @endphp

            {{-- ======================== COMPARISON BREADCRUMB ======================== --}}
            <nav class="flex flex-wrap items-center gap-2 text-[11.5px] leading-[17px]" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="text-[#83908A] transition hover:text-[#33423B]">Developer Dashboard</a>
                <span class="text-[#83908A]">›</span>
                <a href="{{ route('report.detection_result', $bugReport) }}" class="text-[#83908A] transition hover:text-[#33423B]">Detection Results</a>
                <span class="text-[#83908A]">›</span>
                <a href="{{ route('report.detection_result', $bugReport) }}" class="text-[#83908A] transition hover:text-[#33423B]">BUG-{{ $bugReport->id }}</a>
                <span class="text-[#83908A]">›</span>
                <span class="font-semibold text-[#33423B]">Comparison</span>
            </nav>

            {{-- ======================== COMPARISON HEADER ======================== --}}
            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <a href="{{ route('report.detection_result', $bugReport) }}" class="inline-flex items-center gap-[5px] text-[10px] font-semibold leading-[15px] text-[#236B51] hover:underline">
                        <x-auth.icon name="arrow-left" class="size-4" />
                        <span>Back to results</span>
                    </a>
                    <h1 class="pt-3 text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">Detection comparison</h1>
                    <p class="pt-[7px] text-[13px] leading-5 text-[#67736D]">
                        Review TwinBug’s recommendation before deciding how to triage this report.
                    </p>
                </div>

                <div class="flex items-center gap-7 rounded-[9px] border-[0.8px] border-[#E2E8E5] bg-white px-[15px] py-[11px] shrink-0">
                    <div>
                        <p class="text-[8.5px] leading-[13px] text-[#67736D]">Detection status</p>
                        <span class="mt-[5px] inline-flex items-center gap-[5px] rounded-full border-[0.8px] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] {{ $isHighSim ? 'border-[#F2E4BD] bg-[#FBF4E3] text-[#8B6418]' : 'border-[#D9EEE3] bg-[#EDF7F2] text-[#26714F]' }}">
                            <span class="size-[5px] rounded-full {{ $isHighSim ? 'bg-[#8B6418]' : 'bg-[#26714F]' }}"></span>
                            <span>{{ $isHighSim ? 'Potential duplicate' : 'Low similarity' }}</span>
                        </span>
                    </div>
                    <div>
                        <p class="text-[8.5px] leading-[13px] text-[#67736D]">Overall similarity</p>
                        <p class="pt-1 text-[19px] font-bold leading-[19px] text-[#09090B]">{{ $compFinalPct }}%</p>
                        <p class="pt-1 text-[8px] leading-3 text-[#287152]">{{ $compFinalPct >= 80 ? 'Very high' : ($compFinalPct >= 65 ? 'Moderate' : 'Low') }}</p>
                    </div>
                </div>
            </div>

            {{-- ======================== SIMILARITY SCORES BAR ======================== --}}
            <div class="grid grid-cols-1 items-center gap-6 rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white px-[18.8px] py-[15.8px] lg:grid-cols-12">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 lg:col-span-9">
                    <div>
                        <p class="text-[9px] leading-[14px] text-[#67736D]">Text similarity</p>
                        <div class="mt-[7px] flex items-center gap-[9px]">
                            <div class="h-[6px] flex-1 overflow-hidden rounded-full bg-[#E9EEEB]">
                                <div class="h-full rounded-full bg-[#2C795A]" style="width: {{ $compTextPct }}%"></div>
                            </div>
                            <span class="w-7 text-[10px] font-bold leading-[15px] text-[#34413B]">{{ $compTextPct }}%</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] leading-[14px] text-[#67736D]">Image similarity</p>
                        <div class="mt-[7px] flex items-center gap-[9px]">
                            <div class="h-[6px] flex-1 overflow-hidden rounded-full bg-[#E9EEEB]">
                                <div class="h-full rounded-full bg-[#2C795A]" style="width: {{ $compImagePct ?? 0 }}%"></div>
                            </div>
                            <span class="w-7 text-[10px] font-bold leading-[15px] text-[#34413B]">{{ $compImagePct !== null ? $compImagePct . '%' : '—' }}</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] leading-[14px] text-[#67736D]">Final similarity</p>
                        <div class="mt-[7px] flex items-center gap-[9px]">
                            <div class="h-[6px] flex-1 overflow-hidden rounded-full bg-[#E9EEEB]">
                                <div class="h-full rounded-full bg-[#2C795A]" style="width: {{ $compFinalPct }}%"></div>
                            </div>
                            <span class="w-7 text-[10px] font-bold leading-[15px] text-[#34413B]">{{ $compFinalPct }}%</span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-[9px] border-t border-[#E2E8E5] pt-3 lg:col-span-3 lg:border-l lg:border-t-0 lg:pl-5 lg:pt-0">
                    <x-auth.icon name="sparkles" class="size-[18px] shrink-0 text-[#236B51]" />
                    <div>
                        <p class="text-[8px] leading-3 text-[#67736D]">Analysis method</p>
                        <p class="pt-[3px] text-[10px] font-bold leading-[15px] text-[#36443D]">
                            {{ $bugReport->hasScreenshot() && $selectedCandidate->hasScreenshot() ? 'Multimodal Late Fusion' : 'Semantic Text Analysis' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ======================== 2-COLUMN REPORT COMPARISON ======================== --}}
            <div class="grid grid-cols-1 gap-[18px] lg:grid-cols-2">
                {{-- Left Panel: New Bug Report --}}
                <div class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white">
                    <div class="flex items-center gap-[10px] border-b-[0.8px] border-[#E2E8E5] bg-[#FAFBFA] px-[18px] py-4">
                        <div class="grid size-[34px] shrink-0 place-items-center rounded-[8px] bg-[#E8F3EE] text-[#236B51]">
                            <x-auth.icon name="bug" class="size-[18px]" />
                        </div>
                        <div>
                            <p class="text-[8px] uppercase leading-3 tracking-[0.56px] text-[#67736D]">New bug report</p>
                            <h2 class="pt-[3px] text-[12px] font-semibold leading-[18px] text-[#09090B]">BUG-{{ $bugReport->id }}</h2>
                        </div>
                    </div>

                    <div class="px-[18px] pt-4 pb-[5px] space-y-[13px]">
                        <div>
                            <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Bug title</p>
                            <p class="pt-[5px] text-[10.5px] font-medium leading-4 text-[#2E3B35]">{{ $bugReport->title }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 py-1">
                            <div>
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Reporter</p>
                                <p class="pt-[5px] text-[10.5px] font-medium leading-4 text-[#2E3B35]">{{ $bugReport->user?->name ?? 'Reporter' }}</p>
                            </div>
                            <div>
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Module</p>
                                <p class="pt-[5px] text-[10.5px] font-medium leading-4 text-[#2E3B35]">{{ $bugReport->module ?? 'General' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                        <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Bug description</p>
                        <p class="pt-[5px] whitespace-pre-line text-[10px] leading-4 text-[#56635D]">{{ $bugReport->description }}</p>
                    </div>

                    @if ($bugReport->steps_to_reproduce)
                        <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                            <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Steps to reproduce</p>
                            <p class="pt-[5px] whitespace-pre-line text-[10px] leading-4 text-[#56635D]">{{ $bugReport->steps_to_reproduce }}</p>
                        </div>
                    @endif

                    @if ($bugReport->actual_result || $bugReport->expected_result)
                        <div class="grid grid-cols-1 border-t-[0.8px] border-[#EDF0EE] sm:grid-cols-2">
                            <div class="border-b-[0.8px] border-[#EDF0EE] px-[18px] py-3 sm:border-b-0 sm:border-r-[0.8px]">
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Actual result</p>
                                <p class="pt-[5px] text-[10px] leading-4 text-[#56635D]">{{ $bugReport->actual_result ?? '—' }}</p>
                            </div>
                            <div class="px-[18px] py-3">
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Expected result</p>
                                <p class="pt-[5px] text-[10px] leading-4 text-[#56635D]">{{ $bugReport->expected_result ?? '—' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                        <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Screenshot</p>
                        @if ($bugReport->hasScreenshot())
                            <a href="{{ asset('storage/' . $bugReport->screenshot_path) }}" target="_blank" rel="noopener noreferrer" class="mt-[5px] block overflow-hidden rounded-[7px] border-[0.8px] border-[#DFE5E2] bg-[#F5F7F6]">
                                <div class="flex h-5 items-center gap-1 bg-[#E4E9E6] px-2">
                                    <span class="size-1 rounded-full bg-[#A6B0AB]"></span>
                                    <span class="size-1 rounded-full bg-[#A6B0AB]"></span>
                                    <span class="size-1 rounded-full bg-[#A6B0AB]"></span>
                                </div>
                                <img src="{{ asset('storage/' . $bugReport->screenshot_path) }}" alt="Screenshot {{ $bugReport->title }}" class="max-h-52 w-full object-contain p-3">
                            </a>
                        @else
                            <div class="mt-[5px] rounded-[7px] border-[0.8px] border-dashed border-[#DFE5E2] bg-[#F5F7F6] p-6 text-center text-[10px] text-[#87918C]">
                                No screenshot attached
                            </div>
                        @endif
                    </div>

                    <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                        <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Visual Screenshot Analysis (AI)</p>
                        @if ($bugReport->hasImageDescription())
                            <p class="pt-[5px] whitespace-pre-line text-[10px] leading-4 text-[#56635D]">{{ $bugReport->image_description }}</p>
                        @elseif ($bugReport->hasScreenshot())
                            <p class="pt-[5px] text-[10px] text-[#87918C]">No visual analysis yet. Re-run analysis or run backfill command.</p>
                        @else
                            <p class="pt-[5px] text-[10px] text-[#87918C]">No screenshot to analyze.</p>
                        @endif
                    </div>
                </div>

                {{-- Right Panel: Historical Bug Report --}}
                <div class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white">
                    <div class="flex items-center justify-between gap-[10px] border-b-[0.8px] border-[#E2E8E5] bg-[#FAFBFA] px-[18px] py-4">
                        <div class="flex items-center gap-[10px]">
                            <div class="grid size-[34px] shrink-0 place-items-center rounded-[8px] bg-[#EEF3F6] text-[#52748A]">
                                <x-auth.icon name="refresh" class="size-[18px]" />
                            </div>
                            <div>
                                <p class="text-[8px] uppercase leading-3 tracking-[0.56px] text-[#67736D]">Historical bug report</p>
                                <h2 class="pt-[3px] text-[12px] font-semibold leading-[18px] text-[#09090B]">BUG-{{ $selectedCandidate->id }}</h2>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-[5px] rounded-full border-[0.8px] border-[#D9EEE3] bg-[#EDF7F2] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] text-[#26714F]">
                            <span class="size-[5px] rounded-full bg-[#26714F]"></span>
                            <span>{{ $selectedCandidate->status->label() }}</span>
                        </span>
                    </div>

                    <div class="px-[18px] pt-4 pb-[5px] space-y-[13px]">
                        <div>
                            <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Bug title</p>
                            <p class="pt-[5px] text-[10.5px] font-medium leading-4 text-[#2E3B35]">{{ $selectedCandidate->title }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 py-1">
                            <div>
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Reporter</p>
                                <p class="pt-[5px] text-[10.5px] font-medium leading-4 text-[#2E3B35]">{{ $selectedCandidate->user?->name ?? 'Reporter' }}</p>
                            </div>
                            <div>
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Module</p>
                                <p class="pt-[5px] text-[10.5px] font-medium leading-4 text-[#2E3B35]">{{ $selectedCandidate->module ?? 'General' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                        <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Bug description</p>
                        <p class="pt-[5px] whitespace-pre-line text-[10px] leading-4 text-[#56635D]">{{ $selectedCandidate->description }}</p>
                    </div>

                    @if ($selectedCandidate->steps_to_reproduce)
                        <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                            <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Steps to reproduce</p>
                            <p class="pt-[5px] whitespace-pre-line text-[10px] leading-4 text-[#56635D]">{{ $selectedCandidate->steps_to_reproduce }}</p>
                        </div>
                    @endif

                    @if ($selectedCandidate->actual_result || $selectedCandidate->expected_result)
                        <div class="grid grid-cols-1 border-t-[0.8px] border-[#EDF0EE] sm:grid-cols-2">
                            <div class="border-b-[0.8px] border-[#EDF0EE] px-[18px] py-3 sm:border-b-0 sm:border-r-[0.8px]">
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Actual result</p>
                                <p class="pt-[5px] text-[10px] leading-4 text-[#56635D]">{{ $selectedCandidate->actual_result ?? '—' }}</p>
                            </div>
                            <div class="px-[18px] py-3">
                                <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Expected result</p>
                                <p class="pt-[5px] text-[10px] leading-4 text-[#56635D]">{{ $selectedCandidate->expected_result ?? '—' }}</p>
                            </div>
                        </div>
                    @endif

                    <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                        <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Screenshot</p>
                        @if ($selectedCandidate->hasScreenshot())
                            <a href="{{ asset('storage/' . $selectedCandidate->screenshot_path) }}" target="_blank" rel="noopener noreferrer" class="mt-[5px] block overflow-hidden rounded-[7px] border-[0.8px] border-[#DFE5E2] bg-[#F5F7F6]">
                                <div class="flex h-5 items-center gap-1 bg-[#E4E9E6] px-2">
                                    <span class="size-1 rounded-full bg-[#A6B0AB]"></span>
                                    <span class="size-1 rounded-full bg-[#A6B0AB]"></span>
                                    <span class="size-1 rounded-full bg-[#A6B0AB]"></span>
                                </div>
                                <img src="{{ asset('storage/' . $selectedCandidate->screenshot_path) }}" alt="Screenshot {{ $selectedCandidate->title }}" class="max-h-52 w-full object-contain p-3">
                            </a>
                        @else
                            <div class="mt-[5px] rounded-[7px] border-[0.8px] border-dashed border-[#DFE5E2] bg-[#F5F7F6] p-6 text-center text-[10px] text-[#87918C]">
                                No screenshot attached
                            </div>
                        @endif
                    </div>

                    <div class="border-t-[0.8px] border-[#EDF0EE] px-[18px] py-3">
                        <p class="text-[8.5px] font-semibold uppercase leading-[13px] tracking-[0.425px] text-[#77827D]">Visual Screenshot Analysis (AI)</p>
                        @if ($selectedCandidate->hasImageDescription())
                            <p class="pt-[5px] whitespace-pre-line text-[10px] leading-4 text-[#56635D]">{{ $selectedCandidate->image_description }}</p>
                        @elseif ($selectedCandidate->hasScreenshot())
                            <p class="pt-[5px] text-[10px] text-[#87918C]">No visual analysis yet. Re-run analysis or run backfill command.</p>
                        @else
                            <p class="pt-[5px] text-[10px] text-[#87918C]">No screenshot to analyze.</p>
                        @endif
                    </div>

                    @if ($selectedCandidate->resolution_note)
                        <div class="px-[18px] pb-[17px]">
                            <div class="flex items-start gap-[9px] rounded-[7px] border-[0.8px] border-[#D9ECE2] bg-[#EDF7F2] p-[10px]">
                                <x-auth.icon name="check" class="size-[18px] shrink-0 text-[#2D7456]" />
                                <div>
                                    <p class="text-[9.5px] font-bold leading-[14px] text-[#355044]">Historical resolution</p>
                                    <p class="pt-[3px] text-[8.5px] leading-[13px] text-[#5D7067]">{{ $selectedCandidate->resolution_note }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ======================== DECISION BAR ======================== --}}
            <form method="POST" action="{{ route('bug-reports.status.update', $bugReport) }}" class="flex flex-col gap-4 rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white px-[18px] py-4 lg:flex-row lg:items-center lg:justify-between">
                @csrf
                @method('PATCH')
                <input type="hidden" name="duplicate_of_id" value="{{ $selectedCandidate->id }}">
                <div>
                    <p class="text-[11px] font-bold leading-4 text-[#09090B]">Ready to make a decision?</p>
                    <p class="pt-[3px] text-[9px] leading-[14px] text-[#67736D]">TwinBug’s result is a recommendation and requires developer review.</p>
                </div>
                <div class="flex flex-wrap items-center gap-[7px]">
                    <a href="{{ route('bug-reports.show', $bugReport) }}"
                        class="inline-flex h-10 items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        <span>Open external ticket</span>
                    </a>
                    <button type="submit" name="status" value="{{ App\Enums\BugStatus::InProgress->value }}"
                        class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        <span>Reject recommendation</span>
                    </button>
                    <button type="submit" name="status" value="{{ App\Enums\BugStatus::Resolved->value }}"
                        class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] bg-[#A83A36] px-[15px] text-[12px] font-semibold leading-[18px] text-white transition hover:bg-[#8E2F2C]">
                        <span>Link as duplicate</span>
                    </button>
                </div>
            </form>

        @else

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-[11.5px] leading-[17px]" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="text-[#83908A] transition hover:text-[#33423B]">Developer Dashboard</a>
            <span class="text-[#83908A]">›</span>
            <a href="{{ route('bug-reports.index') }}" class="text-[#83908A] transition hover:text-[#33423B]">Detection Results</a>
            <span class="text-[#83908A]">›</span>
            <span class="font-semibold text-[#33423B]">BUG-{{ $bugReport->id }}</span>
        </nav>

        {{-- ======================== PAGE HEADING ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase leading-4 tracking-[1px] text-[#236B51]">
                    Report BUG-{{ $bugReport->id }} · {{ $bugReport->detection_status->label() }}
                </p>
                <h1 class="pt-[7px] text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">
                    {{ $selectedCandidate && request()->has('candidate') ? 'Detection Comparison' : 'Detection Results' }}
                </h1>
                <p class="pt-[7px] text-[13px] leading-5 text-[#67736D]">
                    {{ $bugReport->title }}
                </p>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <span class="inline-flex h-[24.6px] items-center gap-[5px] rounded-full border-[0.8px] border-[#D9EEE3] bg-[#EDF7F2] px-[7px] text-[9.5px] font-semibold leading-[14px] text-[#26714F]">
                    <span class="size-[5px] rounded-full bg-[#26714F]"></span>
                    <span>{{ $bugReport->detection_status->label() }}</span>
                </span>
            </div>
        </div>

        {{-- ======================== DARK GREEN SUMMARY BANNER ======================== --}}
        <section class="detection-summary rounded-[12px] bg-[#0D2D22] p-[22px] text-white shadow-[0_6px_20px_rgba(13,45,34,0.12)]" aria-label="Detection summary">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[180px_1fr]">
                <div class="border-b border-white/[0.13] pb-5 lg:border-b-0 lg:border-r lg:pb-0 lg:pr-5">
                    <p class="text-[10px] leading-[15px] text-[#B8CBC2]">Overall similarity</p>
                    <p class="pt-1 pb-[7px] text-[48px] font-bold leading-[53px] tracking-[-2px] text-white">
                        {{ $overallPercent !== null ? $overallPercent . '%' : '—' }}
                    </p>
                    <div class="flex items-center gap-[5px] text-[9.5px] leading-[14px] text-[#BFE0CF]">
                        <x-auth.icon name="sparkles" class="size-[15px]" />
                        <span>
                            {{ $overallSimilarity !== null ? ($overallSimilarity >= 0.8 ? 'Very high similarity' : ($overallSimilarity >= 0.65 ? 'Moderate similarity' : 'Low similarity')) : 'No matching reports' }}
                        </span>
                    </div>
                </div>

                <div class="flex flex-col justify-between">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <p class="text-[9px] leading-[14px] text-[#94AFA2]">Detection mode</p>
                            <p class="pt-[5px] flex items-center gap-[5px] text-[10.5px] font-bold leading-4 text-white">
                                <x-auth.icon name="eye" class="size-4" />
                                <span>{{ $bugReport->hasScreenshot() ? 'Multimodal' : 'Text only' }}</span>
                            </p>
                        </div>
                        <div>
                            <p class="text-[9px] leading-[14px] text-[#94AFA2]">Candidates found</p>
                            <p class="pt-[5px] text-[10.5px] font-bold leading-4 text-white">
                                {{ $results->count() }} historical {{ $results->count() === 1 ? 'report' : 'reports' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[9px] leading-[14px] text-[#94AFA2]">Analyzed</p>
                            <p class="pt-[5px] text-[10.5px] font-bold leading-4 text-white">
                                {{ $bugReport->detected_at?->format('d M Y, H:i') ?? 'Not completed' }}
                            </p>
                        </div>
                    </div>

                    <p class="pt-4 text-[10px] leading-4 text-[#C4D2CB]">
                        TwinBug found historical reports with semantic and visual similarities. Review each candidate before making a triage decision.
                    </p>

                    <div class="mt-[11px] flex items-center gap-[7px] rounded-[6px] bg-white/[0.06] px-[10px] py-2 text-[9px] leading-[14px] text-[#A9C2B6]">
                        <x-auth.icon name="alert" class="size-4 shrink-0" />
                        <span>Similarity scores are supporting evidence, not an automatic duplicate decision.</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======================== STATUS STATES (FAILED / PENDING / PROCESSING) ======================== --}}
        @if ($bugReport->detection_status === App\Enums\DetectionStatus::Failed)
            <div class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-5">
                <div class="flex items-center gap-2.5 text-danger">
                    <x-auth.icon name="alert" class="size-4" />
                    <h2 class="text-[14px] font-semibold">Analysis Failed</h2>
                </div>
                <p class="mt-2 text-[11px] text-[#67736D]">{{ $bugReport->detection_error ?? 'An error occurred during duplicate analysis.' }}</p>
                <form method="POST" action="{{ route('bug-reports.detection.rerun', $bugReport) }}" class="mt-4">
                    @csrf
                    <button type="submit" class="inline-flex h-10 cursor-pointer items-center gap-2 rounded-[8px] bg-[#A83A36] px-4 text-[12px] font-semibold text-white">
                        <x-auth.icon name="refresh" class="size-3.5" />
                        <span>Re-run analysis</span>
                    </button>
                </form>
            </div>
        @endif

        @if (in_array($bugReport->detection_status, [App\Enums\DetectionStatus::Pending, App\Enums\DetectionStatus::Processing]))
            <div class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-5">
                <div class="flex items-center gap-2.5 text-[#8B6418]">
                    <x-auth.icon name="alert" class="size-4" />
                    <h2 class="text-[14px] font-semibold text-[#09090B]">
                        {{ $bugReport->detection_status === App\Enums\DetectionStatus::Pending ? 'Menunggu Analisis' : 'Sedang Menganalisis' }}
                    </h2>
                </div>
                <p class="mt-2 text-[11px] text-[#67736D]">
                    Analisis kemiripan sedang diproses. Silakan cek kembali beberapa saat lagi.
                </p>
                @if ($bugReport->detection_status === App\Enums\DetectionStatus::Processing)
                    <a href="{{ route('report.detection_result', $bugReport) }}"
                        class="mt-4 inline-flex h-10 items-center gap-2 rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-4 text-[12px] font-semibold text-[#35433C]">
                        <x-auth.icon name="refresh" class="size-3.5" />
                        <span>Refresh</span>
                    </a>
                @endif
            </div>
        @endif

        {{-- ======================== TOP SIMILAR HISTORICAL REPORTS ======================== --}}
        @if ($bugReport->detection_status === App\Enums\DetectionStatus::Completed)
            <section class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                <div class="flex flex-wrap items-center justify-between gap-4 border-b-[0.8px] border-[#E9EEEB] px-5 py-[18px]">
                    <div>
                        <h2 class="text-[14px] font-normal leading-[21px] tracking-[-0.15px] text-[#09090B]">Top similar historical reports</h2>
                        <p class="pt-1 text-[11px] leading-4 text-[#67736D]">Ranked by final similarity score, highest first.</p>
                    </div>
                    <span class="inline-flex items-center gap-[5px] rounded-full bg-[#F3F6F4] px-[9px] py-[6px] text-[9.5px] leading-[14px] text-[#236B51]">
                        <x-auth.icon name="sparkles" class="size-[14px]" />
                        <span>{{ $bugReport->hasScreenshot() ? 'Multimodal analysis' : 'Text analysis' }}</span>
                    </span>
                </div>

                @if ($results->isEmpty())
                    <div class="px-6 py-10 text-center">
                        <p class="text-[13px] font-semibold text-[#09090B]">No similar historical reports found.</p>
                        <p class="mt-1 text-[11px] text-[#67736D]">TwinBug did not find any historical bug reports matching the similarity threshold.</p>
                    </div>
                @else
                    <div class="divide-y-[0.8px] divide-[#E2E8E5] px-[18px]">
                        @foreach ($results as $result)
                            @php
                                $textPct = $result->text_score !== null ? (int) round($result->text_score * 100) : 0;
                                $imagePct = $result->image_score !== null ? (int) round($result->image_score * 100) : 0;
                                $finalPct = (int) round(max((float) ($result->text_score ?? 0), (float) ($result->image_score ?? 0)) * 100);
                                $candStatus = $result->candidateReport->status;
                            @endphp
                            <article class="flex flex-col gap-4 py-4 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <span class="grid size-[30px] shrink-0 place-items-center rounded-[8px] bg-[#F1F5F3] text-[10px] font-bold text-[#236B51]">
                                        #{{ $result->rank }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="text-[16px] font-semibold leading-6 text-[#236B51]">BUG-{{ $result->candidate_report_id }}</span>
                                            <span class="inline-flex items-center gap-[5px] rounded-full border-[0.8px] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] {{ $candStatus === App\Enums\BugStatus::Resolved ? 'border-[#D9EEE3] bg-[#EDF7F2] text-[#26714F]' : 'border-[#E0E5E2] bg-[#F1F4F2] text-[#58665F]' }}">
                                                <span class="size-[5px] rounded-full {{ $candStatus === App\Enums\BugStatus::Resolved ? 'bg-[#26714F]' : 'bg-[#58665F]' }}"></span>
                                                <span>{{ $candStatus->label() }}</span>
                                            </span>
                                        </div>
                                        <h3 class="pt-1 truncate text-[11px] font-semibold leading-[15px] text-[#09090B]">
                                            {{ $result->candidateReport->title }}
                                        </h3>
                                    </div>
                                </div>

                                {{-- 3 Score Bars --}}
                                <div class="grid grid-cols-3 gap-[14px] lg:w-[420px] shrink-0">
                                    <div>
                                        <p class="text-[8.5px] leading-[13px] text-[#67736D]">Text</p>
                                        <div class="mt-1 flex items-center gap-[9px]">
                                            <div class="h-[6px] flex-1 overflow-hidden rounded-full bg-[#E9EEEB]">
                                                <div class="h-full rounded-full {{ $textPct >= 80 ? 'bg-[#2C795A]' : 'bg-[#BE8C32]' }}" style="width: {{ $textPct }}%"></div>
                                            </div>
                                            <span class="w-7 text-[10px] font-bold leading-[15px] text-[#34413B]">{{ $textPct }}%</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[8.5px] leading-[13px] text-[#67736D]">Image</p>
                                        <div class="mt-1 flex items-center gap-[9px]">
                                            <div class="h-[6px] flex-1 overflow-hidden rounded-full bg-[#E9EEEB]">
                                                <div class="h-full rounded-full {{ $imagePct >= 85 ? 'bg-[#2C795A]' : 'bg-[#BE8C32]' }}" style="width: {{ $imagePct }}%"></div>
                                            </div>
                                            <span class="w-7 text-[10px] font-bold leading-[15px] text-[#34413B]">{{ $result->image_score !== null ? $imagePct . '%' : '—' }}</span>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="text-[8.5px] leading-[13px] text-[#67736D]">Final</p>
                                        <div class="mt-1 flex items-center gap-[9px]">
                                            <div class="h-[6px] flex-1 overflow-hidden rounded-full bg-[#E9EEEB]">
                                                <div class="h-full rounded-full {{ $finalPct >= 80 ? 'bg-[#2C795A]' : 'bg-[#BE8C32]' }}" style="width: {{ $finalPct }}%"></div>
                                            </div>
                                            <span class="w-7 text-[10px] font-bold leading-[15px] text-[#34413B]">{{ $finalPct }}%</span>
                                        </div>
                                    </div>
                                </div>

                                <a href="{{ route('bug-reports.detection', ['bugReport' => $bugReport, 'candidate' => $result->candidate_report_id]) }}"
                                    class="inline-flex h-[34px] shrink-0 items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[11px] text-[11px] font-semibold leading-4 text-[#35433C] transition hover:bg-[#F7F9F8]">
                                    <span>View comparison</span>
                                    <span aria-hidden="true">→</span>
                                </a>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- ======================== SIDE-BY-SIDE COMPARISON (WHEN CANDIDATE SELECTED) ======================== --}}
            @if ($selectedCandidate && request()->has('candidate'))
                <section class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-5">
                    <div class="mb-4 flex items-center justify-between border-b-[0.8px] border-[#E9EEEB] pb-3">
                        <div>
                            <h2 class="text-[14px] font-semibold text-[#09090B]">Side-by-side comparison: BUG-{{ $bugReport->id }} vs BUG-{{ $selectedCandidate->id }}</h2>
                            <p class="text-[11px] text-[#67736D]">Compare report descriptions and screenshots before making a triage decision.</p>
                        </div>
                        <a href="{{ route('report.detection_result', $bugReport) }}" class="text-[11px] font-semibold text-[#236B51] hover:underline">Close comparison</a>
                    </div>
                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                        <div class="rounded-[9px] border-[0.8px] border-[#E2E8E5] bg-[#FAFBFA] p-4 space-y-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#236B51]">Incoming: BUG-{{ $bugReport->id }}</p>
                                <h3 class="mt-1 text-[13px] font-semibold text-[#09090B]">{{ $bugReport->title }}</h3>
                                <p class="mt-2 whitespace-pre-line text-[11.5px] leading-5 text-[#536059]">{{ $bugReport->description }}</p>
                            </div>
                            <div class="rounded-[7px] border-[0.8px] border-[#E2E8E5] bg-white p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#67736D]">Screenshot</p>
                                @if ($bugReport->hasScreenshot())
                                    <a href="{{ asset('storage/' . $bugReport->screenshot_path) }}" target="_blank" rel="noopener noreferrer" class="mt-2 block overflow-hidden rounded-[6px] border-[0.8px] border-[#E2E8E5]">
                                        <img src="{{ asset('storage/' . $bugReport->screenshot_path) }}" alt="Screenshot {{ $bugReport->title }}" class="max-h-56 w-full object-contain bg-[#F7F9F8]">
                                    </a>
                                @else
                                    <p class="mt-2 text-[11px] text-[#84908A]">No screenshot attached</p>
                                @endif
                            </div>
                            <div class="rounded-[7px] border-[0.8px] border-[#E2E8E5] bg-white p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#67736D]">Visual Screenshot Analysis (AI)</p>
                                @if ($bugReport->hasImageDescription())
                                    <p class="mt-1.5 whitespace-pre-line text-[11.5px] leading-5 text-[#536059]">{{ $bugReport->image_description }}</p>
                                @elseif ($bugReport->hasScreenshot())
                                    <p class="mt-1.5 text-[11px] text-[#84908A]">No visual analysis yet. Re-run analysis or run backfill command.</p>
                                @else
                                    <p class="mt-1.5 text-[11px] text-[#84908A]">No screenshot to analyze.</p>
                                @endif
                            </div>
                        </div>

                        <div class="rounded-[9px] border-[0.8px] border-[#E2E8E5] bg-[#FAFBFA] p-4 space-y-3">
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#236B51]">Candidate: BUG-{{ $selectedCandidate->id }}</p>
                                <h3 class="mt-1 text-[13px] font-semibold text-[#09090B]">{{ $selectedCandidate->title }}</h3>
                                <p class="mt-2 whitespace-pre-line text-[11.5px] leading-5 text-[#536059]">{{ $selectedCandidate->description }}</p>
                            </div>
                            <div class="rounded-[7px] border-[0.8px] border-[#E2E8E5] bg-white p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#67736D]">Screenshot</p>
                                @if ($selectedCandidate->hasScreenshot())
                                    <a href="{{ asset('storage/' . $selectedCandidate->screenshot_path) }}" target="_blank" rel="noopener noreferrer" class="mt-2 block overflow-hidden rounded-[6px] border-[0.8px] border-[#E2E8E5]">
                                        <img src="{{ asset('storage/' . $selectedCandidate->screenshot_path) }}" alt="Screenshot {{ $selectedCandidate->title }}" class="max-h-56 w-full object-contain bg-[#F7F9F8]">
                                    </a>
                                @else
                                    <p class="mt-2 text-[11px] text-[#84908A]">No screenshot attached</p>
                                @endif
                            </div>
                            <div class="rounded-[7px] border-[0.8px] border-[#E2E8E5] bg-white p-3">
                                <p class="text-[10px] font-bold uppercase tracking-wider text-[#67736D]">Visual Screenshot Analysis (AI)</p>
                                @if ($selectedCandidate->hasImageDescription())
                                    <p class="mt-1.5 whitespace-pre-line text-[11.5px] leading-5 text-[#536059]">{{ $selectedCandidate->image_description }}</p>
                                @elseif ($selectedCandidate->hasScreenshot())
                                    <p class="mt-1.5 text-[11px] text-[#84908A]">No visual analysis yet. Re-run analysis or run backfill command.</p>
                                @else
                                    <p class="mt-1.5 text-[11px] text-[#84908A]">No screenshot to analyze.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endif

        {{-- ======================== TRIAGE DECISION CARD ======================== --}}
        <section class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[20.8px]">
            <form method="POST" action="{{ route('bug-reports.status.update', $bugReport) }}" class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                @csrf
                @method('PATCH')
                <input type="hidden" name="duplicate_of_id" value="{{ $selectedCandidate?->id }}">

                <div class="flex-1 max-w-[680px]">
                    <h2 class="text-[14px] font-normal leading-[21px] tracking-[-0.15px] text-[#09090B]">Triage decision</h2>
                    <p class="pt-1 text-[11px] leading-4 text-[#67736D]">Add notes and choose the most accurate outcome after reviewing the evidence.</p>
                    <textarea
                        name="resolution_note"
                        rows="3"
                        placeholder="Add review notes for your team..."
                        class="mt-[13px] min-h-[75px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] py-[10px] text-[11.5px] leading-[18px] text-[#24322B] placeholder:text-[#A4ADA8] focus:border-[#236B51] focus:outline-none">{{ old('resolution_note', $bugReport->resolution_note) }}</textarea>
                </div>

                <div class="flex flex-wrap items-center gap-2 shrink-0 lg:pt-1">
                    <button type="submit" name="status" value="{{ $bugReport->status->value }}"
                        class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        <span>Save review notes</span>
                    </button>
                    <button type="submit" name="status" value="{{ App\Enums\BugStatus::InProgress->value }}"
                        class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        <span>Confirm as new bug</span>
                    </button>
                    <button type="submit" name="status" value="{{ App\Enums\BugStatus::Resolved->value }}"
                        class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] bg-[#A83A36] px-[15px] text-[12px] font-semibold leading-[18px] text-white transition hover:bg-[#8E2F2C]">
                        <span>Mark as duplicate</span>
                    </button>
                </div>
            </form>
        </section>
        @endif

    </div>

</x-layouts.app>
