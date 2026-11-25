<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth" data-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'light') : 'light' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwinBug' }} — TwinBug</title>
    <meta name="description" content="{{ $metaDescription ?? 'TwinBug helps development teams identify similar bug reports using text and image analysis.' }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function () {
            var theme = document.documentElement.getAttribute('data-theme') || 'light';
            var applyTheme = function (mode) {
                var isDark = mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', isDark);
            };
            applyTheme(theme);
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="public-site min-h-full bg-white font-sans text-[#102018] antialiased selection:bg-[#D7E6DE] selection:text-[#0D2D22]">

    {{-- ======================== HEADER ======================== --}}
    <header class="sticky top-0 z-50 h-[72px] w-full border-b-[0.8px] border-[#E2E8E5] bg-white/96 backdrop-blur-md">
        <div class="mx-auto flex h-full max-w-[1528px] items-center justify-between gap-[26px] px-6 sm:px-10 lg:px-[91.68px]">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-[10px] transition hover:opacity-90">
                <span class="grid size-[34px] place-items-center rounded-[9px] bg-[#123C2E] text-white shadow-[0_4px_12px_rgba(18,60,46,0.17)]">
                    <x-auth.icon name="bug" class="size-5 stroke-[2]" />
                </span>
                <span class="font-sans text-[19px] font-bold leading-[28px] tracking-[-0.45px] text-[#0D2D22]">TwinBug</span>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden md:flex items-center gap-[3px]" aria-label="Landing page navigation">
                <a href="{{ route('home') }}"
                    class="flex h-9 items-center justify-center rounded-[7px] px-[11px] text-[10.5px] font-medium leading-4 transition hover:bg-[#F7F9F8] hover:text-[#0D2D22] {{ request()->routeIs('home') ? 'text-[#0D2D22]' : 'text-[#617068]' }}">
                    Home
                </a>
                <a href="#about"
                    class="flex h-9 items-center justify-center rounded-[7px] px-[11px] text-[10.5px] font-medium leading-4 text-[#617068] transition hover:bg-[#F7F9F8] hover:text-[#0D2D22]">
                    About Us
                </a>
                <a href="#how-it-works"
                    class="flex h-9 items-center justify-center rounded-[7px] px-[11px] text-[10.5px] font-medium leading-4 text-[#617068] transition hover:bg-[#F7F9F8] hover:text-[#0D2D22]">
                    How It Works
                </a>
                <a href="#features"
                    class="flex h-9 items-center justify-center rounded-[7px] px-[11px] text-[10.5px] font-medium leading-4 text-[#617068] transition hover:bg-[#F7F9F8] hover:text-[#0D2D22]">
                    Features
                </a>
                <a href="#faq"
                    class="flex h-9 items-center justify-center rounded-[7px] px-[11px] text-[10.5px] font-medium leading-4 text-[#617068] transition hover:bg-[#F7F9F8] hover:text-[#0D2D22]">
                    FAQ
                </a>
            </nav>

            {{-- Desktop Auth Buttons --}}
            <div class="hidden md:flex shrink-0 items-center gap-2">
                <a href="{{ route('login') }}"
                    class="inline-flex h-10 items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="inline-flex h-10 items-center justify-center rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#0D2D22]">
                    Register
                </a>
            </div>

            {{-- Mobile Menu Toggle --}}
            <button
                id="mobile-menu-toggle"
                type="button"
                class="md:hidden flex items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white p-2.5 text-[#35433C] transition hover:bg-[#F7F9F8]"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="Open mobile navigation menu">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
        </div>

        {{-- Mobile Menu Panel --}}
        <div
            id="mobile-menu"
            class="hidden md:hidden border-b border-[#E2E8E5] bg-white px-6 py-4 space-y-1.5"
            aria-labelledby="mobile-menu-toggle">
            <a href="{{ route('home') }}" class="block rounded-[7px] px-3 py-2 text-xs font-medium text-[#35433C] hover:bg-[#F7F9F8]">Home</a>
            <a href="#about" class="block rounded-[7px] px-3 py-2 text-xs font-medium text-[#617068] hover:bg-[#F7F9F8]">About Us</a>
            <a href="#how-it-works" class="block rounded-[7px] px-3 py-2 text-xs font-medium text-[#617068] hover:bg-[#F7F9F8]">How It Works</a>
            <a href="#features" class="block rounded-[7px] px-3 py-2 text-xs font-medium text-[#617068] hover:bg-[#F7F9F8]">Features</a>
            <a href="#faq" class="block rounded-[7px] px-3 py-2 text-xs font-medium text-[#617068] hover:bg-[#F7F9F8]">FAQ</a>
            <div class="my-2 border-t border-[#E2E8E5]"></div>
            <div class="flex items-center gap-2 pt-1">
                <a href="{{ route('login') }}" class="flex-1 inline-flex h-10 items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white text-xs font-semibold text-[#35433C]">Login</a>
                <a href="{{ route('register') }}" class="flex-1 inline-flex h-10 items-center justify-center rounded-[8px] bg-[#123C2E] text-xs font-semibold text-white">Register</a>
            </div>
        </div>
    </header>

    {{-- ======================== MAIN ======================== --}}
    <main class="relative w-full">
        {{ $slot }}
    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="public-footer w-full bg-[#0A241B] pt-[54px] pb-5 text-white">
        <div class="mx-auto max-w-[1528px] px-6 sm:px-10 lg:px-[106.96px]">
            <div class="grid grid-cols-1 gap-10 pb-[42px] sm:grid-cols-2 lg:grid-cols-12">
                {{-- Brand Column --}}
                <div class="lg:col-span-5 flex flex-col items-start gap-[9px]">
                    <a href="{{ route('home') }}" class="flex items-center gap-[10px] transition hover:opacity-90">
                        <span class="grid size-[34px] place-items-center rounded-[9px] bg-white text-[#123C2E] shadow-[0_4px_12px_rgba(18,60,46,0.17)]">
                            <x-auth.icon name="bug" class="size-5 stroke-[2]" />
                        </span>
                        <span class="font-sans text-[11px] font-bold tracking-[-0.45px] text-[#698277]">TwinBug</span>
                    </a>
                    <p class="max-w-[340px] text-[9.5px] leading-[16px] text-[#91AA9E]">
                        Multimodal duplicate bug report detection for clearer, faster software triage.
                    </p>
                </div>

                {{-- Navigation Column --}}
                <div class="lg:col-span-3 flex flex-col items-start gap-[9px]">
                    <h3 class="pb-[5px] text-[9.5px] font-bold leading-[14px] text-[#D7E4DE]">Navigation</h3>
                    <a href="{{ route('home') }}" class="text-[9px] leading-[14px] text-[#8FA79B] transition hover:text-white">Home</a>
                    <a href="{{ route('about') }}" class="text-[9px] leading-[14px] text-[#8FA79B] transition hover:text-white">About Us</a>
                    <a href="#how-it-works" class="text-[9px] leading-[14px] text-[#8FA79B] transition hover:text-white">How It Works</a>
                    <a href="#features" class="text-[9px] leading-[14px] text-[#8FA79B] transition hover:text-white">Features</a>
                </div>

                {{-- Support Column --}}
                <div class="lg:col-span-2 flex flex-col items-start gap-[9px]">
                    <h3 class="pb-[5px] text-[9.5px] font-bold leading-[14px] text-[#D7E4DE]">Support</h3>
                    <a href="{{ route('faq') }}" class="text-[9px] leading-[14px] text-[#8FA79B] transition hover:text-white">FAQ</a>
                    <span class="text-[8px] leading-[12px] text-[#698277]">Contact channel not available</span>
                </div>

                {{-- Account Column --}}
                <div class="lg:col-span-2 flex flex-col items-start gap-[9px]">
                    <h3 class="pb-[5px] text-[9.5px] font-bold leading-[14px] text-[#D7E4DE]">Account</h3>
                    <a href="{{ route('login') }}" class="text-[9px] leading-[14px] text-[#8FA79B] transition hover:text-white">Login</a>
                    <a href="{{ route('register') }}" class="text-[9px] leading-[14px] text-[#8FA79B] transition hover:text-white">Register</a>
                </div>
            </div>

            <div class="border-t-[0.8px] border-white/10 pt-[18px]">
                <p class="text-[8.5px] leading-[13px] text-[#718A7E]">
                    © {{ date('Y') }} TwinBug. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <x-auth.toast />
    @stack('scripts')
</body>

</html>
