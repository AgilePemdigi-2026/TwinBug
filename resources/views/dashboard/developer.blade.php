<x-layouts.app title="Dashboard Developer" meta-description="Dashboard developer TwinBug untuk memantau antrean laporan bug.">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ======================== WELCOME & ACTION HEADER ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-ink">
                        <x-auth.icon name="code" class="size-3 text-ink" />
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

            {{-- Primary Action Buttons --}}
            <div class="flex shrink-0 items-center gap-2.5">
                <a
                    href="{{ route('bug-reports.index', ['status' => 'open']) }}"
                    id="btn-review-bug-queue"
                    class="inline-flex items-center gap-2 rounded-xl bg-ink px-4 py-2.5 text-xs font-semibold text-white shadow-xs transition hover:bg-slate-800 active:scale-95"
                >
                    <x-auth.icon name="search" class="size-3.5" />
                    <span>Telusuri Antrean Bug</span>
                </a>
                <a
                    href="{{ route('bug-reports.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-divider bg-white px-3.5 py-2.5 text-xs font-semibold text-ink shadow-2xs transition hover:bg-input"
                >
                    <x-auth.icon name="bug" class="size-3.5" />
                    <span>Deteksi Duplikasi</span>
                </a>
            </div>
        </div>

        {{-- ======================== TOP STATS STRIP (4 METRICS) ======================== --}}
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            <x-dashboard.metric-card
                label="Total Laporan"
                :value="number_format($stats['total'])"
                description="Semua laporan tim"
                icon="bug"
                icon-classes="bg-sage-100 text-sage-700"
                :href="route('bug-reports.index')"
            />
            <x-dashboard.metric-card
                label="Terbuka"
                :value="number_format($stats['open'])"
                description="Menunggu review"
                icon="alert"
                icon-classes="bg-sky-100 text-sky-800"
                :href="route('bug-reports.index', ['status' => 'open'])"
            />
            <x-dashboard.metric-card
                label="Diproses"
                :value="number_format($stats['in_progress'])"
                description="Sedang dikerjakan"
                icon="filter"
                icon-classes="bg-amber-100 text-amber-800"
                :href="route('bug-reports.index', ['status' => 'in_progress'])"
            />
            <x-dashboard.metric-card
                label="Kritis"
                :value="number_format($stats['critical'])"
                description="Prioritas utama"
                icon="shield"
                icon-classes="bg-danger/10 text-danger"
                :href="route('bug-reports.index', ['severity' => 'critical'])"
            />
        </div>

        {{-- ======================== 2-COLUMN DASHBOARD GRID ======================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- =============== LEFT COLUMN (8 cols): Primary Feed =============== --}}
            <div class="space-y-6 lg:col-span-8">

                {{-- Queue Quick Banner (Compact) --}}
                <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-800 to-slate-900 p-5 text-white shadow-xs">
                    <div class="relative z-10 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="max-w-lg">
                            <div class="mb-1.5 inline-flex items-center gap-1.5 rounded-md bg-white/10 px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wider text-white">
                                <x-auth.icon name="alert" class="size-3 text-amber-400" />
                                Antrean Aktif
                            </div>
                            <h2 class="font-serif text-lg sm:text-xl font-bold leading-snug">
                                {{ number_format($stats['open']) }} bug menunggu penanganan
                            </h2>
                            <p class="mt-1 text-xs text-white/80 leading-relaxed">
                                {{ number_format($stats['in_progress']) }} laporan sedang diproses dan {{ number_format($stats['critical']) }} berprioritas kritis. Telusuri antrean untuk menelaah bukti visual screenshot.
                            </p>
                        </div>

                        <div class="shrink-0">
                            <a
                                href="{{ route('bug-reports.index', ['status' => 'open']) }}"
                                class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2 text-xs font-bold text-slate-900 shadow-xs transition hover:bg-slate-100 active:scale-95"
                            >
                                <span>Lihat Antrean Terbuka</span>
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14M12 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Recent Bug Queue --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="font-serif text-base font-bold text-ink">Antrean Bug Terbaru</h2>
                            <p class="text-xs text-label">Laporan bug terbaru dari seluruh reporter untuk ditelaah</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <a
                                href="{{ route('bug-reports.index') }}"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-divider bg-input/70 px-3 py-1.5 text-xs font-semibold text-ink transition hover:border-slate-300 hover:bg-white"
                            >
                                <x-auth.icon name="filter" class="size-3 text-sage-600" />
                                <span>Filter &amp; Semua Laporan</span>
                            </a>
                        </div>
                    </div>

                    @if ($recentReports->isEmpty())
                        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-divider bg-input/30 px-6 py-10 text-center">
                            <span class="mb-3 grid size-10 place-items-center rounded-xl bg-emerald-100 text-emerald-700 shadow-2xs">
                                <x-auth.icon name="check" class="size-5" />
                            </span>
                            <h3 class="font-serif text-sm font-bold text-ink">Antrean Masih Kosong</h3>
                            <p class="mt-1 max-w-sm text-xs text-label">
                                Belum ada laporan bug yang masuk. Antrean akan tampil di sini setelah reporter mengirimkan laporan baru.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto -mx-5 sm:mx-0">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="border-b border-divider/80 bg-input/40 text-[10px] font-bold uppercase tracking-wider text-label">
                                        <th class="py-2.5 px-4 font-semibold">Judul Bug</th>
                                        <th class="py-2.5 px-3 font-semibold">Pelapor</th>
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
                                                <div class="mt-0.5 flex items-center gap-1.5 text-[10px] text-label">
                                                    <span class="font-mono text-[10px]">#{{ $report->id }}</span>
                                                    <span>&middot;</span>
                                                    <span class="font-mono">{{ $report->module ?? 'Lainnya' }}</span>
                                                    <span>&middot;</span>
                                                    <span>{{ $report->created_at->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-3">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="grid size-5 place-items-center rounded-full bg-slate-200 text-[9px] font-bold text-slate-800">
                                                        {{ mb_strtoupper(mb_substr($report->user?->name ?? '?', 0, 1)) }}
                                                    </span>
                                                    <span class="truncate max-w-[110px] text-xs font-medium text-ink">{{ $report->user?->name ?? 'Anonim' }}</span>
                                                </div>
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
                                            <td class="py-3 px-4 text-right whitespace-nowrap">
                                                <a
                                                    href="{{ route('bug-reports.show', $report) }}"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-divider bg-input/50 px-2 py-1 text-[11px] font-semibold text-ink transition hover:border-slate-300 hover:bg-slate-100"
                                                >
                                                    <span>Detail</span>
                                                </a>
                                                @if ($report->detection_results_count > 0)
                                                    <a
                                                        href="{{ route('bug-reports.detection', $report) }}"
                                                        class="ml-1.5 inline-flex items-center gap-1 rounded-lg border border-sage-200 bg-sage-50 px-2 py-1 text-[11px] font-semibold text-sage-800 transition hover:bg-sage-100"
                                                    >
                                                        <x-auth.icon name="bug" class="size-2.5 text-sage-600" />
                                                        <span>Deteksi ({{ $report->detection_results_count }})</span>
                                                    </a>
                                                @endif
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

                {{-- Developer Triage Shortcuts --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="grid size-6 place-items-center rounded-lg bg-slate-100 text-slate-800">
                            <x-auth.icon name="code" class="size-3" />
                        </span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink">Alur Triase Developer</h3>
                    </div>
                    <ol class="space-y-2 text-xs text-label list-decimal list-inside">
                        <li><span>Buka tiket dengan status <strong class="text-ink">Terbuka</strong>.</span></li>
                        <li><span>Periksa hasil <strong class="text-ink">Deteksi Duplikasi</strong> untuk mencegah bug kembar.</span></li>
                        <li><span>Ubah status ke <strong class="text-ink">Diproses</strong> jika valid.</span></li>
                        <li><span>Tandai <strong class="text-ink">Selesai</strong> setelah perbaikan terdeploy.</span></li>
                    </ol>
                </div>

                {{-- Application Info --}}
                <x-dashboard.application-info :user="$user" />

                {{-- Account Info --}}
                <x-dashboard.account-info :user="$user" />

            </div>

        </div>

    </div>

</x-layouts.app>