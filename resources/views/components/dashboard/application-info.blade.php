@props(['user'])

@php($application = $user->application)

<div class="rounded-3xl bg-white p-8 shadow-sm">
    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-2 flex items-center gap-2">
                <x-auth.icon name="code" class="size-4 text-sage-600" />
                <h2 class="font-serif text-lg font-bold text-ink">Aplikasi Anda</h2>
            </div>
            <p class="font-serif text-xl font-bold text-ink">{{ $application->name }}</p>
            <p class="mt-1 text-xs leading-5 text-label">
                @if ($user->role === \App\Enums\UserRole::Developer)
                    Anda mengelola antrean laporan bug aplikasi ini.
                @else
                    Setiap laporan yang Anda kirim masuk ke aplikasi ini.
                @endif
            </p>
        </div>

        @if ($user->role === \App\Enums\UserRole::Developer)
            <div class="rounded-2xl bg-input px-4 py-3">
                <p class="text-[10px] font-bold uppercase tracking-wider text-label">Kode undangan</p>
                <div class="mt-1 flex items-center gap-3">
                    <code class="font-mono text-lg font-bold tracking-[0.18em] text-ink">{{ $application->code }}</code>
                    <button
                        type="button"
                        data-copy="{{ $application->code }}"
                        class="rounded-lg border border-divider bg-card px-2.5 py-1 text-[11px] font-bold text-sage-600 transition hover:bg-sage-50"
                    >
                        Salin
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
