@props(['name' => 'user'])

<svg {{ $attributes->merge([
    'class' => 'size-4',
    'viewBox' => '0 0 24 24',
    'fill' => 'none',
    'stroke' => 'currentColor',
    'stroke-width' => '1.8',
    'stroke-linecap' => 'round',
    'stroke-linejoin' => 'round',
    'aria-hidden' => 'true',
]) }}>
    @switch($name)
        @case('mail')
            <rect x="3" y="5" width="18" height="14" rx="2" />
            <path d="m4 7 8 6 8-6" />
            @break

        @case('lock')
            <rect x="5" y="10" width="14" height="11" rx="2" />
            <path d="M8 10V7a4 4 0 0 1 8 0v3" />
            <path d="M12 14v3" />
            @break

        @case('eye')
            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z" />
            <circle cx="12" cy="12" r="2.5" />
            @break

        @case('eye-off')
            <path d="m3 3 18 18" />
            <path d="M10.6 6.2A10.9 10.9 0 0 1 12 6c6.5 0 10 6 10 6a18 18 0 0 1-2.2 2.8" />
            <path d="M6.2 6.2C3.5 8 2 12 2 12s3.5 6 10 6a10.8 10.8 0 0 0 5.8-1.7" />
            <path d="M9.9 9.9a3 3 0 0 0 4.2 4.2" />
            @break

        @case('bug')
            <path d="M8 7V5a4 4 0 0 1 8 0v2" />
            <rect x="6" y="7" width="12" height="13" rx="6" />
            <path d="M3 11h3M18 11h3M3 16h3M18 16h3M12 12v8" />
            @break

        @case('code')
            <path d="m9 18-6-6 6-6M15 6l6 6-6 6" />
            @break

        @case('shield')
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
            <path d="m9 12 2 2 4-4" />
            @break

        @case('key')
            <circle cx="7.5" cy="15.5" r="4.5" />
            <path d="m11 12 8-8M15 4l5 5M17 6l2 2" />
            @break

        @case('check')
            <path d="m5 12 4 4L19 6" />
            @break

        @case('alert')
            <circle cx="12" cy="12" r="9" />
            <path d="M12 8v5" />
            <path d="M12 16.5h.01" />
            @break

        @case('arrow-left')
            <path d="m15 18-6-6 6-6" />
            <path d="M9 12h10" />
            @break

        @case('logout')
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
            <path d="m16 17 5-5-5-5" />
            <path d="M21 12H9" />
            @break

        @case('sparkles')
            <path d="m12 3 1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6L12 3Z" />
            <path d="m19 15 .8 2.2L22 18l-2.2.8L19 21l-.8-2.2L16 18l2.2-.8L19 15Z" />
            @break

        @case('user')
        @default
            <circle cx="12" cy="8" r="4" />
            <path d="M4 21a8 8 0 0 1 16 0" />
            @break
    @endswitch
</svg>
