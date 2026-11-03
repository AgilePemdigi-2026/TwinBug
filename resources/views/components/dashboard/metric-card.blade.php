@props([
    'label',
    'value',
    'description' => null,
    'icon' => 'bug',
    'iconClasses' => 'bg-sage-200 text-sage-700',
    'href' => null,
])

@if ($href)
    <a href="{{ $href }}" class="group block rounded-3xl bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
@else
    <div class="rounded-3xl bg-white p-6 shadow-sm">
@endif
    <div class="flex items-center justify-between">
        <p class="text-xs font-bold uppercase tracking-wider text-label">{{ $label }}</p>
        <span class="grid size-9 place-items-center rounded-xl {{ $iconClasses }}">
            <x-auth.icon :name="$icon" class="size-4" />
        </span>
    </div>
    <p class="mt-3 font-serif text-3xl font-bold text-ink">{{ $value }}</p>
    @if ($description)
        <p class="mt-1 text-xs text-label">{{ $description }}</p>
    @endif
@if ($href)
    </a>
@else
    </div>
@endif
