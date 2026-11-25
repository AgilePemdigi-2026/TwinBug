<x-layouts.app title="Frequently Asked Questions" meta-description="Find answers to common questions about using TwinBug.">

    <div class="workspace-faq-page mx-auto max-w-[1540px] p-8">

        {{-- Hero Section --}}
        <section class="border-b-[0.8px] border-[#E0E9E4] bg-[#F2F7F4] px-6 pt-[74px] pb-[64px] text-center">
            <p class="text-[9.5px] font-bold uppercase leading-[14px] tracking-[1.235px] text-[#78B597]">
                Help center
            </p>
            <h1 class="mx-auto mt-[15px] max-w-3xl text-[40px] font-normal leading-[1.08] tracking-[-2.2px] text-[#13241C] sm:text-[54px] sm:leading-[57px]">
                Frequently Asked Questions
            </h1>
            <p class="mx-auto mt-[10px] max-w-xl text-[12.5px] leading-[19px] text-[#67736D]">
                Find answers to common questions about using TwinBug.
            </p>

            {{-- Centered Search Box --}}
            <div class="mx-auto mt-[28px] max-w-[660px]">
                <label for="faq-search" class="flex h-[52px] w-full items-center gap-[10px] rounded-[10px] border-[0.8px] border-[#D5DFDA] bg-white px-[15px] shadow-[0_7px_22px_rgba(20,55,38,0.06)] focus-within:border-[#236B51]">
                    <x-auth.icon name="search" class="size-5 shrink-0 text-[#738078]" />
                    <input
                        type="text"
                        id="faq-search"
                        placeholder="Search for questions or topics..."
                        class="w-full bg-transparent text-[11.5px] text-[#24322B] placeholder:text-[#A4ADA8] focus:outline-none"
                    >
                </label>
            </div>
        </section>

        {{-- FAQ Content Section --}}
        <section class="mx-auto max-w-[1030px] px-6 pt-[52px] pb-[70px]">

            {{-- Category Filter Pills --}}
            <div role="tablist" aria-label="FAQ categories" class="flex flex-wrap items-center gap-[7px] border-b-[0.8px] border-[#E2E8E5] pb-[25px]">
                @foreach ($categories as $key => $label)
                    @php
                        $isAll = $key === 'all';
                        $displayLabel = $isAll ? 'All Questions' : $label;
                    @endphp
                    <button
                        type="button"
                        role="tab"
                        aria-selected="{{ $isAll ? 'true' : 'false' }}"
                        data-category-filter="{{ $key }}"
                        data-category-title="{{ $displayLabel }}"
                        @class([
                            'faq-category-btn inline-flex h-[31.85px] cursor-pointer items-center justify-center rounded-full border-[0.8px] px-[11px] text-[9.5px] font-semibold leading-[14px] transition',
                            'bg-[#123C2E] border-[#123C2E] text-white' => $isAll,
                            'bg-white border-[#DCE4E0] text-[#65726B] hover:border-[#236B51]' => ! $isAll,
                        ])
                    >
                        {{ $displayLabel }}
                    </button>
                @endforeach
            </div>

            {{-- Active Category Heading --}}
            <div class="flex items-center justify-between pt-[27px]">
                <div>
                    <h2 id="faq-active-heading" class="text-[19px] font-normal leading-7 tracking-[-0.7px] text-[#17201C]">
                        All Questions
                    </h2>
                    <p id="faq-visible-count" class="pt-1 text-[9.5px] leading-[14px] text-[#67736D]">
                        {{ count($faqs) }} answers available
                    </p>
                </div>
            </div>

            {{-- Accordion Items --}}
            <div id="faq-list" class="mt-[15px] space-y-[9px]">
                @foreach ($faqs as $index => $faq)
                    <details
                        id="{{ $faq['id'] }}"
                        data-category="{{ $faq['category'] }}"
                        data-search="{{ mb_strtolower($faq['question'] . ' ' . strip_tags($faq['answer']) . ' ' . $faq['keywords'] . ' ' . $faq['category_label']) }}"
                        @if ($index === 0) open @endif
                        class="faq-item group overflow-hidden rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white transition open:border-[#CDDBD4] open:shadow-[0_4px_14px_rgba(13,45,34,0.035)]"
                    >
                        <summary class="flex min-h-[70px] cursor-pointer list-none items-center justify-between gap-4 px-[18px] py-[15px] transition hover:bg-[#FBFDFC] group-open:bg-[#FBFDFC] [&::-webkit-details-marker]:hidden">
                            <div class="flex flex-col gap-[5px]">
                                <span class="text-[7.5px] font-bold uppercase leading-[11px] tracking-[0.6px] text-[#236B51]">
                                    {{ $faq['category_label'] }}
                                </span>
                                <span class="text-[11.5px] font-bold leading-[17px] text-[#29372F]">
                                    {{ $faq['question'] }}
                                </span>
                            </div>

                            <span class="grid size-[27px] shrink-0 place-items-center rounded-full bg-[#F1F5F3] text-[18px] leading-none text-[#236B51]" aria-hidden="true">
                                <span class="group-open:hidden">+</span>
                                <span class="hidden group-open:inline">−</span>
                            </span>
                        </summary>

                        <div class="px-[18px] pb-[18px] text-[10.5px] leading-[17px] text-[#67736D]">
                            {!! $faq['answer'] !!}
                        </div>
                    </details>
                @endforeach
            </div>

            {{-- Empty Search State --}}
            <div id="faq-empty-state" class="mt-6 hidden rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white px-6 py-10 text-center">
                <p class="text-[13px] font-semibold text-[#142019]">No matching questions found</p>
                <p class="mt-1 text-[11px] text-[#67736D]">Try another keyword or switch back to All Questions.</p>
                <button
                    type="button"
                    id="faq-reset-btn"
                    class="mt-4 inline-flex h-9 cursor-pointer items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-4 text-[11.5px] font-semibold text-[#35433C] transition hover:bg-[#F7F9F8]"
                >
                    Reset filter
                </button>
            </div>

        </section>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('faq-search');
            const filterButtons = document.querySelectorAll('.faq-category-btn');
            const faqItems = document.querySelectorAll('.faq-item');
            const activeHeading = document.getElementById('faq-active-heading');
            const visibleCount = document.getElementById('faq-visible-count');
            const emptyState = document.getElementById('faq-empty-state');
            const resetBtn = document.getElementById('faq-reset-btn');

            let activeCategory = 'all';
            let searchQuery = '';

            function applyFilter() {
                let count = 0;
                const q = searchQuery.trim().toLowerCase();

                faqItems.forEach(function (item) {
                    const matchesCategory = activeCategory === 'all' || item.dataset.category === activeCategory;
                    const matchesSearch = !q || (item.dataset.search || '').includes(q);

                    if (matchesCategory && matchesSearch) {
                        item.classList.remove('hidden');
                        count++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                if (visibleCount) {
                    visibleCount.textContent = `${count} ${count === 1 ? 'answer' : 'answers'} available`;
                }

                if (emptyState) {
                    emptyState.classList.toggle('hidden', count > 0);
                }
            }

            filterButtons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    activeCategory = btn.dataset.categoryFilter || 'all';
                    if (activeHeading && btn.dataset.categoryTitle) {
                        activeHeading.textContent = btn.dataset.categoryTitle;
                    }

                    filterButtons.forEach(function (other) {
                        const isSelected = other === btn;
                        other.setAttribute('aria-selected', isSelected ? 'true' : 'false');
                        other.classList.toggle('bg-[#123C2E]', isSelected);
                        other.classList.toggle('border-[#123C2E]', isSelected);
                        other.classList.toggle('text-white', isSelected);
                        other.classList.toggle('bg-white', !isSelected);
                        other.classList.toggle('border-[#DCE4E0]', !isSelected);
                        other.classList.toggle('text-[#65726B]', !isSelected);
                    });

                    applyFilter();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', function (e) {
                    searchQuery = e.target.value;
                    applyFilter();
                });
            }

            if (resetBtn) {
                resetBtn.addEventListener('click', function () {
                    if (searchInput) searchInput.value = '';
                    searchQuery = '';
                    const allBtn = document.querySelector('.faq-category-btn[data-category-filter="all"]');
                    if (allBtn) allBtn.click();
                    else applyFilter();
                });
            }
        });
    </script>

</x-layouts.app>
