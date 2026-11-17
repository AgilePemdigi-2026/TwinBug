<x-layouts.app title="Pusat Bantuan & Tanya Jawab (FAQ)">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3 text-sage-600" />
                <span class="font-medium">Beranda</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-md bg-input px-2 py-0.5 text-[11px] font-semibold text-ink">Bantuan &amp; FAQ</span>
        </nav>

        {{-- ======================== HERO / HEADER ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="max-w-2xl">
                <div class="mb-2 flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2.5 py-0.5 text-[11px] font-semibold text-sage-800">
                        <x-auth.icon name="sparkles" class="size-3 text-sage-600" />
                        <span>Pusat Bantuan TwinBug</span>
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="text-xs text-label">{{ count($faqs) }} Topik Panduan</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-ink">
                    Pertanyaan yang Sering Diajukan
                </h1>
                <p class="mt-1 text-xs sm:text-sm text-label">
                    Panduan terpadu mengenai alur registrasi OTP, cara pelaporan bug terstruktur, validasi tangkapan layar, dan deteksi duplikasi cerdas.
                </p>
            </div>

            {{-- Kolom Pencarian Cepat --}}
            <div class="w-full sm:w-80 shrink-0">
                <div class="relative">
                    <label for="faq-search" class="sr-only">Cari pertanyaan</label>
                    <input
                        type="text"
                        id="faq-search"
                        placeholder="Cari topik atau kata kunci..."
                        class="w-full rounded-xl border border-divider/80 bg-white py-2.5 pl-9 pr-9 text-xs sm:text-sm text-ink shadow-2xs transition placeholder:text-label/50 focus:border-sage-500 focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                        autocomplete="off"
                    >
                    <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-label/60">
                        <x-auth.icon name="search" class="size-3.5" />
                    </div>
                    <button
                        type="button"
                        id="faq-search-clear"
                        class="hidden absolute inset-y-0 right-2.5 my-auto size-5 place-items-center rounded-md text-label hover:bg-input hover:text-ink transition"
                        title="Hapus pencarian"
                    >
                        <x-auth.icon name="x" class="size-3" />
                    </button>
                </div>
            </div>
        </div>

        {{-- ======================== 2-COLUMN HELP CENTER GRID ======================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- =============== LEFT SIDEBAR (4 cols): Sticky Categories =============== --}}
            <aside class="space-y-5 lg:col-span-4 lg:sticky lg:top-20 lg:self-start">

                {{-- Kategori Bantuan --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-3.5 flex items-center justify-between">
                        <h2 class="font-serif text-sm font-bold text-ink">Kategori Bantuan</h2>
                        <span class="text-[11px] text-label">Pilih topik</span>
                    </div>

                    <nav class="space-y-1" role="tablist" aria-label="Kategori FAQ">
                        @foreach ($categories as $key => $label)
                            @php
                                $count = $key === 'all' ? count($faqs) : collect($faqs)->where('category', $key)->count();
                            @endphp
                            <button
                                type="button"
                                data-category-filter="{{ $key }}"
                                class="faq-filter-btn flex w-full items-center justify-between rounded-xl px-3 py-2 text-xs font-semibold transition text-left cursor-pointer {{ $key === 'all' ? 'bg-sage-500 text-white shadow-cta' : 'bg-input/60 text-label hover:bg-input hover:text-ink' }}"
                            >
                                <span>{{ $label }}</span>
                                <span class="rounded-md bg-black/10 px-1.5 py-0.5 text-[10px] font-bold">{{ $count }}</span>
                            </button>
                        @endforeach
                    </nav>
                </div>

                {{-- Kartu Bantuan Lanjutan --}}
                <div class="rounded-2xl border border-sage-200/80 bg-sage-50/60 p-5 shadow-xs">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-200/80 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-sage-800">
                        Dukungan Teknis
                    </span>
                    <h3 class="mt-2 font-serif text-base font-bold text-ink">
                        Belum menemukan jawaban?
                    </h3>
                    <p class="mt-1 text-xs leading-relaxed text-label">
                        Jika Anda mendapati kendala teknis pada sistem atau memerlukan bantuan, buat laporan baru atau hubungi tim pengembang secara langsung.
                    </p>

                    <div class="mt-4 flex flex-col gap-2">
                        @auth
                            <a
                                href="{{ route('bug-reports.create') }}"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-sage-500 px-3.5 py-2 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                            >
                                <x-auth.icon name="bug" class="size-3.5" />
                                <span>+ Buat Laporan Bug</span>
                            </a>
                        @else
                            <a
                                href="{{ route('login') }}"
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-sage-500 px-3.5 py-2 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600"
                            >
                                <span>Masuk untuk Melapor</span>
                            </a>
                        @endauth

                        <a
                            href="mailto:support@twinbug.local"
                            class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl border border-divider bg-white px-3.5 py-2 text-xs font-semibold text-label transition hover:border-slate-300 hover:bg-input hover:text-ink"
                        >
                            <x-auth.icon name="mail" class="size-3.5" />
                            <span>Hubungi Tim Pengembang</span>
                        </a>
                    </div>
                </div>

            </aside>

            {{-- =============== RIGHT CONTENT (8 cols): FAQ Accordions =============== --}}
            <div class="space-y-3.5 lg:col-span-8">

                {{-- Counter header --}}
                <div class="flex items-center justify-between px-1 text-xs text-label">
                    <span class="font-medium">Daftar Pertanyaan &amp; Jawaban</span>
                    <div id="faq-counter">
                        Menampilkan <span id="faq-visible-count" class="font-bold text-ink">{{ count($faqs) }}</span> dari {{ count($faqs) }} topik
                    </div>
                </div>

                {{-- Accordion List --}}
                <div class="space-y-3" id="faq-container">
                    @foreach ($faqs as $faq)
                        <details
                            class="faq-item group rounded-2xl border border-divider/80 bg-white p-4.5 sm:p-5 shadow-xs transition-all duration-200 hover:border-sage-300 open:border-sage-300 open:shadow-xs"
                            data-category="{{ $faq['category'] }}"
                            data-keywords="{{ strtolower($faq['question'] . ' ' . strip_tags($faq['answer']) . ' ' . $faq['keywords']) }}"
                            id="{{ $faq['id'] }}"
                        >
                            <summary class="flex cursor-pointer list-none items-start justify-between gap-3 select-none">
                                <div class="flex-1">
                                    <span class="inline-block rounded-md bg-input/70 px-2 py-0.5 text-[10px] font-semibold text-label">
                                        {{ $faq['category_label'] }}
                                    </span>
                                    <h2 class="mt-1.5 font-serif text-sm sm:text-base font-semibold tracking-tight text-ink transition group-hover:text-sage-700">
                                        {{ $faq['question'] }}
                                    </h2>
                                </div>
                                <span class="mt-1 grid size-7 shrink-0 place-items-center rounded-lg bg-input text-label transition-transform duration-300 group-open:rotate-180 group-open:bg-sage-100 group-open:text-sage-800">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            </summary>

                            <div class="mt-3.5 border-t border-divider/60 pt-3 text-xs sm:text-sm leading-relaxed text-body">
                                {!! $faq['answer'] !!}
                            </div>
                        </details>
                    @endforeach
                </div>

                {{-- State Pencarian Kosong --}}
                <div id="faq-empty-state" class="hidden my-6 rounded-2xl border border-dashed border-divider bg-white/70 p-8 text-center shadow-xs">
                    <span class="mx-auto grid size-10 place-items-center rounded-xl bg-amber-100 text-amber-800">
                        <x-auth.icon name="alert" class="size-5" />
                    </span>
                    <h3 class="mt-3 font-serif text-sm font-bold text-ink">Tidak ada hasil ditemukan</h3>
                    <p class="mt-1 text-xs text-body">
                        Tidak ada topik pertanyaan yang cocok dengan kata kunci pencarian Anda.
                    </p>
                    <button
                        type="button"
                        id="faq-reset-btn"
                        class="mt-3.5 inline-flex items-center gap-1.5 rounded-xl bg-sage-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-cta hover:bg-sage-600 transition cursor-pointer"
                    >
                        Reset Pencarian &amp; Filter
                    </button>
                </div>

            </div>

        </div>

    </div>

    {{-- Script interaktif untuk pencarian dan filter kategori --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('faq-search');
            const clearBtn = document.getElementById('faq-search-clear');
            const filterButtons = document.querySelectorAll('.faq-filter-btn');
            const faqItems = document.querySelectorAll('.faq-item');
            const emptyState = document.getElementById('faq-empty-state');
            const visibleCountEl = document.getElementById('faq-visible-count');
            const resetBtn = document.getElementById('faq-reset-btn');

            let activeCategory = 'all';
            let searchQuery = '';

            function applyFilter() {
                let visibleCount = 0;
                const query = searchQuery.trim().toLowerCase();

                faqItems.forEach(item => {
                    const itemCategory = item.dataset.category;
                    const keywords = item.dataset.keywords || '';

                    const matchesCategory = (activeCategory === 'all' || itemCategory === activeCategory);
                    const matchesSearch = query === '' || keywords.includes(query);

                    if (matchesCategory && matchesSearch) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                if (visibleCountEl) {
                    visibleCountEl.textContent = visibleCount;
                }

                if (emptyState) {
                    if (visibleCount === 0) {
                        emptyState.classList.remove('hidden');
                    } else {
                        emptyState.classList.add('hidden');
                    }
                }

                if (clearBtn) {
                    if (query.length > 0) {
                        clearBtn.classList.remove('hidden');
                        clearBtn.classList.add('grid');
                    } else {
                        clearBtn.classList.add('hidden');
                        clearBtn.classList.remove('grid');
                    }
                }
            }

            // Event listener pencarian
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    searchQuery = e.target.value;
                    applyFilter();
                });
            }

            // Tombol clear pencarian
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    if (searchInput) {
                        searchInput.value = '';
                        searchQuery = '';
                        searchInput.focus();
                        applyFilter();
                    }
                });
            }

            // Event listener tombol kategori
            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    activeCategory = btn.dataset.categoryFilter;

                    filterButtons.forEach(b => {
                        b.classList.remove('bg-sage-500', 'text-white', 'shadow-cta');
                        b.classList.add('bg-input/60', 'text-label');
                    });

                    btn.classList.remove('bg-input/60', 'text-label');
                    btn.classList.add('bg-sage-500', 'text-white', 'shadow-cta');

                    applyFilter();
                });
            });

            // Tombol reset filter jika kosong
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    searchQuery = '';
                    activeCategory = 'all';

                    filterButtons.forEach(b => {
                        if (b.dataset.categoryFilter === 'all') {
                            b.classList.remove('bg-input/60', 'text-label');
                            b.classList.add('bg-sage-500', 'text-white', 'shadow-cta');
                        } else {
                            b.classList.remove('bg-sage-500', 'text-white', 'shadow-cta');
                            b.classList.add('bg-input/60', 'text-label');
                        }
                    });

                    applyFilter();
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>