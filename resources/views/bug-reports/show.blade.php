<x-layouts.app title="Detail Laporan Bug" meta-description="Detail lengkap laporan bug pada sistem TwinBug.">

    <div class="workspace-report-detail-page mx-auto max-w-[1540px] p-8 space-y-6">

        @if (in_array($report->detection_status, [App\Enums\DetectionStatus::Pending, App\Enums\DetectionStatus::Processing]))
            {{-- ======================== PROCESSING STATUS VIEW ======================== --}}
            <nav class="flex items-center gap-2 text-[11.5px] leading-[17px]" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="text-[#83908A] transition hover:text-[#33423B]">Dashboard</a>
                <span class="text-[#83908A]">›</span>
                <a href="{{ route('bug-reports.index') }}" class="text-[#83908A] transition hover:text-[#33423B]">My Reports</a>
                <span class="text-[#83908A]">›</span>
                <span class="font-semibold text-[#33423B]">BUG-{{ $report->id }}</span>
            </nav>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase leading-[15px] tracking-[1px] text-[#236B51]">Report BUG-{{ $report->id }}</p>
                    <h1 class="pt-[7px] text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">Processing your bug report</h1>
                    <p class="pt-[7px] text-[13px] leading-5 text-[#67736D]">{{ $report->title }}</p>
                </div>
                <span class="inline-flex h-[24.6px] shrink-0 items-center gap-[5px] rounded-full border-[0.8px] border-[#D9E9F2] bg-[#EEF5F9] px-[7px] text-[9.5px] font-semibold leading-[14px] text-[#356C8C]">
                    <span class="size-[5px] rounded-full bg-[#356C8C]"></span>
                    <span>Processing</span>
                </span>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">
                {{-- Left: Analysis Progress Pipeline --}}
                <section class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[22px] shadow-[0_1px_2px_rgba(12,35,25,0.024)] lg:col-span-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h2 class="text-[14px] font-normal leading-[21px] text-[#09090B]">Analysis progress</h2>
                            <p class="pt-1 text-[10.5px] leading-4 text-[#67736D]">TwinBug is comparing your report with historical reports.</p>
                        </div>
                        <span class="text-[11px] font-bold leading-4 text-[#668077]">Data pending</span>
                    </div>

                    <div class="mt-[18px] h-[7px] w-full overflow-hidden rounded-full bg-[#E9EEEB]">
                        <div class="h-full w-[45%] rounded-full bg-[#236B51] transition-all"></div>
                    </div>

                    <div class="mt-[15px] flex items-start gap-[9px] rounded-[8px] border-[0.8px] border-[#E0EBE5] bg-[#F4F8F6] px-3 py-[10px]">
                        <x-auth.icon name="alert" class="size-[17px] shrink-0 text-[#46745F]" />
                        <div>
                            <p class="text-[10px] font-bold leading-[15px] text-[#3C5147]">Progress is based on live processing status.</p>
                            <p class="pt-[2px] text-[9px] leading-[14px] text-[#67736D]">No estimated completion time is available from the system.</p>
                        </div>
                    </div>

                    @php
                        $hasScreenshot = $report->hasScreenshot();
                        $isProcessing = $report->detection_status === App\Enums\DetectionStatus::Processing;
                        $pipelineSteps = [
                            ['Report Validation', 'Checking required fields and file integrity.', 'completed'],
                            ['Text Feature Extraction', config('services.ml.model', 'paraphrase-multilingual-MiniLM-L12-v2'), $isProcessing ? 'completed' : 'in_progress'],
                            ['Image Feature Extraction', $hasScreenshot ? 'CLIP / OCR visual feature extraction' : 'CLIP ViT-B/32 · No screenshot provided', $hasScreenshot ? ($isProcessing ? 'in_progress' : 'pending') : 'skipped'],
                            ['Historical Bug Search', 'FAISS vector similarity search', $isProcessing ? 'in_progress' : 'pending'],
                            ['Similarity Scoring', 'Cosine Similarity', 'pending'],
                            ['Multimodal Late Fusion', $hasScreenshot ? 'Combining text and visual scores' : 'Text-only fallback is active', $hasScreenshot ? 'pending' : 'skipped'],
                            ['Detection Results Generation', 'Preparing ranked candidates', 'pending'],
                        ];
                    @endphp

                    <div class="mt-5 pl-[7px]">
                        @foreach ($pipelineSteps as $stepIndex => [$stepTitle, $stepSub, $stepState])
                            <div class="relative flex min-h-[67px] items-start justify-between gap-4 pb-4">
                                @if (! $loop->last)
                                    <span class="absolute left-[13px] top-[27px] h-[40px] w-[1px] bg-[#DFE6E2]" aria-hidden="true"></span>
                                @endif
                                <div class="flex items-start gap-[14px]">
                                    @if ($stepState === 'completed')
                                        <span class="relative z-10 grid size-[27px] shrink-0 place-items-center rounded-full border-[0.8px] border-[#236B51] bg-[#236B51] text-white">
                                            <x-auth.icon name="check" class="size-[15px]" />
                                        </span>
                                    @elseif ($stepState === 'in_progress')
                                        <span class="relative z-10 grid size-[27px] shrink-0 place-items-center rounded-full border-[0.8px] border-[#CFE1EB] bg-[#EDF5F9] text-[#41718D]">
                                            <x-auth.icon name="refresh" class="size-[15px]" />
                                        </span>
                                    @elseif ($stepState === 'skipped')
                                        <span class="relative z-10 grid size-[27px] shrink-0 place-items-center rounded-full border-[0.8px] border-[#DCE3DF] bg-[#F3F5F4] text-[#89938E]">
                                            <span class="text-[11px]">→</span>
                                        </span>
                                    @else
                                        <span class="relative z-10 grid size-[27px] shrink-0 place-items-center rounded-full border-[0.8px] border-[#DCE3DF] bg-white text-[10px] font-semibold text-[#89938E]">
                                            {{ $stepIndex + 1 }}
                                        </span>
                                    @endif

                                    <div class="pt-[1px]">
                                        <p class="text-[11px] font-bold leading-4 text-[#09090B]">{{ $stepTitle }}</p>
                                        <p class="pt-1 text-[9.5px] leading-[14px] text-[#67736D]">{{ $stepSub }}</p>
                                    </div>
                                </div>

                                @if ($stepState === 'completed')
                                    <span class="inline-flex shrink-0 items-center gap-[5px] rounded-full border-[0.8px] border-[#D9EEE3] bg-[#EDF7F2] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] text-[#26714F]">
                                        <span class="size-[5px] rounded-full bg-[#26714F]"></span>
                                        <span>Completed</span>
                                    </span>
                                @elseif ($stepState === 'in_progress')
                                    <span class="inline-flex shrink-0 items-center gap-[5px] rounded-full border-[0.8px] border-[#D9E9F2] bg-[#EEF5F9] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] text-[#356C8C]">
                                        <span class="size-[5px] rounded-full bg-[#356C8C]"></span>
                                        <span>In Progress</span>
                                    </span>
                                @elseif ($stepState === 'skipped')
                                    <span class="inline-flex shrink-0 items-center gap-[5px] rounded-full border-[0.8px] border-[#E5E9E7] bg-[#F5F6F5] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] text-[#7D8782]">
                                        <span class="size-[5px] rounded-full bg-[#7D8782]"></span>
                                        <span>Skipped</span>
                                    </span>
                                @else
                                    <span class="inline-flex shrink-0 items-center gap-[5px] rounded-full border-[0.8px] border-[#E5E9E7] bg-[#F5F6F5] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] text-[#7D8782]">
                                        <span class="size-[5px] rounded-full bg-[#7D8782]"></span>
                                        <span>Pending</span>
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Right: You can leave this page --}}
                <aside class="lg:col-span-4">
                    <div class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-6 text-center shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                        <div class="mx-auto grid size-12 place-items-center rounded-[12px] bg-[#EEF5F1] text-[#236B51]">
                            <x-auth.icon name="sparkles" class="size-6" />
                        </div>
                        <h2 class="mt-4 text-[13px] font-bold text-[#09090B]">You can leave this page</h2>
                        <p class="mt-1.5 text-[10.5px] leading-4 text-[#67736D]">
                            Analysis continues securely in the background. We'll update the report as soon as results are available.
                        </p>
                        <a href="{{ route('bug-reports.index') }}"
                            class="mt-5 inline-flex h-10 w-full items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold text-[#35433C] transition hover:bg-[#F7F9F8]">
                            View my reports
                        </a>
                        <p class="mt-4 text-[9px] leading-[14px] text-[#84908A]">
                            You'll receive an in-app update when processing is complete.
                        </p>
                    </div>
                </aside>
            </div>
        @endif

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3 text-sage-600" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <a href="{{ route('bug-reports.index') }}" class="text-label transition hover:text-ink">Daftar Laporan</a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-md bg-input px-2 py-0.5 text-[11px] font-semibold text-ink">Detail Laporan #{{ $report->id }}</span>
        </nav>

        {{-- ======================== TWIN BUG ALERTS ======================== --}}
        @if ($report->isDuplicate())
            <div class="rounded-2xl border border-purple-200 bg-purple-50/80 p-4 shadow-xs">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <span class="grid size-8 shrink-0 place-items-center rounded-xl bg-purple-200 text-purple-800">
                            <x-auth.icon name="sparkles" class="size-4" />
                        </span>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700">Laporan Duplikat (Twin Bug)</span>
                            <p class="text-xs sm:text-sm font-semibold text-purple-950">
                                Laporan ini ditandai sebagai duplikat dari laporan
                                <a href="{{ route('bug-reports.show', $report->duplicate_of_id) }}" class="underline decoration-purple-400 hover:text-purple-700">
                                    #{{ $report->duplicate_of_id }} - {{ $report->duplicateOf?->title }}
                                </a>
                            </p>
                        </div>
                    </div>
                    <a
                        href="{{ route('bug-reports.show', $report->duplicate_of_id) }}"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl bg-purple-600 px-3.5 py-1.5 text-xs font-bold text-white shadow-xs transition hover:bg-purple-700"
                    >
                        <span>Buka Bug Master</span>
                        <x-auth.icon name="arrow-left" class="size-3 rotate-180" />
                    </a>
                </div>
            </div>
        @endif

        {{-- ======================== HEADER ======================== --}}
        <div class="workspace-report-heading flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="max-w-3xl">
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $report->severity->badgeClasses() }}">
                        {{ $report->severity->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $report->status->badgeClasses() }}">
                        <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                        {{ $report->status->label() }}
                    </span>

                    @if ($report->environment)
                        <span class="inline-flex items-center rounded-md bg-input px-2 py-0.5 text-[10px] font-mono uppercase tracking-wider text-label">
                            Env: {{ $report->environment }}
                        </span>
                    @endif

                    <span class="text-xs text-label">•</span>
                    <span class="font-mono text-xs font-semibold text-label">#{{ $report->id }}</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-ink">{{ $report->title }}</h1>
                <p class="mt-1 text-xs text-label">
                    Dilaporkan {{ $report->created_at->format('d M Y H:i') }} • {{ $report->created_at->diffForHumans() }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if (auth()->user()->role->value === 'developer')
                    <a
                        href="{{ route('report.detection_result', $report) }}"
                        class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-divider bg-white px-3.5 py-2 text-xs font-semibold text-ink shadow-2xs transition hover:bg-input"
                    >
                        <x-auth.icon name="bug" class="size-3.5 text-sage-600" />
                        <span>Deteksi Duplikasi</span>
                    </a>
                @endif

                <a
                    href="{{ route('bug-reports.index') }}"
                    class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-divider bg-white px-3.5 py-2 text-xs font-semibold text-ink shadow-2xs transition hover:bg-input"
                >
                    <x-auth.icon name="arrow-left" class="size-3.5" />
                    <span>Kembali</span>
                </a>
            </div>
        </div>

        <div class="workspace-report-body grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                {{-- ======================== DESCRIPTION ======================== --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="grid size-6 place-items-center rounded-lg bg-slate-100 text-label">
                            <x-auth.icon name="bug" class="size-3" />
                        </span>
                        <h2 class="font-serif text-base font-bold text-ink">Deskripsi Bug</h2>
                    </div>
                    <p class="min-h-[3rem] whitespace-pre-line text-xs sm:text-sm leading-relaxed text-body">{{ $report->description }}</p>

                    @if ($report->url)
                        <div class="mt-4 flex items-center gap-2 rounded-xl border border-divider/60 bg-input/40 px-3.5 py-2.5">
                            <x-auth.icon name="eye" class="size-3.5 shrink-0 text-label" />
                            <span class="text-[11px] font-semibold text-label">URL:</span>
                            <a href="{{ $report->url }}" target="_blank" rel="noopener noreferrer"
                                class="truncate text-xs font-medium text-sage-700 underline hover:text-sage-900">
                                {{ $report->url }}
                            </a>
                        </div>
                    @endif
                </div>

                {{-- ======================== STRUCTURED REPRODUCTION ======================== --}}
                @if ($report->steps_to_reproduce || $report->expected_result || $report->actual_result)
                    <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                        <div class="mb-4 flex items-center gap-2">
                            <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-800">
                                <x-auth.icon name="code" class="size-3" />
                            </span>
                            <h2 class="font-serif text-base font-bold text-ink">Rincian Teknis &amp; Reproduksi</h2>
                        </div>

                        <div class="space-y-4 text-xs sm:text-sm">
                            @if ($report->steps_to_reproduce)
                                <div class="rounded-xl border border-divider/60 bg-input/40 p-3.5">
                                    <h3 class="text-[10px] font-bold uppercase tracking-wider text-label">Langkah Reproduksi</h3>
                                    <p class="mt-1.5 whitespace-pre-line text-xs leading-relaxed text-ink">{{ $report->steps_to_reproduce }}</p>
                                </div>
                            @endif

                            <div class="grid grid-cols-1 gap-3.5 md:grid-cols-2">
                                @if ($report->expected_result)
                                    <div class="rounded-xl border border-emerald-200/80 bg-emerald-50/40 p-3.5">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Hasil yang Diharapkan</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-emerald-950">{{ $report->expected_result }}</p>
                                    </div>
                                @endif

                                @if ($report->actual_result)
                                    <div class="rounded-xl border border-rose-200/80 bg-rose-50/40 p-3.5">
                                        <h3 class="text-[10px] font-bold uppercase tracking-wider text-rose-800">Hasil Aktual / Galat</h3>
                                        <p class="mt-1 text-xs leading-relaxed text-rose-950">{{ $report->actual_result }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ======================== SCREENSHOT ======================== --}}
                @if ($report->hasScreenshot())
                    <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                        <div class="mb-3.5 flex items-center justify-between">
                            <h2 class="font-serif text-base font-bold text-ink">Lampiran Screenshot</h2>
                            <a
                                href="{{ asset('storage/' . $report->screenshot_path) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-sage-200 bg-sage-50 px-2.5 py-1 text-[11px] font-semibold text-sage-800 transition hover:bg-sage-100"
                            >
                                <x-auth.icon name="eye" class="size-3" />
                                <span>Buka Resolusi Penuh</span>
                            </a>
                        </div>

                        <a
                            href="{{ asset('storage/' . $report->screenshot_path) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="block overflow-hidden rounded-xl border border-divider/80 bg-slate-100"
                        >
                            <img
                                src="{{ asset('storage/' . $report->screenshot_path) }}"
                                alt="Screenshot laporan {{ $report->title }}"
                                class="max-h-[28rem] w-full object-contain"
                            >
                        </a>

                        <dl class="mt-3.5 grid grid-cols-1 gap-2.5 text-xs sm:grid-cols-3">
                            <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Nama Berkas</dt>
                                <dd class="mt-0.5 break-all font-mono text-[11px] font-semibold text-ink">{{ $report->screenshot_name ?? '-' }}</dd>
                            </div>
                            <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Ukuran</dt>
                                <dd class="mt-0.5 font-mono text-[11px] font-semibold text-ink">
                                    {{ $report->screenshot_size ? \Illuminate\Support\Number::fileSize($report->screenshot_size) : '-' }}
                                </dd>
                            </div>
                            <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Dimensi</dt>
                                <dd class="mt-0.5 font-mono text-[11px] font-semibold text-ink">{{ $report->screenshot_dimensions ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                @endif

                {{-- ======================== OCR TEXT ======================== --}}
                @if ($report->hasScreenshot() && auth()->user()->role->value === 'developer' && $report->application_id === auth()->user()->application_id)
                    <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                        <div class="mb-3 flex items-center gap-2">
                            <button
                                class="flex items-center gap-1.5 rounded-lg border border-divider bg-input/40 px-3 py-1.5 text-xs font-semibold text-ink hover:bg-input transition"
                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                            >
                                <x-auth.icon name="text" class="size-3 shrink-0 text-label" />
                                <span>Teks yang Diekstrak dari Screenshot</span>
                                <x-auth.icon name="chevron-down" class="size-2.5 shrink-0 text-label ml-auto transition-transform" />
                            </button>
                        </div>

                        <div class="mt-3 hidden">
                            @if ($report->ocr_status === \App\Enums\OcrStatus::Completed && $report->hasOcrText())
                                <div class="rounded-xl border border-divider/60 bg-input/40 p-3.5">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Hasil OCR</dt>
                                    <dd class="mt-1.5 whitespace-pre-line text-xs sm:text-sm leading-relaxed text-body">{{ $report->ocr_text }}</dd>
                                </div>
                            @elseif ($report->ocr_status === \App\Enums\OcrStatus::Failed)
                                <div class="rounded-xl border border-danger/30 bg-rose-50/70 p-3.5">
                                    <p class="text-xs text-danger">OCR gagal: {{ $report->ocr_error ?? 'Terjadi kesalahan saat mengekstrak teks dari screenshot.' }}</p>
                                </div>
                            @elseif ($report->ocr_status === \App\Enums\OcrStatus::Skipped)
                                <div class="rounded-xl border border-divider/60 bg-input/40 p-3.5">
                                    <p class="text-xs text-label">OCR dilewati: tidak ada gambar yang dapat diekstrak.</p>
                                </div>
                            @else
                                <div class="rounded-xl border border-divider/60 bg-input/40 p-3.5">
                                    <p class="text-xs text-label">OCR belum diproses.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- ======================== LAPORAN SERUPA ======================== --}}
                @if ($similarReports->isNotEmpty())
                    <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                        <div class="mb-4 flex items-center gap-2">
                            <span class="grid size-6 place-items-center rounded-lg bg-purple-100 text-purple-700">
                                <x-auth.icon name="sparkles" class="size-3" />
                            </span>
                            <h2 class="font-serif text-base font-bold text-ink">Laporan Serupa (Twin Bug)</h2>
                        </div>
                        <ul class="space-y-2.5">
                            @foreach ($similarReports as $similar)
                                <li>
                                    <a href="{{ route('bug-reports.show', $similar) }}"
                                        class="flex items-start gap-3 rounded-xl border border-divider/70 bg-input/40 p-3 transition hover:border-purple-300 hover:bg-purple-50/30">
                                        <span class="mt-0.5 font-mono text-xs font-bold text-purple-600">#{{ $similar->id }}</span>
                                        <div class="min-w-0 flex-1">
                                            <p class="truncate text-xs sm:text-sm font-semibold text-ink">{{ $similar->title }}</p>
                                            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $similar->severity->badgeClasses() }}">
                                                    {{ $similar->severity->label() }}
                                                </span>
                                                <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $similar->status->badgeClasses() }}">
                                                    <span class="size-1 rounded-full bg-current opacity-80"></span>
                                                    {{ $similar->status->label() }}
                                                </span>
                                                <span class="font-mono text-[10px] text-label">{{ $similar->module }}</span>
                                            </div>
                                        </div>
                                        <x-auth.icon name="arrow-left" class="mt-1 size-3 shrink-0 rotate-180 text-label" />
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- ======================== SIDEBAR ======================== --}}
            <aside class="space-y-5 lg:sticky lg:top-20 lg:self-start">
                @php
                    $nextStatus = $report->status->next();
                    $canManageStatus = auth()->user()->role->value === 'developer'
                        && $report->application_id === auth()->user()->application_id;
                @endphp

                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-serif text-sm font-bold text-ink">Status Penanganan</h2>
                        <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $report->status->badgeClasses() }}">
                            <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                            {{ $report->status->label() }}
                        </span>
                    </div>

                    @if ($canManageStatus && $nextStatus)
                        <p class="mt-3 text-xs leading-relaxed text-label">
                            Lanjutkan laporan ini ke tahap <strong class="text-ink">{{ $nextStatus->label() }}</strong>.
                        </p>

                        <form method="POST" action="{{ route('bug-reports.status.update', $report) }}" class="mt-3.5">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $nextStatus->value }}">

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-sage-500 px-3.5 py-2 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95 cursor-pointer"
                            >
                                <x-auth.icon name="check" class="size-3.5" />
                                <span>Tandai {{ $nextStatus->label() }}</span>
                            </button>
                        </form>

                        @error('status')
                            <p class="mt-2 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
                        @enderror
                    @elseif ($canManageStatus)
                        <p class="mt-3 text-xs leading-relaxed text-label">
                            Laporan sudah ditutup. Tidak ada tahap status berikutnya.
                        </p>
                    @endif
                </div>

                {{-- ======================== RIWAYAT STATUS ======================== --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <h2 class="font-serif text-sm font-bold text-ink">Riwayat Status</h2>

                    @forelse ($report->statusHistories as $history)
                        <div class="mt-3 border-l-2 border-sage-300 pl-3.5">
                            <div class="flex flex-wrap items-center gap-1 text-[10px] font-semibold">
                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 {{ $history->from_status->badgeClasses() }}">
                                    {{ $history->from_status->label() }}
                                </span>
                                <span class="text-label">&rarr;</span>
                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 {{ $history->to_status->badgeClasses() }}">
                                    {{ $history->to_status->label() }}
                                </span>
                            </div>
                            <p class="mt-1 text-xs font-semibold text-ink">
                                {{ $history->changedBy?->name ?? 'Pengguna dihapus' }}
                            </p>
                            <p class="mt-0.5 text-[10px] text-label">
                                {{ $history->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    @empty
                        <p class="mt-2 text-xs text-label">Belum ada perubahan status.</p>
                    @endforelse
                </div>

                {{-- ======================== INFORMASI LAPORAN ======================== --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <h2 class="font-serif text-sm font-bold text-ink">Informasi Laporan</h2>

                    <dl class="mt-3 space-y-2 text-xs">
                        <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Aplikasi</dt>
                            <dd class="mt-0.5 font-semibold text-ink">{{ $report->application?->name ?? '-' }}</dd>
                        </div>
                        <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul / Komponen</dt>
                            <dd class="mt-0.5 font-mono font-semibold text-ink">{{ $report->module ?? 'Lainnya' }}</dd>
                        </div>

                        @if ($report->environment)
                            <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Environment</dt>
                                <dd class="mt-0.5 font-semibold text-ink capitalize">{{ $report->environment }}</dd>
                            </div>
                        @endif

                        @if ($report->url)
                            <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">URL Kejadian</dt>
                                <dd class="mt-0.5 break-all text-xs font-medium text-sage-700 underline">
                                    <a href="{{ $report->url }}" target="_blank" rel="noopener noreferrer">{{ $report->url }}</a>
                                </dd>
                            </div>
                        @endif

                        @if ($report->device_metadata)
                            <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Metadata Perangkat</dt>
                                <dd class="mt-1.5 flex flex-wrap gap-1">
                                    @if (! empty($report->device_metadata['browser']))
                                        <span class="rounded-md border border-divider bg-white px-2 py-0.5 text-[10px] font-semibold text-ink shadow-2xs">
                                            🌐 {{ $report->device_metadata['browser'] }}
                                        </span>
                                    @endif
                                    @if (! empty($report->device_metadata['os']))
                                        <span class="rounded-md border border-divider bg-white px-2 py-0.5 text-[10px] font-semibold text-ink shadow-2xs">
                                            💻 {{ $report->device_metadata['os'] }}
                                        </span>
                                    @endif
                                    @if (! empty($report->device_metadata['screen']))
                                        <span class="rounded-md border border-divider bg-white px-2 py-0.5 text-[10px] font-semibold text-ink shadow-2xs">
                                            📐 {{ $report->device_metadata['screen'] }}
                                        </span>
                                    @endif
                                </dd>
                            </div>
                        @endif

                        <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Pelapor</dt>
                            <dd class="mt-1 flex items-center gap-2">
                                <span class="grid size-5 place-items-center rounded-full bg-slate-200 text-[9px] font-bold text-slate-800">
                                    {{ mb_strtoupper(mb_substr($report->user?->name ?? '?', 0, 1)) }}
                                </span>
                                <div>
                                    <span class="block font-semibold text-ink">{{ $report->user?->name ?? 'Anonim' }}</span>
                                    <span class="block text-[10px] text-label">{{ $report->user?->role?->label() ?? 'Reporter' }}</span>
                                </div>
                            </dd>
                        </div>
                        <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal Lapor</dt>
                            <dd class="mt-0.5 font-semibold text-ink">{{ $report->created_at->format('d M Y H:i') }}</dd>
                        </div>
                        <div class="rounded-xl border border-divider/60 bg-input/40 p-2.5">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Terakhir Diperbarui</dt>
                            <dd class="mt-0.5 font-semibold text-ink">{{ $report->updated_at->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </div>

    </div>

</x-layouts.app>
