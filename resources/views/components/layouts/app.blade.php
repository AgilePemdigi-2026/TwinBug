<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwinBug' }} — TwinBug</title>
    <meta name="description"
        content="{{ $metaDescription ?? 'Sistem deteksi duplikasi bug multimodal berbasis AI untuk tim pengembang.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full bg-parchment font-sans text-body antialiased">

    {{-- Subtle ambient background glow --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div
            class="absolute -right-24 -top-24 size-96 rounded-full bg-sage-100/60 blur-[100px] motion-safe:animate-orb-drift">
        </div>
        <div
            class="absolute -left-20 top-1/2 size-80 rounded-full bg-emerald-50/50 blur-[100px] motion-safe:animate-orb-drift [animation-delay:-5s]">
        </div>
    </div>

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
                <a href="#" class="text-xs text-label transition hover:text-ink">Dokumentasi API</a>
                <a href="#" class="text-xs text-label transition hover:text-ink">Status Sistem</a>
                <a href="{{ route('faq') }}" class="text-xs text-label transition hover:text-ink">Bantuan & FAQ</a>
            </nav>
        </div>
    </footer>

    <x-auth.toast />
    @stack('scripts')
</body>

</html>