@props(['user'])

@php($application = $user->application)

<div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="mb-1.5 flex items-center gap-2">
                <x-auth.icon name="code" class="size-4 text-sage-600" />
                <h2 class="font-serif text-sm font-bold text-ink">Aplikasi Anda</h2>
            </div>
            <p class="font-serif text-base font-bold text-ink">{{ $application->name }}</p>
            <p class="mt-0.5 text-xs text-label">
                @if ($user->role === \App\Enums\UserRole::Developer)
                    Anda mengelola antrean laporan bug aplikasi ini.
                @else
                    Setiap laporan yang Anda kirim masuk ke aplikasi ini.
                @endif
            </p>
        </div>

        @if ($user->role === \App\Enums\UserRole::Developer)
            <div class="rounded-xl border border-divider/80 bg-input/60 px-3.5 py-2">
                <p class="text-[10px] font-bold uppercase tracking-wider text-label">Kode undangan</p>
                <div class="mt-1 flex items-center gap-2.5">
                    <code class="font-mono text-base font-bold tracking-[0.15em] text-ink">{{ $application->code }}</code>
                    <button
                        type="button"
                        data-copy="{{ $application->code }}"
                        class="rounded-md border border-divider bg-card px-2 py-0.5 text-[11px] font-semibold text-sage-600 transition hover:bg-sage-50"
                    >
                        Salin
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>
