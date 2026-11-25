<x-layouts.landing title="Detect Duplicate Bugs. Simplify Bug Management." meta-description="TwinBug helps development teams identify similar bug reports using text and image analysis, reducing duplicate issues and supporting faster triage.">

    {{-- ======================== HERO SECTION ======================== --}}
    <section id="home" class="w-full bg-gradient-to-b from-white to-[#FBFDFC] py-14 lg:py-0">
        <div class="mx-auto flex min-h-[665px] max-w-[1528px] flex-col items-center justify-between gap-12 px-6 sm:px-10 lg:flex-row lg:px-[103.9px]">

            {{-- Left Column --}}
            <div class="flex w-full max-w-[541.29px] flex-col items-start">
                {{-- Eyebrow --}}
                <div class="flex items-center gap-[6px]">
                    <x-auth.icon name="sparkles" class="size-[14px] text-[#236B51] stroke-[1.17]" />
                    <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#236B51]">
                        Multimodal duplicate detection
                    </span>
                </div>

                {{-- Heading 1 --}}
                <h1 class="pt-[18px] text-[42px] font-normal leading-[1.05] tracking-[-2px] text-[#102018] sm:text-[56px] lg:text-[68px] lg:leading-[70px] lg:tracking-[-2.6px]">
                    Detect Duplicate Bugs. Simplify Bug Management.
                </h1>

                {{-- Paragraph --}}
                <p class="pt-[18px] max-w-[542px] text-[13.5px] font-normal leading-[24px] text-[#5D6B64]">
                    TwinBug helps development teams identify similar bug reports through text and image analysis, reducing duplicate issues and supporting faster triage.
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center gap-[9px] pt-[29px]">
                    <a href="{{ route('register') }}"
                        class="inline-flex h-[46px] items-center justify-center gap-[7px] rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#0D2D22]">
                        <span>Get Started</span>
                        <svg class="size-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <a href="#about"
                        class="inline-flex h-[46px] items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        Learn More
                    </a>
                </div>

                {{-- Small Note --}}
                <div class="flex items-center gap-[6px] pt-[15px]">
                    <svg class="size-[14px] shrink-0 text-[#236B51]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <path d="m9 12 2 2 4-4" />
                    </svg>
                    <span class="text-[9px] leading-[14px] text-[#75827B]">
                        Smarter bug report analysis through text and image evidence
                    </span>
                </div>
            </div>

            {{-- Right Column: Sample TwinBug Dashboard Preview --}}
            <div class="w-full max-w-[689px] overflow-hidden rounded-[15px] border-[0.8px] border-[#D7E0DC] bg-white shadow-[0_26px_65px_rgba(14,49,34,0.13),0_3px_8px_rgba(14,49,34,0.05)]">
                {{-- Window Chrome --}}
                <div class="flex h-[33px] items-center justify-between border-b-[0.8px] border-[#E1E7E4] bg-[#F3F6F4] px-[11px]">
                    <div class="flex items-center gap-[5px]">
                        <span class="size-[6px] rounded-full bg-[#BDC7C2]"></span>
                        <span class="size-[6px] rounded-full bg-[#BDC7C2]"></span>
                        <span class="size-[6px] rounded-full bg-[#BDC7C2]"></span>
                    </div>
                    <span class="text-[7px] leading-[10px] text-[#829089]">Sample workspace preview</span>
                </div>

                {{-- Dashboard Body --}}
                <div class="flex min-h-[420px]">
                    {{-- Mini Sidebar --}}
                    <div class="flex w-[54px] shrink-0 flex-col items-center gap-[15px] bg-[#0D2D22] px-[11px] py-[14px]">
                        <div class="mb-[6px] grid size-[28px] place-items-center rounded-[7px] bg-white text-[#123C2E]">
                            <x-auth.icon name="bug" class="size-[15px] stroke-[1.5]" />
                        </div>
                        <span class="h-[5px] w-[23px] rounded-[3px] bg-white/15"></span>
                        <span class="h-[5px] w-[23px] rounded-[3px] bg-white/15"></span>
                        <span class="h-[5px] w-[23px] rounded-[3px] bg-white/15"></span>
                        <span class="h-[5px] w-[23px] rounded-[3px] bg-white/15"></span>
                    </div>

                    {{-- Dashboard Main Content --}}
                    <div class="flex flex-1 flex-col bg-[#F7F9F8] p-5">
                        {{-- Top Header --}}
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[6px] uppercase leading-[9px] tracking-[0.48px] text-[#236B51]">TRIAGE QUEUE</p>
                                <p class="mt-1 text-[13px] font-bold leading-5 text-[#17201C]">Detection overview</p>
                            </div>
                            <div class="flex h-[26px] w-[125px] items-center gap-[5px] rounded-[5px] border-[0.8px] border-[#E0E6E3] bg-white px-2">
                                <x-auth.icon name="search" class="size-[13px] text-[#9BA49F]" />
                                <span class="text-[6.5px] leading-[10px] text-[#9BA49F]">Search reports</span>
                            </div>
                        </div>

                        {{-- Summary Cards --}}
                        <div class="mt-[15px] grid grid-cols-2 gap-[15px]">
                            <div class="flex items-center gap-2 rounded-[7px] border-[0.8px] border-[#E2E8E5] bg-white p-[9px]">
                                <div class="grid size-[25px] shrink-0 place-items-center rounded-[6px] bg-[#F2F8F5] text-[#236B51]">
                                    <svg class="size-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                        <path d="M10 9H8" />
                                        <path d="M16 13H8" />
                                        <path d="M16 17H8" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[6px] leading-[9px] text-[#7D8983]">Reports</p>
                                    <p class="mt-[2px] text-[8px] font-bold leading-3 text-[#17201C]">Sample data</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 rounded-[7px] border-[0.8px] border-[#E2E8E5] bg-white p-[9px]">
                                <div class="grid size-[25px] shrink-0 place-items-center rounded-[6px] bg-[#F2F8F5] text-[#236B51]">
                                    <svg class="size-[14px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                        <path d="M4.268 21a2 2 0 0 0 1.727 1H18a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v3" />
                                        <path d="m9 18-1.5-1.5" />
                                        <circle cx="5" cy="14" r="3" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[6px] leading-[9px] text-[#7D8983]">Analysis</p>
                                    <p class="mt-[2px] text-[8px] font-bold leading-3 text-[#17201C]">Ready to review</p>
                                </div>
                            </div>
                        </div>

                        {{-- Reports Table --}}
                        <div class="mt-[15px] overflow-hidden rounded-[7px] border-[0.8px] border-[#E0E6E3] bg-white">
                            <div class="grid h-[25px] grid-cols-12 items-center border-b-[0.8px] border-[#E5E9E7] bg-[#FAFBFA] px-3 text-[6px] uppercase tracking-[0.24px] text-[#87918C]">
                                <span class="col-span-5">Report</span>
                                <span class="col-span-2">Module</span>
                                <span class="col-span-3">Detection</span>
                                <span class="col-span-2">Score</span>
                            </div>

                            {{-- Row 1 --}}
                            <div class="grid min-h-[62px] grid-cols-12 items-center border-b-[0.8px] border-[#EDF0EE] px-3">
                                <div class="col-span-5 pr-2">
                                    <p class="text-[6.5px] font-bold leading-[10px] text-[#236B51]">BUG-1048</p>
                                    <p class="mt-1 text-[7.5px] font-bold leading-[11px] text-[#33413A]">Checkout button is unresponsive</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-[7px] leading-[10px] text-[#65716A]">Checkout</span>
                                </div>
                                <div class="col-span-3">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-[#FBF3E1] px-[5px] py-1">
                                        <span class="size-1 rounded-full bg-[#8C661C]"></span>
                                        <span class="text-[6px] leading-[9px] text-[#8C661C]">Potential Duplicate</span>
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <div class="h-1 w-full rounded bg-[#E8EDEB] overflow-hidden">
                                        <div class="h-full w-[86%] rounded bg-[#2D795A]"></div>
                                    </div>
                                    <p class="mt-1 text-[6px] leading-[9px] text-[#66746C]">Sample 86%</p>
                                </div>
                            </div>

                            {{-- Row 2 --}}
                            <div class="grid min-h-[62px] grid-cols-12 items-center px-3">
                                <div class="col-span-5 pr-2">
                                    <p class="text-[6.5px] font-bold leading-[10px] text-[#236B51]">BUG-1044</p>
                                    <p class="mt-1 text-[7.5px] font-bold leading-[11px] text-[#33413A]">Profile form validation issue</p>
                                </div>
                                <div class="col-span-2">
                                    <span class="text-[7px] leading-[10px] text-[#65716A]">Account</span>
                                </div>
                                <div class="col-span-3">
                                    <span class="inline-flex items-center gap-1 rounded-full bg-[#EDF7F2] px-[5px] py-1">
                                        <span class="size-1 rounded-full bg-[#2D7357]"></span>
                                        <span class="text-[6px] leading-[9px] text-[#2D7357]">Unique Report</span>
                                    </span>
                                </div>
                                <div class="col-span-2">
                                    <div class="h-1 w-full rounded bg-[#E8EDEB] overflow-hidden">
                                        <div class="h-full w-[41%] rounded bg-[#7093A5]"></div>
                                    </div>
                                    <p class="mt-1 text-[6px] leading-[9px] text-[#66746C]">Sample 41%</p>
                                </div>
                            </div>
                        </div>

                        {{-- Similarity Candidate Banner --}}
                        <div class="mt-[11px] flex min-h-[50px] items-center justify-between gap-[7px] rounded-[7px] border-[0.8px] border-[#D7E6DE] bg-[#F2F8F5] p-2">
                            <div class="flex items-center gap-[7px]">
                                <div class="grid size-[26px] shrink-0 place-items-center rounded-[6px] bg-white text-[#236B51]">
                                    <svg class="size-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="16" y="16" width="6" height="6" rx="1" />
                                        <rect x="2" y="16" width="6" height="6" rx="1" />
                                        <rect x="9" y="2" width="6" height="6" rx="1" />
                                        <path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                                        <path d="M12 12V8" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[5.5px] leading-2 text-[#728079]">Example similarity candidate</p>
                                    <p class="mt-[2px] text-[7px] font-bold leading-[10px] text-[#35443C]">BUG-0834 · Checkout CTA does not respond</p>
                                </div>
                            </div>
                            <span class="inline-flex h-6 shrink-0 items-center gap-1 rounded-[5px] bg-[#123C2E] px-[7px] text-[6px] text-white">
                                <span>Review comparison</span>
                                <svg class="size-[11px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14" />
                                    <path d="m12 5 7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ======================== THE CHALLENGE SECTION ======================== --}}
    <section class="w-full bg-[#F7F9F8] py-[92px]">
        <div class="mx-auto max-w-[1528px] px-6 sm:px-10 lg:px-[106.96px]">
            <div class="mx-auto flex max-w-[740px] flex-col items-center text-center">
                <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#236B51]">
                    The challenge
                </span>
                <h2 class="pt-[10px] text-[30px] font-normal leading-[1.16] tracking-[-1.3px] text-[#14221B] sm:text-[43px] sm:leading-[50px]">
                    Managing Duplicate Bug Reports Can Be Challenging
                </h2>
                <p class="pt-[13px] text-[12px] leading-[21px] text-[#67736D]">
                    Multiple users may report the same issue with different descriptions or screenshots, making manual comparison slow and inconsistent.
                </p>
            </div>

            <div class="mx-auto mt-10 grid max-w-[1190px] grid-cols-1 gap-[15px] md:grid-cols-3">
                {{-- Card 1 --}}
                <article class="flex flex-col items-start rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[22px]">
                    <div class="grid size-[39px] place-items-center rounded-[9px] bg-[#F2F8F5] text-[#236B51]">
                        <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m12.83 2.18a2 2 0 0 0-1.66 0L2.6 6.08a1 1 0 0 0 0 1.83l8.58 3.91a2 2 0 0 0 1.66 0l8.58-3.9a1 1 0 0 0 0-1.83Z" />
                            <path d="m22 17.65-9.17 4.16a2 2 0 0 1-1.66 0L2 17.65" />
                            <path d="m22 12.65-9.17 4.16a2 2 0 0 1-1.66 0L2 12.65" />
                        </svg>
                    </div>
                    <h3 class="pt-[15px] text-[13px] font-normal leading-5 text-[#17201C]">Repetitive Bug Reports</h3>
                    <p class="pt-[7px] text-[10px] leading-4 text-[#67736D]">
                        Multiple reports may describe the same underlying issue across different users.
                    </p>
                </article>

                {{-- Card 2 --}}
                <article class="flex flex-col items-start rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[22px]">
                    <div class="grid size-[39px] place-items-center rounded-[9px] bg-[#F2F8F5] text-[#236B51]">
                        <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="12 6 12 12 16 14" />
                        </svg>
                    </div>
                    <h3 class="pt-[15px] text-[13px] font-normal leading-5 text-[#17201C]">Time-Consuming Review</h3>
                    <p class="pt-[7px] text-[10px] leading-4 text-[#67736D]">
                        Developers need to manually compare reports to identify duplicate submissions.
                    </p>
                </article>

                {{-- Card 3 --}}
                <article class="flex flex-col items-start rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[22px]">
                    <div class="grid size-[39px] place-items-center rounded-[9px] bg-[#F2F8F5] text-[#236B51]">
                        <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <h3 class="pt-[15px] text-[13px] font-normal leading-5 text-[#17201C]">Fragmented Information</h3>
                    <p class="pt-[7px] text-[10px] leading-4 text-[#67736D]">
                        Related bug reports may be scattered across different modules without clear links.
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- ======================== ABOUT THE PLATFORM SECTION ======================== --}}
    <section id="about" class="w-full bg-white py-[92px]">
        <div class="mx-auto grid max-w-[1528px] grid-cols-1 items-center gap-12 px-6 sm:px-10 lg:grid-cols-12 lg:px-[106.96px]">

            {{-- Left Column: Text and Image Analysis Process Diagram --}}
            <div class="lg:col-span-6">
                <div class="relative mx-auto flex h-[360px] w-full max-w-[567.19px] flex-col items-center justify-center rounded-[14px] border-[0.8px] border-[#E2E8E5] bg-[#F7F9F8] p-5">
                    {{-- Dashed Connector Lines (Desktop) --}}
                    <svg class="pointer-events-none absolute inset-0 hidden size-full sm:block" viewBox="0 0 566 358" fill="none" aria-hidden="true">
                        <path d="M165 78 H225 V165" stroke="#B8CBC1" stroke-width="1.5" stroke-dasharray="4 4" />
                        <path d="M165 236 H225 V185" stroke="#B8CBC1" stroke-width="1.5" stroke-dasharray="4 4" />
                        <path d="M343 166 H403" stroke="#B8CBC1" stroke-width="1.5" stroke-dasharray="4 4" />
                    </svg>

                    <div class="relative flex w-full flex-col items-center justify-between gap-4 sm:h-[260px] sm:flex-row">
                        {{-- Left Inputs --}}
                        <div class="flex flex-col gap-5 sm:gap-[97px]">
                            {{-- Text Info Card --}}
                            <div class="relative flex h-[60px] w-[145px] items-center gap-2 rounded-[9px] border-[0.8px] border-[#DCE4E0] bg-white p-[10px] shadow-[0_8px_20px_rgba(18,52,36,0.06)]">
                                <div class="z-10">
                                    <p class="text-[6.5px] leading-[10px] text-[#829089]">Text information</p>
                                    <p class="mt-[3px] text-[8px] font-bold leading-3 text-[#35433C]">Title &amp; description</p>
                                </div>
                                <svg class="ml-auto size-7 text-[#B8CBC1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-dasharray="2 2" aria-hidden="true">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                </svg>
                            </div>

                            {{-- Visual Evidence Card --}}
                            <div class="relative flex h-[60px] w-[145px] items-center gap-2 rounded-[9px] border-[0.8px] border-[#DCE4E0] bg-white p-[10px] shadow-[0_8px_20px_rgba(18,52,36,0.06)]">
                                <div class="z-10">
                                    <p class="text-[6.5px] leading-[10px] text-[#829089]">Visual evidence</p>
                                    <p class="mt-[3px] text-[8px] font-bold leading-3 text-[#35433C]">Optional screenshot</p>
                                </div>
                                <svg class="ml-auto size-7 text-[#B8CBC1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-dasharray="2 2" aria-hidden="true">
                                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                    <circle cx="10" cy="12" r="2" />
                                    <path d="m20 17-1.296-1.296a2.41 2.41 0 0 0-3.408 0L9 22" />
                                </svg>
                            </div>
                        </div>

                        {{-- Center Hub Circle --}}
                        <div class="relative z-10 flex size-[120px] shrink-0 flex-col items-center justify-center rounded-full bg-[#0D2D22] text-center shadow-[0_0_0_14px_rgba(39,105,77,0.07),0_15px_35px_rgba(13,45,34,0.14)]">
                            <x-auth.icon name="sparkles" class="size-7 text-[#B8CBC1]" />
                            <p class="pt-[7px] text-[8.5px] font-bold leading-[13px] text-white">Combined analysis</p>
                            <p class="pt-[3px] text-[6.5px] leading-[10px] text-[#8AA99A]">Similarity evidence</p>
                        </div>

                        {{-- Right Output Card --}}
                        <div class="relative flex h-[60px] w-[145px] items-center gap-2 rounded-[9px] border-[0.8px] border-[#DCE4E0] bg-white p-[10px] shadow-[0_8px_20px_rgba(18,52,36,0.06)]">
                            <div class="z-10">
                                <p class="text-[6.5px] leading-[10px] text-[#829089]">Review output</p>
                                <p class="mt-[3px] text-[8px] font-bold leading-3 text-[#35433C]">Ranked candidates</p>
                            </div>
                            <svg class="ml-auto size-7 text-[#B8CBC1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-dasharray="2 2" aria-hidden="true">
                                <path d="m3 17 2 2 4-4" />
                                <path d="m3 7 2 2 4-4" />
                                <path d="M13 6h8" />
                                <path d="M13 12h8" />
                                <path d="M13 18h8" />
                            </svg>
                        </div>
                    </div>

                    {{-- Bottom Pill --}}
                    <div class="mt-6 flex items-center gap-[6px] rounded-full border-[0.8px] border-[#DFE6E2] bg-white px-[10px] py-[7px]">
                        <x-auth.icon name="shield" class="size-[14px] text-[#236B51]" />
                        <span class="text-[7px] leading-[10px] text-[#637169]">
                            Text-only analysis remains available without a screenshot
                        </span>
                    </div>
                </div>
            </div>

            {{-- Right Column: What is TwinBug? --}}
            <div class="lg:col-span-6 flex w-full max-w-[600px] flex-col items-start">
                <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#236B51]">
                    About the platform
                </span>
                <h2 class="pt-[10px] text-[32px] font-normal leading-[1.16] tracking-[-1.3px] text-[#14221B] sm:text-[43px] sm:leading-[50px]">
                    What is TwinBug?
                </h2>
                <p class="pt-[13px] text-[12px] leading-[21px] text-[#67736D]">
                    TwinBug is a multimodal duplicate bug report detection platform designed to help teams identify similar reports through combined text and image analysis.
                </p>

                <div class="mt-[27px] flex w-full flex-col gap-[10px]">
                    {{-- Item 1 --}}
                    <div class="flex items-start gap-[11px] rounded-[8px] border-[0.8px] border-[#E3E9E6] p-[11px]">
                        <div class="grid size-[31px] shrink-0 place-items-center rounded-[7px] bg-[#F2F8F5] text-[#236B51]">
                            <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                                <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                                <path d="M10 9H8" />
                                <path d="M16 13H8" />
                                <path d="M16 17H8" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-bold leading-[15px] text-[#17201C]">Text Similarity</h3>
                            <p class="pt-[3px] text-[8.5px] leading-[13px] text-[#67736D]">
                                Compares descriptions and textual information provided in bug reports.
                            </p>
                        </div>
                    </div>

                    {{-- Item 2 --}}
                    <div class="flex items-start gap-[11px] rounded-[8px] border-[0.8px] border-[#E3E9E6] p-[11px]">
                        <div class="grid size-[31px] shrink-0 place-items-center rounded-[7px] bg-[#F2F8F5] text-[#236B51]">
                            <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                <circle cx="9" cy="9" r="2" />
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-bold leading-[15px] text-[#17201C]">Image Similarity</h3>
                            <p class="pt-[3px] text-[8.5px] leading-[13px] text-[#67736D]">
                                Analyzes visual similarities in attached screenshots when provided.
                            </p>
                        </div>
                    </div>

                    {{-- Item 3 --}}
                    <div class="flex items-start gap-[11px] rounded-[8px] border-[0.8px] border-[#E3E9E6] p-[11px]">
                        <div class="grid size-[31px] shrink-0 place-items-center rounded-[7px] bg-[#F2F8F5] text-[#236B51]">
                            <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="16" y="16" width="6" height="6" rx="1" />
                                <rect x="2" y="16" width="6" height="6" rx="1" />
                                <rect x="9" y="2" width="6" height="6" rx="1" />
                                <path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                                <path d="M12 12V8" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[10px] font-bold leading-[15px] text-[#17201C]">Combined Analysis</h3>
                            <p class="pt-[3px] text-[8.5px] leading-[13px] text-[#67736D]">
                                Brings text and image evidence together to surface potential duplicates.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- ======================== CORE CAPABILITIES SECTION ======================== --}}
    <section id="features" class="w-full bg-[#0D2D22] py-[92px] text-white">
        <div class="mx-auto max-w-[1528px] px-6 sm:px-10 lg:px-[106.96px]">
            <div class="mx-auto flex max-w-[740px] flex-col items-center text-center">
                <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#8BC2A8]">
                    Core capabilities
                </span>
                <h2 class="pt-[10px] text-[30px] font-normal leading-[1.16] tracking-[-1.3px] text-white sm:text-[43px] sm:leading-[50px]">
                    Features Designed for Smarter Bug Detection
                </h2>
                <p class="pt-[13px] max-w-[640px] text-[12px] leading-[21px] text-[#AEBFB7]">
                    Focused tools help Reporters provide useful evidence and help Developers review candidate duplicates.
                </p>
            </div>

            <div class="mx-auto mt-10 grid max-w-[1190px] grid-cols-1 gap-[15px] sm:grid-cols-2 lg:grid-cols-4">
                {{-- Feature 1 --}}
                <article class="flex flex-col items-start rounded-[11px] border-[0.8px] border-white/[0.11] bg-white/[0.055] p-[22px]">
                    <div class="grid size-[39px] place-items-center rounded-[9px] bg-white/[0.09] text-[#9DCEB7]">
                        <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                        </svg>
                    </div>
                    <h3 class="pt-[15px] text-[13px] font-normal leading-5 text-white">Text-Based Similarity</h3>
                    <p class="pt-[7px] text-[10px] leading-4 text-[#A6B9B0]">
                        Analyze textual information from submitted bug reports to detect semantic overlap.
                    </p>
                </article>

                {{-- Feature 2 --}}
                <article class="flex flex-col items-start rounded-[11px] border-[0.8px] border-white/[0.11] bg-white/[0.055] p-[22px]">
                    <div class="grid size-[39px] place-items-center rounded-[9px] bg-white/[0.09] text-[#9DCEB7]">
                        <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                            <circle cx="9" cy="9" r="2" />
                            <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                        </svg>
                    </div>
                    <h3 class="pt-[15px] text-[13px] font-normal leading-5 text-white">Image-Based Similarity</h3>
                    <p class="pt-[7px] text-[10px] leading-4 text-[#A6B9B0]">
                        Compare screenshots to identify visual similarities and extracted error context.
                    </p>
                </article>

                {{-- Feature 3 --}}
                <article class="flex flex-col items-start rounded-[11px] border-[0.8px] border-white/[0.11] bg-white/[0.055] p-[22px]">
                    <div class="grid size-[39px] place-items-center rounded-[9px] bg-white/[0.09] text-[#9DCEB7]">
                        <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 8V4H8" />
                            <rect width="16" height="12" x="4" y="8" rx="2" />
                            <path d="M2 14h2" />
                            <path d="M20 14h2" />
                            <path d="M15 13v2" />
                            <path d="M9 13v2" />
                        </svg>
                    </div>
                    <h3 class="pt-[15px] text-[13px] font-normal leading-5 text-white">Multimodal Detection</h3>
                    <p class="pt-[7px] text-[10px] leading-4 text-[#A6B9B0]">
                        Combine textual and visual similarity information for more reliable candidate ranking.
                    </p>
                </article>

                {{-- Feature 4 --}}
                <article class="flex flex-col items-start rounded-[11px] border-[0.8px] border-white/[0.11] bg-white/[0.055] p-[22px]">
                    <div class="grid size-[39px] place-items-center rounded-[9px] bg-white/[0.09] text-[#9DCEB7]">
                        <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m3 17 2 2 4-4" />
                            <path d="m3 7 2 2 4-4" />
                            <path d="M13 6h8" />
                            <path d="M13 12h8" />
                            <path d="M13 18h8" />
                        </svg>
                    </div>
                    <h3 class="pt-[15px] text-[13px] font-normal leading-5 text-white">Bug Report Management</h3>
                    <p class="pt-[7px] text-[10px] leading-4 text-[#A6B9B0]">
                        Organize submitted reports and help developers review and triage issues cleanly.
                    </p>
                </article>
            </div>
        </div>
    </section>

    {{-- ======================== HOW IT WORKS SECTION ======================== --}}
    <section id="how-it-works" class="w-full bg-white py-[92px]">
        <div class="mx-auto max-w-[1528px] px-6 sm:px-10 lg:px-[106.96px]">
            <div class="mx-auto flex max-w-[740px] flex-col items-center text-center">
                <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#236B51]">
                    Simple workflow
                </span>
                <h2 class="pt-[10px] text-[32px] font-normal leading-[1.16] tracking-[-1.3px] text-[#14221B] sm:text-[43px] sm:leading-[50px]">
                    How TwinBug Works
                </h2>
                <p class="pt-[13px] text-[12px] leading-[21px] text-[#67736D]">
                    From submission to review, each stage keeps the available evidence clear.
                </p>
            </div>

            <div class="relative mx-auto mt-10 max-w-[1200px]">
                {{-- Horizontal Connecting Line --}}
                <div class="pointer-events-none absolute top-[29px] left-[132px] right-[132px] hidden h-[1px] bg-[#CDDBD4] lg:block" aria-hidden="true"></div>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4 lg:gap-0">
                    {{-- Step 01 --}}
                    <article class="flex flex-col items-center px-[19px] text-center">
                        <div class="relative grid size-[59px] place-items-center rounded-full border-[0.8px] border-[#CFDCD5] bg-white text-[#236B51] shadow-[0_0_0_7px_#FFFFFF]">
                            <x-auth.icon name="upload" class="size-[21px] stroke-[1.75]" />
                            <span class="absolute -right-[2px] -top-[3px] grid size-5 place-items-center rounded-full bg-[#123C2E] text-[6px] font-bold leading-[9px] text-white">01</span>
                        </div>
                        <h3 class="pt-4 text-[11.5px] font-normal leading-[17px] text-[#17201C]">Submit Bug Report</h3>
                        <p class="pt-[7px] text-[9px] leading-[14px] text-[#67736D]">
                            Enter a title, description, and screenshot if one is available.
                        </p>
                    </article>

                    {{-- Step 02 --}}
                    <article class="flex flex-col items-center px-[19px] text-center">
                        <div class="relative grid size-[59px] place-items-center rounded-full border-[0.8px] border-[#CFDCD5] bg-white text-[#236B51] shadow-[0_0_0_7px_#FFFFFF]">
                            <x-auth.icon name="sparkles" class="size-[21px] stroke-[1.75]" />
                            <span class="absolute -right-[2px] -top-[3px] grid size-5 place-items-center rounded-full bg-[#123C2E] text-[6px] font-bold leading-[9px] text-white">02</span>
                        </div>
                        <h3 class="pt-4 text-[11.5px] font-normal leading-[17px] text-[#17201C]">Analyze Report</h3>
                        <p class="pt-[7px] text-[9px] leading-[14px] text-[#67736D]">
                            TwinBug analyzes the available text and image information.
                        </p>
                    </article>

                    {{-- Step 03 --}}
                    <article class="flex flex-col items-center px-[19px] text-center">
                        <div class="relative grid size-[59px] place-items-center rounded-full border-[0.8px] border-[#CFDCD5] bg-white text-[#236B51] shadow-[0_0_0_7px_#FFFFFF]">
                            <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="16" y="16" width="6" height="6" rx="1" />
                                <rect x="2" y="16" width="6" height="6" rx="1" />
                                <rect x="9" y="2" width="6" height="6" rx="1" />
                                <path d="M5 16v-3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3" />
                                <path d="M12 12V8" />
                            </svg>
                            <span class="absolute -right-[2px] -top-[3px] grid size-5 place-items-center rounded-full bg-[#123C2E] text-[6px] font-bold leading-[9px] text-white">03</span>
                        </div>
                        <h3 class="pt-4 text-[11.5px] font-normal leading-[17px] text-[#17201C]">Similarity Comparison</h3>
                        <p class="pt-[7px] text-[9px] leading-[14px] text-[#67736D]">
                            The report is compared with historical reports to surface related candidates.
                        </p>
                    </article>

                    {{-- Step 04 --}}
                    <article class="flex flex-col items-center px-[19px] text-center">
                        <div class="relative grid size-[59px] place-items-center rounded-full border-[0.8px] border-[#CFDCD5] bg-white text-[#236B51] shadow-[0_0_0_7px_#FFFFFF]">
                            <svg class="size-[21px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m3 17 2 2 4-4" />
                                <path d="m3 7 2 2 4-4" />
                                <path d="M13 6h8" />
                                <path d="M13 12h8" />
                                <path d="M13 18h8" />
                            </svg>
                            <span class="absolute -right-[2px] -top-[3px] grid size-5 place-items-center rounded-full bg-[#123C2E] text-[6px] font-bold leading-[9px] text-white">04</span>
                        </div>
                        <h3 class="pt-4 text-[11.5px] font-normal leading-[17px] text-[#17201C]">Review Detection Results</h3>
                        <p class="pt-[7px] text-[9px] leading-[14px] text-[#67736D]">
                            Developers review the evidence before deciding the next action.
                        </p>
                    </article>
                </div>
            </div>

            {{-- Bottom Note Callout --}}
            <div class="mx-auto mt-[37px] flex max-w-[630px] items-center gap-[9px] rounded-[8px] border-[0.8px] border-[#D9E8E0] bg-[#F2F8F5] px-[13px] py-[11px]">
                <x-auth.icon name="shield" class="size-[18px] shrink-0 text-[#236B51]" />
                <div>
                    <p class="text-[9px] font-bold leading-[14px] text-[#3D4E45]">Screenshot optional</p>
                    <p class="pt-[2px] text-[8px] leading-3 text-[#67736D]">
                        Analysis can continue using textual information when no screenshot is attached.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== ROLES SECTION ======================== --}}
    <section id="roles" class="w-full bg-[#F7F9F8] py-[92px]">
        <div class="mx-auto max-w-[1528px] px-6 sm:px-10 lg:px-[106.96px]">
            <div class="mx-auto flex max-w-[740px] flex-col items-center text-center">
                <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#236B51]">
                    Role-focused experiences
                </span>
                <h2 class="pt-[10px] text-[30px] font-normal leading-[1.16] tracking-[-1.3px] text-[#14221B] sm:text-[43px] sm:leading-[50px]">
                    Designed for Different Roles in Bug Management
                </h2>
                <p class="pt-[13px] text-[12px] leading-[21px] text-[#67736D]">
                    Each workspace prioritizes the information and actions relevant to your role.
                </p>
            </div>

            <div class="mx-auto mt-10 grid max-w-[900px] grid-cols-1 gap-[17px] md:grid-cols-2">
                {{-- Reporter Card --}}
                <article class="flex flex-col justify-between rounded-[12px] border-[0.8px] border-[#E2E8E5] bg-white p-[23px]">
                    <div>
                        <div class="flex items-center gap-[11px] border-b-[0.8px] border-[#E2E8E5] pb-4">
                            <div class="grid size-[41px] place-items-center rounded-[9px] bg-[#F2F8F5] text-[#236B51]">
                                <x-auth.icon name="user" class="size-[23px] stroke-[1.9]" />
                            </div>
                            <div>
                                <p class="text-[8px] leading-3 text-[#67736D]">For issue contributors</p>
                                <h3 class="pt-[2px] text-[15px] font-normal leading-[22px] text-[#17201C]">Reporter</h3>
                            </div>
                        </div>

                        <ul class="space-y-[10px] py-[17px]">
                            @foreach (['Submit new bug reports', 'Provide descriptions and screenshots', 'Monitor submitted report status', 'View report history'] as $item)
                                <li class="flex items-center gap-2">
                                    <x-auth.icon name="check" class="size-[14px] shrink-0 text-[#236B51]" />
                                    <span class="text-[9.5px] leading-[14px] text-[#56635C]">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <a href="{{ route('register') }}"
                        class="inline-flex h-10 w-full items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        <span>Get Started as Reporter</span>
                        <svg class="size-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </article>

                {{-- Developer Card --}}
                <article class="flex flex-col justify-between rounded-[12px] border-[0.8px] border-[#E2E8E5] bg-white p-[23px]">
                    <div>
                        <div class="flex items-center gap-[11px] border-b-[0.8px] border-[#E2E8E5] pb-4">
                            <div class="grid size-[41px] place-items-center rounded-[9px] bg-[#F2F8F5] text-[#236B51]">
                                <x-auth.icon name="code" class="size-[23px] stroke-[1.9]" />
                            </div>
                            <div>
                                <p class="text-[8px] leading-3 text-[#67736D]">For development teams</p>
                                <h3 class="pt-[2px] text-[15px] font-normal leading-[22px] text-[#17201C]">Developer</h3>
                            </div>
                        </div>

                        <ul class="space-y-[10px] py-[17px]">
                            @foreach (['Review submitted bug reports', 'Examine potential duplicate reports', 'Review similarity analysis results', 'Support bug report triage decisions'] as $item)
                                <li class="flex items-center gap-2">
                                    <x-auth.icon name="check" class="size-[14px] shrink-0 text-[#236B51]" />
                                    <span class="text-[9.5px] leading-[14px] text-[#56635C]">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <a href="{{ route('register') }}"
                        class="inline-flex h-10 w-full items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        <span>Get Started as Developer</span>
                        <svg class="size-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                </article>
            </div>

            <div class="mt-[17px] flex items-center justify-center gap-[6px] text-[#7C8882]">
                <svg class="size-[14px] shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                    <path d="M12 17h.01" />
                </svg>
                <span class="text-[8px] leading-3">
                    Access depends on the role assigned or selected during onboarding.
                </span>
            </div>
        </div>
    </section>

    {{-- ======================== CTA BANNER SECTION ======================== --}}
    <section class="w-full bg-white pt-[75px]">
        <div class="mx-auto max-w-[1528px] px-6 sm:px-10 lg:px-[106.96px]">
            <div class="flex flex-col items-start justify-between gap-8 rounded-[16px] border-[0.8px] border-[#D5E7DD] bg-[#EAF4EF] px-6 py-10 sm:px-10 lg:flex-row lg:items-center lg:gap-[45px] lg:px-[65px] lg:py-[47px]">
                <div class="max-w-[720px]">
                    <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#236B51]">
                        Start with clearer reports
                    </span>
                    <h2 class="pt-[10px] text-[30px] font-normal leading-[1.16] tracking-[-1.3px] text-[#14221B] sm:text-[42px] sm:leading-[49px]">
                        Ready to Make Bug Management More Efficient?
                    </h2>
                    <p class="pt-[13px] text-[11px] leading-[18px] text-[#5B6B63]">
                        Start using TwinBug to organize bug reports and identify potential duplicates faster.
                    </p>
                </div>

                <div class="flex shrink-0 flex-wrap items-center gap-2">
                    <a href="{{ route('register') }}"
                        class="inline-flex h-[46px] items-center justify-center gap-[7px] rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#0D2D22]">
                        <span>Get Started</span>
                        <svg class="size-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M5 12h14" />
                            <path d="m12 5 7 7-7 7" />
                        </svg>
                    </a>
                    <a href="#about"
                        class="inline-flex h-[46px] items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        Explore TwinBug
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ======================== FAQ SECTION ======================== --}}
    <section id="faq" class="w-full bg-white pt-[75px] pb-[92px]">
        <div class="mx-auto max-w-[1020px] px-6 sm:px-10 lg:px-[106.96px]">
            <div class="mx-auto flex max-w-[740px] flex-col items-center text-center">
                <span class="text-[9px] font-bold uppercase leading-[14px] tracking-[1.08px] text-[#236B51]">
                    Quick answers
                </span>
                <h2 class="pt-[10px] text-[32px] font-normal leading-[1.16] tracking-[-1.3px] text-[#14221B] sm:text-[43px] sm:leading-[50px]">
                    Frequently Asked Questions
                </h2>
                <p class="pt-[13px] text-[12px] leading-[21px] text-[#67736D]">
                    Understand the core workflow before getting started.
                </p>
            </div>

            @php
                $landingFaqs = [
                    [
                        'question' => 'What is TwinBug?',
                        'answer' => 'TwinBug is a multimodal platform that helps teams detect duplicate bug reports using both text and image similarity analysis.',
                    ],
                    [
                        'question' => 'How does TwinBug detect duplicate bug reports?',
                        'answer' => 'TwinBug combines semantic text comparison with visual and OCR screenshot analysis to rank historical reports that are most similar to a newly submitted issue.',
                    ],
                    [
                        'question' => 'Can I submit a bug report without a screenshot?',
                        'answer' => 'Yes. Attaching a screenshot is optional, and TwinBug will still analyze the report title and description to find potential duplicates.',
                    ],
                    [
                        'question' => 'Who can use TwinBug?',
                        'answer' => 'Development teams and issue contributors can use TwinBug. Each workspace separates applications cleanly for teams managing software products.',
                    ],
                    [
                        'question' => 'What is the difference between Reporter and Developer?',
                        'answer' => 'Reporters submit bug reports, attach evidence, and track status updates, while Developers review incoming reports, inspect similarity scores, and make triage decisions.',
                    ],
                    [
                        'question' => 'Does TwinBug automatically classify every report as duplicate?',
                        'answer' => 'No. TwinBug surfaces ranked similarity candidates and evidence, while Developers make the final decision on whether a report is a duplicate.',
                    ],
                ];
            @endphp

            <div class="mt-10 flex flex-col gap-2" data-landing-faq>
                @foreach ($landingFaqs as $index => $faq)
                    @php $isOpen = $index === 0; @endphp
                    <article
                        class="overflow-hidden rounded-[9px] border-[0.8px] transition-colors {{ $isOpen ? 'border-[#CDDBD4] bg-[#FBFDFC]' : 'border-[#E2E8E5] bg-white' }}"
                        data-faq-item
                        data-open="{{ $isOpen ? 'true' : 'false' }}">
                        <button
                            type="button"
                            class="flex min-h-[61px] w-full cursor-pointer items-center justify-between gap-4 px-[17px] text-left"
                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                            data-faq-trigger>
                            <span class="text-[10.5px] font-bold leading-4 text-[#17201C]">{{ $faq['question'] }}</span>
                            <span class="grid size-[25px] shrink-0 place-items-center rounded-full bg-[#F2F5F3] text-[16px] leading-6 text-[#236B51]" data-faq-symbol>
                                {{ $isOpen ? '−' : '+' }}
                            </span>
                        </button>
                        <div class="{{ $isOpen ? '' : 'hidden' }} px-[17px] pb-4" data-faq-panel>
                            <p class="text-[9.5px] leading-4 text-[#67736D]">{{ $faq['answer'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-[22px]">
                <a href="{{ route('faq') }}"
                    class="inline-flex h-10 items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                    <span>View All FAQs</span>
                    <svg class="size-[15px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14" />
                        <path d="m12 5 7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.querySelectorAll('[data-landing-faq] [data-faq-trigger]').forEach((trigger) => {
                trigger.addEventListener('click', () => {
                    const item = trigger.closest('[data-faq-item]');
                    const panel = item?.querySelector('[data-faq-panel]');
                    const symbol = item?.querySelector('[data-faq-symbol]');
                    if (!item || !panel || !symbol) return;

                    const isOpen = item.dataset.open === 'true';
                    item.dataset.open = isOpen ? 'false' : 'true';
                    trigger.setAttribute('aria-expanded', String(!isOpen));
                    panel.classList.toggle('hidden', isOpen);
                    symbol.textContent = isOpen ? '+' : '−';

                    item.classList.toggle('border-[#CDDBD4]', !isOpen);
                    item.classList.toggle('bg-[#FBFDFC]', !isOpen);
                    item.classList.toggle('border-[#E2E8E5]', isOpen);
                    item.classList.toggle('bg-white', isOpen);
                });
            });
        </script>
    @endpush

</x-layouts.landing>
