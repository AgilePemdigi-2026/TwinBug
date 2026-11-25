<x-layouts.app title="About TwinBug" meta-description="Meet the team behind TwinBug and learn how the platform helps teams detect duplicate bug reports.">
    <section class="about-hero bg-[#0d2d22] text-white">
        <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 sm:py-28 lg:grid-cols-12 lg:px-8">
            <div class="lg:col-span-7">
                <p class="text-xs font-bold uppercase tracking-[.18em] text-[#78b79a]">About Us</p>
                <h1 class="mt-5 max-w-3xl font-sans text-4xl font-normal leading-[1.08] tracking-tight sm:text-6xl lg:text-7xl">Meet the Team Behind TwinBug</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 text-[#b5c8bf] sm:text-lg">We are a team of developers and innovators building smarter solutions for duplicate bug report detection.</p>
            </div>
            <div class="relative mx-auto flex aspect-square w-full max-w-sm items-center justify-center" aria-hidden="true">
                <div class="absolute inset-8 rounded-full border border-dashed border-[#527a68]/60"></div>
                <div class="absolute inset-14 rounded-full border border-[#527a68]/50 bg-[#164333]/60"></div>
                <div class="relative grid size-36 place-items-center rounded-full border border-[#527a68] bg-[#164333] text-[#8fc5a9] shadow-2xl">
                    <x-auth.icon name="bug" class="size-20" />
                    <span class="absolute text-xs font-semibold text-white">Team</span>
                </div>
                <span class="absolute left-0 top-1/3 rounded-2xl border border-white/10 bg-white/5 px-7 py-6 text-sm font-semibold">Build</span>
                <span class="absolute right-0 top-1/3 rounded-2xl border border-white/10 bg-white/5 px-7 py-6 text-sm font-semibold">Analyze</span>
                <span class="absolute bottom-0 rounded-2xl border border-white/10 bg-white/5 px-7 py-6 text-sm font-semibold">Deliver</span>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-28 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[.18em] text-[#398464]">Our Product</p>
        <h2 class="mt-3 font-sans text-3xl font-normal tracking-tight text-[#14231c] sm:text-4xl">About TwinBug</h2>
        <p class="mt-5 max-w-5xl text-base leading-8 text-[#68766f]">TwinBug is a multimodal duplicate bug report detection platform designed to help development teams identify similar bug reports through text and image analysis. By combining semantic text understanding and visual similarity detection, TwinBug helps streamline the bug triage process and improve report management.</p>
        <div class="mt-12 grid gap-5 md:grid-cols-3">
            @foreach ([['bug', 'Multimodal Bug Detection', 'Understand bug reports through both written context and visual evidence.'], ['sparkles', 'AI-Powered Similarity Analysis', 'Surface relevant historical reports through semantic and visual matching.'], ['search', 'Efficient Bug Triage', 'Give development teams structured evidence for faster review decisions.']] as [$icon, $title, $description])
                <article class="rounded-2xl border border-[#e1e9e5] bg-white p-7 shadow-sm">
                    <span class="grid size-12 place-items-center rounded-xl bg-[#f0f7f3] text-[#287755]"><x-auth.icon :name="$icon" class="size-6" /></span>
                    <h3 class="mt-5 text-lg font-semibold text-[#1d2c25]">{{ $title }}</h3>
                    <p class="mt-2 text-sm leading-6 text-[#68766f]">{{ $description }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <section class="bg-[#f4f7f5] px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto max-w-2xl text-center">
                <p class="text-xs font-bold uppercase tracking-[.18em] text-[#398464]">People &amp; Collaboration</p>
                <h2 class="mt-3 font-sans text-3xl font-normal tracking-tight text-[#14231c] sm:text-4xl">Meet Our Team</h2>
                <p class="mt-4 text-base leading-7 text-[#68766f]">Four roles working together to shape TwinBug into a focused, dependable software product.</p>
            </div>
            <div class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($teamMembers as $member)
                    <article class="overflow-hidden rounded-2xl border border-[#e1e9e5] bg-white shadow-sm">
                        <div class="grid aspect-[4/3] place-items-center bg-gradient-to-br from-[#eef4f1] to-[#dce8e2] text-[#849b8f]">
                            <div class="text-center"><x-auth.icon name="user" class="mx-auto size-14" /><p class="mt-3 text-xs">{{ $member['initials'] }}</p></div>
                        </div>
                        <div class="p-5">
                            <p class="text-xs font-bold uppercase tracking-wider text-[#287755]">{{ $member['role'] }}</p>
                            <h3 class="mt-2 text-lg font-semibold leading-snug text-[#1d2c25]">{{ $member['name'] }}</h3>
                            <p class="mt-1 text-xs text-[#7c8983]">{{ $member['nim'] }}</p>
                            <p class="mt-4 min-h-16 text-sm leading-6 text-[#68766f]">{{ $loop->first ? 'Coordinates project planning, sprint management, progress reviews, and alignment with the product goals.' : 'Contributes to the design, development, and refinement of the TwinBug platform and its duplicate detection workflow.' }}</p>
                            <div class="mt-4 border-t border-[#e5ece8] pt-4">
                                <a href="{{ $member['github'] }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-[#287755] hover:underline">{{ '@' . $member['username'] }} <span aria-hidden="true">↗</span></a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-4 rounded-3xl bg-[#0d2d22] p-8 text-white sm:flex-row sm:items-center sm:p-12">
            <span class="grid size-14 shrink-0 place-items-center rounded-xl bg-white/10 text-[#9ccab2]"><x-auth.icon name="sparkles" class="size-7" /></span>
            <div><p class="text-xs font-bold uppercase tracking-[.18em] text-[#78b79a]">Our Mission</p><p class="mt-3 text-2xl leading-tight sm:text-4xl">Making bug tracking smarter, faster, and more efficient through intelligent duplicate detection.</p></div>
        </div>
    </section>
</x-layouts.app>
