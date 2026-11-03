<x-layouts.app title="Riwayat Laporan" meta-description="Riwayat laporan bug yang telah Anda kirimkan ke sistem TwinBug.">

    <div class="mx-auto max-w-screen-xl px-12 py-10">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="mb-8 flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-full bg-divider px-2.5 py-1 text-[11px] font-semibold text-ink">Riwayat Laporan</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="mb-8 flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <h1 class="font-serif text-5xl font-bold tracking-tight text-ink" style="letter-spacing: -1.2px;">
                    Riwayat Laporan Bug
                </h1>
                <p class="mt-3 text-lg leading-7 text-label">
                    Seluruh laporan bug yang pernah Anda kirimkan beserta status penanganannya.
                </p>
            </div>

            <div class="flex shrink-0 items-center gap-3 rounded-3xl bg-input px-5 py-3.5 shadow-sm">
                <span class="grid size-10 place-items-center rounded-2xl bg-sage-200 text-sage-700">
                    <x-auth.icon name="bug" class="size-5" />
                </span>
                <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wide text-label">Total Laporan</p>
                    <p class="font-serif text-base font-bold text-ink">{{ number_format($reports->count()) }} Laporan</p>
                </div>
            </div>
        </div>

        {{-- ======================== FILTER ======================== --}}
        <form method="GET" action="{{ route('report.history') }}" class="mb-6 rounded-3xl bg-white p-5 shadow-sm">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-xs">
                    <label for="status-filter" class="mb-2 block font-serif text-sm font-semibold text-ink">
                        Status
                    </label>
                    <div class="relative">
                        <select
                            id="status-filter"
                            name="status"
                            class="w-full appearance-none rounded-3xl bg-input px-4 py-3 pr-10 text-sm text-ink transition focus:outline-none focus:ring-2 focus:ring-sage-500/40"
                        >
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status->value }}" {{ $selectedStatus === $status ? 'selected' : '' }}>
                                    {{ $status->label() }}
                                </option>
                            @endforeach
                        </select>
                        <span class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 text-label/60">
                            <svg class="size-2.5" viewBox="0 0 10 6" fill="none" aria-hidden="true">
                                <path d="M1 1l4 4 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button
                        type="submit"
                        class="rounded-3xl bg-sage-500 px-6 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                    >
                        Apply Filter
                    </button>

                    @if ($selectedStatus)
                        <a href="{{ route('report.history') }}" class="text-xs font-semibold text-label transition hover:text-ink">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        {{-- ======================== REPORT LIST ======================== --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm">
            @if ($reports->isEmpty())
                <div class="flex flex-col items-center justify-center rounded-2xl bg-input/60 px-6 py-14 text-center">
                    <span class="mb-4 grid size-14 place-items-center rounded-2xl bg-sage-200 text-sage-700 shadow-sm">
                        <x-auth.icon name="bug" class="size-7" />
                    </span>
                    <h3 class="font-serif text-lg font-bold text-ink">Riwayat Masih Kosong</h3>
                    <p class="mt-1.5 max-w-md text-xs leading-relaxed text-label">
                        Anda belum memiliki laporan bug.
                    </p>
                    <a
                        href="{{ route('bug-reports.create') }}"
                        class="mt-5 inline-flex items-center gap-2 rounded-2xl bg-sage-500 px-5 py-2.5 text-xs font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95"
                    >
                        <x-auth.icon name="sparkles" class="size-3.5" />
                        Buat Laporan Bug Pertama
                    </a>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-divider text-[11px] font-bold uppercase tracking-wider text-label">
                                <th class="pb-3 pr-4">Title</th>
                                <th class="pb-3 px-4">Status</th>
                                <th class="pb-3 px-4">Submission Date</th>
                                <th class="pb-3 pl-4 text-right"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-divider">
                            @foreach ($reports as $report)
                                <tr class="transition hover:bg-input/40">
                                    <td class="py-4 pr-4">
                                        <a href="{{ route('report.show', $report) }}" class="group block">
                                            <span class="font-semibold text-ink transition group-hover:text-sage-600">
                                                {{ $report->title }}
                                            </span>
                                            <span class="mt-0.5 block text-[11px] text-label">
                                                {{ $report->module ?? 'Tanpa modul' }}
                                            </span>
                                        </a>
                                    </td>
                                    <td class="py-4 px-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-semibold {{ $report->status->badgeClasses() }}">
                                            <span class="size-1.5 rounded-full bg-current opacity-70"></span>
                                            {{ $report->status->label() }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-4 text-xs text-label">
                                        {{ $report->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="py-4 pl-4 text-right">
                                        <a
                                            href="{{ route('report.show', $report) }}"
                                            class="inline-flex items-center gap-1 text-xs font-semibold text-sage-600 transition hover:text-sage-700 hover:underline"
                                        >
                                            Lihat Detail
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

</x-layouts.app>
