<x-layouts.app title="Hasil Deteksi" meta-description="Hasil deteksi kemiripan laporan bug dengan arsip historis TwinBug.">

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
            <a href="{{ route('report.show', $report) }}" class="font-medium text-label transition hover:text-ink">
                Detail Laporan
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-full bg-divider px-2.5 py-1 text-[11px] font-semibold text-ink">Hasil Deteksi</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="mb-8 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-sage-200 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-sage-700">
                        <x-auth.icon name="sparkles" class="size-3.5" />
                        Deteksi Duplikasi
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $report->analysis_status->badgeClasses() }}">
                        {{ $report->analysis_status->label() }}
                    </span>
                </div>
                <h1 class="font-serif text-4xl font-bold tracking-tight text-ink md:text-5xl" style="letter-spacing: -1px;">
                    Hasil Deteksi Kemiripan
                </h1>
                <p class="mt-3 text-sm text-label">
                    Laporan: <span class="font-semibold text-ink">{{ $report->title }}</span>
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-3">
                <a
                    href="{{ route('report.show', $report) }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-divider px-5 py-3 text-sm font-bold text-label transition hover:bg-input-hover hover:text-ink"
                >
                    <x-auth.icon name="arrow-left" class="size-4" />
                    Kembali ke Detail
                </a>
            </div>
        </div>

        @if ($report->analysis_status->isStillProcessing())
            {{-- ======================== PROCESSING STATE ======================== --}}
            <div class="rounded-3xl bg-white p-10 shadow-sm">
                <div class="flex flex-col items-center justify-center rounded-2xl bg-input/60 px-6 py-14 text-center">
                    <span class="mb-4 grid size-14 place-items-center rounded-2xl bg-gold-100 text-ochre shadow-sm">
                        <x-auth.icon name="sparkles" class="size-7" />
                    </span>
                    <h2 class="font-serif text-lg font-bold text-ink">Analisis Sedang Berjalan</h2>
                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-label">
                        Analisis kemiripan sedang diproses. Silakan cek kembali beberapa saat lagi.
                    </p>
                </div>
            </div>
        @elseif ($matches->isEmpty())
            {{-- ======================== EMPTY STATE ======================== --}}
            <div class="rounded-3xl bg-white p-10 shadow-sm">
                <div class="flex flex-col items-center justify-center rounded-2xl bg-input/60 px-6 py-14 text-center">
                    <span class="mb-4 grid size-14 place-items-center rounded-2xl bg-sage-200 text-sage-700 shadow-sm">
                        <x-auth.icon name="check" class="size-7" />
                    </span>
                    <h2 class="font-serif text-lg font-bold text-ink">Tidak Ada Laporan Serupa</h2>
                    <p class="mt-2 max-w-xl text-sm leading-relaxed text-label">
                        Tidak ditemukan laporan bug serupa.
                    </p>
                </div>
            </div>
        @else
            {{-- ======================== MATCH LIST ======================== --}}
            <section class="rounded-3xl bg-white p-8 shadow-sm">
                <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="font-serif text-xl font-bold text-ink">Similar Historical Bug Reports</h2>
                        <p class="mt-0.5 text-xs text-label">
                            {{ $matches->count() }} laporan historis memiliki kemiripan dengan laporan Anda.
                        </p>
                    </div>
                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-sage-100 px-3 py-1 text-[11px] font-semibold text-sage-700">
                        <span class="size-1.5 rounded-full bg-sage-500"></span>
                        Analisis selesai
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-divider text-[11px] font-bold uppercase tracking-wider text-label">
                                <th class="pb-3 pr-4">Title</th>
                                <th class="pb-3 px-4">Similarity Score</th>
                                <th class="pb-3 px-4">Status</th>
                                <th class="pb-3 pl-4 text-right"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-divider">
                            @foreach ($matches as $match)
                                @php($matchedReport = $match->matchedReport)
                                <tr class="transition hover:bg-input/40">
                                    <td class="py-4 pr-4">
                                        <a href="{{ route('report.comparison', [$report, $match]) }}" class="group block">
                                            <span class="font-semibold text-ink transition group-hover:text-sage-600">
                                                {{ $matchedReport->title }}
                                            </span>
                                            <span class="mt-0.5 block text-[11px] text-label">
                                                Dikirim {{ $matchedReport->created_at->format('d/m/Y') }}
                                                @if ($matchedReport->module)
                                                    &middot; {{ $matchedReport->module }}
                                                @endif
                                            </span>
                                        </a>
                                    </td>
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <span class="font-serif text-lg font-bold text-ink">
                                                {{ number_format($match->overallSimilarity(), 1) }}%
                                            </span>
                                            <div class="hidden w-28 sm:block">
                                                <div class="h-1.5 overflow-hidden rounded-full bg-input">
                                                    <div class="h-full rounded-full bg-sage-500" style="width: {{ min(100, $match->overallSimilarity()) }}%"></div>
                                                </div>
                                                <p class="mt-1 text-[10px] text-label">
                                                    Teks {{ number_format($match->text_similarity, 1) }}%
                                                    @if ($match->image_similarity !== null)
                                                        &middot; Gambar {{ number_format($match->image_similarity, 1) }}%
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $matchedReport->status->badgeClasses() }}">
                                            <span class="size-1.5 rounded-full bg-current opacity-70"></span>
                                            {{ $matchedReport->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 pl-4 text-right">
                                        <a
                                            href="{{ route('report.comparison', [$report, $match]) }}"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-sage-600 transition hover:text-sage-700 hover:underline"
                                        >
                                            Bandingkan
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>

</x-layouts.app>
