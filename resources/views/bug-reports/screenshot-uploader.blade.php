{{--
    Lampiran Screenshot — kartu unggah screenshot.

    File ini berdiri sendiri: markup + script ada di satu tempat, jadi tidak
    bergantung pada @push('scripts') / @stack('scripts') di layout.

    Dipakai di: resources/views/bug-reports/create.blade.php
--}}
<div class="col-span-12 rounded-3xl bg-white p-8 shadow-sm" id="screenshot-section">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="flex items-center gap-2.5">
            <span class="grid size-7 place-items-center rounded-2xl bg-divider font-serif text-xs font-bold text-sage-500">03</span>
            <div>
                <h2 class="font-serif text-xl font-bold text-ink">Lampiran Screenshot</h2>
                <p class="mt-0.5 text-xs text-label">Tambahkan tangkapan layar yang memperlihatkan bug untuk membantu analisis visual TwinBug.</p>
            </div>
        </div>
        <span class="shrink-0 rounded-full bg-divider px-2.5 py-1 text-[10px] font-semibold uppercase tracking-wide text-label">Opsional</span>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-5">

        {{-- Drop zone & preview --}}
        <div class="lg:col-span-3">
            <div
                id="drop-zone"
                class="mb-4 flex cursor-pointer flex-col items-center justify-center rounded-3xl bg-input px-6 py-10 transition hover:bg-input-hover"
                role="button"
                tabindex="0"
                aria-label="Klik, seret, atau tempel gambar untuk melampirkan screenshot"
                onclick="document.getElementById('screenshot').click()"
            >
                <div class="mb-3 grid size-12 place-items-center rounded-3xl bg-white shadow-sm text-sage-500">
                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M4 14.899A7 7 0 1 1 15.71 8h1.79a4.5 4.5 0 0 1 2.5 8.242"/>
                        <path d="M12 12v9"/>
                        <path d="m16 16-4-4-4 4"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-ink">Seret, tempel, atau pilih screenshot</p>
                <p class="mt-1 text-xs font-bold text-sage-500">Klik untuk memilih berkas &middot; Ctrl/&#8984; + V untuk tempel</p>
                <p class="mt-1.5 text-[10px] text-label">PNG, JPG, GIF, WebP &middot; maksimal 5MB &middot; minimal 720p</p>
            </div>

            <input
                type="file"
                id="screenshot"
                name="screenshot"
                accept="image/png,image/jpeg,image/gif,image/webp"
                class="sr-only"
                aria-label="Unggah screenshot"
            >

            {{-- Preview (hidden by default, shown via JS) --}}
            <div id="screenshot-preview" class="mb-4 hidden rounded-3xl bg-input p-4">
                {{-- Full screenshot preview --}}
                <div class="relative overflow-hidden rounded-2xl bg-divider">
                    <img
                        id="preview-img"
                        alt="Preview screenshot yang dilampirkan"
                        class="mx-auto max-h-80 w-full object-contain"
                        decoding="async"
                    >
                    <span id="preview-ext" class="absolute right-2 top-2 rounded-full bg-ink/70 px-2 py-0.5 font-mono text-[9px] font-semibold text-parchment/90">PNG</span>
                </div>

                {{-- Meta & actions --}}
                <div class="mt-3 flex flex-wrap items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-sage-600">
                            <x-auth.icon name="check" class="size-3" />
                            File terlampir
                        </p>
                        <p id="preview-name" class="mt-0.5 truncate font-serif text-sm font-semibold text-ink"></p>
                        <p id="preview-meta" class="mt-1 font-mono text-[11px] text-label"></p>
                    </div>
                    <div class="flex shrink-0 items-center gap-4 pt-1">
                        <button
                            type="button"
                            id="btn-replace"
                            class="flex items-center gap-1 text-[11px] font-semibold text-sage-500 hover:underline"
                            onclick="document.getElementById('screenshot').click()"
                        >
                            <x-auth.icon name="eye" class="size-2.5" />
                            Ganti file
                        </button>
                        <button
                            type="button"
                            id="btn-remove"
                            class="flex items-center gap-1 text-[11px] font-semibold text-danger hover:underline"
                        >
                            <x-auth.icon name="alert" class="size-2.5" />
                            Hapus lampiran
                        </button>
                    </div>
                </div>
            </div>

            @error('screenshot')
                <p class="mb-3 text-xs font-medium text-danger" role="alert">{{ $message }}</p>
            @enderror

            <p id="screenshot-client-error" class="mb-3 hidden text-xs font-medium text-danger" role="alert" aria-live="polite"></p>
        </div>

        {{-- Info lampiran --}}
        <div class="flex flex-col gap-3 lg:col-span-2">
            <div class="rounded-2xl bg-input p-4">
                <p class="text-xs font-semibold text-ink">Cara melampirkan file</p>
                <ul class="mt-2.5 space-y-2 text-[11px] leading-relaxed text-label">
                    <li class="flex gap-2">
                        <span class="mt-1.5 size-1 shrink-0 rounded-full bg-sage-500"></span>
                        <span><strong class="font-semibold text-ink">Pilih berkas</strong> dengan mengklik area unggah.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 size-1 shrink-0 rounded-full bg-sage-500"></span>
                        <span><strong class="font-semibold text-ink">Seret &amp; lepas</strong> file gambar ke area unggah.</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="mt-1.5 size-1 shrink-0 rounded-full bg-sage-500"></span>
                        <span><strong class="font-semibold text-ink">Tempel</strong> screenshot dari clipboard dengan Ctrl/&#8984; + V.</span>
                    </li>
                </ul>
            </div>

            <div class="rounded-2xl bg-input p-4">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold text-ink">Format didukung</p>
                    <span class="font-mono text-[10px] text-label">Maks. 5MB</span>
                </div>
                <div class="mt-2.5 flex flex-wrap gap-1.5">
                    @foreach (['PNG', 'JPG', 'GIF', 'WebP'] as $format)
                        <span class="rounded-full bg-white px-2 py-0.5 font-mono text-[10px] font-semibold text-sage-700">{{ $format }}</span>
                    @endforeach
                </div>
            </div>

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

