@props(['user'])

@php($application = $user->application)

<div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
    <div class="flex items-center justify-between mb-3">
        <div class="flex items-center gap-2">
            <span class="grid size-6 place-items-center rounded-lg bg-sage-100 text-sage-700">
                <x-auth.icon name="code" class="size-3.5" />
            </span>
            <h2 class="text-xs font-bold uppercase tracking-wider text-ink">Aplikasi Proyek</h2>
        </div>
        <span class="rounded-md bg-input px-2 py-0.5 font-mono text-[10px] font-semibold text-label">Workspace</span>
    </div>

    <div>
        <p class="font-serif text-base font-bold text-ink">{{ $application->name ?? 'Aplikasi Utama' }}</p>
        <p class="mt-1 text-xs text-label leading-relaxed">
            @if ($user->role === \App\Enums\UserRole::Developer)
                Anda mengelola antrean dan triase bug pada aplikasi ini.
            @else
                Setiap laporan bug yang Anda kirimkan terhubung ke aplikasi ini.
            @endif
        </p>
    </div>

    @if ($user->role === \App\Enums\UserRole::Developer && $application)
        <div class="mt-4 rounded-xl border border-divider/80 bg-input/50 p-3">
            <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-label mb-1">
                <span>Kode Undangan Tim</span>
                <span class="text-sage-700 font-semibold">Bagi ke Reporter</span>
            </div>
            <div class="flex items-center justify-between gap-2 bg-white rounded-lg border border-divider/80 px-3 py-1.5 shadow-2xs">
                <code class="font-mono text-sm font-bold tracking-[0.15em] text-ink">{{ $application->code }}</code>
                <button
                    type="button"
                    data-copy="{{ $application->code }}"
                    class="inline-flex items-center gap-1 rounded-md bg-sage-50 border border-sage-200 px-2.5 py-1 text-[11px] font-bold text-sage-700 transition hover:bg-sage-100 active:scale-95 cursor-pointer"
                >
                    <span>Salin</span>
                </button>
            </div>
        </div>
    @endif
</div>
