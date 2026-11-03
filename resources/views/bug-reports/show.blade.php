<x-layouts.app title="Detail Laporan Bug" meta-description="Detail lengkap laporan bug pada sistem TwinBug.">

    <div class="mx-auto max-w-screen-xl px-12 py-10 space-y-8">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <a href="{{ route('bug-reports.index') }}" class="text-label transition hover:text-ink">Daftar Laporan</a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-full bg-divider px-2.5 py-1 text-[11px] font-semibold text-ink">Detail Laporan</span>
        </nav>

        {{-- ======================== HEADER ======================== --}}
        <div class="flex flex-col gap-6 md:flex-row md:items-start md:justify-between">
            <div class="max-w-3xl">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $report->severity->badgeClasses() }}">
                        {{ $report->severity->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $report->status->badgeClasses() }}">
                        <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                        {{ $report->status->label() }}
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="text-xs font-semibold text-label">#{{ $report->id }}</span>
                </div>
                <h1 class="font-serif text-3xl font-bold tracking-tight text-ink md:text-4xl">{{ $report->title }}</h1>
                <p class="mt-2 text-xs text-label">
                    Dilaporkan {{ $report->created_at->format('d M Y H:i') }} • {{ $report->created_at->diffForHumans() }}
                </p>
            </div>

            <a
                href="{{ route('bug-reports.index') }}"
                class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-divider bg-white px-4 py-2.5 text-xs font-bold text-ink shadow-sm transition hover:bg-input"
            >
                <x-auth.icon name="arrow-left" class="size-3.5" />
                <span>Kembali</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="space-y-6 lg:col-span-2">
                {{-- ======================== DESCRIPTION ======================== --}}
                <div class="rounded-3xl bg-white p-8 shadow-sm">
                    <h2 class="font-serif text-xl font-bold text-ink">Deskripsi Bug</h2>
                    <p class="mt-3 whitespace-pre-line text-sm leading-relaxed text-body">{{ $report->description }}</p>
                </div>

                {{-- ======================== SCREENSHOT ======================== --}}
                @if ($report->hasScreenshot())
                    <div class="rounded-3xl bg-white p-8 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h2 class="font-serif text-xl font-bold text-ink">Lampiran Screenshot</h2>
                            <a
                                href="{{ asset('storage/' . $report->screenshot_path) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 rounded-lg border border-sage-200 bg-sage-50 px-2.5 py-1 text-[11px] font-semibold text-sage-700 transition hover:bg-sage-100"
                            >
                                <x-auth.icon name="eye" class="size-3" />
                                <span>Buka Resolusi Penuh</span>
                            </a>
                        </div>

                        <a
                            href="{{ asset('storage/' . $report->screenshot_path) }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="block overflow-hidden rounded-2xl border border-divider bg-input"
                        >
                            <img
                                src="{{ asset('storage/' . $report->screenshot_path) }}"
                                alt="Screenshot laporan {{ $report->title }}"
                                class="max-h-[32rem] w-full object-contain"
                            >
                        </a>

                        <dl class="mt-4 grid grid-cols-1 gap-3 text-xs sm:grid-cols-3">
                            <div class="rounded-2xl bg-input p-3">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Nama Berkas</dt>
                                <dd class="mt-1 break-all font-semibold text-ink">{{ $report->screenshot_name ?? '-' }}</dd>
                            </div>
                            <div class="rounded-2xl bg-input p-3">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Ukuran</dt>
                                <dd class="mt-1 font-semibold text-ink">
                                    {{ $report->screenshot_size ? \Illuminate\Support\Number::fileSize($report->screenshot_size) : '-' }}
                                </dd>
                            </div>
                            <div class="rounded-2xl bg-input p-3">
                                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Dimensi</dt>
                                <dd class="mt-1 font-semibold text-ink">{{ $report->screenshot_dimensions ?? '-' }}</dd>
                            </div>
                        </dl>
                    </div>
                @endif
            </div>

            {{-- ======================== METADATA ======================== --}}
            <aside class="space-y-6">
                <div class="rounded-3xl bg-white p-6 shadow-sm">
                    <h2 class="font-serif text-lg font-bold text-ink">Informasi Laporan</h2>

                    <dl class="mt-4 space-y-3 text-sm">
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Aplikasi</dt>
                            <dd class="mt-1 font-semibold text-ink">{{ $report->application?->name ?? '-' }}</dd>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul / Komponen</dt>
                            <dd class="mt-1 font-semibold text-ink">{{ $report->module ?? 'Lainnya' }}</dd>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Pelapor</dt>
                            <dd class="mt-1 flex items-center gap-2">
                                <span class="grid size-6 place-items-center rounded-full bg-sage-200 text-[10px] font-bold text-sage-800">
                                    {{ mb_strtoupper(mb_substr($report->user?->name ?? '?', 0, 1)) }}
                                </span>
                                <span>
                                    <span class="block font-semibold text-ink">{{ $report->user?->name ?? 'Anonim' }}</span>
                                    <span class="block text-[10px] text-label">{{ $report->user?->role?->label() ?? 'Reporter' }}</span>
                                </span>
                            </dd>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal Lapor</dt>
                            <dd class="mt-1 font-semibold text-ink">{{ $report->created_at->format('d M Y H:i') }}</dd>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Terakhir Diperbarui</dt>
                            <dd class="mt-1 font-semibold text-ink">{{ $report->updated_at->format('d M Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </aside>
        </div>

    </div>

</x-layouts.app>
