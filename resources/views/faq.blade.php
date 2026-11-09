<x-layouts.app title="Pusat Bantuan & Tanya Jawab (FAQ)">
    <div class="mx-auto max-w-5xl px-6 py-12 sm:px-8 lg:px-12">

        {{-- ======================== HERO SECTION ======================== --}}
        <div class="relative text-center">
            <span class="inline-flex items-center gap-2 rounded-full border border-sage-200 bg-sage-50 px-3.5 py-1 text-xs font-semibold text-sage-700 shadow-sm">
                <x-auth.icon name="sparkles" class="size-3.5 text-sage-500" />
                Pusat Bantuan TwinBug
            </span>

            <h1 class="mt-4 font-serif text-3xl font-bold tracking-tight text-ink sm:text-4xl md:text-5xl">
                Pertanyaan yang Sering Diajukan
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-body sm:text-lg">
                Panduan terpadu mengenai alur registrasi OTP, cara pelaporan bug terstruktur, validasi tangkapan layar, dan deteksi duplikasi cerdas.
            </p>

            {{-- Kolom Pencarian Cepat --}}
            <div class="mt-8 flex justify-center">
                <div class="relative w-full max-w-2xl">
                    <label for="faq-search" class="sr-only">Cari pertanyaan</label>
                    <input
                        type="text"
                        id="faq-search"
                        placeholder="Cari topik atau kata kunci (misal: OTP, screenshot, severity, peran)..."
                        class="w-full rounded-2xl border border-divider bg-input px-5 py-4 pl-12 pr-12 text-sm text-ink shadow-sm transition placeholder:text-body/60 focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-4 focus:ring-sage-200/50"
                        autocomplete="off"
                    >
                    <div class="pointer-events-none absolute inset-y-0 left-4 flex items-center text-body/70">
                        <x-auth.icon name="search" class="size-5" />
                    </div>
                    <button
                        type="button"
                        id="faq-search-clear"
                        class="hidden absolute inset-y-0 right-3 my-auto size-7 place-items-center rounded-lg text-label hover:bg-input-hover hover:text-ink transition"
                        title="Hapus pencarian"
                    >
                        <x-auth.icon name="x" class="size-4" />
                    </button>
                </div>
            </div>

            {{-- Filter Kategori Pills --}}
            <div class="mt-6 flex flex-wrap items-center justify-center gap-2" role="tablist" aria-label="Kategori FAQ">
                @foreach ($categories as $key => $label)
                    <button
                        type="button"
                        data-category-filter="{{ $key }}"
                        class="faq-filter-btn rounded-xl px-4 py-2 text-xs font-semibold transition {{ $key === 'all' ? 'bg-sage-500 text-white shadow-cta' : 'bg-input text-label hover:bg-input-hover hover:text-ink' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- Counter info --}}
            <div class="mt-4 text-xs font-medium text-body/70" id="faq-counter">
                Menampilkan <span id="faq-visible-count">{{ count($faqs) }}</span> dari {{ count($faqs) }} topik
            </div>
        </div>

        {{-- ======================== DAFTAR ACCORDION FAQ ======================== --}}
        <div class="mt-10 space-y-4" id="faq-container">
            @foreach ($faqs as $faq)
                <details
                    class="faq-item group rounded-2xl border border-divider bg-card p-6 shadow-card transition-all duration-200 hover:border-sage-300 open:border-sage-300 open:bg-card"
                    data-category="{{ $faq['category'] }}"
                    data-keywords="{{ strtolower($faq['question'] . ' ' . strip_tags($faq['answer']) . ' ' . $faq['keywords']) }}"
                    id="{{ $faq['id'] }}"
                >
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 select-none">
                        <div class="flex-1">
                            <span class="inline-block rounded-md border border-divider bg-input px-2.5 py-0.5 text-[11px] font-semibold text-label">
                                {{ $faq['category_label'] }}
                            </span>
                            <h2 class="mt-2 font-serif text-lg font-semibold tracking-tight text-ink transition group-hover:text-sage-700">
                                {{ $faq['question'] }}
                            </h2>
                        </div>
                        <span class="mt-2 grid size-8 shrink-0 place-items-center rounded-xl bg-input text-label transition-transform duration-300 group-open:rotate-180 group-open:bg-sage-100 group-open:text-sage-700">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </summary>

                    <div class="mt-4 border-t border-divider/70 pt-4 text-sm leading-relaxed text-body">
                        {!! $faq['answer'] !!}
                    </div>
                </details>
            @endforeach
        </div>

        {{-- State Pencarian Kosong --}}
        <div id="faq-empty-state" class="hidden my-12 rounded-2xl border border-dashed border-divider bg-input/40 p-12 text-center">
            <span class="mx-auto grid size-12 place-items-center rounded-2xl bg-gold-100 text-ochre">
                <x-auth.icon name="alert" class="size-6" />
            </span>
            <h3 class="mt-4 font-serif text-lg font-semibold text-ink">Tidak ada hasil ditemukan</h3>
            <p class="mt-1 text-sm text-body">
                Tidak ada topik pertanyaan yang cocok dengan kata kunci pencarian Anda.
            </p>
            <button
                type="button"
                id="faq-reset-btn"
                class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-sage-500 px-4 py-2 text-xs font-semibold text-white shadow-cta hover:bg-sage-600 transition"
            >
                Reset Pencarian & Filter
            </button>
        </div>

        {{-- ======================== KARTU BANTUAN LANJUTAN ======================== --}}
        <div class="mt-16 rounded-3xl border border-sage-200 bg-gradient-to-br from-sage-50/90 via-card to-gold-100/40 p-8 shadow-card sm:p-10">
            <div class="flex flex-col items-center justify-between gap-6 text-center sm:flex-row sm:text-left">
                <div class="max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2.5 py-0.5 text-xs font-bold uppercase tracking-wider text-sage-700">
                        Butuh Dukungan Lebih Lanjut?
                    </span>
                    <h3 class="mt-2 font-serif text-2xl font-bold text-ink">
                        Belum menemukan jawaban yang dicari?
                    </h3>
                    <p class="mt-2 text-sm text-body leading-relaxed">
                        Jika Anda mendapati anomali teknis pada sistem atau memerlukan klarifikasi alur pengerjaan bug, buat laporan baru atau hubungi tim pengembang secara langsung.
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
                            Masuk untuk Melapor
                        </a>
                    @endauth

                    <a
                        href="mailto:support@twinbug.local"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl border border-divider bg-white px-5 py-3 text-sm font-semibold text-label transition hover:border-sage-300 hover:bg-sage-50 hover:text-ink"
                    >
                        <x-auth.icon name="mail" class="size-4" />
                        Hubungi Tim
                    </a>
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
                        b.classList.add('bg-input', 'text-label');
                    });

                    btn.classList.remove('bg-input', 'text-label');
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
                            b.classList.remove('bg-input', 'text-label');
                            b.classList.add('bg-sage-500', 'text-white', 'shadow-cta');
                        } else {
                            b.classList.remove('bg-sage-500', 'text-white', 'shadow-cta');
                            b.classList.add('bg-input', 'text-label');
                        }
                    });

                    applyFilter();
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>
