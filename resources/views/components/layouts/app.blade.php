<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" data-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'light') : 'light' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwinBug' }} — TwinBug</title>
    <meta name="description"
        content="{{ $metaDescription ?? 'Sistem deteksi duplikasi bug multimodal berbasis AI untuk tim pengembang.' }}">
    <script>
        (function () {
            var theme = document.documentElement.getAttribute('data-theme') || 'light';
            var applyTheme = function (mode) {
                var isDark = mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', isDark);
            };
            applyTheme(theme);
            if (theme === 'system' && window.matchMedia) {
                window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
                    if ((document.documentElement.getAttribute('data-theme') || 'light') === 'system') {
                        applyTheme('system');
                    }
                });
            }
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body @class([
    'min-h-full bg-parchment font-sans text-body antialiased',
    'developer-dashboard-layout' => request()->routeIs('dashboard') && auth()->user()?->role->value === 'developer',
    'workspace-shell' => auth()->check() && ((request()->routeIs('dashboard') && auth()->user()?->role->value !== 'developer') || request()->routeIs('about') || request()->routeIs('faq') || request()->routeIs('profile') || request()->routeIs('settings') || request()->routeIs('bug-reports.*') || request()->routeIs('report.detection_result')),
])>

    {{-- Subtle ambient background glow --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div
            class="absolute -right-24 -top-24 size-96 rounded-full bg-sage-100/60 blur-[100px] motion-safe:animate-orb-drift">
        </div>
        <div
            class="absolute -left-20 top-1/2 size-80 rounded-full bg-emerald-50/50 blur-[100px] motion-safe:animate-orb-drift [animation-delay:-5s]">
        </div>
    </div>

    @auth
        @if ((request()->routeIs('dashboard') && auth()->user()?->role->value !== 'developer') || request()->routeIs('about') || request()->routeIs('faq') || request()->routeIs('profile') || request()->routeIs('settings') || request()->routeIs('bug-reports.*') || request()->routeIs('report.detection_result'))
            @php
                $isReporter = auth()->user()?->role === \App\Enums\UserRole::Reporter;
                $currentDetectionReport = request()->route('bugReport');
                $detectionResultsUrl = request()->routeIs('report.detection_result') && $currentDetectionReport
                    ? route('report.detection_result', $currentDetectionReport)
                    : route('bug-reports.index');
                $topbarTitle = request()->routeIs('dashboard')
                    ? __('Reporter Dashboard')
                    : (request()->routeIs('bug-reports.create')
                        ? __('Create Bug Report')
                        : (request()->routeIs('report.detection_result')
                            ? __('Detection Results')
                            : (request()->routeIs('about')
                                ? __('About Us')
                                : (request()->routeIs('faq')
                                    ? __('FAQ')
                                    : (request()->routeIs('profile')
                                        ? __('My Profile')
                                        : (request()->routeIs('settings')
                                            ? __('Manage Settings')
                                            : ($isReporter ? __('Report History') : __('Bug Reports'))))))));
            @endphp
            <aside class="workspace-sidebar flex flex-col justify-between bg-white px-[14px] pb-[14px] border-r-[0.8px] border-[#E2E8E5]" aria-label="Workspace navigation">
                <div class="flex flex-col">
                    {{-- Brand --}}
                    <div class="flex h-[54px] items-center justify-between border-b-[0.8px] border-[#EDF0EE] px-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-[10px]">
                            <span class="grid size-[34px] place-items-center rounded-[9px] bg-[#123C2E] text-white shadow-[0_4px_12px_rgba(18,60,46,0.17)]">
                                <x-auth.icon name="bug" class="size-5 stroke-[2]" />
                            </span>
                            <span class="text-[19px] font-bold leading-[28px] tracking-[-0.45px] text-[#0D2D22]">TwinBug</span>
                        </a>
                    </div>

                    {{-- Navigation --}}
                    <div class="pt-[18px]">
                        <p class="px-3 pt-[10px] text-[10px] font-bold uppercase leading-[15px] tracking-[1.1px] text-[#98A19C]">Main menu</p>
                        <nav class="mt-[7px] space-y-[2px]" aria-label="Main menu">
                            <a href="{{ route('dashboard') }}" @class([
                                'flex h-[42px] items-center justify-between rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('dashboard'),
                                'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('dashboard'),
                            ])>
                                <span class="flex items-center gap-[11px]">
                                    <x-auth.icon name="home" class="size-[18px]" />
                                    <span>{{ __('Dashboard') }}</span>
                                </span>
                                @if (request()->routeIs('dashboard'))
                                    <span class="h-[18px] w-[3px] rounded-[3px] bg-[#236B51]"></span>
                                @endif
                            </a>
                            @if ($isReporter)
                                <a href="{{ route('bug-reports.create') }}" @class([
                                    'flex h-[42px] items-center justify-between rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                    'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('bug-reports.create'),
                                    'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('bug-reports.create'),
                                ])>
                                    <span class="flex items-center gap-[11px]">
                                        <svg class="size-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M5 12h14" />
                                            <path d="M12 5v14" />
                                        </svg>
                                        <span>{{ __('Create Bug Report') }}</span>
                                    </span>
                                    @if (request()->routeIs('bug-reports.create'))
                                        <span class="h-[18px] w-[3px] rounded-[3px] bg-[#236B51]"></span>
                                    @endif
                                </a>
                                <a href="{{ route('bug-reports.index') }}" @class([
                                    'flex h-[42px] items-center justify-between rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                    'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('bug-reports.index') || request()->routeIs('bug-reports.show') || request()->routeIs('report.detection_result'),
                                    'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! (request()->routeIs('bug-reports.index') || request()->routeIs('bug-reports.show') || request()->routeIs('report.detection_result')),
                                ])>
                                    <span class="flex items-center gap-[11px]">
                                        <x-auth.icon name="filter" class="size-[18px]" />
                                        <span>{{ __('Report History') }}</span>
                                    </span>
                                    @if (request()->routeIs('bug-reports.index') || request()->routeIs('bug-reports.show') || request()->routeIs('report.detection_result'))
                                        <span class="h-[18px] w-[3px] rounded-[3px] bg-[#236B51]"></span>
                                    @endif
                                </a>
                            @else
                                <a href="{{ $detectionResultsUrl }}" @class([
                                    'flex h-[42px] items-center justify-between rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                    'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('report.detection_result'),
                                    'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('report.detection_result'),
                                ])>
                                    <span class="flex items-center gap-[11px]">
                                        <x-auth.icon name="search" class="size-[18px]" />
                                        <span>{{ __('Detection Results') }}</span>
                                    </span>
                                    @if (request()->routeIs('report.detection_result'))
                                        <span class="h-[18px] w-[3px] rounded-[3px] bg-[#236B51]"></span>
                                    @endif
                                </a>
                                <a href="{{ route('bug-reports.index') }}" @class([
                                    'flex h-[42px] items-center justify-between rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                    'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('bug-reports.*'),
                                    'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('bug-reports.*'),
                                ])>
                                    <span class="flex items-center gap-[11px]">
                                        <x-auth.icon name="filter" class="size-[18px]" />
                                        <span>{{ __('Bug Reports') }}</span>
                                    </span>
                                    @if (request()->routeIs('bug-reports.*'))
                                        <span class="h-[18px] w-[3px] rounded-[3px] bg-[#236B51]"></span>
                                    @endif
                                </a>
                            @endif
                        </nav>

                        <p class="px-3 pt-[10px] text-[10px] font-bold uppercase leading-[15px] tracking-[1.1px] text-[#98A19C]">General</p>
                        <nav class="mt-[7px] space-y-[2px]" aria-label="General">
                            <a href="{{ route('about') }}" @class([
                                'flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('about'),
                                'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('about'),
                            ])>
                                <x-auth.icon name="user" class="size-[18px]" />
                                <span>{{ __('About Us') }}</span>
                            </a>
                            <a href="{{ route('faq') }}" @class([
                                'flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('faq'),
                                'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('faq'),
                            ])>
                                <x-auth.icon name="alert" class="size-[18px]" />
                                <span>{{ __('FAQ') }}</span>
                            </a>
                        </nav>

                        <p class="px-3 pt-[10px] text-[10px] font-bold uppercase leading-[15px] tracking-[1.1px] text-[#98A19C]">Account</p>
                        <nav class="mt-[7px] space-y-[2px]" aria-label="Account">
                            <a href="{{ route('profile') }}" @class([
                                'flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('profile'),
                                'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('profile'),
                            ])>
                                <x-auth.icon name="user" class="size-[18px]" />
                                <span>{{ __('My Profile') }}</span>
                            </a>
                            <a href="{{ route('settings') }}" @class([
                                'flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] leading-5 transition',
                                'bg-[#F2F8F5] font-semibold text-[#19513D]' => request()->routeIs('settings'),
                                'font-medium text-[#65706B] hover:bg-[#F7F9F8]' => ! request()->routeIs('settings'),
                            ])>
                                <x-auth.icon name="code" class="size-[18px]" />
                                <span>{{ __('Manage Settings') }}</span>
                                @if (request()->routeIs('settings'))
                                    <span class="ml-auto h-[18px] w-[3px] rounded-full bg-[#236B51]"></span>
                                @endif
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex h-[42px] w-full cursor-pointer items-center gap-[11px] rounded-[8px] px-3 text-left text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                                    <x-auth.icon name="logout" class="size-[18px]" />
                                    <span>Logout</span>
                                </button>
                            </form>
                        </nav>
                    </div>
                </div>

                {{-- Bottom Help Card + User --}}
                <div class="mt-4 flex flex-col">
                    <div class="rounded-[10px] border-[0.8px] border-[#E5EBE8] bg-[#F5F8F6] p-[14px]">
                        <span class="grid size-[30px] place-items-center rounded-[8px] border-[0.8px] border-[#DFE7E3] bg-white text-[#236B51]">
                            <x-auth.icon name="shield" class="size-[18px]" />
                        </span>
                        <h2 class="mt-[10px] text-[13px] font-bold leading-5 text-[#09090B]">Need help?</h2>
                        <p class="mt-1 text-[11.5px] leading-[17px] text-[#67736D]">Read our guide to writing high-quality bug reports.</p>
                        <a href="{{ route('faq') }}" class="mt-[10px] inline-flex items-center gap-[5px] text-[11.5px] font-semibold leading-[17px] text-[#236B51] hover:underline">
                            <span>Open help center</span>
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>

                    <div class="mt-[14px] flex items-center gap-[9px] border-t-[0.8px] border-[#E2E8E5] px-1 pt-[15px]">
                        <span class="grid size-[34px] shrink-0 place-items-center rounded-full bg-[#DCECE4] text-[11px] font-bold text-[#19513D]">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[12.5px] font-bold leading-[19px] text-[#09090B]">{{ auth()->user()->name }}</p>
                            <p class="truncate text-[10.5px] leading-4 text-[#67736D]">{{ auth()->user()->role->label() }}</p>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="workspace-topbar h-[72px] border-b-[0.8px] border-[#E2E8E5] bg-white/96 px-8 backdrop-blur-md">
                <div class="flex items-center gap-[7px] text-[11px]">
                    <span class="text-[#8A9590]">{{ __('Workspace') }}</span>
                    <span class="text-[#8A9590]">›</span>
                    <span class="font-semibold text-[#34423B]">{{ $topbarTitle }}</span>
                </div>
                <nav class="workspace-mobile-nav" aria-label="Workspace navigation">
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    <a href="{{ route('bug-reports.index') }}">Bug Reports</a>
                    <a href="{{ route('about') }}">About Us</a>
                    <a href="{{ route('faq') }}">FAQ</a>
                </nav>
                <div class="flex items-center gap-[10px]">
                    <span class="hidden h-[29.6px] items-center gap-[5px] rounded-full border-[0.8px] border-[#DFE6E2] bg-[#F7F9F8] px-[9px] text-[10.5px] text-[#718079] sm:inline-flex">
                        <x-auth.icon name="sparkles" class="size-[14px]" />
                        <span>{{ auth()->user()->application?->name ?? 'Workspace' }}</span>
                    </span>
                    <a href="{{ route('bug-reports.index') }}" class="hidden size-9 place-items-center rounded-[8px] border-[0.8px] border-[#E2E8E5] bg-white text-[#66716C] transition hover:bg-[#F7F9F8] sm:grid" aria-label="Notifications">
                        <x-auth.icon name="alert" class="size-[19px]" />
                    </a>
                    <a href="{{ route('profile') }}" class="flex items-center gap-[9px] px-[3px] py-[2px]" aria-label="Open profile">
                        <span class="grid size-[34px] place-items-center rounded-full bg-[#DCECE4] text-[11px] font-bold text-[#19513D]">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <span class="hidden text-left sm:block">
                            <span class="block text-[12px] font-bold leading-[18px] text-[#24302A]">{{ auth()->user()->name }}</span>
                            <span class="block text-[10px] leading-[15px] text-[#59655F]">{{ auth()->user()->role->label() }}</span>
                        </span>
                    </a>
                </div>
            </div>
        @endif
    @endauth

    {{-- ======================== HEADER ======================== --}}
    <header
        class="fixed inset-x-0 top-0 z-50 h-16 border-b border-divider bg-white/90 shadow-[0_1px_2px_rgba(0,0,0,0.03)] backdrop-blur-md">
        <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

            {{-- Logo --}}
            <a href="{{ auth()->check() ? route('dashboard') : url('/') }}" class="flex items-center gap-2.5 transition hover:opacity-90">
                <span class="grid size-7.5 place-items-center rounded-lg bg-sage-500 text-white shadow-cta">
                    <x-auth.icon name="bug" class="size-4" />
                </span>
                <span class="font-serif text-lg font-bold tracking-tight text-ink">TwinBug</span>
            </a>

            {{-- Nav (desktop) --}}
            <nav class="hidden md:flex items-center gap-1 rounded-xl border border-divider/70 bg-input/70 p-1" aria-label="Navigasi utama">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink {{ request()->routeIs('dashboard') ? 'bg-white text-ink shadow-xs font-semibold' : 'text-label' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('bug-reports.index') }}"
                        class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink {{ request()->routeIs('bug-reports.index') ? 'bg-white text-ink shadow-xs font-semibold' : 'text-label' }}">
                        Daftar Laporan
                    </a>
                @endauth

                <a href="{{ route('about') }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink {{ request()->routeIs('about') ? 'bg-white text-ink shadow-xs font-semibold' : 'text-label' }}">
                    Tentang Kami
                </a>

                <a href="{{ route('faq') }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink {{ request()->routeIs('faq') ? 'bg-white text-ink shadow-xs font-semibold' : 'text-label' }}">
                    Bantuan & FAQ
                </a>
            </nav>

            {{-- CTA + User info + logout (desktop) --}}
            <div class="hidden md:flex items-center gap-2.5">
                @auth
                    <a href="{{ route('bug-reports.create') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-sage-500 px-3 py-1.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600 active:scale-95">
                        <x-auth.icon name="sparkles" class="size-3.5" />
                        <span>+ Buat Laporan</span>
                    </a>

                    <a href="{{ route('profile') }}"
                        class="flex items-center gap-2.5 rounded-xl border border-divider/80 bg-white px-2 py-1 pr-3 shadow-xs transition hover:border-slate-300 hover:bg-input/50">
                        <span
                            class="grid size-7 place-items-center rounded-lg bg-sage-100 text-sage-700 text-xs font-bold shadow-2xs">
                            {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <div>
                            <p class="text-xs font-semibold leading-none text-ink">{{ auth()->user()->name }}</p>
                            <p class="mt-0.5 text-[10px] leading-none text-label">{{ auth()->user()->role->label() }}</p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" id="btn-logout"
                            class="grid size-8 place-items-center rounded-lg border border-divider/80 bg-white text-label transition hover:border-danger/30 hover:bg-danger/5 hover:text-danger"
                            title="Keluar">
                            <x-auth.icon name="logout" class="size-3.5" />
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-2.5 py-1.5 text-xs font-medium text-label transition hover:text-ink">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="rounded-lg bg-sage-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600">
                        Daftar
                    </a>
                @endauth
            </div>

            {{-- Mobile menu toggle --}}
            <button
                id="app-mobile-menu-toggle"
                type="button"
                class="md:hidden flex items-center rounded-lg border border-divider bg-white p-2.5 text-label hover:bg-input transition"
                aria-expanded="false"
                aria-controls="app-mobile-menu"
                aria-label="Buka menu navigasi"
            >
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
        </div>

        {{-- Mobile menu panel --}}
        <div
            id="app-mobile-menu"
            class="md:hidden border-b border-divider bg-white hidden"
            aria-labelledby="app-mobile-menu-toggle"
        >
            <div class="px-4 py-4 space-y-2">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink {{ request()->routeIs('dashboard') ? 'bg-input text-ink font-semibold' : 'text-label' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('bug-reports.index') }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink {{ request()->routeIs('bug-reports.index') ? 'bg-input text-ink font-semibold' : 'text-label' }}">
                        Daftar Laporan
                    </a>
                @endauth
                <a href="{{ route('about') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink {{ request()->routeIs('about') ? 'bg-input text-ink font-semibold' : 'text-label' }}">
                    Tentang Kami
                </a>
                <a href="{{ route('faq') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink {{ request()->routeIs('faq') ? 'bg-input text-ink font-semibold' : 'text-label' }}">
                    Bantuan & FAQ
                </a>
                <div class="border-t border-divider/50 my-3"></div>
                @auth
                    <a href="{{ route('bug-reports.create') }}"
                        class="block rounded-lg bg-sage-500 px-3.5 py-2 text-sm font-semibold text-white text-center shadow-cta transition hover:bg-sage-600">
                        + Buat Laporan
                    </a>
                    <a href="{{ route('profile') }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-label transition hover:bg-input hover:text-ink">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full rounded-lg px-3 py-2 text-left text-sm font-medium text-danger transition hover:bg-danger/5">
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block rounded-lg px-3 py-2 text-sm font-medium text-label transition hover:bg-input hover:text-ink">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}"
                        class="block rounded-lg bg-sage-500 px-3.5 py-2 text-sm font-semibold text-white text-center shadow-cta transition hover:bg-sage-600">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ======================== MAIN ======================== --}}
    <main class="relative pt-16">
        {{ $slot }}
    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="mt-16 border-t border-divider bg-white">
        <div class="mx-auto flex flex-col gap-4 sm:flex-row max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <span class="flex items-center gap-2">
                    <span class="grid size-5 place-items-center rounded-md bg-sage-500 text-white">
                        <x-auth.icon name="bug" class="size-3" />
                    </span>
                    <span class="font-serif text-sm font-bold tracking-tight text-ink">TwinBug</span>
                </span>
                <span class="text-[11px] text-label text-center sm:text-left">© {{ date('Y') }} TwinBug Detection System. Arsitektur Berbasis AI
                    Multimodal.</span>
            </div>
            <nav class="flex flex-wrap justify-center items-center gap-5" aria-label="Footer">
                <a href="{{ route('about') }}" class="text-xs text-label transition hover:text-ink">Tentang Kami</a>
                <a href="{{ route('about') }}" class="text-xs text-label transition hover:text-ink">Dokumentasi API</a>
                <a href="{{ route('faq') }}" class="text-xs text-label transition hover:text-ink">Status Sistem</a>
                <a href="{{ route('faq') }}" class="text-xs text-label transition hover:text-ink">Bantuan & FAQ</a>
            </nav>
        </div>
    </footer>

    <x-auth.toast />
    @stack('scripts')
</body>

</html>
