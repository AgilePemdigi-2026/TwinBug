<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwinBug' }} — TwinBug</title>
    <meta name="description" content="{{ $metaDescription ?? 'Laporkan bug dan temukan duplikasi dengan sistem deteksi duplikasi multimodal berbasis AI untuk tim pengembang.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-full bg-parchment font-sans text-body antialiased selection:bg-sage-200 selection:text-ink">

    {{-- Subtle ambient background glow --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -right-24 -top-24 size-96 rounded-full bg-sage-100/60 blur-[120px] motion-safe:animate-orb-drift"></div>
        <div class="absolute -left-20 top-1/3 size-80 rounded-full bg-emerald-50/60 blur-[120px] motion-safe:animate-orb-drift [animation-delay:-5s]"></div>
        <div class="absolute right-1/4 bottom-1/4 size-72 rounded-full bg-amber-50/40 blur-[100px] motion-safe:animate-orb-drift [animation-delay:-10s]"></div>
    </div>

    {{-- ======================== HEADER ======================== --}}
    <header
        class="fixed inset-x-0 top-0 z-50 h-16 border-b border-divider bg-white/90 shadow-[0_1px_2px_rgba(0,0,0,0.03)] backdrop-blur-md">
        <div class="mx-auto flex h-full max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 transition hover:opacity-90">
                <span class="grid size-7.5 place-items-center rounded-lg bg-sage-500 text-white shadow-cta">
                    <x-auth.icon name="bug" class="size-4" />
                </span>
                <span class="font-serif text-lg font-bold tracking-tight text-ink">TwinBug</span>
                <span class="rounded-full border border-sage-200 bg-sage-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-sage-600">Beta</span>
            </a>

            {{-- Nav --}}
            <nav class="hidden md:flex items-center gap-1 rounded-xl border border-divider/70 bg-input/70 p-1" aria-label="Navigasi utama">
                <a href="{{ route('home') }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink {{ request()->routeIs('home') ? 'bg-white text-ink shadow-xs font-semibold' : 'text-label' }}">
                    Beranda
                </a>
                <a href="#fitur"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink">
                    Fitur
                </a>
                <a href="#cara-kerja"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink">
                    Cara Kerja
                </a>
                <a href="#peran"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink">
                    Peran
                </a>
                <a href="{{ route('about') }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink">
                    Tentang Kami
                </a>
                <a href="{{ route('faq') }}"
                    class="rounded-lg px-3 py-1.5 text-xs font-medium transition hover:text-ink">
                    Bantuan & FAQ
                </a>
            </nav>

            {{-- CTAs --}}
            <div class="hidden md:flex items-center gap-2.5">
                <a href="{{ route('login') }}" class="px-2.5 py-1.5 text-xs font-medium text-label transition hover:text-ink">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="rounded-lg bg-sage-500 px-3.5 py-1.5 text-xs font-semibold text-white shadow-cta transition hover:bg-sage-600">
                    Daftar
                </a>
            </div>

            {{-- Mobile menu toggle --}}
                <button
                id="mobile-menu-toggle"
                type="button"
                class="md:hidden flex items-center gap-2.5 rounded-lg border border-divider bg-white p-2.5 text-label hover:bg-input transition"
                aria-expanded="false"
                aria-controls="mobile-menu"
                aria-label="Buka menu navigasi seluler">
                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="12" x2="21" y2="12" />
                    <line x1="3" y1="6" x2="21" y2="6" />
                    <line x1="3" y1="18" x2="21" y2="18" />
                </svg>
            </button>
        </div>

        {{-- Mobile menu panel --}}
        <div
            id="mobile-menu"
            class="md:hidden border-b border-divider bg-white hidden"
            aria-labelledby="mobile-menu-toggle">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ route('home') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink">
                    Beranda
                </a>
                <a href="#fitur"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink">
                    Fitur
                </a>
                <a href="#cara-kerja"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink">
                    Cara Kerja
                </a>
                <a href="#peran"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink">
                    Peran
                </a>
                <a href="{{ route('about') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink">
                    Tentang Kami
                </a>
                <a href="{{ route('faq') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink">
                    Bantuan & FAQ
                </a>
                <div class="border-t border-divider/50 my-3"></div>
                <a href="{{ route('login') }}"
                    class="block rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-input hover:text-ink">
                    Masuk
                </a>
                <a href="{{ route('register') }}"
                    class="block rounded-lg bg-sage-500 px-3.5 py-1.5 text-sm font-semibold text-white shadow-cta transition hover:bg-sage-600">
                    Daftar
                </a>
            </div>
        </div>
    </header>

    {{-- ======================== MAIN ======================== --}}
    <main class="relative pt-16">
        {{ $slot }}
    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="mt-20 border-t border-divider bg-white/80 backdrop-blur-sm">
        <div class="mx-auto flex flex-col gap-6 sm:flex-row max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center gap-3 text-center sm:text-left">
                <a href="{{ route('home') }}" class="flex items-center gap-2 transition hover:opacity-85">
                    <span class="grid size-6 place-items-center rounded-lg bg-sage-500 text-white shadow-cta">
                        <x-auth.icon name="bug" class="size-3.5" />
                    </span>
                    <span class="font-serif text-sm font-bold tracking-tight text-ink">TwinBug</span>
                    <span class="rounded-full border border-sage-200 bg-sage-50 px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-sage-600">Beta</span>
                </a>
                <span class="hidden sm:inline text-divider">•</span>
                <span class="text-xs text-label">Sistem Deteksi Duplikasi Bug Multimodal Berbasis AI</span>
            </div>
            <nav class="flex flex-wrap justify-center items-center gap-6" aria-label="Footer">
                <a href="{{ route('about') }}" class="text-xs font-medium text-label transition hover:text-ink">Tentang Kami</a>
                <a href="{{ route('faq') }}" class="text-xs font-medium text-label transition hover:text-ink">Bantuan & FAQ</a>
                <a href="{{ route('login') }}" class="text-xs font-medium text-label transition hover:text-ink">Masuk</a>
                <a href="{{ route('register') }}" class="text-xs font-semibold text-sage-600 transition hover:text-sage-700">Daftar Akun</a>
            </nav>
        </div>
        <div class="border-t border-divider/60 py-4 text-center">
            <p class="text-[11px] text-label/70">
                © {{ date('Y') }} TwinBug. Dirancang untuk efisiensi dan transparansi alur pelaporan perangkat lunak.
            </p>
        </div>
    </footer>

    <x-auth.toast />
    @stack('scripts')
</body>

</html>
