@php
    $isError = session()->has('error');
    $message = session('error') ?? session('status');
@endphp

<div
    data-toast
    data-visible="{{ $message ? 'true' : 'false' }}"
    @class([
        'fixed inset-x-4 bottom-5 z-50 mx-auto flex max-w-md items-start gap-3 rounded-2xl border border-divider bg-card p-4 shadow-card transition duration-300 sm:bottom-6',
        'translate-y-0 opacity-100' => $message,
        'pointer-events-none translate-y-3 opacity-0' => ! $message,
    ])
    role="status"
    aria-live="polite"
>
    <span @class([
        'grid size-9 shrink-0 place-items-center rounded-full',
        'bg-danger/10 text-danger' => $isError,
        'bg-sage-100 text-sage-600' => ! $isError,
    ])>
        <x-auth.icon :name="$isError ? 'alert' : 'check'" />
    </span>

    <div class="min-w-0 flex-1 pt-0.5">
        <p data-toast-title class="text-sm font-bold text-ink">{{ $isError ? 'Terjadi kesalahan' : 'Berhasil' }}</p>
        <p data-toast-message class="mt-0.5 text-sm leading-5 text-body">{{ $message }}</p>
    </div>

    <button type="button" data-toast-close aria-label="Tutup notifikasi" class="text-lg leading-none text-body/50 transition hover:text-ink">
        &times;
    </button>
</div>