{{-- Script ditaruh inline di file ini supaya selalu ikut ter-render. --}}
<script>
(function () {
    'use strict';

    const MAX_BYTES = 5 * 1024 * 1024;
    const ALLOWED_TYPES = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];

    const form = document.getElementById('bug-report-form');
    const input = document.getElementById('screenshot');
    const dropZone = document.getElementById('drop-zone');
    const preview = document.getElementById('screenshot-preview');
    const previewImg = document.getElementById('preview-img');
    const previewName = document.getElementById('preview-name');
    const previewMeta = document.getElementById('preview-meta');
    const previewExt = document.getElementById('preview-ext');
    const btnRemove = document.getElementById('btn-remove');
    const clientError = document.getElementById('screenshot-client-error');

    if (!form || !input || !dropZone || !preview) {
        return;
    }

    function formatBytes(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function showClientError(message) {
        clientError.textContent = message;
        clientError.classList.remove('hidden');
    }

    function clearClientError() {
        clientError.textContent = '';
        clientError.classList.add('hidden');
    }

    function validateFile(file) {
        if (!ALLOWED_TYPES.includes(file.type)) {
            return 'Format screenshot harus PNG, JPG, GIF, atau WebP.';
        }

        if (file.size > MAX_BYTES) {
            return 'Ukuran screenshot tidak boleh melebihi 5MB.';
        }

        return null;
    }

    function resetPreview() {
        input.value = '';
        previewImg.removeAttribute('src');
        previewName.textContent = '';
        previewMeta.textContent = '';
        previewExt.textContent = '';
        preview.classList.add('hidden');
        dropZone.classList.remove('hidden');
    }

    function handleFile(file) {
        const error = validateFile(file);

        if (error) {
            resetPreview();
            showClientError(error);
            return;
        }

        clearClientError();

        const name = file.name || 'screenshot.png';
        const extension = name.includes('.') ? name.split('.').pop().toUpperCase() : 'PNG';

        previewName.textContent = name;
        previewExt.textContent = extension;

        const reader = new FileReader();
        reader.onload = function (event) {
            previewImg.src = event.target.result;

            const image = new Image();
            image.onload = function () {
                previewMeta.textContent = formatBytes(file.size) + ' • ' + image.width + '×' + image.height;
            };
            image.src = event.target.result;
        };
        reader.readAsDataURL(file);

        preview.classList.remove('hidden');
        dropZone.classList.add('hidden');
    }

    function attachFile(file) {
        if (!validateFile(file)) {
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            input.files = dataTransfer.files;
        }

        handleFile(file);
    }

    input.addEventListener('change', function () {
        if (input.files[0]) {
            handleFile(input.files[0]);
        }
    });

    btnRemove.addEventListener('click', function () {
        resetPreview();
        clearClientError();
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
        e.stopPropagation();

        const file = e.dataTransfer.files[0];

        if (file) {
            attachFile(file);
        }
    });

    // Cegah browser membuka file kalau screenshot dijatuhkan di luar drop zone
    function isFileDrag(e) {
        return e.dataTransfer
            && Array.from(e.dataTransfer.types || []).includes('Files');
    }

    document.addEventListener('dragover', function (e) {
        if (isFileDrag(e)) {
            e.preventDefault();
        }
    });

    document.addEventListener('drop', function (e) {
        if (isFileDrag(e) && !dropZone.contains(e.target)) {
            e.preventDefault();
        }
    });

    // Tempel screenshot langsung dari clipboard (Ctrl/Command + V)
    document.addEventListener('paste', function (e) {
        const items = e.clipboardData ? e.clipboardData.items : null;

        if (!items) {
            return;
        }

        const imageItem = Array.from(items).find(function (item) {
            return item.kind === 'file' && item.type.startsWith('image/');
        });

        if (!imageItem) {
            return;
        }

        const file = imageItem.getAsFile();

        if (!file) {
            return;
        }

        e.preventDefault();
        attachFile(file);
        preview.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    });

    form.addEventListener('submit', function (e) {
        const file = input.files[0];
        const error = file ? validateFile(file) : null;

        if (error) {
            e.preventDefault();
            showClientError(error);
        }
    });

    // Keyboard accessibility for drop zone
    dropZone.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            input.click();
        }
    });
})();
</script>
