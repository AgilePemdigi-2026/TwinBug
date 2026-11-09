<x-layouts.app title="Tentang Kami">
    <div class="mx-auto max-w-6xl px-6 py-12 sm:px-8 lg:px-12">

        {{-- ======================== HERO SECTION ======================== --}}
        <div class="text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-sage-200 bg-sage-50 px-3.5 py-1 text-xs font-semibold text-sage-700 shadow-sm">
                <x-auth.icon name="sparkles" class="size-3.5 text-sage-500" />
                Agile Pemdigi 2026 · Universitas Airlangga
            </span>

            <h1 class="mt-5 font-serif text-3xl font-bold tracking-tight text-ink sm:text-5xl sm:leading-tight">
                Membangun Ekosistem Pelaporan Bug yang Akuntabel & Cerdas
            </h1>

            <p class="mx-auto mt-5 max-w-3xl text-base leading-relaxed text-body sm:text-lg">
                <strong>TwinBug</strong> hadir sebagai platform manajemen penanganan kendala teknis berbasis web yang menjembatani kolaborasi transparan antara penguji perangkat lunak (<em>Reporter / QA</em>) dan tim pengembang (<em>Developer</em>).
            </p>

            {{-- Stat metric pills --}}
            <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-4 sm:gap-4 max-w-4xl mx-auto">
                <div class="rounded-2xl border border-divider bg-card p-4 shadow-card text-center">
                    <p class="font-serif text-2xl font-bold text-sage-600 sm:text-3xl">4 Tahap</p>
                    <p class="mt-1 text-xs font-semibold text-label">Siklus Hidup Terbuka</p>
                </div>
                <div class="rounded-2xl border border-divider bg-card p-4 shadow-card text-center">
                    <p class="font-serif text-2xl font-bold text-sage-600 sm:text-3xl">6-Digit</p>
                    <p class="mt-1 text-xs font-semibold text-label">OTP Verifikasi Surel</p>
                </div>
                <div class="rounded-2xl border border-divider bg-card p-4 shadow-card text-center">
                    <p class="font-serif text-2xl font-bold text-sage-600 sm:text-3xl">Multimodal</p>
                    <p class="mt-1 text-xs font-semibold text-label">Bukti Screenshot Valid</p>
                </div>
                <div class="rounded-2xl border border-divider bg-card p-4 shadow-card text-center">
                    <p class="font-serif text-2xl font-bold text-sage-600 sm:text-3xl">Twin-Proof</p>
                    <p class="mt-1 text-xs font-semibold text-label">Pencegah Laporan Ganda</p>
                </div>
            </div>
        </div>

        {{-- ======================== LATAR BELAKANG & MASALAH ======================== --}}
        <div class="mt-20">
            <div class="text-center">
                <span class="inline-block rounded-md border border-divider bg-input px-3 py-1 text-xs font-bold uppercase tracking-wider text-label">
                    Mengapa TwinBug Ada?
                </span>
                <h2 class="mt-3 font-serif text-2xl font-bold text-ink sm:text-3xl">
                    Menyelesaikan Masalah Pelaporan yang Tercecer
                </h2>
                <p class="mx-auto mt-3 max-w-2xl text-sm leading-relaxed text-body">
                    Dalam alur rekayasa perangkat lunak tradisional, laporan bug sering disampaikan secara informal tanpa langkah reproduksi yang jelas, memicu duplikasi laporan dan ketidakpastian status.
                </p>
            </div>

            <div class="mt-10 grid gap-6 md:grid-cols-3">
                <div class="rounded-2xl border border-divider bg-card p-6 shadow-card transition duration-200 hover:border-sage-300">
                    <div class="grid size-11 place-items-center rounded-xl bg-sage-50 text-sage-600 border border-sage-200">
                        <x-auth.icon name="bug" class="size-5" />
                    </div>
                    <h3 class="mt-4 font-serif text-lg font-semibold text-ink">Formulir Terstandarisasi</h3>
                    <p class="mt-2 text-sm leading-relaxed text-body">
                        Mewajibkan modul terdampak, langkah reproduksi bernomor urut, dan klasifikasi tingkat keparahan (<em>Severity</em>) agar investigasi teknis dapat segera dieksekusi.
                    </p>
                </div>

                <div class="rounded-2xl border border-divider bg-card p-6 shadow-card transition duration-200 hover:border-sage-300">
                    <div class="grid size-11 place-items-center rounded-xl bg-gold-100/70 text-ochre border border-gold-200">
                        <x-auth.icon name="search" class="size-5" />
                    </div>
                    <h3 class="mt-4 font-serif text-lg font-semibold text-ink">Pencegahan Laporan Ganda</h3>
                    <p class="mt-2 text-sm leading-relaxed text-body">
                        Mencegah redundansi tiket (<em>twin bugs</em>) yang menyita waktu pengembang dengan analisis pencarian metadata dan pengelompokan laporan serupa dalam sistem.
                    </p>
                </div>

                <div class="rounded-2xl border border-divider bg-card p-6 shadow-card transition duration-200 hover:border-sage-300">
                    <div class="grid size-11 place-items-center rounded-xl bg-sage-50 text-sage-600 border border-sage-200">
                        <x-auth.icon name="check" class="size-5" />
                    </div>
                    <h3 class="mt-4 font-serif text-lg font-semibold text-ink">Transparansi Siklus Hidup</h3>
                    <p class="mt-2 text-sm leading-relaxed text-body">
                        Setiap tiket bergerak secara transparan: <em>Open</em> &rarr; <em>In Progress</em> &rarr; <em>Resolved</em> &rarr; <em>Closed</em>, sehingga Reporter selalu mengetahui status penanganan kendala mereka.
                    </p>
                </div>
            </div>
        </div>

        {{-- ======================== PROFIL TIM PENGEMBANG ======================== --}}
        <div class="mt-20">
            <div class="text-center">
                <span class="inline-block rounded-md border border-divider bg-input px-3 py-1 text-xs font-bold uppercase tracking-wider text-label">
                    Tim Pengembang
                </span>
                <h2 class="mt-3 font-serif text-2xl font-bold text-ink sm:text-3xl">
                    Di Balik Pengembangan TwinBug
                </h2>
                <p class="mx-auto mt-2 max-w-xl text-sm text-body">
                    Kolaborasi tim mahasiswa yang berdedikasi menciptakan sistem pelacak bug yang efisien, handal, dan berpusat pada kenyamanan pengguna.
                </p>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($teamMembers as $member)
                    <div class="group flex flex-col justify-between rounded-2xl border border-divider bg-card p-6 shadow-card transition duration-200 hover:-translate-y-1 hover:border-sage-300 hover:shadow-lg">
                        <div>
                            {{-- Avatar Initial --}}
                            <div class="flex items-center justify-between">
                                <span class="grid size-14 place-items-center rounded-2xl bg-gradient-to-br from-sage-100 to-sage-200/80 font-serif text-lg font-bold text-sage-700 shadow-sm border border-sage-200">
                                    {{ $member['initials'] }}
                                </span>
                                <span class="rounded-full bg-input px-2.5 py-1 text-[11px] font-mono font-medium text-label">
                                    {{ $member['nim'] }}
                                </span>
                            </div>

                            {{-- Nama & Peran --}}
                            <h3 class="mt-5 font-serif text-base font-bold text-ink leading-snug group-hover:text-sage-700 transition">
                                {{ $member['name'] }}
                            </h3>

                            <div class="mt-2">
                                <span class="inline-block rounded-md bg-sage-50 border border-sage-200 px-2.5 py-0.5 text-[11px] font-semibold text-sage-700">
                                    {{ $member['role'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Tautan Profil GitHub --}}
                        <div class="mt-6 border-t border-divider/70 pt-4">
                            <a
                                href="{{ $member['github'] }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-input px-3 py-2 text-xs font-semibold text-label transition hover:bg-input-hover hover:text-ink"
                            >
                                <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
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
        <div class="mt-16 rounded-2xl border border-divider bg-card p-8 shadow-card">
            <div class="flex flex-col items-center justify-between gap-6 text-center sm:flex-row sm:text-left">
                <div class="flex items-center gap-4">
                    <span class="grid size-12 place-items-center rounded-2xl bg-sage-500 text-white shadow-cta shrink-0">
                        <x-auth.icon name="shield" class="size-6" />
                    </span>
                    <div>
                        <h4 class="font-serif text-base font-bold text-ink">Program Studi S1 Sistem Informasi</h4>
                        <p class="text-xs text-body">Fakultas Sains dan Teknologi · Universitas Airlangga</p>
                        <p class="mt-0.5 text-[11px] font-medium text-sage-600">Mata Kuliah Praktikum Pengembangan Perangkat Lunak Agile (2026)</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center justify-center gap-2">
                    <span class="rounded-xl border border-divider bg-input px-3 py-1.5 text-xs font-semibold text-label">Laravel 13</span>
                    <span class="rounded-xl border border-divider bg-input px-3 py-1.5 text-xs font-semibold text-label">PHP 8.3+</span>
                    <span class="rounded-xl border border-divider bg-input px-3 py-1.5 text-xs font-semibold text-label">Tailwind CSS v4</span>
                    <span class="rounded-xl border border-divider bg-input px-3 py-1.5 text-xs font-semibold text-label">Vite 8.0</span>
                    <span class="rounded-xl border border-divider bg-input px-3 py-1.5 text-xs font-semibold text-label">PHPUnit</span>
                </div>
            </div>
        </div>

        {{-- ======================== CALL TO ACTION ======================== --}}
        <div class="mt-16 rounded-3xl border border-sage-200 bg-gradient-to-br from-sage-50/90 via-card to-gold-100/40 p-8 shadow-card sm:p-10">
            <div class="flex flex-col items-center justify-between gap-6 text-center sm:flex-row sm:text-left">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider text-sage-700">
                        Mulai Eksplorasi
                    </span>
                    <h3 class="mt-2 font-serif text-2xl font-bold text-ink">
                        Siap Melaporkan dan Menangani Bug?
                    </h3>
                    <p class="mt-2 text-sm text-body leading-relaxed">
                        Gunakan TwinBug untuk pengalaman pelaporan kendala teknis yang rapi, terarah, dan transparan bagi seluruh anggota tim Anda.
                    </p>
                </div>

                <div class="flex shrink-0 flex-col gap-3 sm:flex-row">
                    @auth
                        <a
                            href="{{ route('bug-reports.create') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-sage-500 px-5 py-3 text-sm font-semibold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/50"
                        >
                            <x-auth.icon name="bug" class="size-4" />
                            + Buat Laporan Bug
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-sage-500 px-5 py-3 text-sm font-semibold text-white shadow-cta transition hover:bg-sage-600"
                        >
                            Masuk ke Sistem
                        </a>
                    @endauth

                    <a
                        href="{{ route('faq') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-divider bg-white px-5 py-3 text-sm font-semibold text-label transition hover:border-sage-300 hover:bg-sage-50 hover:text-ink"
                    >
                        Pusat Bantuan & FAQ
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-layouts.app>
