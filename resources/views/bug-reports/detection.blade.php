<x-layouts.app title="Hasil Deteksi Duplikasi — #{{ $bugReport->id }}" meta-description="Hasil analisis kemiripan laporan bug dengan laporan historis pada sistem TwinBug.">

    <div class="mx-auto max-w-screen-xl px-12 py-10 space-y-8">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <a href="{{ route('bug-reports.show', $bugReport) }}" class="text-label transition hover:text-ink">Laporan #{{ $bugReport->id }}</a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-full bg-divider px-2.5 py-1 text-[11px] font-semibold text-ink">Deteksi Duplikasi</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <div class="max-w-3xl">
                <div class="mb-3 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $bugReport->severity->badgeClasses() }}">
                        {{ $bugReport->severity->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $bugReport->status->badgeClasses() }}">
                        <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                        {{ $bugReport->status->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $bugReport->detection_status->badgeClasses() }}">
                        {{ $bugReport->detection_status->label() }}
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="text-xs font-semibold text-label">#{{ $bugReport->id }}</span>
                </div>
                <h1 class="font-serif text-3xl font-bold tracking-tight text-ink md:text-4xl">{{ $bugReport->title }}</h1>
                <p class="mt-2 text-xs text-label">
                    Dilaporkan {{ $bugReport->created_at->format('d M Y H:i') }} • Modul: {{ $bugReport->module ?? 'Lainnya' }}
                </p>
            </div>

            <a
                href="{{ route('bug-reports.show', $bugReport) }}"
                class="inline-flex shrink-0 items-center gap-2 rounded-xl border border-divider bg-white px-4 py-2.5 text-xs font-bold text-ink shadow-sm transition hover:bg-input"
            >
                <x-auth.icon name="arrow-left" class="size-3.5" />
                <span>Kembali ke Detail</span>
            </a>
        </div>

        {{-- ======================== ANALYZED REPORT CARD ======================== --}}
        <div class="rounded-3xl bg-white p-8 shadow-sm border border-divider">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-xl font-bold text-ink">Laporan yang Dianalisis</h2>
                    <p class="mt-0.5 text-xs text-label">Informasi laporan sumber yang dikirimkan untuk deteksi duplikasi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-2xl bg-input p-4">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Judul</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ $bugReport->title }}</dd>
                </div>
                <div class="rounded-2xl bg-input p-4">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ $bugReport->module ?? 'Lainnya' }}</dd>
                </div>
                <div class="rounded-2xl bg-input p-4">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Keparahan</dt>
                    <dd class="mt-1 inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $bugReport->severity->badgeClasses() }}">
                        {{ $bugReport->severity->label() }}
                    </dd>
                </div>
                <div class="rounded-2xl bg-input p-4">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status</dt>
                    <dd class="mt-1 inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $bugReport->status->badgeClasses() }}">
                        <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                        {{ $bugReport->status->label() }}
                    </dd>
                </div>
            </div>

            <div class="mt-6 rounded-2xl bg-input p-4">
                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Deskripsi</dt>
                <dd class="mt-1 whitespace-pre-line text-sm leading-relaxed text-body">{{ $bugReport->description }}</dd>
            </div>

            {{-- Detection status info --}}
            <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-3">
                <div class="rounded-2xl bg-white p-4 border border-divider">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status Deteksi</dt>
                    <dd class="mt-1 inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $bugReport->detection_status->badgeClasses() }}">
                        {{ $bugReport->detection_status->label() }}
                    </dd>
                </div>
                @if ($bugReport->detection_threshold)
                    <div class="rounded-2xl bg-white p-4 border border-divider">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Threshold</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ number_format($bugReport->detection_threshold * 100, 2) }}%</dd>
                    </div>
                @endif
                @if ($bugReport->detected_at)
                    <div class="rounded-2xl bg-white p-4 border border-divider">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Terakhir Diperbarui</dt>
                        <dd class="mt-1 font-semibold text-ink">{{ $bugReport->detected_at->format('d M Y H:i') }}</dd>
                    </div>
                @endif
                <div class="rounded-2xl bg-white p-4 border border-divider">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Model</dt>
                    <dd class="mt-1 font-semibold text-ink">{{ config('services.ml.model', 'paraphrase-multilingual-MiniLM-L12-v2') }}</dd>
                </div>
            </div>

            @if ($bugReport->detection_status === App\Enums\DetectionStatus::Failed && $bugReport->detection_error)
                <div class="mt-4 rounded-2xl bg-danger/10 border border-danger/20 p-4">
                    <div class="flex items-start gap-3">
                        <x-auth.icon name="alert" class="size-4 text-danger shrink-0 mt-0.5" />
                        <div>
                            <p class="text-sm font-medium text-danger">Analisis gagal</p>
                            <p class="mt-1 text-xs text-danger/80">{{ $bugReport->detection_error }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ======================== DETECTION STATES ======================== --}}

        {{-- Failed state --}}
        @if ($bugReport->detection_status === App\Enums\DetectionStatus::Failed)
            <div class="rounded-3xl bg-white p-8 shadow-sm border border-divider">
                <div class="flex items-center gap-3">
                    <x-auth.icon name="alert" class="size-5 text-danger" />
                    <h2 class="font-serif text-xl font-bold text-ink">Analisis Gagal</h2>
                </div>
                <p class="mt-2 text-label">{{ $bugReport->detection_error ?? 'Terjadi kesalahan saat menganalisis duplikasi.' }}</p>

                <form method="POST" action="{{ route('bug-reports.detection.rerun', $bugReport) }}">
                    @csrf
                    <button type="submit" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-danger px-5 py-2.5 text-xs font-bold text-white shadow-cta transition hover:bg-danger/90 active:scale-95">
                        <x-auth.icon name="refresh" class="size-3.5" />
                        <span>Analisis Ulang</span>
                    </button>
                </form>
            </div>
        @endif

        {{-- Pending / Processing state --}}
        @if (in_array($bugReport->detection_status, [App\Enums\DetectionStatus::Pending, App\Enums\DetectionStatus::Processing]))
            <div class="rounded-3xl bg-white p-8 shadow-sm border border-divider">
                <div class="flex items-center gap-3">
                    <x-auth.icon name="alert" class="size-5 text-amber-600" />
                    <h2 class="font-serif text-xl font-bold text-ink">
                        {{ $bugReport->detection_status === App\Enums\DetectionStatus::Pending ? 'Menunggu Analisis' : 'Sedang Menganalisis' }}
                    </h2>
                </div>
                <p class="mt-2 text-label">
                    {{ $bugReport->detection_status === App\Enums\DetectionStatus::Pending
                        ? 'Hasil deteksi duplikasi belum tersedia. Klik tombol di bawah untuk memulai analisis.'
                        : 'Analisis duplikasi sedang diproses. Harap tunggu hingga selesai.' }}
                </p>

                @if ($bugReport->detection_status === App\Enums\DetectionStatus::Pending)
                    <form method="POST" action="{{ route('bug-reports.detection.rerun', $bugReport) }}">
                        @csrf
                        <button type="submit" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-sage-500 px-5 py-2.5 text-xs font-bold text-white shadow-cta transition hover:bg-sage-600 active:scale-95">
                            <x-auth.icon name="sparkles" class="size-3.5" />
                            <span>Mulai Analisis</span>
                        </button>
                    </form>
                @else
                    <a
                        href="{{ route('bug-reports.detection', $bugReport) }}"
                        class="mt-6 inline-flex items-center gap-2 rounded-xl border border-divider bg-white px-5 py-2.5 text-xs font-bold text-ink shadow-sm transition hover:bg-input"
                    >
                        <x-auth.icon name="refresh" class="size-3.5" />
                        <span>Segarkan Halaman</span>
                    </a>
                @endif
            </div>
        @endif

        {{-- Completed state --}}
        @if ($bugReport->detection_status === App\Enums\DetectionStatus::Completed)
            {{-- Empty state --}}
            @if ($results->isEmpty())
                <div class="rounded-3xl bg-white p-8 shadow-sm border border-divider">
                    <div class="flex flex-col items-center justify-center rounded-2xl bg-input/50 px-6 py-14 text-center">
                        <span class="mb-4 grid size-16 place-items-center rounded-2xl bg-sage-200 text-sage-700 shadow-sm">
                            <x-auth.icon name="check" class="size-8" />
                        </span>
                        <h3 class="font-serif text-xl font-bold text-ink">Tidak Ada Laporan Serupa</h3>
                        <p class="mt-2 max-w-md text-xs leading-relaxed text-label">
                            Sistem tidak menemukan laporan bug historis yang secara signifikan mirip dengan laporan ini berdasarkan ambang batas deteksi.
                        </p>
                        <p class="mt-3 text-xs text-label/60">
                            Catatan: Hasil deteksi adalah saran saja, bukan keputusan akhir. Keputusan akhir diambil oleh Developer.
                        </p>
                    </div>
                </div>
            @else
                {{-- Candidate list --}}
                <div class="rounded-3xl bg-white p-8 shadow-sm border border-divider">
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            <h2 class="font-serif text-xl font-bold text-ink">Laporan Kandidat Mirip</h2>
                            <p class="mt-0.5 text-xs text-label">
                                Dihasilkan oleh model {{ config('services.ml.model', 'paraphrase-multilingual-MiniLM-L12-v2') }}.
                                Skor menunjukkan potensi kemiripan, bukan keputusan akhir.
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold bg-ink/5 text-ink">
                            {{ $results->count() }} Kandidat Ditemukan
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm divide-y divide-divider">
                            <thead>
                                <tr class="border-b border-divider text-[11px] font-bold uppercase tracking-wider text-label">
                                    <th class="pb-3 px-4 w-12 text-center">Peringkat</th>
                                    <th class="pb-3 px-4">Judul Laporan</th>
                                    <th class="pb-3 px-4">Modul</th>
                                    <th class="pb-3 px-4">Status</th>
                                    <th class="pb-3 px-4 w-24 text-right">Skor</th>
                                    <th class="pb-3 px-4 w-20 text-center">Tingkat</th>
                                    <th class="pb-3 pl-4 text-right w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-divider">
                                @foreach ($results as $result)
                                    @php
                                        $scorePercent = (float) $result->text_score * 100;
                                        $badgeLevel = 'low';
                                        $badgeLabel = 'Rendah';
                                        $badgeClasses = 'bg-sage-100 text-sage-800';
                                        $mediumMin = (float) (config('detection.badge_medium.min') ?? 0.65);
                                        $highMin = (float) (config('detection.badge_high.min') ?? 0.80);

                                        if ($scorePercent >= $highMin * 100) {
                                            $badgeLevel = 'high';
                                            $badgeLabel = 'Tinggi';
                                            $badgeClasses = 'bg-danger/10 text-danger';
                                        } elseif ($scorePercent >= $mediumMin * 100) {
                                            $badgeLevel = 'medium';
                                            $badgeLabel = 'Sedang';
                                            $badgeClasses = 'bg-amber-100 text-amber-800';
                                        } elseif ($scorePercent >= ($bugReport->detection_threshold ?? 0) * 100) {
                                            $badgeLevel = 'low';
                                            $badgeLabel = 'Rendah';
                                            $badgeClasses = 'bg-sage-100 text-sage-800';
                                        }
                                    @endphp
                                    <tr class="transition hover:bg-input/40 cursor-pointer group"
                                        onclick="location.href='{{ route('bug-reports.detection', ['bugReport' => $bugReport, 'candidate' => $result->candidate_report_id]) }}'"
                                        role="button"
                                        tabindex="0"
                                        aria-label="Pilih kandidat {{ $result->candidateReport->title }}"
                                    >
                                        <td class="py-4 px-4 text-center">
                                            <span class="font-medium text-ink">{{ $result->rank }}</span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="font-semibold text-ink">{{ $result->candidateReport->title }}</div>
                                            <div class="mt-0.5 text-[11px] text-label/70 line-clamp-1 max-w-xs">
                                                #{{ $result->candidate_report_id }} • {{ $result->candidateReport->created_at->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center rounded-lg bg-input px-2.5 py-1 text-[11px] font-medium text-ink">
                                                {{ $result->candidateReport->module ?? 'Lainnya' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $result->candidateReport->status->badgeClasses() }}">
                                                <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                                                {{ $result->candidateReport->status->label() }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <span class="font-serif text-xl font-bold text-ink">{{ number_format($scorePercent, 1) }}%</span>
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $badgeClasses }}">
                                                {{ $badgeLabel }}
                                            </span>
                                        </td>
                                        <td class="py-4 pl-4 text-right">
                                            <a
                                                href="{{ route('bug-reports.detection', ['bugReport' => $bugReport, 'candidate' => $result->candidate_report_id]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-divider bg-white px-2.5 py-1.5 text-[11px] font-semibold text-ink transition hover:bg-sage-50"
                                            >
                                                <x-auth.icon name="eye" class="size-3" />
                                                <span>Bandingkan</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="mt-4 text-xs text-label/60">
                        Catatan: Hasil deteksi adalah saran kemiripan saja, bukan verifikasi definitif. Keputusan akhir apakah
                        dua laporan adalah duplikat dibuat oleh Developer setelah meninjau bukti dan metadata masing-masing.
                    </p>
                    <p class="mt-1 text-xs text-label/60">
                        Model membaca sekitar 128 token awal dari setiap laporan untuk menghitung kemiripan teks.
                    </p>
                </div>

                {{-- Side-by-side comparison --}}
                @if ($selectedCandidate)
                    <div class="rounded-3xl bg-white p-8 shadow-sm border border-divider">
                        <div class="mb-6 flex items-center justify-between">
                            <div>
                                <h2 class="font-serif text-xl font-bold text-ink">Perbandingan Sampingan</h2>
                                <p class="mt-0.5 text-xs text-label">
                                    Membandingkan laporan sumber dengan kandidat "{{ $selectedCandidate->title }}".
                                </p>
                            </div>
                            <a
                                href="{{ route('bug-reports.detection', $bugReport) }}"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-divider bg-white px-3.5 py-2 text-xs font-semibold text-ink transition hover:bg-input"
                            >
                                <x-auth.icon name="x" class="size-3" />
                                <span>Batal</span>
                            </a>
                        </div>

                        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                            {{-- Source report --}}
                            <div class="space-y-4">
                                <div class="rounded-xl bg-sage-50 p-4 border border-sage-100">
                                    <div class="flex items-center gap-2 mb-3">
                                        <x-auth.icon name="bug" class="size-4 text-sage-700" />
                                        <span class="font-semibold text-sage-800">Laporan Sumber</span>
                                    </div>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Judul</dt>
                                            <dd class="font-medium text-ink">{{ $bugReport->title }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul</dt>
                                            <dd class="text-ink">{{ $bugReport->module ?? 'Lainnya' }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Keparahan</dt>
                                            <dd class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $bugReport->severity->badgeClasses() }}">
                                                {{ $bugReport->severity->label() }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status</dt>
                                            <dd class="text-ink">{{ $bugReport->status->label() }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal</dt>
                                            <dd class="text-ink">{{ $bugReport->created_at->format('d M Y H:i') }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="rounded-xl bg-white p-4 border border-divider">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Deskripsi Sumber</dt>
                                    <dd class="mt-2 whitespace-pre-line text-sm leading-relaxed text-body">{{ $bugReport->description }}</dd>
                                </div>
                            </div>

                            {{-- Candidate report --}}
                            <div class="space-y-4">
                                <div class="rounded-xl bg-ink/5 p-4 border border-divider/30">
                                    <div class="flex items-center gap-2 mb-3">
                                        <x-auth.icon name="eye" class="size-4 text-ink" />
                                        <span class="font-semibold text-ink">Kandidat Mirip</span>
                                    </div>
                                    <dl class="space-y-2 text-sm">
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Judul</dt>
                                            <dd class="font-medium text-ink">{{ $selectedCandidate->title }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul</dt>
                                            <dd class="text-ink">{{ $selectedCandidate->module ?? 'Lainnya' }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Keparahan</dt>
                                            <dd class="inline-flex items-center gap-1.5 rounded-full px-2 py-0.5 text-[10px] font-bold {{ $selectedCandidate->severity->badgeClasses() }}">
                                                {{ $selectedCandidate->severity->label() }}
                                            </dd>
                                        </div>
                                        <div class="flex justify-between border-b border-divider/50 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status</dt>
                                            <dd class="text-ink">{{ $selectedCandidate->status->label() }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal</dt>
                                            <dd class="text-ink">{{ $selectedCandidate->created_at->format('d M Y H:i') }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="rounded-xl bg-white p-4 border border-divider">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Deskripsi Kandidat</dt>
                                    <dd class="mt-2 whitespace-pre-line text-sm leading-relaxed text-body">{{ $selectedCandidate->description }}</dd>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif

    </div>

</x-layouts.app>
