<x-layouts.landing title="Laporkan bug dan temukan duplikasi — TwinBug" meta-description="TwinBug membandingkan laporan baru dengan laporan sebelumnya dan menampilkan saran laporan serupa untuk ditinjau Developer. Sistem deteksi duplikasi bug untuk tim pengembang.">

    {{-- ======================== HERO SECTION ======================== --}}
    <section class="relative pt-8 sm:pt-16 lg:pt-20 pb-16 sm:pb-24 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            {{-- Hero Header Content --}}
            <div class="flex flex-col items-center text-center max-w-4xl mx-auto">
                {{-- Release Badge --}}
                <div class="inline-flex items-center gap-2 rounded-full border border-sage-200/80 bg-white/80 px-3.5 py-1.5 shadow-2xs backdrop-blur-sm transition hover:border-sage-300">
                    <span class="flex size-2 rounded-full bg-sage-500 animate-pulse"></span>
                    <span class="font-mono text-[11px] font-bold uppercase tracking-wider text-sage-800">TwinBug AI Engine</span>
                    <span class="text-slate-300">•</span>
                    <span class="text-xs font-medium text-label">Deteksi Duplikasi Multimodal</span>
                    <span class="rounded-full bg-sage-100 px-2 py-0.5 text-[10px] font-bold text-sage-700">Beta</span>
                </div>

                {{-- Hero Headline --}}
                <h1 class="mt-6 font-serif text-3xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-ink leading-[1.18] sm:leading-[1.15]">
                    Laporkan bug dan temukan duplikasi <br class="hidden sm:inline">
                    <span class="text-transparent bg-clip-text bg-linear-to-r from-sage-600 via-emerald-600 to-teal-700">secara cerdas &amp; akurat.</span>
                </h1>

                {{-- Hero Subtitle --}}
                <p class="mt-5 max-w-2xl text-sm sm:text-base lg:text-lg leading-relaxed text-body">
                    TwinBug membandingkan laporan baru dengan laporan sebelumnya dan menampilkan saran laporan serupa untuk ditinjau Developer. Mengurangi tiket kembar, mempercepat triase, dan menghemat waktu tim Anda.
                </p>

                {{-- Primary CTA Buttons --}}
                <div class="mt-8 flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                    <a
                        href="{{ route('register') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl bg-sage-500 px-7 py-3.5 text-sm font-bold text-white shadow-cta transition-all duration-200 hover:bg-sage-600 hover:shadow-lg active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40"
                    >
                        <x-auth.icon name="sparkles" class="size-4" />
                        <span>Daftar Sekarang</span>
                    </a>
                    <a
                        href="{{ route('login') }}"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 rounded-xl border border-divider/90 bg-white/90 px-7 py-3.5 text-sm font-bold text-ink shadow-2xs backdrop-blur-sm transition-all duration-200 hover:bg-input hover:border-slate-300 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40"
                    >
                        <span>Masuk</span>
                    </a>
                </div>

                {{-- Micro Trust Indicators --}}
                <div class="mt-6 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs text-label">
                    <span class="inline-flex items-center gap-1.5">
                        <x-auth.icon name="check" class="size-3.5 text-sage-600" />
                        <span>Model Sentence-Transformers</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-auth.icon name="check" class="size-3.5 text-sage-600" />
                        <span>OCR Gambar Otomatis</span>
                    </span>
                    <span class="inline-flex items-center gap-1.5">
                        <x-auth.icon name="check" class="size-3.5 text-sage-600" />
                        <span>Multi-Aplikasi Terpisah</span>
                    </span>
                </div>
            </div>

            {{-- ======================== INTERACTIVE HERO PREVIEW MOCKUP ======================== --}}
            <div class="mt-12 sm:mt-16 relative mx-auto max-w-5xl reveal-on-scroll">
                {{-- Decorative Glow behind mockup --}}
                <div class="pointer-events-none absolute -inset-2 rounded-3xl bg-linear-to-r from-sage-300/30 via-emerald-200/20 to-teal-300/30 blur-2xl opacity-70"></div>

                <div class="relative rounded-2xl sm:rounded-3xl border border-divider/90 bg-white shadow-xl overflow-hidden backdrop-blur-xl">
                    {{-- Mockup Window Chrome --}}
                    <div class="flex items-center justify-between border-b border-divider/80 bg-input/40 px-4 py-3">
                        <div class="flex items-center gap-2">
                            <span class="size-3 rounded-full bg-rose-400"></span>
                            <span class="size-3 rounded-full bg-amber-400"></span>
                            <span class="size-3 rounded-full bg-emerald-400"></span>
                            <span class="ml-2 font-mono text-[11px] font-semibold text-label">twinbug-duplication-inspector.ai</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-semibold text-emerald-700">
                                <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Model Siap
                            </span>
                        </div>
                    </div>

                    {{-- Mockup Body --}}
                    <div class="p-4 sm:p-6 lg:p-8 bg-linear-to-b from-white to-slate-50/50">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-center">

                            {{-- Incoming Bug Card (Left, 5 cols) --}}
                            <div class="lg:col-span-5 rounded-2xl border border-divider bg-white p-4 sm:p-5 shadow-xs">
                                <div class="flex items-center justify-between pb-3 border-b border-divider/60">
                                    <div class="flex items-center gap-2">
                                        <span class="grid size-6 place-items-center rounded-lg bg-sky-100 text-sky-700 font-mono text-xs font-bold">#</span>
                                        <span class="text-xs font-bold text-ink">Laporan Masuk</span>
                                    </div>
                                    <span class="font-mono text-xs font-bold text-sky-700">#2041</span>
                                </div>
                                <div class="mt-3">
                                    <h2 class="text-xs sm:text-sm font-bold text-ink leading-snug">
                                        Tombol checkout QRIS tidak merespons di iOS Safari
                                    </h2>
                                    <p class="mt-1.5 text-xs text-label leading-relaxed line-clamp-2">
                                        Setelah menekan konfirmasi pembayaran, indikator loading berputar terus tanpa menampilkan kode QR.
                                    </p>
                                </div>

                                {{-- Simulated Screenshot with OCR detection chip --}}
                                <div class="mt-3.5 rounded-xl border border-divider/80 bg-input/60 p-3">
                                    <div class="flex items-center justify-between text-[11px] text-label mb-2">
                                        <span class="flex items-center gap-1">
                                            <x-auth.icon name="upload" class="size-3 text-sage-600" />
                                            Screenshot Lampiran
                                        </span>
                                        <span class="rounded bg-sage-100 px-1.5 py-0.5 font-mono text-[9px] font-bold text-sage-800">OCR Aktif</span>
                                    </div>
                                    <div class="relative rounded-lg border border-divider bg-white p-2.5 shadow-2xs overflow-hidden">
                                        <div class="flex items-center gap-2">
                                            <span class="grid size-5 place-items-center rounded bg-rose-50 text-rose-600 text-[10px] font-bold">!</span>
                                            <p class="font-mono text-[10px] text-rose-700 font-semibold truncate">Error 504 Gateway Timeout: Payment API</p>
                                        </div>
                                        <p class="mt-1 text-[10px] text-label/70 italic">
                                            Teks error pada gambar terbaca otomatis dan dianalisis model.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Center AI Match Beam (2 cols) --}}
                            <div class="lg:col-span-2 flex flex-col items-center justify-center py-2 lg:py-0">
                                <div class="relative flex flex-col items-center">
                                    {{-- Rotating Match Pulse --}}
                                    <div class="grid size-12 place-items-center rounded-2xl bg-sage-500 text-white shadow-cta relative">
                                        <x-auth.icon name="sparkles" class="size-6" />
                                    </div>
                                    <div class="mt-2 text-center">
                                        <span class="inline-flex items-center rounded-md bg-rose-50 border border-rose-200 px-2 py-0.5 text-[10px] font-bold text-rose-700">
                                            Kemiripan Tinggi
                                        </span>
                                        <p class="mt-0.5 font-mono text-[10px] text-label">Kandidat Top 1</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Matched Existing Bug Card (Right, 5 cols) --}}
                            <div class="lg:col-span-5 rounded-2xl border border-emerald-200/80 bg-emerald-50/40 p-4 sm:p-5 shadow-xs">
                                <div class="flex items-center justify-between pb-3 border-b border-emerald-200/60">
                                    <div class="flex items-center gap-2">
                                        <span class="grid size-6 place-items-center rounded-lg bg-emerald-100 text-emerald-800 font-mono text-xs font-bold">✓</span>
                                        <span class="text-xs font-bold text-emerald-950">Bug Serupa di Database</span>
                                    </div>
                                    <span class="font-mono text-xs font-bold text-emerald-800">#1988</span>
                                </div>
                                <div class="mt-3">
                                    <h2 class="text-xs sm:text-sm font-bold text-ink leading-snug">
                                        Checkout macet pada halaman pembayaran QRIS
                                    </h2>
                                    <p class="mt-1.5 text-xs text-label leading-relaxed line-clamp-2">
                                        Pelanggan melaporkan gateway timeout saat melakukan konfirmasi pesanan metode QRIS.
                                    </p>
                                </div>

                                <div class="mt-3.5 rounded-xl border border-emerald-200/70 bg-white p-3 shadow-2xs">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="text-[11px] font-semibold text-emerald-900">Rekomendasi Sistem:</span>
                                        <span class="inline-flex items-center gap-1 rounded bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800">
                                            Potensi Duplikat
                                        </span>
                                    </div>
                                    <p class="mt-1 text-[11px] text-label leading-relaxed">
                                        Developer dapat menandai laporan #2041 sebagai duplikasi dari #1988 dalam satu klik.
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- 4 Capability Highlights Bar --}}
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 reveal-on-scroll">
                <div class="rounded-2xl border border-divider/80 bg-white p-4 shadow-xs flex items-start gap-3 transition hover:border-slate-300">
                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-sage-100 text-sage-700">
                        <x-auth.icon name="sparkles" class="size-4" />
                    </span>
                    <div>
                        <h2 class="text-xs font-bold text-ink">Deteksi Semantik Cepat</h2>
                        <p class="mt-0.5 text-[11px] leading-relaxed text-label">Memahami makna kalimat bahkan dengan pilihan kata berbeda.</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-divider/80 bg-white p-4 shadow-xs flex items-start gap-3 transition hover:border-slate-300">
                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-sky-100 text-sky-700">
                        <x-auth.icon name="eye" class="size-4" />
                    </span>
                    <div>
                        <h2 class="text-xs font-bold text-ink">Multimodal OCR</h2>
                        <p class="mt-0.5 text-[11px] leading-relaxed text-label">Membaca teks error langsung dari gambar tangkapan layar.</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-divider/80 bg-white p-4 shadow-xs flex items-start gap-3 transition hover:border-slate-300">
                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-amber-100 text-amber-700">
                        <x-auth.icon name="code" class="size-4" />
                    </span>
                    <div>
                        <h2 class="text-xs font-bold text-ink">Rekomendasi Top-K</h2>
                        <p class="mt-0.5 text-[11px] leading-relaxed text-label">Daftar kandidat paling relevan untuk diputuskan developer.</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-divider/80 bg-white p-4 shadow-xs flex items-start gap-3 transition hover:border-slate-300">
                    <span class="grid size-9 shrink-0 place-items-center rounded-xl bg-indigo-100 text-indigo-700">
                        <x-auth.icon name="shield" class="size-4" />
                    </span>
                    <div>
                        <h2 class="text-xs font-bold text-ink">Multi-Tenant Terisolasi</h2>
                        <p class="mt-0.5 text-[11px] leading-relaxed text-label">Setiap aplikasi memiliki antrean tiket yang aman dan terpisah.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ======================== MASALAH YANG KAMI ATASI ======================== --}}
    <section id="masalah" class="py-16 sm:py-20 lg:py-24 border-y border-divider/80 bg-white/70">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12 sm:mb-16 reveal-on-scroll">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 border border-rose-200 px-3 py-1 text-xs font-bold text-rose-700 uppercase tracking-wider">
                    Tantangan Pengembang
                </span>
                <h2 class="mt-3 font-serif text-2xl sm:text-4xl font-bold tracking-tight text-ink">
                    Masalah yang kami atasi
                </h2>
                <p class="mt-3 text-sm sm:text-base text-label leading-relaxed">
                    Laporan duplikasi buang waktu dan menunda perbaikan. Inilah kendala yang sering terjadi di tim pengembang:
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 lg:gap-8">
                {{-- Problem 1 --}}
                <div class="rounded-2xl border border-divider bg-white p-6 sm:p-7 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-rose-200 reveal-on-scroll reveal-delay-100">
                    <div class="flex items-center justify-between mb-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-rose-50 text-rose-600">
                            <x-auth.icon name="alert" class="size-5" />
                        </span>
                        <span class="rounded-md bg-input px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-label">Overload</span>
                    </div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-ink">Laporan ganda menumpuk</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Beberapa reporter melaporkan masalah yang sama secara terpisah, sehingga antrean penanganan menjadi berlebih dan membingungkan penanggung jawab modul.
                    </p>
                </div>

                {{-- Problem 2 --}}
                <div class="rounded-2xl border border-divider bg-white p-6 sm:p-7 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-amber-200 reveal-on-scroll reveal-delay-200">
                    <div class="flex items-center justify-between mb-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-amber-50 text-amber-600">
                            <x-auth.icon name="refresh" class="size-5" />
                        </span>
                        <span class="rounded-md bg-input px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-label">Bottleneck</span>
                    </div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-ink">Triage Developer lambat</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Tanpa indikator kemiripan, Developer harus membaca satu per satu untuk menentukan apakah dua laporan adalah masalah yang sama, menghabiskan waktu coding berharga.
                    </p>
                </div>

                {{-- Problem 3 --}}
                <div class="rounded-2xl border border-divider bg-white p-6 sm:p-7 shadow-xs transition-all duration-300 hover:-translate-y-1 hover:shadow-md hover:border-gold-200 reveal-on-scroll reveal-delay-300">
                    <div class="flex items-center justify-between mb-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-gold-100 text-ochre">
                            <x-auth.icon name="search" class="size-5" />
                        </span>
                        <span class="rounded-md bg-input px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-label">Fragmentasi</span>
                    </div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-ink">Satu bug, banyak tempat</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Masalah yang sama dilaporkan di berbagai modul dan sulit dilacak karena tersebar di banyak tiket yang tidak terkait, memicu perbaikan parsial yang berulang.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== BENTO GRID FITUR UNGGULAN ======================== --}}
    <section id="fitur" class="py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12 sm:mb-16 reveal-on-scroll">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sage-100 border border-sage-200 px-3 py-1 text-xs font-bold text-sage-800 uppercase tracking-wider">
                    Arsitektur Canggih
                </span>
                <h2 class="mt-3 font-serif text-2xl sm:text-4xl font-bold tracking-tight text-ink">
                    Fitur
                </h2>
                <p class="mt-3 text-sm sm:text-base text-label leading-relaxed">
                    Setiap fitur dirancang untuk mengurangi laporan duplikasi dan mempercepat penanganan bug dalam alur kerja tim Anda.
                </p>
            </div>

            {{-- Bento Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                {{-- Feature 1: Large Bento Card (Span 2 cols on lg) --}}
                <div class="lg:col-span-2 rounded-2xl border border-divider/90 bg-white p-6 sm:p-8 shadow-xs transition-all duration-300 hover:shadow-md flex flex-col justify-between reveal-on-scroll reveal-delay-100">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="grid size-10 place-items-center rounded-xl bg-sage-100 text-sage-700">
                                <x-auth.icon name="search" class="size-5" />
                            </span>
                            <span class="rounded-full bg-sage-50 border border-sage-200 px-2.5 py-0.5 text-[10px] font-bold text-sage-700">Multilingual</span>
                        </div>
                        <h3 class="font-serif text-lg sm:text-xl font-bold text-ink">Deteksi laporan serupa berbasis teks</h3>
                        <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed max-w-xl">
                            Sistem membandingkan judul dan deskripsi laporan baru dengan laporan sebelumnya, baik dalam Bahasa Indonesia maupun bahasa Inggris, menggunakan model representasi vektor MiniLM.
                        </p>
                    </div>

                    {{-- Visual representation inside card --}}
                    <div class="mt-6 rounded-xl border border-divider/80 bg-input/40 p-3.5 space-y-2">
                        <div class="flex items-center justify-between text-xs bg-white rounded-lg p-2.5 border border-divider/70">
                            <span class="text-ink font-medium">Input ID: "Tidak bisa login setelah pembaruan aplikasi"</span>
                            <span class="text-sage-600 font-bold text-[11px]">Query Baru</span>
                        </div>
                        <div class="flex items-center justify-between text-xs bg-emerald-50 rounded-lg p-2.5 border border-emerald-200">
                            <span class="text-emerald-950 font-medium">Target EN: "Users unable to authenticate on mobile app"</span>
                            <span class="text-emerald-700 font-bold text-[11px]">Kandidat Cocok</span>
                        </div>
                    </div>
                </div>

                {{-- Feature 2: OCR Card --}}
                <div class="rounded-2xl border border-divider/90 bg-white p-6 sm:p-8 shadow-xs transition-all duration-300 hover:shadow-md flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="grid size-10 place-items-center rounded-xl bg-sky-100 text-sky-700">
                                <x-auth.icon name="eye" class="size-5" />
                            </span>
                            <span class="rounded-full bg-sky-50 border border-sky-200 px-2.5 py-0.5 text-[10px] font-bold text-sky-700">OCR Engine</span>
                        </div>
                        <h3 class="font-serif text-lg font-bold text-ink">Teks pada screenshot dibaca otomatis (OCR)</h3>
                        <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                            Teks yang ada pada screenshot diekstrak dan ikut dibandingkan dengan laporan lain untuk deteksi yang lebih akurat, menangkap pesan error konsol dan dialog modal.
                        </p>
                    </div>

                    <div class="mt-6 rounded-xl border border-dashed border-sky-300 bg-sky-50/50 p-3 text-center">
                        <span class="font-mono text-[11px] text-sky-800 font-semibold">RapidOCR Optical Scanner Active</span>
                    </div>
                </div>

                {{-- Feature 3: Visual Hash --}}
                <div class="rounded-2xl border border-divider/90 bg-white p-6 sm:p-8 shadow-xs transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-amber-100 text-amber-700">
                            <x-auth.icon name="filter" class="size-5" />
                        </span>
                        <span class="rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[10px] font-bold text-amber-700">Visual Hash</span>
                    </div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-ink">Screenshot hampir identik ditandai</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Laporan dengan screenshot visual yang hampir sama ditandai sebagai potensi duplikasi, bahkan jika teks laporan berbeda atau ditulis dengan redaksi lain.
                    </p>
                </div>

                {{-- Feature 4: Top-K --}}
                <div class="rounded-2xl border border-divider/90 bg-white p-6 sm:p-8 shadow-xs transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-emerald-100 text-emerald-700">
                            <x-auth.icon name="code" class="size-5" />
                        </span>
                        <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[10px] font-bold text-emerald-700">Rekomendasi</span>
                    </div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-ink">Saran Top-K dengan skor kemiripan</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Hasil deteksi berupa daftar laporan serupa dengan skor kemiripan. Keputusan akhir apakah dua laporan adalah duplikasi tetap di tangan Developer.
                    </p>
                </div>

                {{-- Feature 5: Multi-Tenant Data Isolation --}}
                <div class="rounded-2xl border border-divider/90 bg-white p-6 sm:p-8 shadow-xs transition-all duration-300 hover:shadow-md">
                    <div class="flex items-center justify-between mb-4">
                        <span class="grid size-10 place-items-center rounded-xl bg-purple-100 text-purple-700">
                            <x-auth.icon name="key" class="size-5" />
                        </span>
                        <span class="rounded-full bg-purple-50 border border-purple-200 px-2.5 py-0.5 text-[10px] font-bold text-purple-700">Multi-Aplikasi</span>
                    </div>
                    <h3 class="font-serif text-base sm:text-lg font-bold text-ink">Data dipisah per aplikasi</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Setiap aplikasi memiliki antrean laporan sendiri, sehingga laporan dari satu aplikasi tidak bercampur dengan aplikasi lain. Terhubung dengan kode undangan unik.
                    </p>
                </div>

                {{-- Feature 6: OTP Security (Span 3 cols or 1 col) --}}
                <div class="md:col-span-2 lg:col-span-3 rounded-2xl border border-divider/90 bg-white p-6 sm:p-7 shadow-xs transition-all duration-300 hover:shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-start sm:items-center gap-4">
                        <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                            <x-auth.icon name="check" class="size-5" />
                        </span>
                        <div>
                            <h3 class="font-serif text-base sm:text-lg font-bold text-ink">Verifikasi akun lewat OTP email</h3>
                            <p class="mt-1 text-xs sm:text-sm text-label leading-relaxed">
                                Setiap pendaftaran diverifikasi melalui kode OTP yang dikirim ke email, sehingga identitas Reporter terjamin dan mencegah spam tiket.
                            </p>
                        </div>
                    </div>
                    <span class="shrink-0 inline-flex items-center gap-1.5 rounded-xl border border-sage-200 bg-sage-50 px-3 py-1.5 text-xs font-bold text-sage-800">
                        <x-auth.icon name="shield" class="size-3.5 text-sage-600" />
                        Otentikasi Aman
                    </span>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================== CARA KERJA ======================== --}}
    <section id="cara-kerja" class="py-16 sm:py-20 lg:py-24 border-y border-divider/80 bg-white/70">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12 sm:mb-16 reveal-on-scroll">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-sage-100 border border-sage-200 px-3 py-1 text-xs font-bold text-sage-800 uppercase tracking-wider">
                    Alur 3 Langkah
                </span>
                <h2 class="mt-3 font-serif text-2xl sm:text-4xl font-bold tracking-tight text-ink">
                    Cara Kerja
                </h2>
                <p class="mt-3 text-sm sm:text-base text-label leading-relaxed">
                    Dari laporan hingga penanganan, dalam tiga langkah terstruktur.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">

                {{-- Step 1 --}}
                <div class="relative rounded-2xl border border-divider bg-white p-6 sm:p-8 shadow-xs flex flex-col items-center text-center reveal-on-scroll reveal-delay-100">
                    <div class="grid size-14 place-items-center rounded-2xl bg-sage-100 text-sage-700 font-serif text-xl font-bold mb-5 shadow-2xs">
                        1
                    </div>
                    <h3 class="font-serif text-lg font-bold text-ink mb-2">Reporter membuat laporan</h3>
                    <p class="text-xs sm:text-sm text-body leading-relaxed">
                        Reporter mengisi judul, deskripsi, modul, dan opsional menyertakan screenshot hasil temuan bug pada aplikasi yang digunakan.
                    </p>
                    <div class="mt-5 w-full pt-4 border-t border-divider/60 text-[11px] font-semibold text-sage-700">
                        Formulir Terstandarisasi
                    </div>
                </div>

                {{-- Step 2 --}}
                <div class="relative rounded-2xl border border-divider bg-white p-6 sm:p-8 shadow-xs flex flex-col items-center text-center reveal-on-scroll reveal-delay-200">
                    <div class="grid size-14 place-items-center rounded-2xl bg-emerald-500 text-white font-serif text-xl font-bold mb-5 shadow-cta">
                        2
                    </div>
                    <h3 class="font-serif text-lg font-bold text-ink mb-2">Sistem menganalisis</h3>
                    <p class="text-xs sm:text-sm text-body leading-relaxed">
                        Teks dan screenshot dianalisis untuk mencari laporan serupa yang sudah ada di sistem menggunakan model AI canggih dan OCR.
                    </p>
                    <div class="mt-5 w-full pt-4 border-t border-divider/60 text-[11px] font-semibold text-emerald-700">
                        Pencocokan Vektor Otomatis
                    </div>
                </div>

                {{-- Step 3 --}}
                <div class="relative rounded-2xl border border-divider bg-white p-6 sm:p-8 shadow-xs flex flex-col items-center text-center reveal-on-scroll reveal-delay-300">
                    <div class="grid size-14 place-items-center rounded-2xl bg-slate-900 text-white font-serif text-xl font-bold mb-5 shadow-xs">
                        3
                    </div>
                    <h3 class="font-serif text-lg font-bold text-ink mb-2">Developer meninjau</h3>
                    <p class="text-xs sm:text-sm text-body leading-relaxed">
                        Developer melihat saran laporan serupa dengan skor kemiripan dan memutuskan apakah akan ditandai sebagai duplikasi.
                    </p>
                    <div class="mt-5 w-full pt-4 border-t border-divider/60 text-[11px] font-semibold text-slate-800">
                        Keputusan Akhir Transparan
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ======================== PERAN PENGGUNA ======================== --}}
    <section id="peran" class="py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="max-w-2xl mx-auto text-center mb-12 sm:mb-16 reveal-on-scroll">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-bold text-slate-800 uppercase tracking-wider">
                    Kolaborasi Tim
                </span>
                <h2 class="mt-3 font-serif text-2xl sm:text-4xl font-bold tracking-tight text-ink">
                    Peran dalam Sistem
                </h2>
                <p class="mt-3 text-sm sm:text-base text-label leading-relaxed">
                    Setiap peran memiliki tampilan dan tanggung jawab yang disesuaikan untuk efisiensi maksimal.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                {{-- Reporter Role Card --}}
                <div class="rounded-3xl border border-sage-200/90 bg-white p-7 sm:p-9 shadow-sm flex flex-col justify-between reveal-on-scroll reveal-delay-100">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <span class="grid size-12 place-items-center rounded-2xl bg-sage-100 text-sage-700 shadow-2xs">
                                <x-auth.icon name="bug" class="size-6" />
                            </span>
                            <div>
                                <h3 class="font-serif text-xl font-bold text-ink">Reporter</h3>
                                <p class="text-xs text-label">Pelapor Kendala &amp; Penguji Aplikasi</p>
                            </div>
                        </div>

                        <p class="text-xs sm:text-sm text-body leading-relaxed mb-6">
                            Fokus pada pelaporan kendala secara cepat dan rinci tanpa perlu memikirkan apakah bug tersebut sudah pernah dilaporkan sebelumnya.
                        </p>

                        <ul class="space-y-3.5 text-xs sm:text-sm text-body">
                            <li class="flex items-start gap-3">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-sage-100 text-sage-700 text-xs mt-0.5">✓</span>
                                <span>Melaporkan bug dengan judul, deskripsi, dan langkah reproduksi.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-sage-100 text-sage-700 text-xs mt-0.5">✓</span>
                                <span>Mengunggah screenshot sebagai bukti visual untuk dianalisis OCR.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-sage-100 text-sage-700 text-xs mt-0.5">✓</span>
                                <span>Memantau status laporan yang telah dikirim dan melihat hasil tindak lanjut.</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8 pt-6 border-t border-divider/70">
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-xs font-bold text-sage-600 transition hover:text-sage-700">
                            <span>Daftar sebagai Reporter</span>
                            <span class="text-sm">→</span>
                        </a>
                    </div>
                </div>

                {{-- Developer Role Card --}}
                <div class="rounded-3xl border border-divider bg-white p-7 sm:p-9 shadow-sm flex flex-col justify-between reveal-on-scroll reveal-delay-200">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <span class="grid size-12 place-items-center rounded-2xl bg-slate-100 text-slate-800 shadow-2xs">
                                <x-auth.icon name="code" class="size-6" />
                            </span>
                            <div>
                                <h3 class="font-serif text-xl font-bold text-ink">Developer</h3>
                                <p class="text-xs text-label">Pengembang &amp; Triase Bug</p>
                            </div>
                        </div>

                        <p class="text-xs sm:text-sm text-body leading-relaxed mb-6">
                            Mendapatkan antrean bug yang rapi dengan indikator duplikasi cerdas untuk mempercepat verifikasi dan penyelesaian masalah.
                        </p>

                        <ul class="space-y-3.5 text-xs sm:text-sm text-body">
                            <li class="flex items-start gap-3">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-800 text-xs mt-0.5">✓</span>
                                <span>Meninjau saran laporan serupa dengan skor kemiripan secara berdampingan.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-800 text-xs mt-0.5">✓</span>
                                <span>Menandai laporan sebagai duplikasi jika memang sama dalam satu klik.</span>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-slate-100 text-slate-800 text-xs mt-0.5">✓</span>
                                <span>Memperbarui status penanganan laporan (Terbuka, Diproses, Selesai, Ditutup).</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8 pt-6 border-t border-divider/70">
                        <a href="{{ route('register') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-800 transition hover:text-ink">
                            <span>Daftar sebagai Developer</span>
                            <span class="text-sm">→</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== INTERACTIVE FAQ TEASER ======================== --}}
    <section class="py-16 sm:py-20 lg:py-24 border-t border-divider/80 bg-white/70">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16 reveal-on-scroll">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-bold text-amber-800 uppercase tracking-wider">
                    Pertanyaan Umum
                </span>
                <h2 class="mt-3 font-serif text-2xl sm:text-4xl font-bold tracking-tight text-ink">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="mt-2 text-sm text-label">
                    Jawaban cepat seputar cara kerja deteksi duplikasi TwinBug.
                </p>
            </div>

            <div class="space-y-4">
                <div class="rounded-2xl border border-divider/90 bg-white p-5 sm:p-6 shadow-xs reveal-on-scroll">
                    <h3 class="text-sm sm:text-base font-bold text-ink">Bagaimana sistem mendeteksi bug duplikat?</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Sistem menggabungkan analisis kemiripan semantik teks (menggunakan model Sentence-Transformers MiniLM) dan ekstraksi teks visual dari screenshot (menggunakan Optical Character Recognition). Hasilnya dirangking berdasarkan skor kemiripan.
                    </p>
                </div>

                <div class="rounded-2xl border border-divider/90 bg-white p-5 sm:p-6 shadow-xs reveal-on-scroll">
                    <h3 class="text-sm sm:text-base font-bold text-ink">Apakah sistem langsung menutup laporan secara otomatis?</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Tidak. Hasil analisis sistem adalah rekomendasi cerdas. Keputusan akhir apakah dua laporan memang menduplikasi satu sama lain sepenuhnya berada di bawah kendali tim Developer.
                    </p>
                </div>

                <div class="rounded-2xl border border-divider/90 bg-white p-5 sm:p-6 shadow-xs reveal-on-scroll">
                    <h3 class="text-sm sm:text-base font-bold text-ink">Apakah screenshot wajib disertakan saat melapor?</h3>
                    <p class="mt-2 text-xs sm:text-sm text-body leading-relaxed">
                        Lampiran screenshot bersifat opsional. Namun, menyertakan tangkapan layar sangat disarankan karena sistem dapat mengekstrak teks error pada gambar untuk meningkatkan akurasi deteksi kemiripan.
                    </p>
                </div>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('faq') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-sage-600 hover:text-sage-700">
                    <span>Lihat seluruh pertanyaan di Pusat Bantuan &amp; FAQ</span>
                    <span class="text-sm">→</span>
                </a>
            </div>
        </div>
    </section>

    {{-- ======================== FINAL CTA BANNER ======================== --}}
    <section class="py-16 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-slate-950 via-slate-900 to-emerald-950 p-8 sm:p-14 lg:p-16 text-center text-white shadow-xl reveal-on-scroll">
                {{-- Decorative Glow inside banner --}}
                <div class="pointer-events-none absolute -right-20 -top-20 size-80 rounded-full bg-emerald-500/20 blur-[90px]"></div>
                <div class="pointer-events-none absolute -left-20 -bottom-20 size-80 rounded-full bg-sage-500/20 blur-[90px]"></div>

                <div class="relative z-10 max-w-2xl mx-auto">
                    <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-400/30 bg-emerald-500/10 px-3.5 py-1 text-xs font-semibold text-emerald-300">
                        <x-auth.icon name="sparkles" class="size-3.5" />
                        <span>Mulai Sekarang</span>
                    </span>

                    <h2 class="mt-4 font-serif text-2xl sm:text-4xl font-bold tracking-tight text-white leading-tight">
                        Mulai laporkan bug dengan lebih rapi
                    </h2>

                    <p class="mt-3 text-sm sm:text-base text-slate-300 leading-relaxed">
                        Buat akun dan mulai laporan bug yang akan dianalisis sistem TwinBug untuk deteksi duplikasi.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3.5 justify-center">
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-sage-500 px-7 py-3.5 text-sm font-bold text-white shadow-cta transition-all duration-200 hover:bg-sage-600 hover:shadow-lg active:scale-95"
                        >
                            <x-auth.icon name="sparkles" class="size-4" />
                            <span>Daftar</span>
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/20 bg-white/10 px-7 py-3.5 text-sm font-bold text-white backdrop-blur-sm transition-all duration-200 hover:bg-white/20 active:scale-95"
                        >
                            <span>Masuk</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</x-layouts.landing>
