@php
    $user = auth()->user();
@endphp

<x-layouts.auth title="Dashboard">
    <div class="w-full max-w-3xl space-y-4">
        <header class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-2.5">
                <span class="grid size-9 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                    <x-auth.icon name="bug" class="size-4" />
                </span>
                <span class="font-serif text-xl font-semibold tracking-tight text-ink">TwinBug</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl border border-divider bg-card px-3.5 py-2 text-xs font-semibold text-label transition hover:border-danger/30 hover:text-danger">
                    <x-auth.icon name="logout" class="size-4" />
                    Keluar
                </button>
            </form>
        </header>

        <section class="overflow-hidden rounded-[24px] border border-white/70 bg-card shadow-card">
            <div class="bg-sage-500 px-7 py-8 text-white sm:px-9">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-wider">
                    <x-auth.icon :name="$user->role === \App\Enums\UserRole::Developer ? 'code' : 'bug'" class="size-3.5" />
                    {{ $user->role->label() }}
                </span>
                <h1 class="mt-4 font-serif text-3xl font-semibold leading-tight">Halo, {{ $user->name }}</h1>
                <p class="mt-2 max-w-xl text-sm leading-6 text-white/80">{{ $user->role->description() }}</p>
            </div>

            <div class="grid gap-3 p-7 sm:grid-cols-3 sm:p-9">
                <div class="rounded-xl bg-input p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-body/60">Peran</p>
                    <p class="mt-1 text-sm font-bold text-ink">{{ $user->role->label() }}</p>
                </div>
                <div class="rounded-xl bg-input p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-body/60">Status akun</p>
                    <p class="mt-1 flex items-center gap-1.5 text-sm font-bold text-ink">
                        <span class="size-2 rounded-full bg-success"></span>
                        Aktif
                    </p>
                </div>
                <div class="rounded-xl bg-input p-4">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-body/60">Bergabung</p>
                    <p class="mt-1 text-sm font-bold text-ink">{{ $user->created_at->format('d/m/Y') }}</p>
                </div>
            </div>
        </section>

        <section class="rounded-[24px] border border-white/70 bg-card p-7 shadow-card sm:p-9">
            <div class="mb-2 flex items-center gap-2">
                <x-auth.icon name="user" class="size-4 text-sage-600" />
                <h2 class="font-serif text-lg font-semibold text-ink">Detail akun</h2>
            </div>

            <dl class="divide-y divide-divider text-sm">
                <div class="flex items-start justify-between gap-6 py-3.5">
                    <dt class="text-body">Nama lengkap</dt>
                    <dd class="text-right font-semibold text-ink">{{ $user->name }}</dd>
                </div>
                <div class="flex items-start justify-between gap-6 py-3.5">
                    <dt class="text-body">Email</dt>
                    <dd class="break-all text-right font-semibold text-ink">{{ $user->email }}</dd>
                </div>
                <div class="flex items-start justify-between gap-6 py-3.5">
                    <dt class="text-body">Peran</dt>
                    <dd class="text-right font-semibold text-ink">{{ $user->role->label() }}</dd>
                </div>
            </dl>
        </section>

        <p class="flex items-center justify-center gap-1.5 pb-2 text-center text-[11px] text-body/60">
            <x-auth.icon name="sparkles" class="size-3.5" />
            Dashboard ini akan menjadi pusat aktivitas TwinBug Anda.
        </p>
    </div>
</x-layouts.auth>
