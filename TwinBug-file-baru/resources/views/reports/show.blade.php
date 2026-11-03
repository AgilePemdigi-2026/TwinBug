<x-layouts.app title="Detail Laporan" meta-description="Detail laporan bug yang Anda kirimkan ke sistem TwinBug.">

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
            <a href="{{ route('report.history') }}" class="font-medium text-label transition hover:text-ink">
                Riwayat Laporan
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-full bg-divider px-2.5 py-1 text-[11px] font-semibold text-ink">Detail Laporan</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="mb-8 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $report->status->badgeClasses() }}">
                        {{ $report->status->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-wider {{ $report->analysis_status->badgeClasses() }}">
                        {{ $report->analysis_status->label() }}
                    </span>
                </div>
                <h1 class="font-serif text-4xl font-bold tracking-tight text-ink md:text-5xl" style="letter-spacing: -1px;">
                    {{ $report->title }}
                </h1>
                <p class="mt-3 text-sm text-label">
                    Dikirim pada {{ $report->created_at->format('d M Y, H:i') }} WIB
                    @if ($report->module)
                        &middot; Modul {{ $report->module }}
                    @endif
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-3">
                <a
                    href="{{ route('report.detection', $report) }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-sage-500 px-5 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                >
                    <x-auth.icon name="sparkles" class="size-4" />
                    Lihat Hasil Deteksi
                </a>
                <a
                    href="{{ route('report.history') }}"
                    class="inline-flex items-center gap-2 rounded-2xl bg-divider px-5 py-3 text-sm font-bold text-label transition hover:bg-input-hover hover:text-ink"
                >
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">

            {{-- ======================== MAIN CONTENT ======================== --}}
            <div class="col-span-12 flex flex-col gap-6 lg:col-span-8">

                {{-- Description card --}}
                <section class="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 class="mb-4 flex items-center gap-2 font-serif text-xl font-bold text-ink">
                        <x-auth.icon name="bug" class="size-4 text-sage-600" />
                        Description
                    </h2>
                    <p class="whitespace-pre-line text-sm leading-7 text-body">{{ $report->description }}</p>
                </section>

                {{-- Screenshot card --}}
                <section class="rounded-3xl bg-white p-8 shadow-sm">
                    <div class="mb-5 flex items-center justify-between gap-4">
                        <h2 class="flex items-center gap-2 font-serif text-xl font-bold text-ink">
                            <x-auth.icon name="eye" class="size-4 text-sage-600" />
                            Attached Screenshot
                        </h2>
                        @if ($report->hasScreenshot())
                            <span class="rounded-full bg-input px-3 py-1 text-[11px] font-semibold text-label">
                                {{ $report->screenshot_name }}
                            </span>
                        @endif
                    </div>

                    @if ($report->hasScreenshot())
                        <a
                            href="{{ $report->screenshotUrl() }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="group block overflow-hidden rounded-2xl border border-divider bg-input"
                        >
                            <img
                                src="{{ $report->screenshotUrl() }}"
                                alt="Screenshot laporan {{ $report->title }}"
                                class="max-h-[520px] w-full object-contain transition group-hover:opacity-90"
                            >
                        </a>
                        <p class="mt-3 text-[11px] text-label">
                            {{ $report->screenshot_dimensions ?? 'Ukuran tidak diketahui' }}
                            @if ($report->screenshot_size)
                                &middot; {{ number_format($report->screenshot_size / 1024, 1) }} KB
                            @endif
                        </p>
                    @else
                        <div class="flex flex-col items-center justify-center rounded-2xl bg-input/70 px-6 py-10 text-center">
                            <span class="mb-3 grid size-12 place-items-center rounded-2xl bg-white text-label shadow-sm">
                                <x-auth.icon name="eye-off" class="size-5" />
                            </span>
                            <p class="text-xs text-label">Laporan ini tidak memiliki lampiran screenshot.</p>
                        </div>
                    @endif
                </section>
            </div>

            {{-- ======================== SIDEBAR ======================== --}}
            <aside class="col-span-12 flex flex-col gap-6 lg:col-span-4">
                <section class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="mb-4 font-serif text-lg font-bold text-ink">Informasi Laporan</h2>

                    <dl class="space-y-4 text-sm">
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Title</dt>
                            <dd class="mt-1 font-semibold text-ink">{{ $report->title }}</dd>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-input p-4">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status</dt>
                                <dd class="mt-1 font-semibold text-ink">{{ $report->status->label() }}</dd>
                            </div>
                            <div class="rounded-2xl bg-input p-4">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Priority</dt>
                                <dd class="mt-1 font-semibold text-ink">{{ $report->severity->label() }}</dd>
                            </div>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul</dt>
                            <dd class="mt-1 font-semibold text-ink">{{ $report->module ?? 'Tidak ditentukan' }}</dd>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal Pengiriman</dt>
                            <dd class="mt-1 font-semibold text-ink">{{ $report->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="rounded-3xl bg-sage-500 p-6 text-white shadow-card">
                    <div class="mb-3 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-wider">
                        <x-auth.icon name="sparkles" class="size-3.5" />
                        Deteksi Duplikasi
                    </div>
                    <h2 class="font-serif text-xl font-bold">Cek Laporan Serupa</h2>
                    <p class="mt-2 text-xs leading-relaxed text-white/85">
                        Sistem TwinBug membandingkan laporan ini dengan arsip historis menggunakan kemiripan teks dan gambar.
                    </p>
                    <a
                        href="{{ route('report.detection', $report) }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-white px-4 py-2.5 text-xs font-bold text-sage-700 shadow-md transition hover:bg-parchment hover:text-ink active:scale-95"
                    >
                        Buka Hasil Deteksi
                    </a>
                </section>
            </aside>
        </div>
    </div>

</x-layouts.app>
