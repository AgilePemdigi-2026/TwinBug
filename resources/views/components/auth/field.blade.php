@props([
    'name',
    'label',
    'type' => 'text',
    'icon' => 'user',
    'toggle' => false,
    'autocomplete' => null,
    'placeholder' => null,
    'value' => null,
    'hint' => null,
    'strength' => false,
])

@php
    $hasError = $errors->has($name);
    $inputType = $toggle ? 'password' : $type;
@endphp

<div class="space-y-1.5">
    <div class="flex items-center justify-between gap-3">
        <label for="{{ $name }}" class="text-sm font-semibold text-label">{{ $label }}</label>
        @if ($hint)
            <span class="text-[11px] font-medium text-body/70">{{ $hint }}</span>
        @endif
        {{ $labelRight ?? '' }}
    </div>

    <div class="relative">
        <span class="pointer-events-none absolute inset-y-0 left-0 grid w-11 place-items-center text-body/60">
            <x-auth.icon :name="$icon" />
        </span>

        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="{{ $inputType }}"
            value="{{ old($name, $value) }}"
            @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
            placeholder="{{ $placeholder ?? '' }}"
            @if ($toggle) data-password-input @endif
            @if ($strength) data-strength-input @endif
            aria-invalid="{{ $hasError ? 'true' : 'false' }}"
            aria-describedby="{{ $hasError ? $name.'-error' : null }}"
            @class([
                'w-full rounded-xl border border-transparent bg-input py-3 pl-11 text-sm text-ink placeholder:text-body/50 transition focus:border-sage-500 focus:bg-card focus:outline-none focus:ring-2 focus:ring-sage-500/20',
                'pr-11' => $toggle,
                'pr-4' => ! $toggle,
                'border-danger/50 bg-danger/5 focus:border-danger focus:ring-danger/20' => $hasError,
            ])
            required
        >

        @if ($toggle)
            <button
                type="button"
                data-password-toggle="{{ $name }}"
                aria-label="Tampilkan kata sandi"
                aria-pressed="false"
                class="absolute inset-y-0 right-0 grid w-11 place-items-center rounded-r-xl text-body/60 transition hover:text-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/30"
            >
                <span data-password-show><x-auth.icon name="eye" /></span>
                <span data-password-hide class="hidden"><x-auth.icon name="eye-off" /></span>
            </button>
        @endif
    </div>

    @error($name)
        <p id="{{ $name }}-error" class="text-xs font-medium text-danger">{{ $message }}</p>
    @enderror
</div>
