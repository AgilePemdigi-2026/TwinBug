<x-layouts.app title="Buat Laporan Bug" meta-description="Laporkan bug baru ke sistem TwinBug untuk dianalisis secara otomatis.">

    <div class="mx-auto max-w-screen-xl px-12 py-10">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="mb-8 flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-full bg-divider px-2.5 py-1 text-[11px] font-semibold text-ink">Buat Laporan Bug Baru</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="mb-10 flex items-end justify-between gap-8">
            <div class="max-w-2xl">
                <h1 class="font-serif text-5xl font-bold tracking-tight text-ink" style="letter-spacing: -1.2px;">
                    Formulir Pelaporan Bug
                </h1>
                <p class="mt-3 text-lg leading-7 text-label">
                    Sistem multimodal TwinBug akan otomatis menganalisis kemiripan semantik teks dan visual screenshot
                    dengan arsip historis begitu laporan dikirimkan.
                </p>
                <span class="mt-3 inline-flex items-center gap-1.5 rounded-full bg-sage-100 px-3 py-1 text-xs font-semibold text-sage-700">
                    <x-auth.icon name="code" class="size-3.5" />
                    {{ $application->name }}
                </span>
            </div>

            {{-- Quick Metametric Badge --}}
            <div class="flex shrink-0 items-center gap-3 rounded-3xl bg-input px-5 py-3.5 shadow-sm">
                <span class="grid size-10 place-items-center rounded-2xl bg-sage-200 text-sage-700">
                    <x-auth.icon name="sparkles" class="size-5" />
                </span>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-label">Arsip Aktif</p>
                    <p class="font-serif text-base font-bold text-ink">{{ number_format($totalReports) }} Laporan Tersinkronisasi</p>
                </div>
            </div>
        </div>

        {{-- ======================== FORM ======================== --}}
        <form
            id="bug-report-form"
            method="POST"
            action="{{ route('bug-reports.store') }}"
            enctype="multipart/form-data"
            novalidate
        >
            @csrf

            {{-- Auto-captured client device metadata --}}
            <input type="hidden" name="device_metadata[browser]" id="meta_browser" value="">
            <input type="hidden" name="device_metadata[os]" id="meta_os" value="">
            <input type="hidden" name="device_metadata[screen]" id="meta_screen" value="">
            <input type="hidden" name="device_metadata[user_agent]" id="meta_user_agent" value="">

            <div class="grid grid-cols-12 gap-6">

                {{-- =============== COLUMN LEFT (7 cols) =============== --}}
                <div class="col-span-12 flex flex-col gap-6 lg:col-span-7">

                    {{-- CARD 1: Primary Details --}}
                    <div class="rounded-3xl bg-white p-8 shadow-sm">
                        {{-- Card header --}}
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="grid size-7 place-items-center rounded-2xl bg-divider font-serif text-xs font-bold text-sage-500">01</span>
                                <h2 class="font-serif text-xl font-bold text-ink">Identitas Permasalahan</h2>
                            </div>
                            <span class="flex items-center gap-1.5 text-xs font-semibold text-danger">
                                <span class="size-1.5 rounded-full bg-danger"></span>
                                Wajib diisi
                            </span>
                        </div>

                        {{-- Bug Title --}}
                        <div class="mb-6">
                            <label for="title" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                Judul Bug (Bug Title) <span class="text-danger">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder='Tombol "Bayar Sekarang" tidak merespons di iOS Safari saat checkout'
                                    maxlength="255"
                                    class="w-full rounded-3xl bg-input px-4 py-3.5 pr-12 text-base text-ink placeholder:text-label/50 transition focus:outline-none focus:ring-2 focus:ring-sage-500/40 {{ $errors->has('title') ? 'ring-2 ring-danger/40' : '' }}"
                                    aria-describedby="title-hint title-error"
                                    autocomplete="off"
                                >
                                <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-label/60">
                                    <x-auth.icon name="code" class="size-4" />
                                </span>
                            </div>
                            <p id="title-hint" class="mt-1.5 text-xs text-label">
                                Sertakan komponen visual utama dan platform agar parser TwinBug mengenali konteks dengan presisi.
                            </p>
                            @error('title')
                                <p id="title-error" class="mt-1 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
                            @enderror

                            {{-- Real-time Duplicate Warning Box --}}
                            <div id="duplicate-warning-box" class="mt-4 hidden rounded-2xl border border-purple-200 bg-purple-50/90 p-4 transition">
                                <div class="flex items-start gap-3">
                                    <span class="grid size-7 shrink-0 place-items-center rounded-xl bg-purple-200 text-purple-700">
                                        <x-auth.icon name="sparkles" class="size-4" />
                                    </span>
                                    <div class="flex-1">
                                        <h3 class="text-xs font-bold text-purple-900">
                                            TwinBug mendeteksi kemungkinan laporan serupa di aplikasi Anda:
                                        </h3>
                                        <ul id="duplicate-warning-list" class="mt-2 space-y-1.5 text-xs">
                                            {{-- Dynamic items will be appended here --}}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Two-column: Module + Severity --}}
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                            {{-- Module --}}
                            <div>
                                <label for="module" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                    Modul / Komponen Terkait <span class="text-danger">*</span>
                                </label>
                                <div class="relative">
                                    <select
                                        id="module"
                                        name="module"
                                        class="w-full appearance-none rounded-3xl bg-input px-4 py-3.5 pr-10 text-base text-ink transition focus:outline-none focus:ring-2 focus:ring-sage-500/40 {{ $errors->has('module') ? 'ring-2 ring-danger/40' : '' }}"
                                        aria-describedby="module-error"
                                    >
                                        <option value="" disabled {{ old('module') ? '' : 'selected' }}>Pilih modul aplikasi...</option>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module }}" {{ old('module') === $module ? 'selected' : '' }}>
                                                {{ $module }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-label/60">
                                        <svg class="size-2.5" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </div>
                                @error('module')
                                    <p id="module-error" class="mt-1 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Severity --}}
                            <div>
                                <label for="severity" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                    Tingkat Keparahan <span class="text-danger">*</span>
                                </label>
                                <div class="relative">
                                    <select
                                        id="severity"
                                        name="severity"
                                        class="w-full appearance-none rounded-3xl bg-input px-4 py-3.5 pr-10 text-base text-ink transition focus:outline-none focus:ring-2 focus:ring-sage-500/40 {{ $errors->has('severity') ? 'ring-2 ring-danger/40' : '' }}"
                                        aria-describedby="severity-error"
                                    >
                                        <option value="" disabled {{ old('severity') ? '' : 'selected' }}>Pilih keparahan...</option>
                                        @foreach ($severities as $severity)
                                            <option value="{{ $severity->value }}" {{ old('severity') === $severity->value ? 'selected' : '' }}>
                                                {{ $severity->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-label/60">
                                        <svg class="size-2.5" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </div>
                                @error('severity')
                                    <p id="severity-error" class="mt-1 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- Two-column: Environment + URL --}}
                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="environment" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                    Environment / Lingkungan
                                </label>
                                <div class="relative">
                                    <select
                                        id="environment"
                                        name="environment"
                                        class="w-full appearance-none rounded-3xl bg-input px-4 py-3.5 pr-10 text-base text-ink transition focus:outline-none focus:ring-2 focus:ring-sage-500/40"
                                    >
                                        <option value="production" {{ old('environment', 'production') === 'production' ? 'selected' : '' }}>Production (Live)</option>
                                        <option value="staging" {{ old('environment') === 'staging' ? 'selected' : '' }}>Staging (UAT)</option>
                                        <option value="testing" {{ old('environment') === 'testing' ? 'selected' : '' }}>Testing / QA</option>
                                        <option value="local" {{ old('environment') === 'local' ? 'selected' : '' }}>Local / Dev</option>
                                    </select>
                                    <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-label/60">
                                        <svg class="size-2.5" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                                            <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <div>
                                <label for="url" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                    URL Halaman Kejadian (Opsional)
                                </label>
                                <input
                                    type="text"
                                    id="url"
                                    name="url"
                                    value="{{ old('url') }}"
                                    placeholder="https://app.example.com/checkout"
                                    class="w-full rounded-3xl bg-input px-4 py-3.5 text-base text-ink placeholder:text-label/50 transition focus:outline-none focus:ring-2 focus:ring-sage-500/40"
                                >
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: Structured Reproduction Details (Metadata) --}}
                    <div class="rounded-3xl bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="grid size-7 place-items-center rounded-2xl bg-divider font-serif text-xs font-bold text-sage-500">02</span>
                                <h2 class="font-serif text-xl font-bold text-ink">Metadata &amp; Langkah Reproduksi</h2>
                            </div>
                            <span class="text-xs font-semibold text-label">Sangat Disarankan</span>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="steps_to_reproduce" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                    Langkah-langkah Reproduksi (Steps to Reproduce)
                                </label>
                                <textarea
                                    id="steps_to_reproduce"
                                    name="steps_to_reproduce"
                                    rows="4"
                                    placeholder="1. Masuk ke keranjang belanja&#10;2. Pilih metode bayar QRIS&#10;3. Klik tombol konfirmasi bayar"
                                    class="w-full rounded-3xl bg-input px-4 py-3 text-sm leading-relaxed text-ink placeholder:text-label/40 transition focus:outline-none focus:ring-2 focus:ring-sage-500/40"
                                >{{ old('steps_to_reproduce') }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="expected_result" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                        Hasil yang Diharapkan
                                    </label>
                                    <textarea
                                        id="expected_result"
                                        name="expected_result"
                                        rows="3"
                                        placeholder="Kode QRIS muncul dan timer pembayaran berjalan."
                                        class="w-full rounded-3xl bg-input px-4 py-3 text-sm leading-relaxed text-ink placeholder:text-label/40 transition focus:outline-none focus:ring-2 focus:ring-sage-500/40"
                                    >{{ old('expected_result') }}</textarea>
                                </div>

                                <div>
                                    <label for="actual_result" class="mb-2 block font-serif text-sm font-semibold text-ink">
                                        Hasil Aktual (Yang Terjadi)
                                    </label>
                                    <textarea
                                        id="actual_result"
                                        name="actual_result"
                                        rows="3"
                                        placeholder="Layar blank putih dan tidak merespons klik."
                                        class="w-full rounded-3xl bg-input px-4 py-3 text-sm leading-relaxed text-ink placeholder:text-label/40 transition focus:outline-none focus:ring-2 focus:ring-sage-500/40"
                                    >{{ old('actual_result') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: Deep Bug Description --}}
                    <div class="rounded-3xl bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="grid size-7 place-items-center rounded-2xl bg-divider font-serif text-xs font-bold text-sage-500">03</span>
                                <h2 class="font-serif text-xl font-bold text-ink">Deskripsi Lengkap Bug <span class="text-danger">*</span></h2>
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <p class="text-xs text-label">Uraikan kronologi, pesan error, atau catatan tambahan:</p>
                                <span id="char-count" class="font-mono text-[11px] text-label">0 karakter</span>
                            </div>

                            <textarea
                                id="description"
                                name="description"
                                rows="8"
                                maxlength="10000"
                                placeholder="Jelaskan detail kronologi kendala atau lampirkan informasi tambahan yang relevan."
                                class="w-full resize-none overflow-y-auto rounded-3xl bg-input px-4 py-4 text-sm leading-relaxed text-ink placeholder:text-label/40 transition focus:outline-none focus:ring-2 focus:ring-sage-500/40 {{ $errors->has('description') ? 'ring-2 ring-danger/40' : '' }}"
                                aria-describedby="description-hint description-error"
                                oninput="document.getElementById('char-count').textContent = this.value.length + ' karakter'"
                            >{{ old('description') }}</textarea>

                            <p id="description-hint" class="mt-2 text-xs text-label">
                                Minimal 20 karakter agar analisis semantik TwinBug optimal.
                            </p>
                            @error('description')
                                <p id="description-error" class="mt-1 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- =============== COLUMN RIGHT (5 cols) =============== --}}
                <div class="col-span-12 flex flex-col gap-6 lg:col-span-5">

                    @include('bug-reports.screenshot-uploader')

                    {{-- Panduan Pelaporan Efektif --}}
                    <div class="rounded-3xl border border-sage-200 bg-sage-50/60 p-6 shadow-sm">
                        <div class="mb-4 flex items-center gap-2.5">
                            <span class="grid size-7 place-items-center rounded-2xl bg-sage-200 text-sage-700">
                                <x-auth.icon name="sparkles" class="size-3.5" />
                            </span>
                            <h3 class="text-sm font-bold text-sage-900">Panduan Laporan Efektif</h3>
                        </div>

                        <ul class="space-y-3">
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-sage-200 text-[9px] font-bold text-sage-800">1</span>
                                <div>
                                    <p class="text-xs font-semibold text-ink">Judul yang spesifik</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-label">Sebutkan komponen, platform, dan tindakan. Contoh: <span class="font-medium text-ink">"Tombol QRIS tidak merespons di iOS Safari"</span></p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-sage-200 text-[9px] font-bold text-sage-800">2</span>
                                <div>
                                    <p class="text-xs font-semibold text-ink">Pilih modul yang tepat</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-label">Memudahkan developer menemukan lokasi kode dan mempercepat triase bug.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-sage-200 text-[9px] font-bold text-sage-800">3</span>
                                <div>
                                    <p class="text-xs font-semibold text-ink">Langkah reproduksi rinci</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-label">Tulis langkah langkah-per-langkah. Bug yang bisa direproduksi lebih cepat diperbaiki.</p>
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full bg-sage-200 text-[9px] font-bold text-sage-800">4</span>
                                <div>
                                    <p class="text-xs font-semibold text-ink">Sertakan pesan error konsol</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-label">Buka DevTools → Console, salin pesan merah dan tempel di deskripsi.</p>
                                </div>
                            </li>
                        </ul>

                        <div class="mt-5 rounded-2xl border border-sage-200 bg-white/70 p-3">
                            <p class="flex items-center gap-1.5 text-[11px] font-semibold text-sage-700">
                                <x-auth.icon name="shield" class="size-3.5 shrink-0" />
                                TwinBug mendeteksi laporan serupa secara otomatis — periksa peringatan yang muncul saat Anda mengetik judul.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ======================== STICKY ACTION BAR ======================== --}}
            <div class="mt-6 rounded-3xl bg-white/95 p-5 shadow-[0_10px_15px_-3px_rgba(0,0,0,0.08),0_4px_6px_-4px_rgba(0,0,0,0.08)] backdrop-blur-sm">
                <div class="flex items-center justify-between gap-4">

                    {{-- Left: privacy note --}}
                    <div class="flex items-center gap-2 text-label">
                        <x-auth.icon name="shield" class="size-4 shrink-0 text-sage-500" />
                        <p class="text-xs">
                            Semua input dienkripsi dan diproses secara aman dalam klaster lokal.
                        </p>
                    </div>

                    {{-- Right: buttons --}}
                    <div class="flex items-center gap-3">
                        <a
                            href="{{ route('dashboard') }}"
                            id="btn-cancel"
                            class="rounded-3xl bg-divider px-6 py-2.5 text-sm font-bold text-label transition hover:bg-input-hover hover:text-ink"
                        >
                            Batal
                        </a>
                        <button
                            type="submit"
                            id="btn-submit"
                            class="group relative flex items-center gap-2.5 overflow-hidden rounded-3xl bg-sage-500 px-7 py-3 text-sm font-bold text-white shadow-[0_4px_6px_-1px_rgba(74,124,89,0.25),0_2px_4px_-2px_rgba(74,124,89,0.2)] transition hover:bg-sage-600 active:scale-95"
                        >
                            <x-auth.icon name="sparkles" class="size-5 shrink-0" />
                            <span>Kirim &amp; Analisis Duplikasi</span>
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    {{-- Script for auto-capturing client metadata and real-time twin bug detection --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Capture Client Metadata
            try {
                const ua = navigator.userAgent;
                let browser = 'Unknown';
                let os = 'Unknown';

                if (ua.indexOf('Chrome') > -1) browser = 'Chrome';
                else if (ua.indexOf('Safari') > -1) browser = 'Safari';
                else if (ua.indexOf('Firefox') > -1) browser = 'Firefox';
                else if (ua.indexOf('Edge') > -1) browser = 'Edge';

                if (ua.indexOf('Win') > -1) os = 'Windows';
                else if (ua.indexOf('Mac') > -1) os = 'macOS';
                else if (ua.indexOf('Linux') > -1) os = 'Linux';
                else if (ua.indexOf('Android') > -1) os = 'Android';
                else if (ua.indexOf('like Mac') > -1) os = 'iOS';

                const screenDim = window.screen ? `${window.screen.width}×${window.screen.height}` : '';

                const metaBrowser = document.getElementById('meta_browser');
                const metaOs = document.getElementById('meta_os');
                const metaScreen = document.getElementById('meta_screen');
                const metaUa = document.getElementById('meta_user_agent');

                if (metaBrowser) metaBrowser.value = browser;
                if (metaOs) metaOs.value = os;
                if (metaScreen) metaScreen.value = screenDim;
                if (metaUa) metaUa.value = ua;
            } catch (e) {
                // Ignore metadata capture errors gracefully
            }

            // 2. Real-time Duplicate Detection
            const titleInput = document.getElementById('title');
            const moduleSelect = document.getElementById('module');
            const warningBox = document.getElementById('duplicate-warning-box');
            const warningList = document.getElementById('duplicate-warning-list');
            let debounceTimer = null;

            function checkDuplicates() {
                const titleVal = titleInput ? titleInput.value.trim() : '';
                const moduleVal = moduleSelect ? moduleSelect.value : '';

                if (titleVal.length < 4) {
                    if (warningBox) warningBox.classList.add('hidden');
                    return;
                }

                const url = new URL('{{ route('bug-reports.check-similar') }}', window.location.origin);
                url.searchParams.set('title', titleVal);
                if (moduleVal) url.searchParams.set('module', moduleVal);

                fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.similar || data.similar.length === 0) {
                        if (warningBox) warningBox.classList.add('hidden');
                        return;
                    }

                    warningList.innerHTML = '';
                    data.similar.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'flex items-center justify-between gap-2 rounded-xl bg-white/80 px-2.5 py-1.5 shadow-xs';
                        li.innerHTML = `
                            <div class="flex items-center gap-2 truncate">
                                <span class="font-mono font-bold text-purple-700">#${item.id}</span>
                                <span class="truncate font-semibold text-ink">${item.title}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="rounded-full px-2 py-0.5 text-[10px] font-bold ${item.status_classes}">${item.status_label}</span>
                                <a href="${item.url}" target="_blank" class="text-[11px] font-bold text-purple-700 underline hover:text-purple-900">Buka &rarr;</a>
                            </div>
                        `;
                        warningList.appendChild(li);
                    });

                    if (warningBox) warningBox.classList.remove('hidden');
                })
                .catch(() => {
                    // Silently fail on network issues
                });
            }

            if (titleInput) {
                titleInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(checkDuplicates, 450);
                });
            }

            if (moduleSelect) {
                moduleSelect.addEventListener('change', checkDuplicates);
            }
        });
    </script>

</x-layouts.app>
