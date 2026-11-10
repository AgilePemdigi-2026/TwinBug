<x-layouts.app title="Dashboard Developer" meta-description="Dashboard developer TwinBug untuk memantau antrean laporan bug.">

    <div class="mx-auto max-w-screen-xl px-12 py-10 space-y-8">

        {{-- ======================== WELCOME & ACTION HEADER ======================== --}}
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <div class="mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-ink/5 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-ink">
                        <x-auth.icon name="code" class="size-3.5" />
                        {{ $user->role->label() }}
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="text-xs font-medium text-label">{{ $user->email }}</span>
                </div>
                <h1 class="font-serif text-4xl font-bold tracking-tight text-ink md:text-5xl" style="letter-spacing: -1px;">
                    Halo, {{ $user->name }}
                </h1>
                <p class="mt-2 text-base text-label">
                    {{ $user->role->description() }}
                </p>
            </div>

            {{-- Primary Action Button --}}
            <div class="flex shrink-0 items-center gap-3">
                <a
                    href="{{ route('bug-reports.index', ['status' => 'open']) }}"
                    id="btn-review-bug-queue"
                    class="group inline-flex items-center gap-2.5 rounded-2xl bg-ink px-6 py-3.5 text-sm font-bold text-white shadow-[0_4px_14px_rgba(46,50,48,0.25)] transition hover:bg-ink/90 hover:shadow-lg active:scale-95"
                >
                    <x-auth.icon name="search" class="size-4 shrink-0" />
                    <span>Telusuri Antrean Bug</span>
                </a>
                <a
                    href="{{ route('bug-reports.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-divider bg-white px-4 py-3 text-sm font-medium text-ink transition hover:bg-input"
                >
                    <x-auth.icon name="bug" class="size-3.5" />
                    <span>Deteksi Duplikasi</span>
                </a>
            </div>
        </div>

        {{-- ======================== QUEUE BANNER ======================== --}}
        <div class="relative overflow-hidden rounded-3xl bg-sage-600 p-8 text-white shadow-card">
            {{-- Background decorative shapes --}}
            <div class="pointer-events-none absolute -right-12 -top-12 size-64 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-16 right-48 size-48 rounded-full bg-sage-700/60 blur-xl"></div>

            <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="max-w-2xl">
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">
                        <x-auth.icon name="alert" class="size-3.5" />
                        Antrean Aktif
                    </div>
                    <h2 class="font-serif text-2xl font-bold leading-tight sm:text-3xl">
                        {{ number_format($stats['open']) }} bug menunggu penanganan
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-white/85">
                        {{ number_format($stats['in_progress']) }} laporan sedang diproses dan {{ number_format($stats['critical']) }} berprioritas kritis. Tinjau antrean lalu buka detail laporan untuk menelaah bukti dan metadata screenshot.
                    </p>
                </div>

                <div class="shrink-0">
                    <a
                        href="{{ route('bug-reports.index', ['status' => 'open']) }}"
                        class="inline-flex items-center gap-2.5 rounded-2xl bg-white px-6 py-3.5 text-sm font-bold text-sage-700 shadow-md transition hover:bg-parchment hover:text-ink active:scale-95"
                    >
                        <span>Lihat Antrean Terbuka</span>
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- ======================== GLOBAL QUEUE METRICS ======================== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-dashboard.metric-card
                label="Total Laporan"
                :value="number_format($stats['total'])"
                description="Seluruh laporan dalam sistem"
                icon="bug"
                icon-classes="bg-sage-200 text-sage-700"
                :href="route('bug-reports.index')"
            />
            <x-dashboard.metric-card
                label="Terbuka"
                :value="number_format($stats['open'])"
                description="Menunggu ditangani"
                icon="alert"
                icon-classes="bg-sky-100 text-sky-800"
                :href="route('bug-reports.index', ['status' => 'open'])"
            />
            <x-dashboard.metric-card
                label="Diproses"
                :value="number_format($stats['in_progress'])"
                description="Sedang ditelaah"
                icon="filter"
                icon-classes="bg-amber-100 text-amber-800"
                :href="route('bug-reports.index', ['status' => 'in_progress'])"
            />
            <x-dashboard.metric-card
                label="Kritis"
                :value="number_format($stats['critical'])"
                description="Prioritas penanganan utama"
                icon="shield"
                icon-classes="bg-danger/10 text-danger"
                :href="route('bug-reports.index', ['severity' => 'critical'])"
            />
        </div>

        {{-- ======================== RECENT BUG QUEUE ======================== --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-xl font-bold text-ink">Antrean Bug Terbaru</h2>
                    <p class="mt-0.5 text-xs text-label">Laporan bug terbaru dari seluruh reporter untuk ditelaah</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <a
                        href="{{ route('bug-reports.index') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-divider bg-input px-3.5 py-2 text-xs font-semibold text-ink transition hover:border-sage-300 hover:bg-sage-50"
                    >
                        <x-auth.icon name="filter" class="size-3.5 text-sage-600" />
                        <span>Filter &amp; Semua Laporan</span>
                    </a>
                </div>
            </div>

            @if ($recentReports->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl bg-input/60 px-6 py-12 text-center">
                    <span class="mb-4 grid size-14 place-items-center rounded-2xl bg-sage-200 text-sage-700 shadow-sm">
                        <x-auth.icon name="check" class="size-7" />
                    </span>
                    <h3 class="font-serif text-lg font-bold text-ink">Antrean Masih Kosong</h3>
                    <p class="mt-1.5 max-w-md text-xs leading-relaxed text-label">
                        Belum ada laporan bug yang masuk. Antrean akan tampil di sini setelah reporter mengirimkan laporan baru.
                    </p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                                <tr class="border-b border-divider text-[11px] font-bold uppercase tracking-wider text-label">
                                    <th class="pb-3 pr-4">Judul Bug</th>
                                    <th class="pb-3 px-4">Pelapor</th>
                                    <th class="pb-3 px-4">Modul</th>
                                    <th class="pb-3 px-4">Keparahan</th>
                                    <th class="pb-3 px-4">Status</th>
                                    <th class="pb-3 px-4 text-center">Deteksi</th>
                                    <th class="pb-3 px-4 text-right">Aksi</th>
                                    <th class="pb-3 pl-4 text-right">Tanggal</th>
                                </tr>
                        </thead>
                        <tbody class="divide-y divide-divider">
                            @foreach ($recentReports as $report)
                                <tr class="transition hover:bg-input/40">
                                    <td class="py-4 pr-4">
                                        <a
                                            href="{{ route('bug-reports.show', $report) }}"
                                            class="font-semibold text-ink transition hover:text-sage-600"
                                        >
                                            {{ $report->title }}
                                        </a>
                                    </td>
                                    <td class="py-4 px-4 text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="grid size-6 place-items-center rounded-full bg-sage-200 text-[10px] font-bold text-sage-800">
                                                {{ mb_strtoupper(mb_substr($report->user?->name ?? '?', 0, 1)) }}
                                            </span>
                                            <span class="font-medium text-ink">{{ $report->user?->name ?? 'Anonim' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-xs">
                                        <span class="inline-flex items-center rounded-lg bg-input px-2.5 py-1 text-[11px] font-medium text-ink">
                                            {{ $report->module ?? 'Lainnya' }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $report->severity->badgeClasses() }}">
                                            {{ $report->severity->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $report->status->badgeClasses() }}">
                                            <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                                            {{ $report->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-center">
                                        @if ($report->detection_results_count > 0)
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold bg-sage-100 text-sage-800">
                                                {{ $report->detection_results_count }}
                                            </span>
                                        @else
                                            <span class="text-label/50 text-[11px] italic">-</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-right">
                                        <a
                                            href="{{ route('bug-reports.show', $report) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-sage-200 bg-sage-50 px-2.5 py-1 text-[11px] font-semibold text-sage-700 transition hover:bg-sage-100"
                                        >
                                            <x-auth.icon name="eye" class="size-3" />
                                            <span>Lihat Detail</span>
                                        </a>
                                        @if ($report->detection_results_count > 0)
                                            <a
                                                href="{{ route('bug-reports.detection', $report) }}"
                                                class="ml-2 inline-flex items-center gap-1.5 rounded-lg border border-divider bg-white px-2.5 py-1 text-[11px] font-semibold text-ink transition hover:bg-input"
                                            >
                                                <x-auth.icon name="bug" class="size-3" />
                                                <span>Deteksi</span>
                                            </a>
                                        @endif
                                    </td>
                                    <td class="py-4 pl-4 text-right text-xs text-label whitespace-nowrap">
                                        <div class="font-medium text-ink">{{ $report->created_at->format('d M Y') }}</div>
                                        <div class="text-[10px] text-label">{{ $report->created_at->diffForHumans() }}</div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <x-dashboard.application-info :user="$user" />

        <x-dashboard.account-info :user="$user" />

    </div>

</x-layouts.app>
