<x-layouts.app title="Dashboard Reporter" meta-description="Dashboard reporter TwinBug untuk memantau histori laporan bug pribadi.">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ======================== WELCOME & ACTION HEADER ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
            <div>
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2.5 py-0.5 text-[11px] font-semibold text-sage-800">
                        <x-auth.icon name="bug" class="size-3 text-sage-700" />
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

        {{-- ======================== STATUS DISTRIBUTION PROGRESS STRIP ======================== --}}
        @php
            $myTotal = max(1, $stats['my_total']);
            $myOpenPct = round(($stats['my_open'] / $myTotal) * 100);
            $myInProgressPct = round(($stats['my_in_progress'] / $myTotal) * 100);
            $myResolvedPct = max(0, 100 - $myOpenPct - $myInProgressPct);
        @endphp
        <div class="rounded-2xl border border-divider/80 bg-white p-4 sm:p-5 shadow-xs">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="grid size-6 place-items-center rounded-lg bg-input text-label">
                        <x-auth.icon name="check" class="size-3.5" />
                    </span>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-ink">Progres Penanganan Laporan Saya</h2>
                </div>
                <div class="flex flex-wrap items-center gap-3 text-xs">
                    <a href="{{ route('bug-reports.index', ['scope' => 'my', 'status' => 'open']) }}" class="flex items-center gap-1.5 font-medium text-sky-700 hover:underline">
                        <span class="size-2 rounded-full bg-sky-500"></span>
                        <span>{{ number_format($stats['my_open']) }} Terbuka</span>
                    </a>
                    <a href="{{ route('bug-reports.index', ['scope' => 'my', 'status' => 'in_progress']) }}" class="flex items-center gap-1.5 font-medium text-amber-700 hover:underline">
                        <span class="size-2 rounded-full bg-amber-500"></span>
                        <span>{{ number_format($stats['my_in_progress']) }} Diproses</span>
                    </a>
                    <a href="{{ route('bug-reports.index', ['scope' => 'my', 'status' => 'resolved']) }}" class="flex items-center gap-1.5 font-medium text-emerald-700 hover:underline">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        <span>{{ number_format($stats['my_resolved']) }} Selesai</span>
                    </a>
                </div>
            </div>

            {{-- Segmented Progress Bar --}}
            <div class="flex h-2.5 w-full overflow-hidden rounded-full bg-input/80">
                @if ($stats['my_total'] > 0)
                    <div class="bg-sky-500 transition-all duration-500" style="width: {{ $myOpenPct }}%" title="Terbuka: {{ $myOpenPct }}%"></div>
                    <div class="bg-amber-500 transition-all duration-500" style="width: {{ $myInProgressPct }}%" title="Diproses: {{ $myInProgressPct }}%"></div>
                    <div class="bg-emerald-500 transition-all duration-500" style="width: {{ $myResolvedPct }}%" title="Selesai: {{ $myResolvedPct }}%"></div>
                @else
                    <div class="w-full bg-slate-200" title="Belum ada data laporan"></div>
                @endif
            </div>
        </div>

        {{-- ======================== 2-COLUMN DASHBOARD GRID ======================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- =============== LEFT COLUMN (8 cols): Primary Feed =============== --}}
            <div class="space-y-6 lg:col-span-8">

                {{-- Personal Report History --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
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
                        <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-divider bg-input/30 px-6 py-12 text-center">
                            <span class="mb-3 grid size-10 place-items-center rounded-xl bg-sage-100 text-sage-700 shadow-2xs">
                                <x-auth.icon name="bug" class="size-5" />
                            </span>
                            <h3 class="font-serif text-sm font-bold text-ink">Belum Ada Laporan Bug</h3>
                            <p class="mt-1 max-w-sm text-xs text-label leading-relaxed">
                                Anda belum mengirimkan laporan bug ke sistem. Mulai laporkan temuan kendala teknis agar sistem TwinBug menganalisis kemiripannya.
                            </p>
                            <a
                                href="{{ route('bug-reports.create') }}"
                                class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-sage-500 px-4 py-2 text-xs font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                            >
                                <x-auth.icon name="sparkles" class="size-3.5" />
                                <span>Buat Laporan Bug Pertama</span>
                            </a>
                        </div>
                    @else
                        {{-- Desktop Table View --}}
                        <div class="hidden md:block overflow-x-auto -mx-5 sm:mx-0">
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
                                            <td class="py-3.5 px-4">
                                                <a
                                                    href="{{ route('bug-reports.show', $report) }}"
                                                    class="font-semibold text-ink transition hover:text-sage-600 line-clamp-1"
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
                                            <td class="py-3.5 px-3 text-body font-mono text-[11px]">
                                                <span class="rounded bg-input px-1.5 py-0.5">
                                                    {{ $report->module ?? '-' }}
                                                </span>
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
                                            <td class="py-3.5 px-4 text-right">
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

                        {{-- Mobile Stacked Cards View --}}
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
                                                <span class="font-mono">{{ $report->module ?? '-' }}</span>
                                                <span>&middot;</span>
                                                <span>{{ $report->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                        <span class="shrink-0 inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $report->severity->badgeClasses() }}">
                                            {{ $report->severity->label() }}
                                        </span>
                                    </div>

                                    <p class="text-xs text-label line-clamp-2">
                                        {{ $report->description }}
                                    </p>

                                    <div class="flex items-center justify-between pt-1">
                                        <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $report->status->badgeClasses() }}">
                                            <span class="size-1 rounded-full bg-current opacity-80"></span>
                                            {{ $report->status->label() }}
                                        </span>

                                        <a
                                            href="{{ route('bug-reports.show', $report) }}"
                                            class="inline-flex items-center gap-1 rounded-lg border border-divider bg-input/50 px-2.5 py-1 text-[11px] font-semibold text-ink"
                                        >
                                            <x-auth.icon name="eye" class="size-3" />
                                            <span>Lihat Detail</span>
                                        </a>
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

                {{-- Modul Laporan Saya Widget --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-3.5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                                <x-auth.icon name="filter" class="size-3" />
                            </span>
                            <h3 class="text-xs font-bold uppercase tracking-wider text-ink">Modul Laporan Saya</h3>
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
                                        <a href="{{ route('bug-reports.index', ['scope' => 'my', 'module' => $mod->module]) }}" class="font-mono font-medium text-ink hover:text-sage-600 transition">
                                            {{ $mod->module }}
                                        </a>
                                        <span class="font-semibold text-label">{{ $mod->count }} laporan</span>
                                    </div>
                                    <div class="h-1.5 w-full rounded-full bg-input overflow-hidden">
                                        <div class="h-full rounded-full bg-sage-500" style="width: {{ ($mod->count / $maxModuleCount) * 100 }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-label italic py-2">Belum ada modul yang pernah Anda laporkan.</p>
                    @endif
                </div>

                {{-- Tips Pelaporan Cepat --}}
                <div class="rounded-2xl border border-sage-200/80 bg-sage-50/60 p-5 shadow-xs">
                    <div class="mb-3 flex items-center gap-2">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-200 text-sage-800">
                            <x-auth.icon name="sparkles" class="size-3" />
                        </span>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-sage-900">Tips Pelaporan Cepat</h3>
                    </div>
                    <ul class="space-y-2.5 text-xs text-body">
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1 size-1.5 shrink-0 rounded-full bg-sage-500"></span>
                            <span><strong>Sertakan Screenshot:</strong> AI mendeteksi kemiripan visual teks OCR secara presisi.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1 size-1.5 shrink-0 rounded-full bg-sage-500"></span>
                            <span><strong>Langkah Rinci:</strong> Tulis urutan langkah agar mudah direproduksi tim pengembang.</span>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <span class="mt-1 size-1.5 shrink-0 rounded-full bg-sage-500"></span>
                            <span><strong>Cek Kemiripan:</strong> Kotak deteksi duplikasi muncul otomatis saat mengisi judul.</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>

    </div>

</x-layouts.app>
