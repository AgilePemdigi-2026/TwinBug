@props([
    'label',
    'value',
    'description' => null,
    'icon' => 'bug',
    'iconClasses' => 'bg-sage-100 text-sage-700',
    'href' => null,
])

@if ($href)
    <a href="{{ $href }}" class="group block rounded-xl border border-divider/80 bg-white p-4 shadow-xs transition hover:border-slate-300 hover:shadow-sm">
@else
    <div class="rounded-xl border border-divider/80 bg-white p-4 shadow-xs">
@endif
    <div class="flex items-center justify-between">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-label">{{ $label }}</p>
        <span class="grid size-8 place-items-center rounded-lg {{ $iconClasses }}">
            <x-auth.icon :name="$icon" class="size-3.5" />
        </span>
    </div>
    <p class="mt-2.5 font-serif text-2xl font-bold tracking-tight text-ink">{{ $value }}</p>
    @if ($description)
        <p class="mt-0.5 text-xs text-label">{{ $description }}</p>
    @endif
@if ($href)
    </a>
@else
    </div>
@endif
