<x-layouts.app title="Tentang Kami">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-12">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3 text-sage-600" />
                <span class="font-medium">Beranda</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-md bg-input px-2 py-0.5 text-[11px] font-semibold text-ink">Tentang Kami</span>
        </nav>

        {{-- ======================== 2-COLUMN HERO SECTION ======================== --}}
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12 lg:items-center">

            <div class="lg:col-span-7">
                <span class="inline-flex items-center gap-1.5 rounded-md border border-sage-200 bg-sage-50 px-2.5 py-1 text-xs font-semibold text-sage-800">
                    <x-auth.icon name="sparkles" class="size-3.5 text-sage-600" />
                    <span>Agile Pemdigi 2026 · Universitas Airlangga</span>
                </span>

                <h1 class="mt-4 font-serif text-3xl font-bold tracking-tight text-ink sm:text-4xl lg:text-5xl leading-tight">
                    Membangun Ekosistem Pelaporan Bug yang Akuntabel &amp; Cerdas
                </h1>

                <p class="mt-3 text-sm sm:text-base leading-relaxed text-body max-w-2xl">
                    <strong>TwinBug</strong> hadir sebagai platform manajemen penanganan kendala teknis berbasis web yang menjembatani kolaborasi transparan antara penguji perangkat lunak (<em>Reporter / QA</em>) dan tim pengembang (<em>Developer</em>).
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-2.5">
                    @auth
                        <a
                            href="{{ route('bug-reports.create') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-sage-500 px-4.5 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                        >
                            <x-auth.icon name="sparkles" class="size-3.5" />
                            <span>Mulai Laporkan Bug</span>
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 rounded-xl bg-sage-500 px-4.5 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                        >
                            <span>Masuk ke Sistem</span>
                        </a>
                    @endauth

                    <a
                        href="{{ route('faq') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-divider bg-white px-4 py-2.5 text-xs font-semibold text-ink shadow-2xs transition hover:bg-input"
                    >
                        <span>Pelajari di FAQ</span>
                    </a>
                </div>
            </div>

            {{-- Stat metric grid (2x2) --}}
            <div class="grid grid-cols-2 gap-3.5 sm:gap-4 lg:col-span-5">
                {{-- Card 1: 4 Tahap --}}
                <div class="flex flex-col items-center text-center rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition hover:border-sage-300 hover:shadow-sm">
                    <span class="grid size-11 place-items-center rounded-xl bg-emerald-100 text-emerald-800 shadow-2xs">
                        <x-auth.icon name="check" class="size-5 shrink-0" />
                    </span>
                    <p class="mt-3 font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">4 Tahap</p>
                    <p class="mt-1 text-xs font-semibold text-label">Siklus Hidup Terbuka</p>
                    <p class="mt-1 text-[11px] text-body/80 leading-relaxed">Open &rarr; In Progress &rarr; Resolved &rarr; Closed</p>
                </div>

                {{-- Card 2: 6-Digit --}}
                <div class="flex flex-col items-center text-center rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition hover:border-amber-300 hover:shadow-sm">
                    <span class="grid size-11 place-items-center rounded-xl bg-amber-100 text-amber-800 shadow-2xs">
                        <x-auth.icon name="lock" class="size-5 shrink-0" />
                    </span>
                    <p class="mt-3 font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">6-Digit</p>
                    <p class="mt-1 text-xs font-semibold text-label">OTP Verifikasi Surel</p>
                    <p class="mt-1 text-[11px] text-body/80 leading-relaxed">Keamanan akun anti-spam berbasis email</p>
                </div>

                {{-- Card 3: Multimodal --}}
                <div class="flex flex-col items-center text-center rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition hover:border-sky-300 hover:shadow-sm">
                    <span class="grid size-11 place-items-center rounded-xl bg-sky-100 text-sky-800 shadow-2xs">
                        <x-auth.icon name="eye" class="size-5 shrink-0" />
                    </span>
                    <p class="mt-3 font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">Multimodal</p>
                    <p class="mt-1 text-xs font-semibold text-label">Bukti Screenshot Valid</p>
                    <p class="mt-1 text-[11px] text-body/80 leading-relaxed">Dukungan visual PNG, JPG, WebP &middot; 5MB</p>
                </div>

                {{-- Card 4: Twin-Proof --}}
                <div class="flex flex-col items-center text-center rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition hover:border-purple-300 hover:shadow-sm">
                    <span class="grid size-11 place-items-center rounded-xl bg-purple-100 text-purple-800 shadow-2xs">
                        <x-auth.icon name="sparkles" class="size-5 shrink-0" />
                    </span>
                    <p class="mt-3 font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">Twin-Proof</p>
                    <p class="mt-1 text-xs font-semibold text-label">Pencegah Laporan Ganda</p>
                    <p class="mt-1 text-[11px] text-body/80 leading-relaxed">Deteksi semantik AI &amp; hash kemiripan</p>
                </div>
            </div>

        </div>

        {{-- ======================== LATAR BELAKANG & MASALAH ======================== --}}
        <div>
            <div class="max-w-2xl">
                <span class="inline-block rounded-md border border-divider bg-input px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider text-label">
                    Mengapa TwinBug Ada?
                </span>
                <h2 class="mt-2.5 font-serif text-xl sm:text-2xl font-bold text-ink">
                    Menyelesaikan Masalah Pelaporan yang Tercecer
                </h2>
                <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-body">
                    Dalam alur rekayasa perangkat lunak tradisional, laporan bug sering disampaikan secara informal tanpa langkah reproduksi yang jelas, memicu duplikasi laporan dan ketidakpastian status.
                </p>
            </div>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition duration-200 hover:border-sage-300">
                    <div class="grid size-10 place-items-center rounded-xl bg-sage-50 text-sage-700 border border-sage-200">
                        <x-auth.icon name="bug" class="size-5 shrink-0" />
                    </div>
                    <h3 class="mt-3.5 font-serif text-base font-semibold text-ink">Formulir Terstandarisasi</h3>
                    <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-body">
                        Mewajibkan modul terdampak, langkah reproduksi bernomor urut, dan klasifikasi tingkat keparahan (<em>Severity</em>) agar investigasi teknis dapat segera dieksekusi.
                    </p>
                </div>

                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition duration-200 hover:border-amber-300">
                    <div class="grid size-10 place-items-center rounded-xl bg-amber-50 text-amber-700 border border-amber-200">
                        <x-auth.icon name="search" class="size-5 shrink-0" />
                    </div>
                    <h3 class="mt-3.5 font-serif text-base font-semibold text-ink">Pencegahan Laporan Ganda</h3>
                    <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-body">
                        Mencegah redundansi tiket (<em>twin bugs</em>) yang menyita waktu pengembang dengan analisis pencarian metadata dan pengelompokan laporan serupa dalam sistem.
                    </p>
                </div>

                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition duration-200 hover:border-emerald-300">
                    <div class="grid size-10 place-items-center rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <x-auth.icon name="check" class="size-5 shrink-0" />
                    </div>
                    <h3 class="mt-3.5 font-serif text-base font-semibold text-ink">Transparansi Siklus Hidup</h3>
                    <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-body">
                        Setiap tiket bergerak secara transparan: <em>Open</em> &rarr; <em>In Progress</em> &rarr; <em>Resolved</em> &rarr; <em>Closed</em>, sehingga Reporter selalu mengetahui status penanganan kendala mereka.
                    </p>
                </div>
            </div>
        </div>

        {{-- ======================== PROFIL TIM PENGEMBANG ======================== --}}
        <div>
            <div class="max-w-xl">
                <span class="inline-block rounded-md border border-divider bg-input px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider text-label">
                    Tim Pengembang
                </span>
                <h2 class="mt-2.5 font-serif text-xl sm:text-2xl font-bold text-ink">
                    Di Balik Pengembangan TwinBug
                </h2>
                <p class="mt-1 text-xs sm:text-sm text-body">
                    Kolaborasi tim mahasiswa yang berdedikasi menciptakan sistem pelacak bug yang efisien, handal, dan berpusat pada kenyamanan pengguna.
                </p>
            </div>

            <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($teamMembers as $member)
                    <div class="group flex flex-col justify-between rounded-2xl border border-divider/80 bg-white p-5 shadow-xs transition duration-200 hover:-translate-y-0.5 hover:border-sage-300 hover:shadow-sm">
                        <div>
                            {{-- Avatar Initial --}}
                            <div class="flex items-center justify-between">
                                <span class="grid size-11 place-items-center rounded-xl bg-gradient-to-br from-sage-100 to-sage-200/80 font-serif text-base font-bold text-sage-800 shadow-2xs border border-sage-200">
                                    {{ $member['initials'] }}
                                </span>
                                <span class="rounded-md bg-input px-2 py-0.5 text-[11px] font-mono font-medium text-label">
                                    {{ $member['nim'] }}
                                </span>
                            </div>

                            {{-- Nama & Peran --}}
                            <h3 class="mt-4 font-serif text-sm font-bold text-ink leading-snug group-hover:text-sage-700 transition">
                                {{ $member['name'] }}
                            </h3>

                            <div class="mt-1.5">
                                <span class="inline-block rounded-md bg-sage-50 border border-sage-200 px-2 py-0.5 text-[10px] font-semibold text-sage-700">
                                    {{ $member['role'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Tautan Profil GitHub --}}
                        <div class="mt-5 border-t border-divider/70 pt-3.5">
                            <a
                                href="{{ $member['github'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-divider bg-input/40 px-3 py-1.5 text-xs font-semibold text-label transition hover:border-slate-300 hover:bg-input hover:text-ink"
                            >
                                <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                </svg>
                                <span>{{ '@' . $member['username'] }}</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ======================== ATRIBUSI AKADEMIK ======================== --}}
        <div class="rounded-2xl border border-divider/80 bg-white p-6 shadow-xs">
            <div class="flex flex-col items-center justify-between gap-5 text-center sm:flex-row sm:text-left">
                <div class="flex items-center gap-3.5">
                    <span class="grid size-11 place-items-center rounded-xl bg-sage-500 text-white shadow-cta shrink-0">
                        <x-auth.icon name="shield" class="size-5 shrink-0" />
                    </span>
                    <div>
                        <h4 class="font-serif text-sm font-bold text-ink">Program Studi S1 Sistem Informasi</h4>
                        <p class="text-xs text-body">Fakultas Sains dan Teknologi · Universitas Airlangga</p>
                        <p class="mt-0.5 text-[11px] font-medium text-sage-600">Mata Kuliah Praktikum Pengembangan Perangkat Lunak Agile (2026)</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-1.5">
                    <span class="rounded-md border border-divider bg-input/60 px-2.5 py-1 text-[11px] font-semibold text-label">Laravel 13</span>
                    <span class="rounded-md border border-divider bg-input/60 px-2.5 py-1 text-[11px] font-semibold text-label">PHP 8.3+</span>
                    <span class="rounded-md border border-divider bg-input/60 px-2.5 py-1 text-[11px] font-semibold text-label">Tailwind CSS v4</span>
                    <span class="rounded-md border border-divider bg-input/60 px-2.5 py-1 text-[11px] font-semibold text-label">Vite 8.0</span>
                    <span class="rounded-md border border-divider bg-input/60 px-2.5 py-1 text-[11px] font-semibold text-label">PHPUnit</span>
                </div>
            </div>
        </div>

        {{-- ======================== CALL TO ACTION ======================== --}}
        <div class="rounded-2xl border border-sage-200/80 bg-gradient-to-br from-sage-50 via-white to-amber-50/20 p-6 sm:p-8 shadow-xs">
            <div class="flex flex-col items-center justify-between gap-5 text-center sm:flex-row sm:text-left">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2 py-0.5 text-[11px] font-bold uppercase tracking-wider text-sage-800">
                        Mulai Eksplorasi
                    </span>
                    <h3 class="mt-2 font-serif text-xl sm:text-2xl font-bold text-ink">
                        Siap Melaporkan dan Menangani Bug?
                    </h3>
                    <p class="mt-1 text-xs sm:text-sm text-body leading-relaxed">
                        Gunakan TwinBug untuk pengalaman pelaporan kendala teknis yang rapi, terarah, dan transparan bagi seluruh anggota tim Anda.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-2.5 sm:flex-row">
                    @auth
                        <a
                            href="{{ route('bug-reports.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-sage-500 px-4 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/50"
                        >
                            <x-auth.icon name="bug" class="size-3.5" />
                            <span>+ Buat Laporan Bug</span>
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-sage-500 px-4 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600"
                        >
                            <span>Masuk ke Sistem</span>
                        </a>
                    @endauth

                    <a
                        href="{{ route('faq') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-divider bg-white px-4 py-2.5 text-xs font-semibold text-label transition hover:border-slate-300 hover:bg-input hover:text-ink"
                    >
                        <span>Pusat Bantuan &amp; FAQ</span>
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>