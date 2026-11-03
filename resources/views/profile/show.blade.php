@php
    $user = auth()->user();

    // Role-aware configuration
    $roleConfig = match ($user->role) {
        \App\Enums\UserRole::Reporter => [
            'badge_classes' => 'bg-sage-200 text-sage-700',
            'avatar_classes' => 'bg-sage-500 text-white',
            'icon' => 'bug',
            'label' => 'Reporter',
            'description' => 'Melaporkan bug dengan detail dan langkah reproduksi yang jelas.',
        ],
        \App\Enums\UserRole::Developer => [
            'badge_classes' => 'bg-amber-100 text-amber-800',
            'avatar_classes' => 'bg-amber-500 text-white',
            'icon' => 'code',
            'label' => 'Developer',
            'description' => 'Menelusuri, memperbaiki, dan memverifikasi laporan bug.',
        ],
        \App\Enums\UserRole::Admin => [
            'badge_classes' => 'bg-indigo-100 text-indigo-800',
            'avatar_classes' => 'bg-indigo-500 text-white',
            'icon' => 'sparkles',
            'label' => 'Administrator',
            'description' => 'Mengelola pengguna, memantau metrik sistem, dan mengawasi sprint.',
        ],
    };

    // Generate initials (max 2 chars)
    $nameParts = explode(' ', trim($user->name));
    $initials = mb_strtoupper(
        count($nameParts) >= 2
            ? mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1], 0, 1)
            : mb_substr($nameParts[0], 0, 2)
    );
@endphp

