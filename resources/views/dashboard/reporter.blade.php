<x-layouts.app title="Dashboard Reporter" meta-description="Dashboard reporter TwinBug untuk memantau histori laporan bug pribadi.">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ======================== WELCOME & ACTION HEADER ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2.5 py-0.5 text-[11px] font-semibold text-sage-800">
                        <x-auth.icon name="bug" class="size-3 text-sage-700" />
                        {{ $user->role->label() }}
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="text-xs text-label">{{ $user->email }}</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-ink">
                    Halo, {{ $user->name }}
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-label">
                    {{ $user->role->description() }}
                </p>
            </div>

            {{-- Primary Action Button --}}
            <div class="flex shrink-0 items-center gap-2.5">
                <a
                    href="{{ route('bug-reports.create') }}"
                    id="btn-create-bug-report-hero"
                    class="inline-flex items-center gap-2 rounded-xl bg-sage-500 px-4 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                >
                    <x-auth.icon name="sparkles" class="size-3.5" />
                    <span>+ Buat Laporan Bug Baru</span>
                </a>
            </div>
        </div>

        {{-- ======================== TOP STATS STRIP (4 METRICS) ======================== --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <x-dashboard.metric-card
                label="Laporan Saya"
                :value="number_format($stats['my_total'])"
                description="Total laporan"
                icon="bug"
                icon-classes="bg-sage-100 text-sage-700"
                :href="route('bug-reports.index', ['scope' => 'my'])"
            />
            <x-dashboard.metric-card
                label="Terbuka"
                :value="number_format($stats['my_open'])"
                description="Menunggu review"
                icon="alert"
                icon-classes="bg-sky-100 text-sky-800"
                :href="route('bug-reports.index', ['scope' => 'my', 'status' => 'open'])"
            />
            <x-dashboard.metric-card
                label="Diproses"
                :value="number_format($stats['my_in_progress'])"
                description="Sedang ditelaah"
                icon="filter"
                icon-classes="bg-amber-100 text-amber-800"
                :href="route('bug-reports.index', ['scope' => 'my', 'status' => 'in_progress'])"
            />
            <x-dashboard.metric-card
                label="Selesai"
                :value="number_format($stats['my_resolved'])"
                description="Sudah diperbaiki"
                icon="check"
                icon-classes="bg-emerald-100 text-emerald-800"
                :href="route('bug-reports.index', ['scope' => 'my', 'status' => 'resolved'])"
            />
        </div>

        {{-- ======================== 2-COLUMN DASHBOARD GRID ======================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- =============== LEFT COLUMN (8 cols): Primary Feed =============== --}}
            <div class="space-y-6 lg:col-span-8">

                {{-- Feature Quick Banner (Compact) --}}
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-sage-600 to-sage-700 p-5 text-white shadow-xs">
                    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="max-w-lg">
                            <div class="mb-1.5 inline-flex items-center gap-1.5 rounded-md bg-white/15 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white">
                                <x-auth.icon name="sparkles" class="size-3" />
                                Fitur Utama
                            </div>
                            <h2 class="font-serif text-lg sm:text-xl font-bold leading-snug">
                                Laporkan Bug &amp; Deteksi Duplikasi Secara Otomatis
                            </h2>
                            <p class="mt-1 text-xs text-white/85 leading-relaxed">
                                Kirimkan temuan bug dengan screenshot dan langkah reproduksi. AI TwinBug otomatis menganalisis kemiripan untuk mencegah laporan ganda.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <a
                                href="{{ route('bug-reports.create') }}"
                                id="btn-create-bug-report-banner"
                                class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-bold text-sage-800 shadow-xs transition hover:bg-slate-50 active:scale-95"
                            >
                                <span>Mulai Buat Laporan</span>
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Personal Report History --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="font-serif text-base font-bold text-ink">Histori Laporan Saya</h2>
                            <p class="text-xs text-label">Laporan bug yang baru saja Anda buat dan status penanganannya</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('bug-reports.index', ['scope' => 'my']) }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-divider bg-input/70 px-3 py-1.5 text-xs font-semibold text-ink transition hover:border-slate-300 hover:bg-white"
                            >
                                <x-auth.icon name="sparkles" class="size-3 text-sage-600" />
                                <span>Semua Laporan Saya</span>
                            </a>
                            <a
                                href="{{ route('bug-reports.create') }}"
                                class="inline-flex items-center gap-1.5 rounded-lg bg-sage-500 px-3 py-1.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600"
                            >
                                + Buat Baru
                            </a>
                        </div>
                    </div>

                    @if ($recentReports->isEmpty())
                        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-divider bg-input/30 px-6 py-10 text-center">
                            <span class="mb-3 grid size-10 place-items-center rounded-xl bg-sage-100 text-sage-700 shadow-2xs">
                                <x-auth.icon name="bug" class="size-5" />
                            </span>
                            <h3 class="font-serif text-sm font-bold text-ink">Belum Ada Laporan Bug</h3>
                            <p class="mt-1 max-w-sm text-xs text-label">
                                Anda belum mengirimkan laporan bug ke sistem. Mulai laporkan temuan kendala teknis agar sistem TwinBug menganalisis kemiripannya.
                            </p>
                            <a
                                href="{{ route('bug-reports.create') }}"
                                class="mt-3.5 inline-flex items-center gap-1.5 rounded-xl bg-sage-500 px-3.5 py-1.5 text-xs font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                            >
                                <x-auth.icon name="sparkles" class="size-3" />
                                <span>Buat Laporan Bug Pertama</span>
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto -mx-5 sm:mx-0">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-divider/80 bg-input/40 text-[10px] font-bold uppercase tracking-wider text-label">
                                        <th class="py-2.5 px-4 font-semibold">Judul &amp; Ringkasan Bug</th>
                                        <th class="py-2.5 px-3 font-semibold">Modul</th>
                                        <th class="py-2.5 px-3 font-semibold">Keparahan</th>
                                        <th class="py-2.5 px-3 font-semibold">Status</th>
                                        <th class="py-2.5 px-4 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-divider/70">
                                    @foreach ($recentReports as $report)
                                        <tr class="transition hover:bg-slate-50/70">
                                            <td class="py-3 px-4">
                                                <a
                                                    href="{{ route('bug-reports.show', $report) }}"
                                                    class="font-semibold text-ink transition hover:text-sage-600"
                                                >
                                                    {{ $report->title }}
                                                </a>
                                                <div class="mt-0.5 line-clamp-1 max-w-md text-xs text-label">
                                                    {{ $report->description }}
                                                </div>
                                                <div class="mt-1 text-[10px] font-medium text-label/70">
                                                    #{{ $report->id }} &middot; {{ $report->created_at->diffForHumans() }}
                                                </div>
                                            </td>
                                            <td class="py-3 px-3 text-body font-mono text-[11px]">
                                                {{ $report->module ?? '-' }}
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $report->severity->badgeClasses() }}">
                                                    {{ $report->severity->label() }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-3">
                                                <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $report->status->badgeClasses() }}">
                                                    <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                                                    {{ $report->status->label() }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-right">
                                                <a
                                                    href="{{ route('bug-reports.show', $report) }}"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-divider bg-input/50 px-2.5 py-1 text-[11px] font-semibold text-ink transition hover:border-sage-300 hover:bg-sage-50 hover:text-sage-700"
                                                >
                                                    <x-auth.icon name="eye" class="size-3" />
                                                    <span>Lihat Detail</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

            {{-- =============== RIGHT COLUMN (4 cols): Sidebar =============== --}}
            <div class="space-y-5 lg:col-span-4">

                {{-- Panduan Singkat Reporter --}}
                <div class="rounded-2xl border border-sage-200/80 bg-sage-50/60 p-5 shadow-xs">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-200 text-sage-800">
                            <x-auth.icon name="sparkles" class="size-3" />
                        </span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-sage-900">Tips Pelaporan Cepat</h3>
                    </div>
                    <ul class="space-y-2 text-xs text-label">
                        <li class="flex items-start gap-2">
                            <span class="mt-1 size-1.5 shrink-0 rounded-full bg-sage-500"></span>
                            <span><strong>Sertakan Screenshot:</strong> AI mendeteksi kemiripan visual secara presisi.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 size-1.5 shrink-0 rounded-full bg-sage-500"></span>
                            <span><strong>Langkah Rinci:</strong> Tulis urutan langkah agar mudah direproduksi tim pengembang.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="mt-1 size-1.5 shrink-0 rounded-full bg-sage-500"></span>
                            <span><strong>Cek Kemiripan:</strong> Kotak deteksi duplikasi muncul otomatis saat mengisi judul.</span>
                        </li>
                    </ul>
                </div>

                {{-- Application Info --}}
                <x-dashboard.application-info :user="$user" />

                {{-- Account Info --}}
                <x-dashboard.account-info :user="$user" />

            </div>

        </div>

    </div>

</x-layouts.app>