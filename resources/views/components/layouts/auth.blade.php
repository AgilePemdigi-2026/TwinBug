<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" data-theme="{{ auth()->check() ? (auth()->user()->theme ?? 'light') : 'light' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwinBug' }} — TwinBug</title>
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

<body class="auth-page min-h-full bg-white font-sans text-body antialiased">
    <header class="auth-header relative z-10 border-b border-divider bg-white">
        <div class="mx-auto flex h-[76px] max-w-[1680px] items-center justify-between px-5 sm:px-8 lg:px-12">
            <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="TwinBug home">
                <span class="grid size-10 place-items-center rounded-xl bg-[#123c2e] text-white shadow-cta"><x-auth.icon name="bug" class="size-5" /></span>
                <span class="text-xl font-bold tracking-tight text-[#0d2d22]">TwinBug</span>
            </a>
            <nav class="hidden items-center gap-8 md:flex" aria-label="Main navigation">
                <a href="{{ route('home') }}" class="text-sm font-medium text-label hover:text-ink">Home</a>
                <a href="{{ route('about') }}" class="text-sm font-medium text-label hover:text-ink">About Us</a>
                <a href="{{ route('faq') }}" class="text-sm font-medium text-label hover:text-ink">FAQ</a>
            </nav>
            <div class="flex items-center gap-2.5 sm:gap-3">
                <a href="{{ route('login') }}" class="rounded-xl border border-divider px-3.5 py-2.5 text-xs font-semibold text-body hover:bg-input sm:px-5 sm:text-sm">Sign in</a>
                <a href="{{ route('register') }}" class="rounded-xl bg-[#123c2e] px-3.5 py-2.5 text-xs font-semibold text-white shadow-cta hover:bg-[#0d2d22] sm:px-5 sm:text-sm">Create account</a>
            </div>
        </div>
    </header>

    <main class="auth-main grid min-h-[calc(100vh-76px)] lg:grid-cols-[52.6%_47.4%]">
        <section class="auth-story relative hidden overflow-hidden bg-[#0d2d22] text-white lg:flex lg:flex-col lg:justify-between lg:px-12 lg:py-12 xl:px-20">
            <div class="pointer-events-none absolute -bottom-48 -left-32 size-[640px] rounded-full border border-white/5"></div>
            <div class="pointer-events-none absolute bottom-10 left-1/3 size-[400px] rounded-full border border-white/5"></div>
            <div class="relative z-10">
                <p class="flex items-center gap-2.5 text-xs font-bold uppercase tracking-[.16em] text-[#9ac7b0]"><x-auth.icon name="sparkles" class="size-5" /> Smarter bug triage starts here</p>
                <h1 class="mt-8 max-w-3xl text-5xl font-normal leading-[1.1] tracking-tight xl:text-6xl">{{ request()->routeIs('register') ? 'Report once. Detect duplicates before they slow you down.' : 'Turn duplicate noise into actionable clarity.' }}</h1>
                <p class="mt-6 max-w-2xl text-base leading-7 text-[#b5c8bf]">TwinBug combines semantic text analysis and screenshot similarity to help teams identify related bug reports with confidence.</p>
            </div>
            <div class="auth-illustration relative mx-auto my-8 flex h-64 w-full max-w-2xl items-center justify-center" aria-hidden="true">
                <div class="absolute size-64 rounded-full border border-dashed border-[#557b69]/60"></div>
                <div class="absolute size-44 rounded-full border border-[#557b69]/60 bg-[#164333]/70"></div>
                <div class="relative grid size-32 place-items-center rounded-full border border-[#527a68] bg-[#164333] text-[#9ac7b0]">
                    <x-auth.icon name="bug" class="size-16" />
                    <span class="absolute text-xs font-semibold text-white">TwinBug AI</span>
                </div>
                <span class="absolute left-[4%] top-[15%] rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-xs font-semibold">Bug report</span>
                <span class="absolute left-[4%] bottom-[12%] rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-xs font-semibold">Screenshot</span>
                <span class="absolute right-[2%] top-[42%] rounded-2xl border border-white/10 bg-white/5 px-5 py-4 text-xs font-semibold">Potential match</span>
            </div>
            <div class="relative z-10 flex gap-8 text-sm text-[#9bb5a8]">
                <span class="inline-flex items-center gap-2"><x-auth.icon name="check" class="size-4" /> Text + image analysis</span>
                <span class="inline-flex items-center gap-2"><x-auth.icon name="check" class="size-4" /> Human-reviewed decisions</span>
            </div>
        </section>

        <section class="auth-form-panel flex min-w-0 flex-col justify-between px-5 py-10 sm:px-10 lg:px-12 xl:px-16">
            <div class="auth-form-content mx-auto flex w-full max-w-[720px] flex-1 flex-col justify-center py-2">
                {{ $slot }}
            </div>
            <p class="pt-8 text-center text-xs text-label/70">© {{ date('Y') }} TwinBug · Secure multimodal bug detection</p>
        </section>
    </main>

    <x-auth.toast />
</body>

</html>
