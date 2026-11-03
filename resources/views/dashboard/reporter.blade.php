<x-layouts.app title="Dashboard Reporter" meta-description="Dashboard reporter TwinBug untuk memantau histori laporan bug pribadi.">

    <div class="mx-auto max-w-screen-xl px-12 py-10 space-y-8">

        {{-- ======================== WELCOME & ACTION HEADER ======================== --}}
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <div class="mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-sage-200/80 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-sage-700">
                        <x-auth.icon name="bug" class="size-3.5" />
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
                    href="{{ route('bug-reports.create') }}"
                    id="btn-create-bug-report-hero"
                    class="group inline-flex items-center gap-2.5 rounded-2xl bg-sage-500 px-6 py-3.5 text-sm font-bold text-white shadow-[0_4px_14px_rgba(74,124,89,0.35)] transition hover:bg-sage-600 hover:shadow-lg active:scale-95"
                >
                    <x-auth.icon name="sparkles" class="size-4 shrink-0 transition-transform group-hover:rotate-12" />
                    <span>+ Buat Laporan Bug Baru</span>
                </a>
            </div>
        </div>

        {{-- ======================== PROMINENT CTA BANNER ======================== --}}
        <div class="relative overflow-hidden rounded-3xl bg-sage-500 p-8 text-white shadow-card">
            {{-- Background decorative shapes --}}
            <div class="pointer-events-none absolute -right-12 -top-12 size-64 rounded-full bg-white/10 blur-2xl"></div>
            <div class="pointer-events-none absolute -bottom-16 right-48 size-48 rounded-full bg-sage-600/60 blur-xl"></div>

            <div class="relative z-10 flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <div class="max-w-2xl">
                    <div class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-white">
                        <x-auth.icon name="sparkles" class="size-3.5" />
                        Fitur Utama
                    </div>
                    <h2 class="font-serif text-2xl font-bold leading-tight sm:text-3xl">
                        Laporkan Bug &amp; Deteksi Duplikasi Secara Otomatis
                    </h2>
                    <p class="mt-2 text-sm leading-relaxed text-white/85">
                        Kirimkan laporan bug dengan langkah reproduksi dan tangkapan layar. Sistem AI multimodal TwinBug akan otomatis menganalisis kemiripan semantik dan visual untuk mencegah laporan ganda.
                    </p>
                </div>

                <div class="shrink-0">
                    <a
                        href="{{ route('bug-reports.create') }}"
                        id="btn-create-bug-report-banner"
                        class="inline-flex items-center gap-2.5 rounded-2xl bg-white px-6 py-3.5 text-sm font-bold text-sage-700 shadow-md transition hover:bg-parchment hover:text-ink active:scale-95"
                    >
                        <span>Mulai Buat Laporan</span>
                        <svg class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- ======================== PERSONAL METRICS ======================== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <x-dashboard.metric-card
                label="Laporan Saya"
                :value="number_format($stats['my_total'])"
                description="Total laporan bug yang Anda kirimkan"
                icon="bug"
                icon-classes="bg-sage-200 text-sage-700"
            />
            <x-dashboard.metric-card
                label="Terbuka"
                :value="number_format($stats['my_open'])"
                description="Menunggu review developer"
                icon="alert"
                icon-classes="bg-sky-100 text-sky-800"
            />
            <x-dashboard.metric-card
                label="Diproses"
                :value="number_format($stats['my_in_progress'])"
                description="Sedang ditelaah developer"
                icon="filter"
                icon-classes="bg-amber-100 text-amber-800"
            />
            <x-dashboard.metric-card
                label="Selesai"
                :value="number_format($stats['my_resolved'])"
                description="Laporan yang sudah diselesaikan"
                icon="check"
                icon-classes="bg-sage-200 text-sage-800"
            />
        </div>

        {{-- ======================== PERSONAL REPORT HISTORY ======================== --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-xl font-bold text-ink">Histori Laporan Saya</h2>
                    <p class="mt-0.5 text-xs text-label">Laporan bug yang baru saja Anda buat dan status penanganannya</p>
                </div>
                <div class="flex items-center gap-2.5">
                    <a
                        href="{{ route('bug-reports.index', ['scope' => 'my']) }}"
                        class="inline-flex items-center gap-1.5 rounded-xl border border-divider bg-input px-3.5 py-2 text-xs font-semibold text-ink transition hover:border-sage-300 hover:bg-sage-50"
                    >
                        <x-auth.icon name="sparkles" class="size-3.5 text-sage-600" />
                        <span>Semua Laporan Saya</span>
                    </a>
                    <a
                        href="{{ route('bug-reports.create') }}"
                        class="inline-flex items-center gap-1.5 rounded-xl bg-sage-500 px-3.5 py-2 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600"
                    >
                        + Buat Baru
                    </a>
                </div>
            </div>

            @if ($recentReports->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl bg-input/60 px-6 py-12 text-center">
                    <span class="mb-4 grid size-14 place-items-center rounded-2xl bg-sage-200 text-sage-700 shadow-sm">
                        <x-auth.icon name="bug" class="size-7" />
                    </span>
                    <h3 class="font-serif text-lg font-bold text-ink">Belum Ada Laporan Bug</h3>
                    <p class="mt-1.5 max-w-md text-xs leading-relaxed text-label">
                        Anda belum mengirimkan laporan bug ke sistem. Mulai laporkan temuan isu atau error agar sistem TwinBug dapat menganalisis dan mendeteksi duplikasi.
                    </p>
                    <a
                        href="{{ route('bug-reports.create') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-sage-500 px-5 py-2.5 text-xs font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                    >
                        <x-auth.icon name="sparkles" class="size-3.5" />
                        Buat Laporan Bug Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-divider text-[11px] font-bold uppercase tracking-wider text-label">
                                <th class="pb-3 pr-4">Judul &amp; Ringkasan Bug</th>
                                <th class="pb-3 px-4">Modul</th>
                                <th class="pb-3 px-4">Keparahan</th>
                                <th class="pb-3 px-4">Status</th>
                                <th class="pb-3 px-4">Screenshot</th>
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
                                        <div class="mt-0.5 line-clamp-2 max-w-md text-xs text-label">
                                            {{ $report->description }}
                                        </div>
                                        <div class="mt-1 text-[10px] font-medium text-label/70">
                                            #{{ $report->id }}
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-xs text-body">
                                        {{ $report->module ?? '-' }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $report->severity->badgeClasses() }}">
                                            {{ $report->severity->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $report->status->badgeClasses() }}">
                                            <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                                            {{ $report->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-xs">
                                        @if ($report->hasScreenshot())
                                            <a
                                                href="{{ asset('storage/' . $report->screenshot_path) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="group inline-flex items-center gap-1.5 rounded-lg border border-sage-200 bg-sage-50 px-2 py-1 text-[11px] font-semibold text-sage-700 transition hover:bg-sage-100"
                                                title="Lihat screenshot resolusi penuh ({{ $report->screenshot_dimensions ?? 'Unknown dim' }})"
                                            >
                                                <x-auth.icon name="check" class="size-3 text-sage-600" />
                                                <span>Ada ({{ $report->screenshot_dimensions ?? 'Lihat' }})</span>
                                            </a>
                                        @else
                                            <span class="text-label/50 text-[11px] italic">Tidak ada</span>
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
                                    </td>
                                    <td class="py-4 pl-4 text-right text-xs text-label">
                                        {{ $report->created_at->format('d/m/Y H:i') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <x-dashboard.account-info :user="$user" />

    </div>

</x-layouts.app>
