<x-layouts.app title="Perbandingan Laporan" meta-description="Perbandingan detail antara laporan bug Anda dengan laporan historis yang serupa.">

    <div class="mx-auto max-w-screen-xl px-12 py-10">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="mb-8 flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <a href="{{ route('report.detection', $report) }}" class="font-medium text-label transition hover:text-ink">
                Hasil Deteksi
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-full bg-divider px-2.5 py-1 text-[11px] font-semibold text-ink">Perbandingan</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="mb-8 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                <h1 class="font-serif text-4xl font-bold tracking-tight text-ink md:text-5xl" style="letter-spacing: -1px;">
                    Perbandingan Laporan
                </h1>
                <p class="mt-3 text-sm text-label">
                    Perbandingan detail antara laporan Anda dan laporan historis yang terdeteksi serupa.
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-3">
                <a
                    href="{{ route('report.detection', $report) }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-divider px-5 py-3 text-sm font-bold text-label transition hover:bg-input-hover hover:text-ink"
                >
                    <x-auth.icon name="arrow-left" class="size-4" />
                    Kembali ke Hasil Deteksi
                </a>
            </div>
        </div>

        {{-- ======================== SIMILARITY SCORES ======================== --}}
        <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wider text-label">Text Similarity Score</p>
                <p class="mt-2 font-serif text-3xl font-bold text-ink">{{ number_format($match->text_similarity, 1) }}%</p>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-input">
                    <div class="h-full rounded-full bg-sage-500" style="width: {{ min(100, $match->text_similarity) }}%"></div>
                </div>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wider text-label">Image Similarity Score</p>
                <p class="mt-2 font-serif text-3xl font-bold text-ink">
                    {{ $match->image_similarity !== null ? number_format($match->image_similarity, 1).'%' : 'Tidak tersedia' }}
                </p>
                <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-input">
                    <div class="h-full rounded-full bg-gold-200" style="width: {{ $match->image_similarity !== null ? min(100, $match->image_similarity) : 0 }}%"></div>
                </div>
            </div>

            <div class="rounded-3xl bg-sage-500 p-6 text-white shadow-card">
                <p class="text-[11px] font-bold uppercase tracking-wider text-white/75">Overall Similarity</p>
                <p class="mt-2 font-serif text-3xl font-bold">{{ number_format($match->overallSimilarity(), 1) }}%</p>
                <p class="mt-3 text-[11px] leading-relaxed text-white/80">
                    Skor gabungan dari kemiripan teks (60%) dan gambar (40%).
                </p>
            </div>
        </div>

        {{-- ======================== SIDE BY SIDE ======================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Submitted report --}}
            <section class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-sage-600">Laporan Anda</p>
                        <h2 class="mt-1 font-serif text-xl font-bold text-ink">{{ $report->title }}</h2>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $report->status->badgeClasses() }}">
                        {{ $report->status->label() }}
                    </span>
                </div>

                <div class="overflow-hidden rounded-2xl border border-divider bg-input">
                    @if ($report->hasScreenshot())
                        <a href="{{ $report->screenshotUrl() }}" target="_blank" rel="noopener noreferrer" class="block">
                            <img
                                src="{{ $report->screenshotUrl() }}"
                                alt="Screenshot laporan {{ $report->title }}"
                                class="aspect-video w-full object-cover transition hover:opacity-90"
                            >
                        </a>
                    @else
                        <div class="flex aspect-video items-center justify-center px-6 text-center">
                            <p class="text-xs text-label">Laporan ini tidak memiliki lampiran screenshot.</p>
                        </div>
                    @endif
                </div>

                <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl bg-input p-4">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Priority</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ $report->severity->label() }}</dd>
                    </div>
                    <div class="rounded-2xl bg-input p-4">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ $report->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>

                <div class="mt-4 rounded-2xl bg-input p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-label">Description</p>
                    <p class="mt-1.5 whitespace-pre-line text-xs leading-relaxed text-body">{{ $report->description }}</p>
                </div>
            </section>

            {{-- Matched historical report --}}
            <section class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="mb-5 flex items-start justify-between gap-3">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-ochre">Laporan Historis</p>
                        <h2 class="mt-1 font-serif text-xl font-bold text-ink">{{ $matchedReport->title }}</h2>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $matchedReport->status->badgeClasses() }}">
                        {{ $matchedReport->status->label() }}
                    </span>
                </div>

                <div class="overflow-hidden rounded-2xl border border-divider bg-input">
                    @if ($matchedReport->hasScreenshot())
                        <a href="{{ $matchedReport->screenshotUrl() }}" target="_blank" rel="noopener noreferrer" class="block">
                            <img
                                src="{{ $matchedReport->screenshotUrl() }}"
                                alt="Screenshot laporan historis {{ $matchedReport->title }}"
                                class="aspect-video w-full object-cover transition hover:opacity-90"
                            >
                        </a>
                    @else
                        <div class="flex aspect-video items-center justify-center px-6 text-center">
                            <p class="text-xs text-label">Laporan historis ini tidak memiliki lampiran screenshot.</p>
                        </div>
                    @endif
                </div>

                <dl class="mt-5 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-2xl bg-input p-4">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Priority</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ $matchedReport->severity->label() }}</dd>
                    </div>
                    <div class="rounded-2xl bg-input p-4">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ $matchedReport->created_at->format('d M Y') }}</dd>
                    </div>
                </dl>

                <div class="mt-4 rounded-2xl bg-input p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-label">Description</p>
                    <p class="mt-1.5 whitespace-pre-line text-xs leading-relaxed text-body">{{ $matchedReport->description }}</p>
                </div>
            </section>
        </div>
    </div>

</x-layouts.app>
