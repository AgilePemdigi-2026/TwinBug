@php
    $user = auth()->user();
    $myReportsCount = \App\Models\BugReport::where('user_id', $user->id)->count();
    $totalReportsCount = \App\Models\BugReport::count();
    $recentReports = \App\Models\BugReport::where('user_id', $user->id)->latest()->take(5)->get();
@endphp

<x-layouts.app title="Dashboard" meta-description="Pusat aktivitas dan manajemen laporan bug sistem TwinBug.">

    <div class="mx-auto max-w-screen-xl px-12 py-10 space-y-8">

        {{-- ======================== WELCOME & ACTION HEADER ======================== --}}
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <div class="mb-3 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-sage-200/80 px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-sage-700">
                        <x-auth.icon :name="$user->role === \App\Enums\UserRole::Developer ? 'code' : 'bug'" class="size-3.5" />
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

        {{-- ======================== METRICS GRID ======================== --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-label">Laporan Saya</p>
                    <span class="grid size-9 place-items-center rounded-xl bg-sage-200 text-sage-700">
                        <x-auth.icon name="bug" class="size-4" />
                    </span>
                </div>
                <p class="mt-3 font-serif text-3xl font-bold text-ink">{{ number_format($myReportsCount) }}</p>
                <p class="mt-1 text-xs text-label">Laporan bug yang telah Anda kirimkan</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-label">Arsip Sistem</p>
                    <span class="grid size-9 place-items-center rounded-xl bg-input text-ink">
                        <x-auth.icon name="sparkles" class="size-4" />
                    </span>
                </div>
                <p class="mt-3 font-serif text-3xl font-bold text-ink">{{ number_format($totalReportsCount) }}</p>
                <p class="mt-1 text-xs text-label">Total bug aktif dalam basis data TwinBug</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-label">Status Akun</p>
                    <span class="grid size-9 place-items-center rounded-xl bg-input text-success">
                        <x-auth.icon name="check" class="size-4" />
                    </span>
                </div>
                <p class="mt-3 flex items-center gap-2 font-serif text-3xl font-bold text-ink">
                    <span class="size-3 rounded-full bg-success"></span>
                    Aktif
                </p>
                <p class="mt-1 text-xs text-label">Akses penuh pelaporan &amp; pemantauan</p>
            </div>

            <div class="rounded-3xl bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-bold uppercase tracking-wider text-label">Terdaftar Sejak</p>
                    <span class="grid size-9 place-items-center rounded-xl bg-input text-label">
                        <x-auth.icon name="user" class="size-4" />
                    </span>
                </div>
                <p class="mt-3 font-serif text-2xl font-bold text-ink">{{ $user->created_at->format('d M Y') }}</p>
                <p class="mt-1 text-xs text-label">{{ $user->created_at->diffForHumans() }}</p>
            </div>
        </div>

        {{-- ======================== RECENT BUG REPORTS ======================== --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-xl font-bold text-ink">Laporan Bug Terkini Anda</h2>
                    <p class="mt-0.5 text-xs text-label">Daftar laporan bug yang baru saja Anda buat dan status analisisnya</p>
                </div>
                <a
                    href="{{ route('bug-reports.create') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-sage-600 transition hover:text-sage-700 hover:underline"
                >
                    + Buat Laporan Baru
                </a>
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
                                <th class="pb-3 pr-4">Judul Bug</th>
                                <th class="pb-3 px-4">Modul</th>
                                <th class="pb-3 px-4">Keparahan</th>
                                <th class="pb-3 px-4">Status</th>
                                <th class="pb-3 px-4">Screenshot</th>
                                <th class="pb-3 pl-4 text-right">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-divider">
                            @foreach ($recentReports as $report)
                                <tr class="transition hover:bg-input/40">
                                    <td class="py-4 pr-4 font-semibold text-ink">
                                        {{ $report->title }}
                                    </td>
                                    <td class="py-4 px-4 text-xs text-body">
                                        {{ $report->module ?? '-' }}
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-semibold bg-input text-label">
                                            {{ $report->severity->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold bg-sage-200 text-sage-700">
                                            <span class="size-1.5 rounded-full bg-sage-500"></span>
                                            {{ $report->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-xs text-label">
                                        @if ($report->hasScreenshot())
                                            <span class="inline-flex items-center gap-1 text-sage-600">
                                                <x-auth.icon name="check" class="size-3" />
                                                Ada
                                            </span>
                                        @else
                                            <span class="text-label/50">-</span>
                                        @endif
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

        {{-- ======================== DETAIL AKUN ======================== --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm">
            <div class="mb-4 flex items-center gap-2">
                <x-auth.icon name="user" class="size-4 text-sage-600" />
                <h2 class="font-serif text-lg font-bold text-ink">Informasi Akun Anda</h2>
            </div>

            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-3 text-sm">
                <div class="rounded-2xl bg-input p-4">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Nama Lengkap</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ $user->name }}</dd>
                </div>
                <div class="rounded-2xl bg-input p-4">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Email</dt>
                    <dd class="mt-1 break-all font-semibold text-ink">{{ $user->email }}</dd>
                </div>
                <div class="rounded-2xl bg-input p-4">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Peran Sistem</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ $user->role->label() }}</dd>
                </div>
            </dl>
        </div>

    </div>

</x-layouts.app>
