<x-layouts.app title="Frequently Asked Questions" meta-description="Find answers to common questions about using TwinBug.">
    <div class="workspace-faq-page mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-6">

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
        <div class="relative overflow-hidden rounded-3xl border border-divider/80 bg-gradient-to-br from-white via-parchment to-sage-50/50 p-6 sm:p-8 shadow-xs">
            {{-- Ambient Decorative Glow Orbs --}}
            <div class="pointer-events-none absolute -right-20 -top-20 size-72 rounded-full bg-sage-200/40 blur-3xl" aria-hidden="true"></div>
            <div class="pointer-events-none absolute -left-20 -bottom-20 size-72 rounded-full bg-amber-100/40 blur-3xl" aria-hidden="true"></div>

            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <div class="mb-2.5 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 rounded-md bg-sage-100 px-2.5 py-0.5 text-[11px] font-semibold text-sage-800">
                            <x-auth.icon name="sparkles" class="size-3 text-sage-600" />
                        <span>Help Center</span>
                        </span>
                        <span class="text-xs text-label">•</span>
                        <span class="text-xs font-medium text-label">{{ count($faqs) }} Topik Panduan</span>
                        <span class="text-xs text-label">•</span>
                        <span class="inline-flex items-center gap-1 rounded bg-input px-1.5 py-0.5 text-[10px] font-mono text-label">
                            <span>Pencarian Cepat</span>
                        </span>
                    </div>
                    <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-ink">
                        Frequently Asked Questions
                    </h1>
                    <p class="mt-1.5 text-xs sm:text-sm leading-relaxed text-body">
                        Find answers to common questions about using TwinBug.
                    </p>
                </div>

                {{-- Kolom Pencarian Cepat --}}
                <div class="w-full lg:w-96 shrink-0">
                    <div class="relative">
                        <label for="faq-search" class="sr-only">Cari pertanyaan</label>
                        <input
                            type="text"
                            id="faq-search"
                            placeholder="Search for questions or topics..."
                            class="w-full rounded-2xl border border-divider/90 bg-white py-3 pl-10 pr-16 text-xs sm:text-sm text-ink shadow-xs transition placeholder:text-label/50 focus:border-sage-500 focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                            autocomplete="off"
                        >
                        <div class="pointer-events-none absolute inset-y-0 left-3.5 flex items-center text-label/60">
                            <x-auth.icon name="search" class="size-4" />
                        </div>
                        <div class="absolute inset-y-0 right-3 flex items-center gap-1">
                            <button
                                type="button"
                                id="faq-search-clear"
                                class="hidden size-6 place-items-center rounded-lg text-label hover:bg-input hover:text-ink transition cursor-pointer"
                                title="Hapus pencarian"
                            >
                                <x-auth.icon name="x" class="size-3.5" />
                            </button>
                            <kbd class="pointer-events-none hidden sm:inline-flex items-center justify-center rounded border border-divider bg-input/80 px-1.5 py-0.5 text-[10px] font-mono text-label/70">
                                /
                            </kbd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================== MOBILE CATEGORY BAR (HORIZONTAL CHIPS) ======================== --}}
        <div class="faq-categories block lg:hidden">
            <div class="flex items-center justify-between pb-2 text-[11px] font-semibold uppercase tracking-wider text-label">
                <span>Filter Topik</span>
                <span id="mobile-category-label" class="text-sage-700 normal-case font-bold">Semua Topik</span>
            </div>
            <div class="flex gap-2 overflow-x-auto no-scrollbar py-1 text-xs">
                @foreach ($categories as $key => $label)
                    @php
                        $count = $key === 'all' ? count($faqs) : collect($faqs)->where('category', $key)->count();
                    @endphp
                    <button
                        type="button"
                        data-category-filter="{{ $key }}"
                        class="faq-filter-btn inline-flex shrink-0 items-center gap-1.5 rounded-xl px-3.5 py-2 text-xs font-semibold transition cursor-pointer {{ $key === 'all' ? 'bg-sage-500 text-white shadow-cta' : 'bg-white border border-divider text-label hover:bg-input hover:text-ink' }}"
                    >
                        <span>{{ $label }}</span>
                        <span class="faq-count-pill rounded-md {{ $key === 'all' ? 'bg-white/20 text-white' : 'bg-input text-label' }} px-1.5 py-0.5 text-[10px] font-bold">{{ $count }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- ======================== 2-COLUMN HELP CENTER GRID ======================== --}}
        <div class="faq-content grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- =============== LEFT SIDEBAR (4 cols): Sticky Categories & Support =============== --}}
            <aside class="hidden lg:block lg:col-span-4 lg:sticky lg:top-20 lg:self-start space-y-5">

                {{-- Kategori Bantuan --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
                    <div class="mb-3.5 flex items-center justify-between">
                        <h2 class="font-serif text-sm font-bold text-ink">Kategori Bantuan</h2>
                        <span class="text-[11px] text-label">Pilih topik</span>
                    </div>

                    <nav class="space-y-1.5" role="tablist" aria-label="Kategori FAQ">
                        @foreach ($categories as $key => $label)
                            @php
                                $count = $key === 'all' ? count($faqs) : collect($faqs)->where('category', $key)->count();
                            @endphp
                            <button
                                type="button"
                                data-category-filter="{{ $key }}"
                                class="faq-filter-btn flex w-full items-center justify-between rounded-xl px-3.5 py-2.5 text-xs font-semibold transition text-left cursor-pointer {{ $key === 'all' ? 'bg-sage-500 text-white shadow-cta' : 'bg-input/60 text-label hover:bg-input hover:text-ink' }}"
                            >
                                <span>{{ $label }}</span>
                                <span class="faq-count-pill rounded-md {{ $key === 'all' ? 'bg-white/20 text-white' : 'bg-black/10 text-label' }} px-1.5 py-0.5 text-[10px] font-bold">{{ $count }}</span>
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
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-sage-500 px-3.5 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                            >
                                <x-auth.icon name="bug" class="size-3.5" />
                                <span>+ Buat Laporan Bug</span>
                            </a>
                        @else
                            <div class="grid grid-cols-2 gap-2">
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center gap-1 rounded-xl bg-sage-500 px-3 py-2 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600"
                                >
                                    <span>Masuk</span>
                                </a>
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center gap-1 rounded-xl border border-divider bg-white px-3 py-2 text-xs font-semibold text-ink transition hover:bg-input"
                                >
                                    <span>Daftar</span>
                                </a>
                            </div>
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

                {{-- Tips Pintasan Keyboard --}}
                <div class="rounded-2xl border border-divider/60 bg-white/60 p-4 text-[11px] text-label shadow-2xs">
                    <div class="flex items-center gap-1.5 font-semibold text-ink mb-1">
                        <x-auth.icon name="sparkles" class="size-3 text-sage-600" />
                        <span>Pintasan Cepat</span>
                    </div>
                    <p class="leading-relaxed">
                        Tekan tombol <kbd class="rounded border border-divider bg-input px-1 py-0.5 font-mono text-[10px] text-ink">/</kbd> di mana saja untuk langsung mencari topik panduan.
                    </p>
                </div>

            </aside>

            {{-- =============== RIGHT CONTENT (8 cols): FAQ Accordions & Toolbar =============== --}}
            <div class="space-y-4 lg:col-span-8">

                {{-- Smart Action Toolbar --}}
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-divider/80 bg-white p-3.5 sm:px-4 text-xs text-label shadow-2xs">
                    <div class="flex items-center gap-2">
                        <span class="size-2 rounded-full bg-sage-500 animate-pulse"></span>
                        <div id="faq-counter">
                            Menampilkan <span id="faq-visible-count" class="font-bold text-ink">{{ count($faqs) }}</span> dari {{ count($faqs) }} topik
                        </div>
                        <span id="faq-active-filter-badge" class="hidden rounded-md bg-sage-100 px-2 py-0.5 text-[10px] font-semibold text-sage-800">
                            Semua Topik
                        </span>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            id="faq-toggle-all-btn"
                            class="inline-flex items-center gap-1.5 rounded-xl border border-divider bg-input/40 px-3 py-1.5 text-xs font-semibold text-ink hover:bg-input hover:border-slate-300 transition cursor-pointer active:scale-95"
                        >
                            <svg id="faq-toggle-icon" class="size-3.5 text-label transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                            <span id="faq-toggle-text">Buka Semua</span>
                        </button>
                    </div>
                </div>

                {{-- Accordion List --}}
                <div class="space-y-3" id="faq-container">
                    @foreach ($faqs as $faq)
                        <details
                            class="faq-item group rounded-2xl border border-divider/80 bg-white p-4.5 sm:p-5 shadow-xs transition-all duration-200 hover:border-sage-300 open:border-sage-400 open:ring-1 open:ring-sage-400/20 open:shadow-xs"
                            data-category="{{ $faq['category'] }}"
                            data-keywords="{{ strtolower($faq['question'] . ' ' . strip_tags($faq['answer']) . ' ' . $faq['keywords']) }}"
                            id="{{ $faq['id'] }}"
                        >
                            <summary class="flex cursor-pointer list-none items-start justify-between gap-3 select-none">
                                <div class="flex-1 pr-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block rounded-md bg-input/80 px-2 py-0.5 text-[10px] font-semibold text-label">
                                            {{ $faq['category_label'] }}
                                        </span>
                                        <span class="text-[10px] font-mono text-label/50">#{{ $faq['id'] }}</span>
                                    </div>
                                    <h2 class="faq-question-text mt-1.5 font-serif text-sm sm:text-base font-semibold tracking-tight text-ink transition group-hover:text-sage-700">
                                        {{ $faq['question'] }}
                                    </h2>
                                </div>
                                <div class="flex items-center gap-1.5 shrink-0 mt-0.5">
                                    <button
                                        type="button"
                                        class="faq-copy-link-btn grid size-7 place-items-center rounded-lg text-label/60 hover:bg-input hover:text-ink transition cursor-pointer"
                                        data-faq-id="{{ $faq['id'] }}"
                                        title="Salin tautan topik ini"
                                    >
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </button>
                                    <span class="grid size-7 place-items-center rounded-lg bg-input text-label transition-transform duration-300 group-open:rotate-180 group-open:bg-sage-100 group-open:text-sage-800">
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </span>
                                </div>
                            </summary>

                            <div class="mt-3.5 border-t border-divider/60 pt-3.5 text-xs sm:text-sm leading-relaxed text-body">
                                <div class="faq-answer-text">
                                    {!! $faq['answer'] !!}
                                </div>

                                {{-- Micro-Feedback Pill --}}
                                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 rounded-xl border border-divider/50 bg-parchment/60 px-3.5 py-2.5 text-xs">
                                    <span class="text-label text-[11px] sm:text-xs">Apakah informasi panduan ini membantu Anda?</span>
                                    <div class="faq-feedback-container flex items-center gap-1.5">
                                        <button
                                            type="button"
                                            class="faq-feedback-btn inline-flex items-center gap-1 rounded-lg border border-divider bg-white px-2.5 py-1 text-[11px] font-semibold text-ink hover:border-sage-400 hover:bg-sage-50 transition cursor-pointer"
                                            data-val="yes"
                                        >
                                            <span>👍</span>
                                            <span>Ya</span>
                                        </button>
                                        <button
                                            type="button"
                                            class="faq-feedback-btn inline-flex items-center gap-1 rounded-lg border border-divider bg-white px-2.5 py-1 text-[11px] font-semibold text-ink hover:border-amber-400 hover:bg-amber-50 transition cursor-pointer"
                                            data-val="no"
                                        >
                                            <span>👎</span>
                                            <span>Belum</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </details>
                    @endforeach
                </div>

                {{-- State Pencarian Kosong --}}
                <div id="faq-empty-state" class="hidden my-8 rounded-2xl border border-dashed border-divider bg-white/70 p-8 text-center shadow-xs">
                    <span class="mx-auto grid size-12 place-items-center rounded-2xl bg-amber-100 text-amber-800">
                        <x-auth.icon name="alert" class="size-6" />
                    </span>
                    <h3 class="mt-3.5 font-serif text-base font-bold text-ink">Tidak ada hasil ditemukan</h3>
                    <p class="mt-1 text-xs sm:text-sm text-body max-w-md mx-auto">
                        Tidak ada topik pertanyaan yang cocok dengan kata kunci atau filter yang Anda pilih.
                    </p>
                    <button
                        type="button"
                        id="faq-reset-btn"
                        class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-sage-500 px-4 py-2 text-xs font-semibold text-white shadow-cta hover:bg-sage-600 transition cursor-pointer"
                    >
                        <x-auth.icon name="refresh" class="size-3.5" />
                        <span>Reset Pencarian &amp; Filter</span>
                    </button>
                </div>

                {{-- Mobile Escalation Card (Bawah Halaman Mobile) --}}
                <div class="block lg:hidden rounded-2xl border border-sage-200/80 bg-sage-50/60 p-5 shadow-xs">
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
                                class="inline-flex w-full items-center justify-center gap-1.5 rounded-xl bg-sage-500 px-3.5 py-2.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                            >
                                <x-auth.icon name="bug" class="size-3.5" />
                                <span>+ Buat Laporan Bug</span>
                            </a>
                        @else
                            <div class="grid grid-cols-2 gap-2">
                                <a
                                    href="{{ route('login') }}"
                                    class="inline-flex items-center justify-center gap-1 rounded-xl bg-sage-500 px-3 py-2 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600"
                                >
                                    <span>Masuk</span>
                                </a>
                                <a
                                    href="{{ route('register') }}"
                                    class="inline-flex items-center justify-center gap-1 rounded-xl border border-divider bg-white px-3 py-2 text-xs font-semibold text-ink transition hover:bg-input"
                                >
                                    <span>Daftar</span>
                                </a>
                            </div>
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

            </div>

        </div>

    </div>

    {{-- Floating Toast Notification --}}
    <div
        id="faq-toast"
        class="fixed bottom-6 right-6 z-50 hidden items-center gap-2 rounded-xl border border-sage-300 bg-white/95 px-4 py-2.5 text-xs font-semibold text-ink shadow-lg backdrop-blur-md transition-all duration-300"
    >
        <span class="grid size-5 place-items-center rounded-full bg-sage-100 text-sage-700">
            <x-auth.icon name="check" class="size-3" />
        </span>
        <span id="faq-toast-msg">Tautan topik berhasil disalin!</span>
    </div>

    {{-- Script interaktif untuk pencarian, filter kategori, copy link, dan highlight --}}
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
            const toggleAllBtn = document.getElementById('faq-toggle-all-btn');
            const toggleAllText = document.getElementById('faq-toggle-text');
            const toggleAllIcon = document.getElementById('faq-toggle-icon');
            const mobileCategoryLabel = document.getElementById('mobile-category-label');
            const activeFilterBadge = document.getElementById('faq-active-filter-badge');
            const copyButtons = document.querySelectorAll('.faq-copy-link-btn');
            const toast = document.getElementById('faq-toast');
            const toastMsg = document.getElementById('faq-toast-msg');

            let activeCategory = 'all';
            let searchQuery = '';
            let allExpanded = false;
            let toastTimeout = null;

            // Simpan HTML asli pertanyaan & jawaban untuk fungsi highlight
            faqItems.forEach(item => {
                const qEl = item.querySelector('.faq-question-text');
                const aEl = item.querySelector('.faq-answer-text');
                if (qEl) qEl.dataset.originalHtml = qEl.innerHTML;
                if (aEl) aEl.dataset.originalHtml = aEl.innerHTML;
            });

            // Tampilkan Toast
            function showToast(message) {
                if (!toast) return;
                if (toastMsg) toastMsg.textContent = message;
                toast.classList.remove('hidden');
                toast.classList.add('flex');
                if (toastTimeout) clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => {
                    toast.classList.add('hidden');
                    toast.classList.remove('flex');
                }, 2500);
            }

            // Fungsi Escape Regex
            function escapeRegExp(string) {
                return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            }

            // Terapkan Filter & Highlighting
            function applyFilter() {
                let visibleCount = 0;
                const query = searchQuery.trim().toLowerCase();

                faqItems.forEach(item => {
                    const itemCategory = item.dataset.category;
                    const keywords = item.dataset.keywords || '';

                    const matchesCategory = (activeCategory === 'all' || itemCategory === activeCategory);
                    const matchesSearch = query === '' || keywords.includes(query);

                    const qEl = item.querySelector('.faq-question-text');
                    const aEl = item.querySelector('.faq-answer-text');

                    if (matchesCategory && matchesSearch) {
                        item.classList.remove('hidden');
                        visibleCount++;

                        // Jika ada query pencarian, beri highlight dan buka accordion
                        if (query.length >= 2) {
                            item.open = true;
                            const regex = new RegExp(`(${escapeRegExp(query)})`, 'gi');
                            const highlightReplacer = '<mark class="bg-amber-200/90 text-ink rounded-xs px-0.5 font-medium">$1</mark>';

                            if (qEl && qEl.dataset.originalHtml) {
                                qEl.innerHTML = qEl.dataset.originalHtml.replace(regex, highlightReplacer);
                            }
                            if (aEl && aEl.dataset.originalHtml) {
                                // Hanya highlight di luar tag HTML
                                const parts = aEl.dataset.originalHtml.split(/(<[^>]+>)/g);
                                const highlighted = parts.map(part => {
                                    if (part.startsWith('<') && part.endsWith('>')) {
                                        return part;
                                    }
                                    return part.replace(regex, highlightReplacer);
                                }).join('');
                                aEl.innerHTML = highlighted;
                            }
                        } else {
                            if (qEl && qEl.dataset.originalHtml) qEl.innerHTML = qEl.dataset.originalHtml;
                            if (aEl && aEl.dataset.originalHtml) aEl.innerHTML = aEl.dataset.originalHtml;
                        }
                    } else {
                        item.classList.add('hidden');
                        if (qEl && qEl.dataset.originalHtml) qEl.innerHTML = qEl.dataset.originalHtml;
                        if (aEl && aEl.dataset.originalHtml) aEl.innerHTML = aEl.dataset.originalHtml;
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

                // Perbarui badge filter aktif
                if (activeFilterBadge) {
                    if (activeCategory !== 'all') {
                        const activeBtn = document.querySelector(`.faq-filter-btn[data-category-filter="${activeCategory}"]`);
                        const text = activeBtn ? activeBtn.querySelector('span:first-child').textContent.trim() : activeCategory;
                        activeFilterBadge.textContent = text;
                        activeFilterBadge.classList.remove('hidden');
                    } else {
                        activeFilterBadge.classList.add('hidden');
                    }
                }
            }

            // Update gaya tombol filter (Desktop & Mobile)
            function updateFilterButtonStyles() {
                filterButtons.forEach(btn => {
                    const isSelected = btn.dataset.categoryFilter === activeCategory;
                    const countPill = btn.querySelector('.faq-count-pill');

                    if (isSelected) {
                        btn.classList.remove('bg-input/60', 'text-label', 'bg-white', 'border-divider');
                        btn.classList.add('bg-sage-500', 'text-white', 'shadow-cta');
                        if (countPill) {
                            countPill.classList.remove('bg-input', 'bg-black/10', 'text-label');
                            countPill.classList.add('bg-white/20', 'text-white');
                        }
                        if (mobileCategoryLabel) {
                            mobileCategoryLabel.textContent = btn.querySelector('span:first-child').textContent.trim();
                        }
                    } else {
                        btn.classList.remove('bg-sage-500', 'text-white', 'shadow-cta');
                        btn.classList.add(btn.closest('aside') ? 'bg-input/60' : 'bg-white', 'text-label');
                        if (countPill) {
                            countPill.classList.remove('bg-white/20', 'text-white');
                            countPill.classList.add(btn.closest('aside') ? 'bg-black/10' : 'bg-input', 'text-label');
                        }
                    }
                });
            }

            // Event listener pencarian
            if (searchInput) {
                searchInput.addEventListener('input', (e) => {
                    searchQuery = e.target.value;
                    applyFilter();
                });
            }

            // Shortcut keyboard '/' untuk fokus pencarian
            document.addEventListener('keydown', (e) => {
                if (e.key === '/' && document.activeElement !== searchInput && !['INPUT', 'TEXTAREA'].includes(document.activeElement.tagName)) {
                    e.preventDefault();
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.select();
                    }
                }
            });

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

            // Event listener tombol kategori (Desktop & Mobile)
            filterButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    activeCategory = btn.dataset.categoryFilter;
                    updateFilterButtonStyles();
                    applyFilter();
                });
            });

            // Tombol Buka Semua / Tutup Semua
            if (toggleAllBtn) {
                toggleAllBtn.addEventListener('click', () => {
                    allExpanded = !allExpanded;
                    faqItems.forEach(item => {
                        if (!item.classList.contains('hidden')) {
                            item.open = allExpanded;
                        }
                    });

                    if (toggleAllText) {
                        toggleAllText.textContent = allExpanded ? 'Tutup Semua' : 'Buka Semua';
                    }
                    if (toggleAllIcon) {
                        toggleAllIcon.style.transform = allExpanded ? 'rotate(180deg)' : 'rotate(0deg)';
                    }
                });
            }

            // Tombol reset filter jika kosong
            if (resetBtn) {
                resetBtn.addEventListener('click', () => {
                    if (searchInput) searchInput.value = '';
                    searchQuery = '';
                    activeCategory = 'all';
                    updateFilterButtonStyles();
                    applyFilter();
                });
            }

            // Tombol Salin Tautan Anchor FAQ
            copyButtons.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    const faqId = btn.dataset.faqId;
                    const url = `${window.location.origin}${window.location.pathname}#${faqId}`;

                    if (navigator.clipboard && window.isSecureContext) {
                        navigator.clipboard.writeText(url).then(() => {
                            showToast('Tautan topik berhasil disalin!');
                        });
                    } else {
                        // Fallback
                        const input = document.createElement('input');
                        input.value = url;
                        document.body.appendChild(input);
                        input.select();
                        document.execCommand('copy');
                        document.body.removeChild(input);
                        showToast('Tautan topik berhasil disalin!');
                    }
                });
            });

            // Micro-Feedback Tanggapan Panduan
            document.querySelectorAll('.faq-feedback-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const container = this.closest('.faq-feedback-container');
                    if (container) {
                        container.innerHTML = `
                            <span class="inline-flex items-center gap-1 font-semibold text-sage-700 text-[11px]">
                                <svg class="size-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                                <span>Terima kasih atas tanggapan Anda!</span>
                            </span>
                        `;
                    }
                });
            });

            // Auto-expand & smooth scroll jika URL memiliki hash (#faq-x)
            if (window.location.hash) {
                const targetId = window.location.hash.substring(1);
                const targetItem = document.getElementById(targetId);
                if (targetItem && targetItem.classList.contains('faq-item')) {
                    targetItem.open = true;
                    setTimeout(() => {
                        targetItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }, 200);
                }
            }
        });
    </script>
    @endpush
</x-layouts.app>
