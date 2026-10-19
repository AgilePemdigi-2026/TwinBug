<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'TwinBug' }} — TwinBug</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-parchment font-sans text-body antialiased">
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -left-40 -top-40 size-[480px] rounded-full bg-sage-200 opacity-60 blur-3xl motion-safe:animate-orb-drift"></div>
        <div class="absolute -bottom-48 -right-36 size-[520px] rounded-full bg-gold-200 opacity-50 blur-3xl motion-safe:animate-orb-drift [animation-delay:-5s]"></div>
        <div class="absolute left-1/2 top-1/2 size-[760px] -translate-x-1/2 -translate-y-1/2 rounded-full bg-sage-500/10 blur-[90px]"></div>
    </div>

    <main class="relative flex min-h-screen flex-col items-center justify-center px-5 py-10">
        {{ $slot }}
    </main>

    <x-auth.toast />
</body>
</html>