<x-layouts.app title="Profil Saya" meta-description="Kelola informasi akun dan keamanan profil TwinBug Anda.">

    <div class="mx-auto max-w-screen-xl px-12 py-10 space-y-6">

        {{-- ======================== PROFILE HERO ======================== --}}
        <div class="relative overflow-hidden rounded-3xl bg-white p-8 shadow-sm">
            {{-- Subtle decorative bg --}}
            <div class="pointer-events-none absolute -right-16 -top-16 size-64 rounded-full opacity-30 blur-[80px]
                {{ $user->role === \App\Enums\UserRole::Reporter ? 'bg-sage-200' : ($user->role === \App\Enums\UserRole::Developer ? 'bg-amber-200' : 'bg-indigo-200') }}">
            </div>

            <div class="relative z-10 flex flex-col gap-6 sm:flex-row sm:items-center">
                {{-- Avatar --}}
                <div class="shrink-0">
                    <div class="grid size-24 place-items-center rounded-3xl text-3xl font-bold shadow-card {{ $roleConfig['avatar_classes'] }}">
                        {{ $initials }}
                    </div>
                </div>

                {{-- Identity --}}
                <div class="flex-1 min-w-0">
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        {{-- Role Badge --}}
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wider {{ $roleConfig['badge_classes'] }}">
                            <x-auth.icon :name="$roleConfig['icon']" class="size-3.5" />
                            {{ $roleConfig['label'] }}
                        </span>
                        {{-- Verified Badge --}}
                        @if ($user->email_verified_at)
                            <span class="inline-flex items-center gap-1 rounded-full bg-sage-100 px-2.5 py-0.5 text-[11px] font-semibold text-sage-700">
                                <x-auth.icon name="check" class="size-3" />
                                Terverifikasi
                            </span>
                        @endif
                    </div>
                    <h1 class="font-serif text-3xl font-bold tracking-tight text-ink truncate" style="letter-spacing:-0.5px;">
                        {{ $user->name }}
                    </h1>
                    <p class="mt-1 text-sm text-label">{{ $user->email }}</p>
                    <p class="mt-0.5 text-xs text-label/70">
                        Bergabung sejak {{ $user->created_at->format('d M Y') }} · {{ $user->created_at->diffForHumans() }}
                    </p>
                </div>

                {{-- Role description chip --}}
                <div class="shrink-0 hidden lg:block max-w-xs rounded-2xl bg-input p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-label mb-1">Tanggung Jawab</p>
                    <p class="text-xs leading-relaxed text-body">{{ $roleConfig['description'] }}</p>
                </div>
            </div>
        </div>

        {{-- ======================== STATS + SECURITY (2-col grid) ======================== --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- ---- STATISTICS (role-aware) ---- --}}
            <div class="rounded-3xl bg-white p-8 shadow-sm">
                <div class="mb-5 flex items-center gap-2">
                    <x-auth.icon :name="$roleConfig['icon']" class="size-4 text-label" />
                    <h2 class="font-serif text-lg font-bold text-ink">
                        @if ($stats['type'] === 'reporter') Statistik Laporan Saya
                        @elseif ($stats['type'] === 'developer') Statistik Bug Sistem
                        @else Statistik Platform
                        @endif
                    </h2>
                </div>

                {{-- Reporter Stats --}}
                @if ($stats['type'] === 'reporter')
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2 rounded-2xl bg-sage-50 border border-sage-200/60 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-sage-600">Total Laporan Dikirim</p>
                            <p class="mt-1 font-serif text-4xl font-bold text-ink">{{ number_format($stats['total']) }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Terbuka</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-sky-700">{{ $stats['open'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Diproses</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-amber-700">{{ $stats['in_progress'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Diselesaikan</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-sage-700">{{ $stats['resolved'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Ditutup</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-label">{{ $stats['closed'] }}</p>
                        </div>
                    </div>

                {{-- Developer Stats --}}
                @elseif ($stats['type'] === 'developer')
                    <div class="grid grid-cols-2 gap-3">
                        <div class="col-span-2 rounded-2xl bg-amber-50 border border-amber-200/60 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Total Bug di Sistem</p>
                            <p class="mt-1 font-serif text-4xl font-bold text-ink">{{ number_format($stats['total_system']) }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Open</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-sky-700">{{ $stats['open'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">In Progress</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-amber-700">{{ $stats['in_progress'] }}</p>
                        </div>
                        <div class="col-span-2 rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Resolved / Ditutup</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-sage-700">{{ $stats['resolved'] }}</p>
                        </div>
                    </div>

                {{-- Admin Stats --}}
                @else
                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl bg-indigo-50 border border-indigo-200/60 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-700">Total Pengguna</p>
                            <p class="mt-1 font-serif text-4xl font-bold text-ink">{{ number_format($stats['total_users']) }}</p>
                        </div>
                        <div class="rounded-2xl bg-indigo-50 border border-indigo-200/60 p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-700">Total Bug</p>
                            <p class="mt-1 font-serif text-4xl font-bold text-ink">{{ number_format($stats['total_bugs']) }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Bug Terbuka</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-sky-700">{{ $stats['open_bugs'] }}</p>
                        </div>
                        <div class="rounded-2xl bg-input p-4">
                            <p class="text-[10px] font-bold uppercase tracking-wider text-label">Bug Resolved</p>
                            <p class="mt-1 font-serif text-2xl font-bold text-sage-700">{{ $stats['resolved_bugs'] }}</p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ---- SECURITY ---- --}}
            <div class="rounded-3xl bg-white p-8 shadow-sm">
                <div class="mb-5 flex items-center gap-2">
                    <x-auth.icon name="logout" class="size-4 text-label" />
                    <h2 class="font-serif text-lg font-bold text-ink">Keamanan Akun</h2>
                </div>

                <form id="form-password" method="POST" action="{{ route('profile.password') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-xs font-bold uppercase tracking-wider text-label mb-1.5">
                            Kata Sandi Saat Ini
                        </label>
                        <input
                            type="password"
                            id="current_password"
                            name="current_password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            class="w-full rounded-2xl border border-divider bg-input px-4 py-3 text-sm text-ink placeholder-label/50 transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20
                                {{ $errors->has('current_password') ? 'border-danger bg-danger/5' : '' }}"
                        >
                        @error('current_password')
                            <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-label mb-1.5">
                            Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Min. 8 karakter"
                            class="w-full rounded-2xl border border-divider bg-input px-4 py-3 text-sm text-ink placeholder-label/50 transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20
                                {{ $errors->has('password') ? 'border-danger bg-danger/5' : '' }}"
                        >
                        @error('password')
                            <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-label mb-1.5">
                            Konfirmasi Kata Sandi Baru
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            placeholder="Ulangi kata sandi baru"
                            class="w-full rounded-2xl border border-divider bg-input px-4 py-3 text-sm text-ink placeholder-label/50 transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20"
                        >
                    </div>

                    <button
                        type="submit"
                        id="btn-update-password"
                        class="inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-ink px-5 py-3 text-sm font-bold text-white transition hover:bg-ink/80 active:scale-95"
                    >
                        <x-auth.icon name="check" class="size-4" />
                        Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>

        {{-- ======================== EDIT PROFILE ======================== --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm">
            <div class="mb-6 flex items-center gap-2">
                <x-auth.icon name="user" class="size-4 text-label" />
                <h2 class="font-serif text-lg font-bold text-ink">Informasi Profil</h2>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                {{-- Edit Form --}}
                <form id="form-profile" method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-label mb-1.5">
                            Nama Lengkap
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            autocomplete="name"
                            class="w-full rounded-2xl border border-divider bg-input px-4 py-3 text-sm text-ink transition focus:border-sage-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sage-500/20
                                {{ $errors->has('name') ? 'border-danger bg-danger/5' : '' }}"
                        >
                        @error('name')
                            <p class="mt-1.5 text-xs text-danger">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (read-only) --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-label mb-1.5">
                            Alamat Surel
                        </label>
                        <div class="relative">
                            <input
                                type="email"
                                value="{{ $user->email }}"
                                disabled
                                class="w-full rounded-2xl border border-divider bg-input/60 px-4 py-3 text-sm text-label cursor-not-allowed pr-24"
                            >
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 inline-flex items-center gap-1 rounded-xl bg-sage-100 px-2.5 py-1 text-[10px] font-bold text-sage-700">
                                <x-auth.icon name="check" class="size-3" />
                                OTP Verified
                            </span>
                        </div>
                        <p class="mt-1.5 text-[11px] text-label/70">
                            Email tidak dapat diubah karena sudah terverifikasi melalui OTP.
                        </p>
                    </div>

                    {{-- Role (read-only) --}}
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-label mb-1.5">
                            Peran di Sistem
                        </label>
                        <div class="flex items-center gap-3 rounded-2xl border border-divider bg-input/60 px-4 py-3">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider {{ $roleConfig['badge_classes'] }}">
                                <x-auth.icon :name="$roleConfig['icon']" class="size-3" />
                                {{ $roleConfig['label'] }}
                            </span>
                            <p class="text-xs text-label">Peran ditetapkan saat registrasi dan tidak dapat diubah sendiri.</p>
                        </div>
                    </div>

                    <button
                        type="submit"
                        id="btn-update-profile"
                        class="inline-flex items-center gap-2 rounded-2xl bg-sage-500 px-6 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                    >
                        <x-auth.icon name="check" class="size-4" />
                        Simpan Perubahan
                    </button>
                </form>

                {{-- Account info summary --}}
                <div class="space-y-3">
                    <p class="text-xs font-bold uppercase tracking-wider text-label">Ringkasan Akun</p>

                    <dl class="space-y-2">
                        <div class="flex items-center justify-between rounded-2xl bg-input px-4 py-3">
                            <dt class="text-xs text-label">Status Akun</dt>
                            <dd class="flex items-center gap-1.5 text-xs font-semibold text-success">
                                <span class="size-2 rounded-full bg-success"></span>
                                Aktif
                            </dd>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-input px-4 py-3">
                            <dt class="text-xs text-label">Verifikasi Email</dt>
                            <dd class="text-xs font-semibold {{ $user->email_verified_at ? 'text-success' : 'text-warning' }}">
                                {{ $user->email_verified_at ? 'Terverifikasi' : 'Belum Terverifikasi' }}
                            </dd>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-input px-4 py-3">
                            <dt class="text-xs text-label">Bergabung</dt>
                            <dd class="text-xs font-semibold text-ink">{{ $user->created_at->format('d M Y') }}</dd>
                        </div>
                        <div class="flex items-center justify-between rounded-2xl bg-input px-4 py-3">
                            <dt class="text-xs text-label">ID Pengguna</dt>
                            <dd class="font-mono text-xs font-semibold text-label">#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

    </div>

</x-layouts.app>
