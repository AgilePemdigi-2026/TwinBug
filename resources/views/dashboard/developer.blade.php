<x-layouts.app title="Dashboard Developer" meta-description="Dashboard developer TwinBug untuk memantau antrean laporan bug.">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ======================== WELCOME & ACTION HEADER ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
            <div>
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-slate-100 px-2.5 py-0.5 text-[11px] font-semibold text-ink">
                        <x-auth.icon name="code" class="size-3 text-ink" />
                        {{ $user->role->label() }}
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="font-mono text-xs text-label">{{ $user->application?->name ?? 'Aplikasi' }}</span>
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
            <div class="flex flex-wrap shrink-0 items-center gap-2.5">
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

        {{-- ======================== STATUS DISTRIBUTION PROGRESS STRIP ======================== --}}
        @php
            $totalReports = max(1, $stats['total']);
            $openPercent = round(($stats['open'] / $totalReports) * 100);
            $inProgressPercent = round(($stats['in_progress'] / $totalReports) * 100);
            $resolvedCount = max(0, $stats['total'] - $stats['open'] - $stats['in_progress']);
            $resolvedPercent = max(0, 100 - $openPercent - $inProgressPercent);
        @endphp
        <div class="rounded-2xl border border-divider/80 bg-white p-4 sm:p-5 shadow-xs">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="grid size-6 place-items-center rounded-lg bg-input text-label">
                        <x-auth.icon name="filter" class="size-3.5" />
                    </span>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-ink">Distribusi Status Antrean</h2>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <a href="{{ route('bug-reports.index', ['status' => 'open']) }}" class="flex items-center gap-1.5 font-medium text-sky-700 hover:underline">
                        <span class="size-2 rounded-full bg-sky-500"></span>
                        <span>{{ number_format($stats['open']) }} Terbuka</span>
                    </a>
                    <a href="{{ route('bug-reports.index', ['status' => 'in_progress']) }}" class="flex items-center gap-1.5 font-medium text-amber-700 hover:underline">
                        <span class="size-2 rounded-full bg-amber-500"></span>
                        <span>{{ number_format($stats['in_progress']) }} Diproses</span>
                    </a>
                    <a href="{{ route('bug-reports.index', ['status' => 'resolved']) }}" class="flex items-center gap-1.5 font-medium text-emerald-700 hover:underline">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <span>{{ number_format($resolvedCount) }} Selesai / Ditutup</span>
                    </a>
                </div>
            </div>

            {{-- Segmented Progress Bar --}}
            <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-input/80">
                @if ($stats['total'] > 0)
                    <div class="bg-sky-500 transition-all duration-500" style="width: {{ $openPercent }}%" title="Terbuka: {{ $openPercent }}%"></div>
                    <div class="bg-amber-500 transition-all duration-500" style="width: {{ $inProgressPercent }}%" title="Diproses: {{ $inProgressPercent }}%"></div>
                    <div class="bg-emerald-500 transition-all duration-500" style="width: {{ $resolvedPercent }}%" title="Selesai: {{ $resolvedPercent }}%"></div>
                @else
                    <div class="w-full bg-slate-200" title="Belum ada data"></div>
                @endif
            </div>
        </div>

        {{-- ======================== 2-COLUMN DASHBOARD GRID ======================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- =============== LEFT COLUMN (8 cols): Primary Bug Queue =============== --}}
            <div class="space-y-6 lg:col-span-8">

                {{-- Recent Bug Queue --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
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
                        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-divider bg-input/30 px-6 py-12 text-center">
                            <span class="mb-3 grid size-10 place-items-center rounded-xl bg-emerald-100 text-emerald-700 shadow-2xs">
                                <x-auth.icon name="check" class="size-5" />
                            </span>
                            <h3 class="font-serif text-sm font-bold text-ink">Antrean Masih Kosong</h3>
                            <p class="mt-1 max-w-sm text-xs text-label leading-relaxed">
                                Belum ada laporan bug yang masuk. Antrean akan tampil di sini setelah reporter mengirimkan laporan baru.
                            </p>
                        </div>
                    @else
                        {{-- Desktop Table View (Hidden on mobile) --}}
                        <div class="hidden md:block overflow-x-auto -mx-5 sm:mx-0">
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
                                            <td class="py-3.5 px-4">
                                                <div class="flex items-center gap-2">
                                                    <a
                                                        href="{{ route('bug-reports.show', $report) }}"
                                                        class="font-semibold text-ink transition hover:text-sage-600 line-clamp-1"
                                                    >
                                                        {{ $report->title }}
                                                    </a>
                                                    @if ($report->detection_results_count > 0)
                                                        <span class="shrink-0 inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-200 px-2 py-0.5 text-[9px] font-bold text-rose-700" title="{{ $report->detection_results_count }} kemungkinan duplikasi terdeteksi">
                                                            <x-auth.icon name="sparkles" class="size-2.5" />
                                                            <span>{{ $report->detection_results_count }} Duplikat</span>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="mt-1 flex items-center gap-1.5 text-[10px] text-label">
                                                    <span class="font-mono">#{{ $report->id }}</span>
                                                    <span>&middot;</span>
                                                    <span class="font-mono bg-input px-1.5 py-0.2 rounded">{{ $report->module ?? 'Lainnya' }}</span>
                                                    <span>&middot;</span>
                                                    <span>{{ $report->created_at->diffForHumans() }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-3">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="grid size-5 place-items-center rounded-full bg-slate-200 text-[9px] font-bold text-slate-800">
                                                        {{ mb_strtoupper(mb_substr($report->user?->name ?? '?', 0, 1)) }}
                                                    </span>
                                                    <span class="truncate max-w-[110px] text-xs font-medium text-ink">{{ $report->user?->name ?? 'Anonim' }}</span>
                                                </div>
                                            </td>
                                            <td class="py-3.5 px-3">
                                                <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $report->severity->badgeClasses() }}">
                                                    {{ $report->severity->label() }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-3">
                                                <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $report->status->badgeClasses() }}">
                                                    <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                                                    {{ $report->status->label() }}
                                                </span>
                                            </td>
                                            <td class="py-3.5 px-4 text-right whitespace-nowrap">
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

                        {{-- Mobile Stacked Cards View (Clean on mobile screen, no awkward horizontal scroll) --}}
                        <div class="block md:hidden divide-y divide-divider/70 -mx-5">
                            @foreach ($recentReports as $report)
                                <div class="p-4 space-y-2.5 transition hover:bg-slate-50/70">
                                    <div class="flex items-start justify-between gap-2">
                                        <div class="space-y-1">
                                            <a
                                                href="{{ route('bug-reports.show', $report) }}"
                                                class="font-semibold text-xs text-ink transition hover:text-sage-600 line-clamp-2"
                                            >
                                                {{ $report->title }}
                                            </a>
                                            <div class="flex items-center gap-1.5 text-[10px] text-label">
                                                <span class="font-mono">#{{ $report->id }}</span>
                                                <span>&middot;</span>
                                                <span class="font-mono">{{ $report->module ?? 'Lainnya' }}</span>
                                                <span>&middot;</span>
                                                <span>{{ $report->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <span class="shrink-0 inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $report->severity->badgeClasses() }}">
                                            {{ $report->severity->label() }}
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between pt-1">
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $report->status->badgeClasses() }}">
                                                <span class="size-1 rounded-full bg-current opacity-80"></span>
                                                {{ $report->status->label() }}
                                            </span>
                                            @if ($report->detection_results_count > 0)
                                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 border border-rose-200 px-1.5 py-0.5 text-[9px] font-bold text-rose-700">
                                                    {{ $report->detection_results_count }} Duplikat
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-1.5">
                                            <a
                                                href="{{ route('bug-reports.show', $report) }}"
                                                class="inline-flex items-center gap-1 rounded-lg border border-divider bg-input/50 px-2 py-1 text-[11px] font-semibold text-ink"
                                            >
                                                Detail
                                            </a>
                                            @if ($report->detection_results_count > 0)
                                                <a
                                                    href="{{ route('bug-reports.detection', $report) }}"
                                                    class="inline-flex items-center gap-1 rounded-lg border border-sage-200 bg-sage-50 px-2 py-1 text-[11px] font-semibold text-sage-800"
                                                >
                                                    <x-auth.icon name="bug" class="size-2.5 text-sage-600" />
                                                    <span>Deteksi</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- =============== RIGHT COLUMN (4 cols): Contextual Widgets =============== --}}
            <div class="space-y-6 lg:col-span-4">

                {{-- Application Info Card --}}
                <x-dashboard.application-info :user="$user" />

                {{-- Area Modul Terbanyak Widget --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="grid size-6 place-items-center rounded-lg bg-sky-100 text-sky-700">
                                <x-auth.icon name="filter" class="size-3" />
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-ink">Area Modul Terbanyak</h3>
                        </div>
                        <span class="text-[10px] text-label">Top 4</span>
                    </div>

                    @if ($topModules->isNotEmpty())
                        <div class="space-y-3">
                            @php
                                $maxModuleCount = max(1, $topModules->max('count'));
                            @endphp
                            @foreach ($topModules as $mod)
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <a href="{{ route('bug-reports.index', ['module' => $mod->module]) }}" class="font-mono font-medium text-ink hover:text-sage-600 transition">
                                            {{ $mod->module }}
                                        </a>
                                        <span class="font-semibold text-label">{{ $mod->count }} tiket</span>
                                    </div>
                                    <div class="h-1.5 w-full rounded-full bg-input overflow-hidden">
                                        <div class="h-full rounded-full bg-sky-500" style="width: {{ ($mod->count / $maxModuleCount) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-label italic py-2">Belum ada data modul yang tercatat.</p>
                    @endif
                </div>

                {{-- Developer Triage Shortcuts & Protocol --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="grid size-6 place-items-center rounded-lg bg-slate-100 text-slate-800">
                            <x-auth.icon name="code" class="size-3" />
                        </span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-ink">Alur Triase Developer</h3>
                    </div>
                    <ol class="space-y-2.5 text-xs text-body list-decimal list-inside">
                        <li><span>Buka tiket dengan status <strong class="text-ink">Terbuka</strong>.</span></li>
                        <li><span>Periksa hasil <strong class="text-ink">Deteksi Duplikasi</strong> untuk mencegah bug kembar.</span></li>
                        <li><span>Ubah status ke <strong class="text-ink">Diproses</strong> jika laporan valid.</span></li>
                        <li><span>Tandai <strong class="text-ink">Selesai</strong> setelah perbaikan berhasil dirilis.</span></li>
                    </ol>
                </div>

            </div>

        </div>

    </div>

</x-layouts.app>
