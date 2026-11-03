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

        {{-- ======================== VALIDATION SUMMARY ======================== --}}
        @if ($errors->any())
            <div class="mb-6 flex items-start gap-3 rounded-2xl border border-danger/20 bg-danger/5 p-4" role="alert">
                <span class="grid size-9 shrink-0 place-items-center rounded-full bg-danger/10 text-danger">
                    <x-auth.icon name="alert" class="size-4" />
                </span>
                <div class="min-w-0 flex-1 pt-0.5">
                    <p class="text-sm font-bold text-ink">Terjadi kesalahan</p>
                    <p class="mt-0.5 text-sm leading-5 text-body">{{ $errors->first() }}</p>
                </div>
            </div>
        @endif

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

            <div class="grid grid-cols-12 gap-6">

                {{-- =============== COLUMN LEFT (8 cols) =============== --}}
                <div class="col-span-12 flex flex-col gap-6 lg:col-span-8">

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
                        </div>

                        {{-- Two-column: Module + Severity --}}
                        <div class="grid grid-cols-2 gap-4">

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
                    </div>

                    {{-- CARD 2: Deep Bug Description --}}
                    <div class="rounded-3xl bg-white p-8 shadow-sm">
                        <div class="mb-6 flex items-center justify-between">
                            <div class="flex items-center gap-2.5">
                                <span class="grid size-7 place-items-center rounded-2xl bg-divider font-serif text-xs font-bold text-sage-500">02</span>
                                <h2 class="font-serif text-xl font-bold text-ink">Deskripsi Lengkap Bug</h2>
                            </div>
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between">
                                <p class="text-xs text-label">Gunakan format terstruktur agar embedding teks menangkap alur eksekusi:</p>
                                <span id="char-count" class="font-mono text-[11px] text-label">0 karakter</span>
                            </div>

                            <textarea
                                id="description"
                                name="description"
                                rows="10"
                                maxlength="10000"
                                placeholder="[LANGKAH REPRODUKSI]&#10;1. Masuk ke menu Keranjang Belanja dengan 1 item aktif&#10;2. Tekan 'Checkout' lalu pilih metode Bank Transfer&#10;3. Klik tombol submit pembayaran&#10;&#10;[PERILAKU AKTUAL]&#10;Aplikasi macet selama 3 detik, tombol tidak dapat ditekan kembali.&#10;&#10;[PERILAKU YANG DIHARAPKAN]&#10;Redirect ke halaman ringkasan order nomor Virtual Account."
                                class="w-full resize-none overflow-y-auto rounded-3xl bg-input px-4 py-4 text-sm leading-relaxed text-ink placeholder:text-label/40 transition focus:outline-none focus:ring-2 focus:ring-sage-500/40 {{ $errors->has('description') ? 'ring-2 ring-danger/40' : '' }}"
                                aria-describedby="description-hint description-error"
                                oninput="document.getElementById('char-count').textContent = this.value.length + ' karakter'"
                            >{{ old('description') }}</textarea>

                            <p id="description-hint" class="mt-2 text-xs text-label">
                                Format: <span class="font-mono text-[11px]">[LANGKAH REPRODUKSI] · [PERILAKU AKTUAL] · [YANG DIHARAPKAN]</span>
                            </p>
                            @error('description')
                                <p id="description-error" class="mt-1 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- =============== COLUMN RIGHT (4 cols) =============== --}}
                <div class="col-span-12 flex flex-col gap-6 lg:col-span-4">

                    {{-- Panduan --}}
                    <div class="rounded-3xl bg-white p-6 shadow-sm">
                        <div class="mb-5 flex items-center gap-2">
                            <span class="grid size-8 place-items-center rounded-2xl bg-sage-200 text-sage-700">
                                <x-auth.icon name="sparkles" class="size-4" />
                            </span>
                            <h2 class="font-serif text-lg font-bold text-ink">Panduan Singkat</h2>
                        </div>

                        <ol class="space-y-4">
                            <li class="flex gap-3">
                                <span class="grid size-6 shrink-0 place-items-center rounded-full bg-sage-100 font-serif text-[11px] font-bold text-sage-700">1</span>
                                <div>
                                    <p class="text-xs font-semibold text-ink">Tulis judul yang spesifik</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-label">Sebutkan komponen dan platform agar bug mudah ditelusuri.</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="grid size-6 shrink-0 place-items-center rounded-full bg-sage-100 font-serif text-[11px] font-bold text-sage-700">2</span>
                                <div>
                                    <p class="text-xs font-semibold text-ink">Jelaskan langkah reproduksi</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-label">Gunakan format langkah, perilaku aktual, dan perilaku yang diharapkan.</p>
                                </div>
                            </li>
                            <li class="flex gap-3">
                                <span class="grid size-6 shrink-0 place-items-center rounded-full bg-sage-100 font-serif text-[11px] font-bold text-sage-700">3</span>
                                <div>
                                    <p class="text-xs font-semibold text-ink">Lampirkan screenshot</p>
                                    <p class="mt-0.5 text-[11px] leading-relaxed text-label">Tambahkan tangkapan layar pada bagian lampiran di bawah formulir.</p>
                                </div>
                            </li>
                        </ol>
                    </div>

                    {{-- Catatan keamanan --}}
                    <div class="rounded-3xl bg-white p-6 shadow-sm">
                        <div class="flex items-start gap-3">
                            <span class="grid size-9 shrink-0 place-items-center rounded-2xl bg-sage-200 text-sage-700">
                                <x-auth.icon name="shield" class="size-4" />
                            </span>
                            <div>
                                <p class="text-xs font-semibold text-ink">Data Anda aman</p>
                                <p class="mt-1 text-[11px] leading-relaxed text-label">Laporan dan lampiran diproses secara aman dalam klaster lokal TwinBug.</p>
                            </div>
                        </div>
                    </div>

                </div>

                @include('bug-reports.partials.screenshot-uploader')
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
    <script>
    (function () {
        'use strict';

        // Sinkronkan penghitung karakter dengan nilai old() saat validasi gagal
        const description = document.getElementById('description');
        const charCount = document.getElementById('char-count');

        if (description && charCount && description.value) {
            charCount.textContent = description.value.length + ' karakter';
        }
    })();
    </script>

</x-layouts.app>
