<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwinBug' }} — TwinBug</title>
    <meta name="description" content="{{ $metaDescription ?? 'Sistem deteksi duplikasi bug multimodal berbasis AI untuk tim pengembang.' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-parchment font-sans text-body antialiased">

    {{-- Ambient background blobs --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -right-24 -top-24 size-96 rounded-full bg-sage-200 opacity-20 blur-[80px] motion-safe:animate-orb-drift"></div>
        <div class="absolute -left-20 top-1/2 size-80 rounded-full bg-gold-200 opacity-30 blur-[80px] motion-safe:animate-orb-drift [animation-delay:-5s]"></div>
    </div>

    {{-- ======================== HEADER ======================== --}}
    <header class="fixed inset-x-0 top-0 z-50 h-20 bg-parchment/85 shadow-[0_1px_8px_rgba(46,50,48,0.05)] backdrop-blur-xl">
        <div class="mx-auto flex h-full max-w-screen-xl items-center justify-between px-12">

            {{-- Logo --}}
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <span class="grid size-8 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                    <x-auth.icon name="bug" class="size-4" />
                </span>
                <span class="font-serif text-xl font-semibold tracking-tight text-ink">TwinBug</span>
            </a>

            {{-- Nav --}}
            <nav class="flex items-center gap-1.5 rounded-[20px] bg-input p-1.5" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}"
                   class="rounded-2xl px-4 py-2 text-sm font-medium text-label transition hover:bg-white/60 hover:text-ink {{ request()->routeIs('dashboard') ? 'bg-white text-ink shadow-sm' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('bug-reports.create') }}"
                   class="rounded-2xl px-4 py-2.5 text-base font-semibold transition {{ request()->routeIs('bug-reports.*') ? 'bg-sage-500 text-white shadow-[0_2px_10px_rgba(74,124,89,0.3)]' : 'text-label hover:bg-white/60 hover:text-ink' }}">
                    Buat Laporan
                </a>
                <a href="#"
                   class="rounded-2xl px-4 py-2 text-sm font-medium text-label transition hover:bg-white/60 hover:text-ink">
                    Histori Laporan
                </a>
            </nav>

            {{-- User info + logout --}}
            <div class="flex items-center gap-4">
                @auth
                <div class="flex items-center gap-3 rounded-2xl bg-input px-2 py-1 pr-3">
                    <span class="grid size-8 place-items-center rounded-xl bg-sage-200 text-sage-700 text-sm font-bold shadow-sm">
                        {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                    </span>
                    <div>
                        <p class="text-xs font-semibold leading-none text-ink">{{ auth()->user()->name }}</p>
                        <p class="mt-0.5 text-[10px] leading-none text-label">{{ auth()->user()->role->label() }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" id="btn-logout"
                            class="grid size-9 place-items-center rounded-2xl bg-input text-label transition hover:bg-divider hover:text-danger"
                            title="Keluar">
                        <x-auth.icon name="logout" class="size-4" />
                    </button>
                </form>
                @endauth
            </div>
        </div>
    </header>

    {{-- ======================== MAIN ======================== --}}
    <main class="relative pt-20">
        {{ $slot }}
    </main>

    {{-- ======================== FOOTER ======================== --}}
    <footer class="bg-input mt-16">
        <div class="mx-auto flex max-w-screen-xl items-center justify-between px-12 py-12">
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-2 opacity-70">
                    <span class="grid size-5 place-items-center rounded-lg bg-sage-500 text-white">
                        <x-auth.icon name="bug" class="size-3" />
                    </span>
                    <span class="font-serif text-sm font-semibold tracking-tight text-ink">TwinBug</span>
                </span>
                <span class="text-[11px] text-label">© {{ date('Y') }} TwinBug Detection System. Arsitektur Berbasis AI Multimodal.</span>
            </div>
            <nav class="flex items-center gap-6" aria-label="Footer">
                <a href="#" class="text-xs text-label transition hover:text-ink">Dokumentasi API</a>
                <a href="#" class="text-xs text-label transition hover:text-ink">Kamus Model Duplikasi</a>
                <a href="#" class="text-xs text-label transition hover:text-ink">Status Sistem</a>
                <a href="#" class="text-xs text-label transition hover:text-ink">Bantuan & FAQ</a>
            </nav>
        </div>
    </footer>

    <x-auth.toast />
    @stack('scripts')
</body>
</html>
