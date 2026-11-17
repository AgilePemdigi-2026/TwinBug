<x-layouts.app title="Daftar &amp; Filter Laporan Bug" meta-description="Pusat penelusuran, pencarian, dan penyaringan laporan bug terstruktur pada sistem TwinBug.">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3 text-sage-600" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-md bg-input px-2 py-0.5 text-[11px] font-semibold text-ink">Daftar &amp; Filter Laporan</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2.5 py-0.5 text-[11px] font-semibold text-sage-800">
                        <x-auth.icon name="filter" class="size-3 text-sage-700" />
                        Pusat Penelusuran
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="text-xs text-label">{{ number_format($reports->total()) }} Laporan Ditemukan</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-ink">
                    Daftar &amp; Filter Laporan Bug
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-label">
                    Telusuri dan saring laporan permasalahan berdasarkan kata kunci, modul, tingkat keparahan, serta status tindak lanjut penanganan.
                </p>
            </div>

            {{-- Action Button --}}
            <div class="flex shrink-0 items-center gap-2.5">
                <a
                    href="{{ route('bug-reports.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-sage-500 px-4 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                >
                    <x-auth.icon name="sparkles" class="size-3.5" />
                    <span>+ Buat Laporan Baru</span>
                </a>
            </div>
        </div>

        {{-- ======================== QUICK STATS CHIPS ======================== --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-5">
            <a
                href="{{ route('bug-reports.index') }}"
                class="flex flex-col rounded-xl p-3.5 transition border {{ empty($filters['status']) || $filters['status'] === 'all' && empty($filters['severity']) || $filters['severity'] === 'all' ? 'bg-white border-sage-500/50 shadow-xs ring-1 ring-sage-500/20' : 'bg-white border-divider/80 hover:border-slate-300' }}"
            >
                <span class="text-[10px] font-bold uppercase tracking-wider text-label">Total Arsip</span>
                <span class="mt-1.5 font-serif text-xl font-bold text-ink">{{ number_format($stats['total']) }}</span>
                <span class="text-[10px] text-label/80">Semua laporan</span>
            </a>

            <a
                href="{{ route('bug-reports.index', array_merge($filters, ['status' => 'open'])) }}"
                class="flex flex-col rounded-xl p-3.5 transition border {{ ($filters['status'] ?? '') === 'open' ? 'bg-sky-50 border-sky-400 shadow-xs ring-1 ring-sky-400/20' : 'bg-white border-divider/80 hover:border-sky-300' }}"
            >
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-sky-800">Terbuka</span>
                    <span class="size-1.5 rounded-full bg-sky-500"></span>
                </div>
                <span class="mt-1.5 font-serif text-xl font-bold text-sky-950">{{ number_format($stats['open']) }}</span>
                <span class="text-[10px] text-sky-700/80">Menunggu review</span>
            </a>

            <a
                href="{{ route('bug-reports.index', array_merge($filters, ['status' => 'in_progress'])) }}"
                class="flex flex-col rounded-xl p-3.5 transition border {{ ($filters['status'] ?? '') === 'in_progress' ? 'bg-amber-50 border-amber-400 shadow-xs ring-1 ring-amber-400/20' : 'bg-white border-divider/80 hover:border-amber-300' }}"
            >
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-amber-800">Diproses</span>
                    <span class="size-1.5 rounded-full bg-amber-500"></span>
                </div>
                <span class="mt-1.5 font-serif text-xl font-bold text-amber-950">{{ number_format($stats['in_progress']) }}</span>
                <span class="text-[10px] text-amber-700/80">Dalam pengerjaan</span>
            </a>

            <a
                href="{{ route('bug-reports.index', array_merge($filters, ['status' => 'resolved'])) }}"
                class="flex flex-col rounded-xl p-3.5 transition border {{ ($filters['status'] ?? '') === 'resolved' ? 'bg-emerald-50 border-emerald-500 shadow-xs ring-1 ring-emerald-500/20' : 'bg-white border-divider/80 hover:border-emerald-300' }}"
            >
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Selesai</span>
                    <span class="size-1.5 rounded-full bg-emerald-600"></span>
                </div>
                <span class="mt-1.5 font-serif text-xl font-bold text-emerald-950">{{ number_format($stats['resolved']) }}</span>
                <span class="text-[10px] text-emerald-700/80">Telah diperbaiki</span>
            </a>

            <a
                href="{{ route('bug-reports.index', array_merge($filters, ['severity' => 'critical'])) }}"
                class="col-span-2 sm:col-span-1 flex flex-col rounded-xl p-3.5 transition border {{ ($filters['severity'] ?? '') === 'critical' ? 'bg-rose-50 border-rose-400 shadow-xs ring-1 ring-rose-400/20' : 'bg-white border-divider/80 hover:border-rose-300' }}"
            >
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-danger">Kritis</span>
                    <span class="size-1.5 rounded-full bg-danger"></span>
                </div>
                <span class="mt-1.5 font-serif text-xl font-bold text-danger">{{ number_format($stats['critical']) }}</span>
                <span class="text-[10px] text-danger/80">Prioritas tertinggi</span>
            </a>
        </div>

        {{-- ======================== FILTER TOOLBAR CARD ======================== --}}
        <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
            <form method="GET" action="{{ route('bug-reports.index') }}" id="filter-form" class="space-y-4">

                {{-- Search Bar --}}
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-label/60">
                        <x-auth.icon name="search" class="size-4" />
                    </div>
                    <input
                        type="text"
                        name="q"
                        id="filter-search"
                        value="{{ $filters['q'] }}"
                        placeholder="Cari kata kunci pada judul bug, kendala teknis, atau langkah reproduksi..."
                        class="w-full rounded-xl border border-divider/70 bg-input/60 pl-10 pr-10 py-2.5 text-xs text-ink placeholder:text-label/50 transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                    >
                    @if(filled($filters['q']))
                        <a
                            href="{{ route('bug-reports.index', array_merge($filters, ['q' => null])) }}"
                            class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-label hover:text-danger"
                            title="Hapus pencarian kata kunci"
                        >
                            <x-auth.icon name="x" class="size-3.5" />
                        </a>
                    @endif
                </div>

                {{-- Dropdowns Grid --}}
                <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2 lg:grid-cols-5">

                    {{-- 1. Severity Filter --}}
                    <div>
                        <label for="filter-severity" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-label">
                            Keparahan
                        </label>
                        <select
                            name="severity"
                            id="filter-severity"
                            class="w-full rounded-xl border border-divider/70 bg-input/60 px-3 py-2 text-xs font-medium text-ink transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                        >
                            <option value="all" @selected(($filters['severity'] ?? 'all') === 'all')>Semua Keparahan</option>
                            @foreach ($severities as $sev)
                                <option value="{{ $sev->value }}" @selected(($filters['severity'] ?? '') === $sev->value)>
                                    {{ $sev->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 2. Status Filter --}}
                    <div>
                        <label for="filter-status" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-label">
                            Status Penanganan
                        </label>
                        <select
                            name="status"
                            id="filter-status"
                            class="w-full rounded-xl border border-divider/70 bg-input/60 px-3 py-2 text-xs font-medium text-ink transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                        >
                            <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>Semua Status</option>
                            @foreach ($statuses as $stat)
                                <option value="{{ $stat->value }}" @selected(($filters['status'] ?? '') === $stat->value)>
                                    {{ $stat->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 3. Module Filter --}}
                    <div>
                        <label for="filter-module" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-label">
                            Modul Aplikasi
                        </label>
                        <select
                            name="module"
                            id="filter-module"
                            class="w-full rounded-xl border border-divider/70 bg-input/60 px-3 py-2 text-xs font-medium text-ink transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                        >
                            <option value="all" @selected(($filters['module'] ?? 'all') === 'all')>Semua Modul</option>
                            @foreach ($modules as $mod)
                                <option value="{{ $mod }}" @selected(($filters['module'] ?? '') === $mod)>
                                    {{ $mod }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- 4. Screenshot Filter --}}
                    <div>
                        <label for="filter-screenshot" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-label">
                            Bukti Screenshot
                        </label>
                        <select
                            name="has_screenshot"
                            id="filter-screenshot"
                            class="w-full rounded-xl border border-divider/70 bg-input/60 px-3 py-2 text-xs font-medium text-ink transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                        >
                            <option value="all" @selected(($filters['has_screenshot'] ?? 'all') === 'all')>Semua Laporan</option>
                            <option value="yes" @selected(($filters['has_screenshot'] ?? '') === 'yes')>Ada Screenshot</option>
                            <option value="no" @selected(($filters['has_screenshot'] ?? '') === 'no')>Tanpa Screenshot</option>
                        </select>
                    </div>

                    {{-- 5. Scope Filter (Semua vs Laporan Saya) --}}
                    <div>
                        <label for="filter-scope" class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-label">
                            Lingkup Pelapor
                        </label>
                        <select
                            name="scope"
                            id="filter-scope"
                            class="w-full rounded-xl border border-divider/70 bg-input/60 px-3 py-2 text-xs font-medium text-ink transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                        >
                            <option value="all" @selected(($filters['scope'] ?? 'all') === 'all')>Semua Pelapor</option>
                            <option value="my" @selected(($filters['scope'] ?? '') === 'my')>Hanya Laporan Saya</option>
                        </select>
                    </div>

                </div>

                {{-- Bottom Toolbar Actions & Active Filter Pills --}}
                <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-divider/70">
                    {{-- Left: Active Filters Pills --}}
                    <div class="flex flex-wrap items-center gap-1.5">
                        <span class="text-xs font-medium text-label">Filter Aktif:</span>

                        @if(! $hasActiveFilter)
                            <span class="text-xs text-label/60 italic">Tidak ada filter aktif (menampilkan seluruh data).</span>
                        @endif

                        @if(filled($filters['q']))
                            <span class="inline-flex items-center gap-1 rounded-md bg-sage-100 px-2 py-0.5 text-[11px] font-semibold text-sage-800">
                                <span>Pencarian: "{{ $filters['q'] }}"</span>
                                <a href="{{ route('bug-reports.index', array_merge($filters, ['q' => null])) }}" class="hover:text-danger">
                                    <x-auth.icon name="x" class="size-3" />
                                </a>
                            </span>
                        @endif

                        @if(($filters['severity'] ?? 'all') !== 'all')
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-ink">
                                <span>Keparahan: {{ ucfirst($filters['severity']) }}</span>
                                <a href="{{ route('bug-reports.index', array_merge($filters, ['severity' => 'all'])) }}" class="hover:text-danger">
                                    <x-auth.icon name="x" class="size-3" />
                                </a>
                            </span>
                        @endif

                        @if(($filters['status'] ?? 'all') !== 'all')
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-ink">
                                <span>Status: {{ ucfirst(str_replace('_', ' ', $filters['status'])) }}</span>
                                <a href="{{ route('bug-reports.index', array_merge($filters, ['status' => 'all'])) }}" class="hover:text-danger">
                                    <x-auth.icon name="x" class="size-3" />
                                </a>
                            </span>
                        @endif

                        @if(($filters['module'] ?? 'all') !== 'all')
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-ink">
                                <span>Modul: {{ $filters['module'] }}</span>
                                <a href="{{ route('bug-reports.index', array_merge($filters, ['module' => 'all'])) }}" class="hover:text-danger">
                                    <x-auth.icon name="x" class="size-3" />
                                </a>
                            </span>
                        @endif

                        @if(($filters['has_screenshot'] ?? 'all') !== 'all')
                            <span class="inline-flex items-center gap-1 rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-ink">
                                <span>Screenshot: {{ $filters['has_screenshot'] === 'yes' ? 'Ada' : 'Tidak Ada' }}</span>
                                <a href="{{ route('bug-reports.index', array_merge($filters, ['has_screenshot' => 'all'])) }}" class="hover:text-danger">
                                    <x-auth.icon name="x" class="size-3" />
                                </a>
                            </span>
                        @endif

                        @if(($filters['scope'] ?? 'all') === 'my')
                            <span class="inline-flex items-center gap-1 rounded-md bg-sage-200 px-2 py-0.5 text-[11px] font-semibold text-sage-800">
                                <span>Hanya Laporan Saya</span>
                                <a href="{{ route('bug-reports.index', array_merge($filters, ['scope' => 'all'])) }}" class="hover:text-danger">
                                    <x-auth.icon name="x" class="size-3" />
                                </a>
                            </span>
                        @endif
                    </div>

                    {{-- Right: Submit & Reset Buttons --}}
                    <div class="flex items-center gap-2 shrink-0 ml-auto">
                        @if($hasActiveFilter)
                            <a
                                href="{{ route('bug-reports.index') }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-divider px-3 py-1.5 text-xs font-semibold text-label transition hover:border-danger hover:bg-danger/5 hover:text-danger"
                            >
                                <x-auth.icon name="x" class="size-3" />
                                <span>Reset Filter</span>
                            </a>
                        @endif

                        <button
                            type="submit"
                            id="filter-submit-btn"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-sage-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95 cursor-pointer"
                        >
                            <x-auth.icon name="filter" class="size-3" />
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- ======================== RESULTS LISTING TABLE ======================== --}}
        <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="font-serif text-base font-bold text-ink">Hasil Penelusuran</h2>
                    <p class="text-xs text-label">
                        Menampilkan <span class="font-semibold text-ink">{{ $reports->firstItem() ?? 0 }}</span> - <span class="font-semibold text-ink">{{ $reports->lastItem() ?? 0 }}</span> dari total <span class="font-semibold text-ink">{{ $reports->total() }}</span> laporan terdaftar
                    </p>
                </div>

                <div class="text-[11px] text-label">
                    Diperbarui secara real-time
                </div>
            </div>

            @if ($reports->isEmpty())
                {{-- Empty State --}}
                <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-divider bg-input/30 px-6 py-10 text-center">
                    <span class="mb-3 grid size-10 place-items-center rounded-xl bg-sage-100 text-sage-700 shadow-2xs">
                        <x-auth.icon name="bug" class="size-5" />
                    </span>
                    <h3 class="font-serif text-sm font-bold text-ink">Tidak Ada Laporan Ditemukan</h3>
                    <p class="mt-1 max-w-md text-xs leading-relaxed text-label">
                        @if($hasActiveFilter)
                            Tidak ada data laporan bug yang cocok dengan kombinasi filter yang Anda pilih. Coba sesuaikan kata kunci atau bersihkan kriteria penyaringan.
                        @else
                            Sistem belum memiliki catatan laporan bug aktif. Mulai laporkan isu pertama Anda sekarang.
                        @endif
                    </p>
                    <div class="mt-4 flex items-center gap-2.5">
                        @if($hasActiveFilter)
                            <a
                                href="{{ route('bug-reports.index') }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-divider bg-white px-3 py-1.5 text-xs font-semibold text-ink shadow-2xs transition hover:bg-input"
                            >
                                <x-auth.icon name="x" class="size-3" />
                                <span>Bersihkan Semua Filter</span>
                            </a>
                        @endif
                        <a
                            href="{{ route('bug-reports.create') }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-sage-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                        >
                            <x-auth.icon name="sparkles" class="size-3" />
                            <span>+ Buat Laporan Bug Baru</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto -mx-5 sm:mx-0">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-divider/80 bg-input/40 text-[10px] font-bold uppercase tracking-wider text-label">
                                <th class="py-2.5 px-4 font-semibold">Judul &amp; Ringkasan Bug</th>
                                <th class="py-2.5 px-3 font-semibold">Modul</th>
                                <th class="py-2.5 px-3 font-semibold">Pelapor</th>
                                <th class="py-2.5 px-3 font-semibold">Keparahan</th>
                                <th class="py-2.5 px-3 font-semibold">Status</th>
                                <th class="py-2.5 px-3 font-semibold">Screenshot</th>
                                <th class="py-2.5 px-4 font-semibold text-right">Tanggal Lapor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-divider/70">
                            @foreach ($reports as $report)
                                <tr class="transition hover:bg-slate-50/70">
                                    {{-- Title & Summary --}}
                                    <td class="py-3 px-4">
                                        <a
                                            href="{{ route('bug-reports.show', $report) }}"
                                            title="Lihat detail laporan"
                                            class="block font-semibold text-ink line-clamp-1 transition hover:text-sage-600"
                                        >
                                            {{ $report->title }}
                                        </a>
                                        <div class="mt-0.5 text-xs text-label line-clamp-1 max-w-md">
                                            {{ $report->description }}
                                        </div>
                                    </td>

                                    {{-- Module --}}
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center rounded-md bg-input px-2 py-0.5 text-[11px] font-mono text-ink">
                                            {{ $report->module ?? 'Lainnya' }}
                                        </span>
                                    </td>

                                    {{-- User Reporter --}}
                                    <td class="py-3 px-3">
                                        <div class="flex items-center gap-1.5">
                                            <span class="grid size-5 place-items-center rounded-full bg-slate-200 text-[9px] font-bold text-slate-800">
                                                {{ mb_strtoupper(mb_substr($report->user?->name ?? '?', 0, 1)) }}
                                            </span>
                                            <div>
                                                <p class="font-medium text-ink leading-tight">{{ $report->user?->name ?? 'Anonim' }}</p>
                                                <p class="text-[10px] text-label leading-tight">{{ $report->user?->role?->label() ?? 'Reporter' }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Severity Badge --}}
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $report->severity->badgeClasses() }}">
                                            {{ $report->severity->label() }}
                                        </span>
                                    </td>

                                    {{-- Status Badge --}}
                                    <td class="py-3 px-3">
                                        <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $report->status->badgeClasses() }}">
                                            <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                                            {{ $report->status->label() }}
                                        </span>
                                    </td>

                                    {{-- Screenshot Indicator --}}
                                    <td class="py-3 px-3">
                                        @if ($report->hasScreenshot())
                                            <a
                                                href="{{ asset('storage/' . $report->screenshot_path) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="group inline-flex items-center gap-1 rounded-md border border-sage-200 bg-sage-50 px-2 py-0.5 text-[10px] font-semibold text-sage-800 transition hover:bg-sage-100"
                                                title="Lihat screenshot resolusi penuh ({{ $report->screenshot_dimensions ?? 'Unknown dim' }})"
                                            >
                                                <x-auth.icon name="check" class="size-2.5 text-sage-600" />
                                                <span>Ada</span>
                                            </a>
                                        @else
                                            <span class="text-label/50 text-[11px] italic">Tidak ada</span>
                                        @endif
                                    </td>

                                    {{-- Date --}}
                                    <td class="py-3 px-4 text-right text-xs text-label whitespace-nowrap">
                                        <div class="font-medium text-ink">{{ $report->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-label">{{ $report->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Pagination Links --}}
                <div class="mt-4 pt-4 border-t border-divider/70">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>

    </div>

</x-layouts.app>