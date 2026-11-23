<x-layouts.landing title="Laporkan bug dan temukan duplikasi — TwinBug" meta-description="TwinBug membandingkan laporan baru dengan laporan sebelumnya dan menampilkan saran laporan serupa untuk ditinjau Developer. Sistem deteksi duplikasi bug untuk tim pengembang.">
    {{-- ======================== HERO ======================== --}}
    <section class="py-12 sm:py-20 lg:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col items-start lg:flex-row lg:items-center lg:justify-between gap-6 mb-12 sm:mb-16 lg:mb-20">
                <div class="max-w-2xl lg:max-w-3xl text-center lg:text-left">
                    <span class="inline-flex items-center gap-1.5 rounded-md border border-sage-200 bg-sage-50 px-2.5 py-1 text-xs font-semibold text-sage-800">
                        <x-auth.icon name="sparkles" class="size-3 text-sage-600" />
                        <span>BETA</span>
                    </span>
                    <h1 class="mt-4 font-serif text-3xl font-bold tracking-tight text-ink sm:text-4xl lg:text-5xl leading-tight">
                        Laporkan bug dan temukan duplikasi
                    </h1>
                    <p class="mt-4 text-sm sm:text-base leading-relaxed text-body max-w-xl sm:max-w-none lg:max-w-lg">
                        TwinBug membandingkan laporan baru dengan laporan sebelumnya dan menampilkan saran laporan serupa untuk ditinjau Developer.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a
                            href="{{ route('register') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-sage-500 px-6 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2"
                        >
                            <x-auth.icon name="sparkles" class="size-4" />
                            <span>Daftar Sekarang</span>
                        </a>
                        <a
                            href="{{ route('login') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-divider bg-white px-6 py-3 text-sm font-bold text-ink shadow-sm transition hover:bg-input active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2"
                        >
                            Masuk
                        </a>
                    </div>
                </div>

                {{-- Product mockup (HTML/Tailwind, no images) --}}
                <div class="mt-8 sm:mt-0 lg:ml-12 w-full lg:w-[420px]">
                    <div class="rounded-xl border border-divider bg-white shadow-card p-4">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-label">Laporan Baru</span>
                            <span class="text-[10px] font-bold text-sage-600">#2041</span>
                        </div>
                        <p class="text-xs text-label mb-3">Tombol checkout tidak dapat diklik setelah menambahkan item ke keranjang.</p>

                        <div class="space-y-2">
                            <div class="flex items-center gap-2 p-2 rounded-lg border border-divider/50 bg-input/50">
                                <span class="text-[10px] font-bold text-label">Saran #1</span>
                                <span class="ml-auto inline-flex items-center gap-1 rounded-lg border border-sage-200 bg-sage-50 px-2 py-0.5 text-[9px] font-bold text-sage-700">
                                    <span>Tinggi</span>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 p-2 rounded-lg border border-divider/50 bg-input/50">
                                <span class="text-[10px] font-bold text-label">Saran #2</span>
                                <span class="ml-auto inline-flex items-center gap-1 rounded-lg border border-divider bg-white px-2 py-0.5 text-[9px] font-bold text-label">
                                    <span>Sedang</span>
                                </span>
                            </div>
                        </div>
                        <p class="mt-3 text-[10px] text-label/70 italic">
                            Skor kemiripan dihitung dari teks dan konteks screenshot. Keputusan akhir di tangan Developer.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== MASALAH ======================== --}}
    <section id="masalah" class="py-12 sm:py-16 lg:py-20 border-y border-divider">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center lg:text-left max-w-2xl lg:max-w-none lg:mb-12">
                <h2 class="font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">Masalah yang kami atasi</h2>
                <p class="mt-2 text-sm text-label">Laporan duplikasi buang waktu dan menunda perbaikan.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="grid size-6 place-items-center rounded-lg bg-red-50 text-red-600">
                            <x-auth.icon name="alert" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Laporan ganda menumpuk</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Beberapa reporter melaporkan masalah yang sama secara terpisah, sehingga antrean penanganan menjadi berlebih.
                    </p>
                </div>
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="grid size-6 place-items-center rounded-lg bg-amber-50 text-amber-600">
                            <x-auth.icon name="alert" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Triage Developer lambat</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Tanpa indikator kemiripan, Developer harus membaca satu per satu untuk menentukan apakah dua laporan adalah masalah yang sama.
                    </p>
                </div>
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="grid size-6 place-items-center rounded-lg bg-gold-50 text-ochre">
                            <x-auth.icon name="search" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Satu bug, banyak tempat</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Masalah yang sama dilaporkan di berbagai modul dan sulit dilacak karena tersebar di banyak tiket yang tidak terkait.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== FITUR ======================== --}}
    <section id="fitur" class="py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center lg:text-left max-w-2xl lg:max-w-none lg:mb-12">
                <h2 class="font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">Fitur</h2>
                <p class="mt-2 text-sm text-label">Setiap fitur dirancang untuk mengurangi laporan duplikasi dan mempercepat penanganan.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="search" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Deteksi laporan serupa berbasis teks</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Sistem membandingkan judul dan deskripsi laporan baru dengan laporan sebelumnya, baik dalam Bahasa Indonesia maupun bahasa Inggris.
                    </p>
                </div>
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="eye" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Teks pada screenshot dibaca otomatis (OCR)</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Teks yang ada pada screenshot diekstrak dan ikut dibandingkan dengan laporan lain untuk deteksi yang lebih akurat.
                    </p>
                </div>
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="filter" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Screenshot hampir identik ditandai</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Laporan dengan screenshot visual yang hampir sama ditandai sebagai potensi duplikasi, bahkan jika teksnya berbeda.
                    </p>
                </div>
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="code" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Saran Top-K dengan skor kemiripan</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Hasil deteksi berupa daftar laporan serupa dengan skor kemiripan. Keputusan akhir apakah dua laporan adalah duplikasi tetap di tangan Developer.
                    </p>
                </div>
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="key" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Data dipisah per aplikasi</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Setiap aplikasi memiliki antrean laporan sendiri, sehingga laporan dari satu aplikasi tidak bercampur dengan aplikasi lain.
                    </p>
                </div>
                <div class="rounded-xl border border-divider bg-white p-6 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="check" class="size-4" />
                        </span>
                        <h3 class="font-serif text-base font-bold text-ink">Verifikasi akun lewat OTP email</h3>
                    </div>
                    <p class="text-sm text-label leading-relaxed">
                        Setiap pendaftaran diverifikasi melalui kode OTP yang dikirim ke email, sehingga identitas Reporter terjamin.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== CARA KERJA ======================== --}}
    <section id="cara-kerja" class="py-12 sm:py-16 lg:py-20 border-y border-divider">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center lg:text-left max-w-2xl lg:max-w-none lg:mb-12">
                <h2 class="font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">Cara Kerja</h2>
                <p class="mt-2 text-sm text-label">Dari laporan hingga penanganan, dalam tiga langkah.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="grid size-12 place-items-center rounded-full bg-sage-50 text-sage-600 mx-auto mb-4">
                        <span class="font-serif text-xl font-bold text-ink">1</span>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-ink mb-2">Reporter membuat laporan</h3>
                    <p class="text-sm text-label leading-relaxed">
                        Reporter mengisi judul, deskripsi, modul, dan opsional menyertakan screenshot hasil temuan bug.
                    </p>
                </div>
                <div class="text-center">
                    <div class="grid size-12 place-items-center rounded-full bg-sage-50 text-sage-600 mx-auto mb-4">
                        <span class="font-serif text-xl font-bold text-ink">2</span>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-ink mb-2">Sistem menganalisis</h3>
                    <p class="text-sm text-label leading-relaxed">
                        Teks dan screenshot dianalisis untuk mencari laporan serupa yang sudah ada di sistem.
                    </p>
                </div>
                <div class="text-center">
                    <div class="grid size-12 place-items-center rounded-full bg-sage-50 text-sage-600 mx-auto mb-4">
                        <span class="font-serif text-xl font-bold text-ink">3</span>
                    </div>
                    <h3 class="font-serif text-lg font-bold text-ink mb-2">Developer meninjau</h3>
                    <p class="text-sm text-label leading-relaxed">
                        Developer melihat saran laporan serupa dengan skor kemiripan dan memutuskan apakah akan ditandai sebagai duplikasi.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== PERAN ======================== --}}
    <section id="peran" class="py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="rounded-xl border border-divider bg-white p-8 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="bug" class="size-4" />
                        </span>
                        <h3 class="font-serif text-lg font-bold text-ink">Reporter</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-label">
                        <li class="flex items-start gap-2">
                            <span class="text-sage-500">•</span>
                            Melaporkan bug dengan judul, deskripsi, dan langkah reproduksi.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-sage-500">•</span>
                            Mengunggah screenshot sebagai bukti visual.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-sage-500">•</span>
                            Memantau status laporan yang telah dikirim.
                        </li>
                    </ul>
                </div>
                <div class="rounded-xl border border-divider bg-white p-8 shadow-card">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                            <x-auth.icon name="code" class="size-4" />
                        </span>
                        <h3 class="font-serif text-lg font-bold text-ink">Developer</h3>
                    </div>
                    <ul class="space-y-3 text-sm text-label">
                        <li class="flex items-start gap-2">
                            <span class="text-sage-500">•</span>
                            Meninjau saran laporan serupa dengan skor kemiripan.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-sage-500">•</span>
                            Menandai laporan sebagai duplikasi jika memang sama.
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-sage-500">•</span>
                            Memperbarui status penanganan laporan.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== CTA BAND ======================== --}}
    <section class="py-12 sm:py-16 lg:py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-xl border border-divider bg-sage-50 p-8 sm:p-12 text-center">
                <h2 class="font-serif text-2xl font-bold tracking-tight text-ink sm:text-3xl">Mulai laporkan bug dengan lebih rapi</h2>
                <p class="mt-2 text-sm text-label max-w-md mx-auto">
                    Buat akun dan mulai laporan bug yang akan dianalisis sistem TwinBug untuk deteksi duplikasi.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    <a
                        href="{{ route('register') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-sage-500 px-6 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                    >
                        Daftar
                    </a>
                    <a
                        href="{{ route('login') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-divider bg-white px-6 py-3 text-sm font-bold text-ink shadow-sm transition hover:bg-input active:scale-95"
                    >
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.landing>
