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

                    {{-- CARD 3: Screenshot Upload --}}
                    <div class="rounded-3xl bg-white p-6 shadow-sm">
                        <div class="mb-5 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="grid size-7 place-items-center rounded-2xl bg-divider font-serif text-xs font-bold text-sage-500">03</span>
                                <h2 class="font-serif text-lg font-bold text-ink">Tangkapan Layar UI</h2>
                            </div>
                            <span class="rounded-full bg-divider px-2 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-label">Opsional</span>
                        </div>

                        {{-- Drop zone --}}
                        <div
                            id="drop-zone"
                            class="relative mb-4 flex cursor-pointer flex-col items-center justify-center rounded-3xl bg-input px-6 py-8 transition hover:bg-input-hover"
                            role="button"
                            tabindex="0"
                            aria-label="Klik atau seret file untuk mengunggah screenshot"
                        >
                            <div class="mb-3 grid size-12 place-items-center rounded-3xl bg-white shadow-sm text-sage-500">
                                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                                    <path d="M12 12v9"/>
                                    <path d="m16 16-4-4-4 4"/>
                                </svg>
                            </div>
                            <p class="text-xs font-semibold text-ink">Seret tangkapan layar ke sini</p>
                            <p class="mt-0.5 text-[11px] font-bold text-sage-500">atau Pilih Berkas (PNG, JPG hingga 5MB)</p>
                            <p class="mt-1 text-[10px] text-label">Rekomendasi resolusi minimal 720p</p>
                            <input
                                type="file"
                                id="screenshot"
                                name="screenshot"
                                accept="image/png,image/jpeg,image/gif,image/webp"
                                class="absolute inset-0 cursor-pointer opacity-0"
                                aria-label="Unggah screenshot"
                            >
                        </div>

                        {{-- Preview (hidden by default, shown via JS) --}}
                        <div id="screenshot-preview" class="mb-4 hidden rounded-3xl bg-input p-3.5">
                            <div class="flex items-start gap-3">
                                {{-- Thumbnail --}}
                                <div class="relative size-16 shrink-0 overflow-hidden rounded-2xl bg-divider">
                                    <img id="preview-img" src="" alt="Preview screenshot" class="size-full object-cover">
                                    <div class="absolute inset-x-0 bottom-0 flex justify-center bg-ink/60 py-0.5">
                                        <span id="preview-ext" class="font-mono text-[9px] text-parchment/90">PNG</span>
                                    </div>
                                </div>

                                {{-- Meta --}}
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <p id="preview-name" class="truncate font-serif text-xs font-semibold text-ink"></p>
                                        <x-auth.icon name="check" class="size-3.5 shrink-0 text-sage-500" />
                                    </div>
                                    <p id="preview-meta" class="mt-0.5 font-mono text-[11px] text-label"></p>
                                    <div class="mt-2 flex items-center gap-3">
                                        <button
                                            type="button"
                                            id="btn-replace"
                                            class="flex items-center gap-1 text-[11px] font-semibold text-sage-500 hover:underline"
                                            onclick="document.getElementById('screenshot').click()"
                                        >
                                            <x-auth.icon name="eye" class="size-2.5" />
                                            Ganti
                                        </button>
                                        <button
                                            type="button"
                                            id="btn-remove"
                                            class="flex items-center gap-1 text-[11px] font-semibold text-danger hover:underline"
                                        >
                                            <x-auth.icon name="alert" class="size-2.5" />
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @error('screenshot')
                            <p class="mb-3 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
                        @enderror

                        {{-- Fallback note --}}
                        <div class="flex items-start gap-2.5 rounded-2xl bg-gold-100/60 p-3">
                            <span class="mt-0.5 shrink-0 text-ochre">
                                <x-auth.icon name="alert" class="size-4" />
                            </span>
                            <p class="text-xs font-semibold leading-snug text-ink">
                                Catatan: Laporan tanpa gambar tetap didukung penuh menggunakan kemiripan teks semantik.
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

</x-layouts.app>

@push('scripts')
<script>
(function () {
    'use strict';

    const input = document.getElementById('screenshot');
    const dropZone = document.getElementById('drop-zone');
    const preview = document.getElementById('screenshot-preview');
    const previewImg = document.getElementById('preview-img');
    const previewName = document.getElementById('preview-name');
    const previewMeta = document.getElementById('preview-meta');
    const previewExt = document.getElementById('preview-ext');
    const btnRemove = document.getElementById('btn-remove');

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function handleFile(file) {
        if (!file || !file.type.startsWith('image/')) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            previewImg.src = e.target.result;

            const img = new Image();
            img.onload = function () {
                previewMeta.textContent = formatBytes(file.size) + ' • ' + img.width + '×' + img.height;
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);

        previewName.textContent = file.name;
        previewExt.textContent = file.name.split('.').pop().toUpperCase();
        preview.classList.remove('hidden');
        dropZone.classList.add('hidden');
    }

    input.addEventListener('change', function () {
        if (this.files[0]) handleFile(this.files[0]);
    });

    btnRemove.addEventListener('click', function () {
        input.value = '';
        preview.classList.add('hidden');
        dropZone.classList.remove('hidden');
        previewImg.src = '';
    });

    // Drag-and-drop
    ['dragenter', 'dragover'].forEach(function (event) {
        dropZone.addEventListener(event, function (e) {
            e.preventDefault();
            dropZone.classList.add('ring-2', 'ring-sage-500/50', 'bg-sage-50');
        });
    });

    ['dragleave', 'drop'].forEach(function (event) {
        dropZone.addEventListener(event, function (e) {
            e.preventDefault();
            dropZone.classList.remove('ring-2', 'ring-sage-500/50', 'bg-sage-50');
        });
    });

    dropZone.addEventListener('drop', function (e) {
        const file = e.dataTransfer.files[0];
        if (file) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
            handleFile(file);
        }
    });

    // Keyboard accessibility for drop zone
    dropZone.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            input.click();
        }
    });

    // Character counter initial sync for old() values
    const desc = document.getElementById('description');
    if (desc && desc.value) {
        document.getElementById('char-count').textContent = desc.value.length + ' karakter';
    }
})();
</script>
@endpush
