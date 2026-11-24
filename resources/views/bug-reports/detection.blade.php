<x-layouts.app title="Hasil Deteksi Duplikasi — #{{ $bugReport->id }}" meta-description="Hasil analisis kemiripan laporan bug dengan laporan historis pada sistem TwinBug.">

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-xs" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-label transition hover:text-ink">
                <x-auth.icon name="bug" class="size-3 text-sage-600" />
                <span class="font-medium">Dashboard</span>
            </a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <a href="{{ route('bug-reports.show', $bugReport) }}" class="text-label transition hover:text-ink">Laporan #{{ $bugReport->id }}</a>
            <svg class="size-2.5 text-label/60" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="rounded-md bg-input px-2 py-0.5 text-[11px] font-semibold text-ink">Deteksi Duplikasi</span>
        </nav>

        {{-- ======================== PAGE HEADER ======================== --}}
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <div class="max-w-3xl">
                <div class="mb-2 flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $bugReport->severity->badgeClasses() }}">
                        {{ $bugReport->severity->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $bugReport->status->badgeClasses() }}">
                        <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                        {{ $bugReport->status->label() }}
                    </span>
                    <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[11px] font-semibold {{ $bugReport->detection_status->badgeClasses() }}">
                        {{ $bugReport->detection_status->label() }}
                    </span>
                    <span class="text-xs text-label">•</span>
                    <span class="font-mono text-xs font-semibold text-label">#{{ $bugReport->id }}</span>
                </div>
                <h1 class="font-serif text-2xl sm:text-3xl font-bold tracking-tight text-ink">{{ $bugReport->title }}</h1>
                <p class="mt-1 text-xs text-label">
                    Dilaporkan {{ $bugReport->created_at->format('d M Y H:i') }} • Modul: <span class="font-mono">{{ $bugReport->module ?? 'Lainnya' }}</span>
                </p>
            </div>

            <a
                href="{{ route('bug-reports.show', $bugReport) }}"
                class="inline-flex shrink-0 items-center gap-1.5 rounded-xl border border-divider bg-white px-3.5 py-2 text-xs font-semibold text-ink shadow-2xs transition hover:bg-input"
            >
                <x-auth.icon name="arrow-left" class="size-3.5" />
                <span>Kembali ke Detail</span>
            </a>
        </div>

        {{-- ======================== ANALYZED REPORT CARD ======================== --}}
        <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <h2 class="font-serif text-base font-bold text-ink">Laporan yang Dianalisis</h2>
                    <p class="text-xs text-label">Informasi laporan sumber yang dikirimkan untuk deteksi duplikasi.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div class="rounded-xl border border-divider/60 bg-input/40 p-3">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Judul</dt>
                    <dd class="mt-0.5 text-xs font-semibold text-ink truncate" title="{{ $bugReport->title }}">{{ $bugReport->title }}</dd>
                </div>
                <div class="rounded-xl border border-divider/60 bg-input/40 p-3">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul</dt>
                    <dd class="mt-0.5 text-xs font-mono font-semibold text-ink">{{ $bugReport->module ?? 'Lainnya' }}</dd>
                </div>
                <div class="rounded-xl border border-divider/60 bg-input/40 p-3">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Keparahan</dt>
                    <dd class="mt-0.5">
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $bugReport->severity->badgeClasses() }}">
                            {{ $bugReport->severity->label() }}
                        </span>
                    </dd>
                </div>
                <div class="rounded-xl border border-divider/60 bg-input/40 p-3">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status</dt>
                    <dd class="mt-0.5">
                        <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $bugReport->status->badgeClasses() }}">
                            <span class="size-1.5 rounded-full bg-current opacity-80"></span>
                            {{ $bugReport->status->label() }}
                        </span>
                    </dd>
                </div>
            </div>

            <div class="mt-4 rounded-xl border border-divider/60 bg-input/40 p-3.5">
                <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Deskripsi</dt>
                <dd class="mt-1 whitespace-pre-line text-xs sm:text-sm leading-relaxed text-body">{{ $bugReport->description }}</dd>
            </div>

            {{-- Detection status info --}}
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <div class="rounded-xl border border-divider/60 bg-white p-3 shadow-2xs">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status Deteksi</dt>
                    <dd class="mt-1 inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $bugReport->detection_status->badgeClasses() }}">
                        {{ $bugReport->detection_status->label() }}
                    </dd>
                </div>
                @if ($bugReport->detection_threshold)
                    <div class="rounded-xl border border-divider/60 bg-white p-3 shadow-2xs">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Threshold</dt>
                        <dd class="mt-1 font-mono text-xs font-semibold text-ink">{{ number_format($bugReport->detection_threshold * 100, 2) }}%</dd>
                    </div>
                @endif
                @if ($bugReport->detected_at)
                    <div class="rounded-xl border border-divider/60 bg-white p-3 shadow-2xs">
                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Terakhir Diperbarui</dt>
                        <dd class="mt-1 text-xs font-semibold text-ink">{{ $bugReport->detected_at->format('d M Y H:i') }}</dd>
                    </div>
                @endif
                <div class="rounded-xl border border-divider/60 bg-white p-3 shadow-2xs">
                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Model</dt>
                    <dd class="mt-1 font-mono text-[11px] font-semibold text-ink truncate" title="{{ config('services.ml.model', 'paraphrase-multilingual-MiniLM-L12-v2') }}">
                        {{ config('services.ml.model', 'paraphrase-multilingual-MiniLM-L12-v2') }}
                    </dd>
                </div>
            </div>

            @if ($bugReport->detection_status === App\Enums\DetectionStatus::Failed && $bugReport->detection_error)
                <div class="mt-4 rounded-xl border border-danger/30 bg-rose-50/70 p-3.5">
                    <div class="flex items-start gap-2.5">
                        <x-auth.icon name="alert" class="size-4 text-danger shrink-0 mt-0.5" />
                        <div>
                            <p class="text-xs font-semibold text-danger">Analisis gagal</p>
                            <p class="mt-0.5 text-xs text-danger/80">{{ $bugReport->detection_error }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- ======================== DETECTION STATES ======================== --}}

        {{-- Failed state --}}
        @if ($bugReport->detection_status === App\Enums\DetectionStatus::Failed)
            <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <x-auth.icon name="alert" class="size-4 text-danger" />
                    <h2 class="font-serif text-base font-bold text-ink">Analisis Gagal</h2>
                </div>
                <p class="mt-2 text-xs text-label">{{ $bugReport->detection_error ?? 'Terjadi kesalahan saat menganalisis duplikasi.' }}</p>

                <form method="POST" action="{{ route('bug-reports.detection.rerun', $bugReport) }}">
                    @csrf
                    <button type="submit" class="mt-4 inline-flex items-center gap-1.5 rounded-xl bg-danger px-4 py-2 text-xs font-semibold text-white shadow-cta transition hover:bg-danger/90 active:scale-95 cursor-pointer">
                        <x-auth.icon name="refresh" class="size-3.5" />
                        <span>Jalankan ulang analisis</span>
                    </button>
                </form>
            </div>
        @endif

        {{-- Pending / Processing state --}}
        @if (in_array($bugReport->detection_status, [App\Enums\DetectionStatus::Pending, App\Enums\DetectionStatus::Processing]))
            <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                <div class="flex items-center gap-2.5">
                    <x-auth.icon name="alert" class="size-4 text-amber-600" />
                    <h2 class="font-serif text-base font-bold text-ink">
                        {{ $bugReport->detection_status === App\Enums\DetectionStatus::Pending ? 'Menunggu Analisis' : 'Sedang Menganalisis' }}
                    </h2>
                </div>
                <p class="mt-2 text-xs text-label">
                    Analisis kemiripan sedang diproses. Silakan cek kembali beberapa saat lagi.
                </p>

                @if ($bugReport->detection_status === App\Enums\DetectionStatus::Processing)
                    <a
                        href="{{ route('report.detection_result', $bugReport) }}"
                        class="mt-4 inline-flex items-center gap-1.5 rounded-xl border border-divider bg-white px-4 py-2 text-xs font-semibold text-ink shadow-2xs transition hover:bg-input"
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
                <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                    <div class="flex flex-col items-center justify-center rounded-xl border border-dashed border-divider bg-input/30 px-6 py-10 text-center">
                        <span class="mb-3 grid size-10 place-items-center rounded-xl bg-emerald-100 text-emerald-700 shadow-2xs">
                            <x-auth.icon name="check" class="size-5" />
                        </span>
                        <h3 class="font-serif text-sm font-bold text-ink">Tidak ditemukan laporan bug serupa.</h3>
                        <p class="mt-1 max-w-md text-xs leading-relaxed text-label">
                            Sistem tidak menemukan laporan bug historis yang secara signifikan mirip dengan laporan ini berdasarkan ambang batas deteksi.
                        </p>
                        <p class="mt-2 text-[11px] text-label/60">
                            Catatan: Hasil deteksi adalah saran saja, bukan keputusan akhir. Keputusan akhir diambil oleh Developer.
                        </p>
                    </div>
                </div>
            @else
                {{-- Candidate list --}}
                <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                        <div>
                            <h2 class="font-serif text-base font-bold text-ink">Similar Historical Bug Reports</h2>
                            <p class="text-xs text-label">
                                Dihasilkan oleh model <span class="font-mono text-[11px]">{{ config('services.ml.model', 'paraphrase-multilingual-MiniLM-L12-v2') }}</span>. Skor menunjukkan potensi kemiripan.
                            </p>
                        </div>
                        <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold bg-input text-ink">
                            {{ $results->count() }} Kandidat Ditemukan
                        </span>
                    </div>

                    <div class="overflow-x-auto -mx-5 sm:mx-0">
                        <table class="w-full text-left text-xs divide-y divide-divider/70">
                            <thead>
                                <tr class="border-b border-divider/80 bg-input/40 text-[10px] font-bold uppercase tracking-wider text-label">
                                    <th class="py-2.5 px-3 w-12 text-center font-semibold">Rank</th>
                                    <th class="py-2.5 px-4 font-semibold">Judul Laporan</th>
                                    <th class="py-2.5 px-3 font-semibold">Modul</th>
                                    <th class="py-2.5 px-3 font-semibold">Status</th>
                                    <th class="py-2.5 px-3 w-20 text-right font-semibold">Skor Teks</th>
                                    <th class="py-2.5 px-3 w-20 text-right font-semibold">Skor Gambar</th>
                                    <th class="py-2.5 px-3 w-20 text-center font-semibold">Tingkat</th>
                                    <th class="py-2.5 px-4 text-right w-24 font-semibold">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-divider/70">
                                @foreach ($results as $result)
                                    @php
                                        $textScore = $result->text_score ?? null;
                                        $imageScore = $result->image_score ?? null;
                                        $combined = max($textScore ?? 0, $imageScore ?? 0);
                                        $combinedPercent = $combined * 100;

                                        $badgeLevel = 'low';
                                        $badgeLabel = 'Rendah';
                                        $badgeClasses = 'bg-slate-100 text-slate-800';
                                        $mediumMin = (float) (config('detection.badge_medium.min') ?? 0.65);
                                        $highMin = (float) (config('detection.badge_high.min') ?? 0.80);

                                        if ($combinedPercent >= $highMin * 100) {
                                            $badgeLevel = 'high';
                                            $badgeLabel = 'Tinggi';
                                            $badgeClasses = 'bg-rose-50 text-rose-700 ring-1 ring-rose-200';
                                        } elseif ($combinedPercent >= $mediumMin * 100) {
                                            $badgeLevel = 'medium';
                                            $badgeLabel = 'Sedang';
                                            $badgeClasses = 'bg-amber-50 text-amber-800 ring-1 ring-amber-200';
                                        } elseif ($combinedPercent >= ($bugReport->detection_threshold ?? 0) * 100) {
                                            $badgeLevel = 'low';
                                            $badgeLabel = 'Rendah';
                                            $badgeClasses = 'bg-slate-100 text-slate-800';
                                        }
                                    @endphp
                                    <tr class="transition hover:bg-slate-50/70 cursor-pointer group"
                                        onclick="location.href='{{ route('bug-reports.detection', ['bugReport' => $bugReport, 'candidate' => $result->candidate_report_id]) }}'"
                                        role="button"
                                        tabindex="0"
                                        aria-label="Pilih kandidat {{ $result->candidateReport->title }}"
                                    >
                                        <td class="py-3 px-3 text-center">
                                            <span class="grid size-6 place-items-center rounded-md font-mono text-xs font-bold text-ink bg-input mx-auto">{{ $result->rank }}</span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <div class="font-semibold text-ink line-clamp-1">{{ $result->candidateReport->title }}</div>
                                            <div class="mt-0.5 text-[10px] text-label/70 line-clamp-1">
                                                #{{ $result->candidate_report_id }} • {{ $result->candidateReport->created_at->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center rounded-md bg-input px-2 py-0.5 text-[10px] font-mono font-medium text-ink">
                                                {{ $result->candidateReport->module ?? 'Lainnya' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $result->candidateReport->status->badgeClasses() }}">
                                                <span class="size-1 rounded-full bg-current opacity-80"></span>
                                                {{ $result->candidateReport->status->label() }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-right">
                                            @if ($textScore !== null)
                                                <span class="font-serif text-sm font-bold text-ink">{{ number_format($textScore * 100, 1) }}%</span>
                                            @else
                                                <span class="text-xs text-label/50 italic">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-right">
                                            @if ($imageScore !== null)
                                                <span class="font-serif text-sm font-bold text-ink">{{ number_format($imageScore * 100, 1) }}%</span>
                                            @else
                                                <span class="text-xs text-label/50 italic">-</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="inline-flex items-center rounded-md px-2 py-0.5 text-[10px] font-semibold {{ $badgeClasses }}">
                                                {{ $badgeLabel }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-right">
                                            <a
                                                href="{{ route('bug-reports.detection', ['bugReport' => $bugReport, 'candidate' => $result->candidate_report_id]) }}"
                                                class="inline-flex items-center gap-1 rounded-lg border border-divider bg-white px-2.5 py-1 text-[11px] font-semibold text-ink transition hover:border-slate-300 hover:bg-slate-50"
                                            >
                                                <x-auth.icon name="eye" class="size-2.5" />
                                                <span>Bandingkan</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3.5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 text-[11px] text-label/70 border-t border-divider/60 pt-3">
                        <p>Catatan: Hasil deteksi adalah saran kemiripan. Keputusan duplikasi dibuat oleh Developer.</p>
                        <p class="font-mono">Model: ~128 token input</p>
                    </div>
                </div>

                {{-- Side-by-side comparison --}}
                @if ($selectedCandidate)
                    <div class="rounded-2xl border border-divider/80 bg-white p-5 sm:p-6 shadow-xs">
                        <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                            <div>
                                <h2 class="font-serif text-base font-bold text-ink">Perbandingan Sampingan</h2>
                                <p class="text-xs text-label">
                                    Membandingkan laporan sumber dengan kandidat <strong class="text-ink">"{{ $selectedCandidate->title }}"</strong>.
                                </p>
                            </div>
                            <a
                                href="{{ route('bug-reports.detection', $bugReport) }}"
                                class="inline-flex items-center gap-1 rounded-lg border border-divider bg-white px-2.5 py-1 text-xs font-semibold text-ink transition hover:bg-input"
                            >
                                <x-auth.icon name="x" class="size-3" />
                                <span>Batal</span>
                            </a>
                        </div>

                        {{-- Similarity Banner --}}
                        <div class="mb-5 rounded-xl border border-divider/80 bg-input/40 p-3.5">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-center gap-3">
                                    <div>
                                        <p class="text-[10px] font-bold uppercase tracking-wider text-label">Skor Kemiripan</p>
                                        @if ($selectedResult->text_score !== null)
                                            <p class="font-serif text-2xl font-bold text-ink">{{ number_format((float) $selectedResult->text_score * 100, 1) }}%</p>
                                        @else
                                            <p class="font-serif text-2xl font-bold text-ink">-</p>
                                        @endif
                                    </div>
                                    <span class="rounded-md bg-sage-100 px-2 py-0.5 text-[10px] font-semibold text-sage-800">
                                        Rank #{{ $selectedResult->rank }} dari {{ $results->count() }}
                                    </span>
                                </div>
                                <p class="text-xs text-label leading-relaxed max-w-md">
                                    Dihitung dari judul, deskripsi, dan konteks visual screenshot bila tersedia.
                                </p>
                                <p class="text-xs text-label">
                                    @if ($selectedResult->image_score !== null)
                                        Skor kemiripan gambar: {{ number_format((float) $selectedResult->image_score * 100, 1) }}%
                                    @else
                                        Skor kemiripan gambar: tidak tersedia
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                            {{-- Source report --}}
                            <div class="space-y-3.5">
                                <div class="rounded-xl border border-emerald-200/80 bg-emerald-50/50 p-3.5">
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <x-auth.icon name="bug" class="size-3.5 text-emerald-800" />
                                        <span class="text-xs font-bold text-emerald-950 uppercase tracking-wider">Laporan Sumber</span>
                                    </div>
                                    <dl class="space-y-1.5 text-xs">
                                        <div class="flex justify-between border-b border-emerald-100 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Judul</dt>
                                            <dd class="font-semibold text-ink truncate max-w-xs text-right">{{ $bugReport->title }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-emerald-100 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul</dt>
                                            <dd class="font-mono text-ink">{{ $bugReport->module ?? 'Lainnya' }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-emerald-100 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Keparahan</dt>
                                            <dd>
                                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $bugReport->severity->badgeClasses() }}">
                                                    {{ $bugReport->severity->label() }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="flex justify-between border-b border-emerald-100 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status</dt>
                                            <dd class="font-semibold text-ink">{{ $bugReport->status->label() }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal</dt>
                                            <dd class="text-ink">{{ $bugReport->created_at->format('d M Y H:i') }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="rounded-xl border border-divider/70 bg-input/30 p-3.5">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Deskripsi Sumber</dt>
                                    <dd class="mt-1.5 whitespace-pre-line text-xs sm:text-sm leading-relaxed text-body">{{ $bugReport->description }}</dd>
                                </div>
                                <div class="rounded-xl border border-divider/70 bg-white p-3.5 shadow-2xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Screenshot</dt>
                                        @if ($bugReport->hasScreenshot())
                                            <a
                                                href="{{ asset('storage/' . $bugReport->screenshot_path) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1 text-[11px] font-semibold text-sage-700 transition hover:text-sage-800"
                                            >
                                                <x-auth.icon name="eye" class="size-3" />
                                                <span>Buka Penuh</span>
                                            </a>
                                        @endif
                                    </div>
                                    @if ($bugReport->hasScreenshot())
                                        <a
                                            href="{{ asset('storage/' . $bugReport->screenshot_path) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="mt-2 block overflow-hidden rounded-xl border border-divider/80 bg-slate-100"
                                        >
                                            <img
                                                src="{{ asset('storage/' . $bugReport->screenshot_path) }}"
                                                alt="Screenshot laporan sumber {{ $bugReport->title }}"
                                                class="max-h-60 w-full object-contain"
                                            >
                                        </a>
                                    @else
                                        <p class="mt-2 rounded-xl border border-dashed border-divider bg-input/40 px-4 py-6 text-center text-xs text-label">Tidak ada screenshot</p>
                                    @endif
                                </div>
                                <div class="rounded-xl border border-divider/70 bg-white p-3.5 shadow-2xs">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Analisis Visual Screenshot (AI)</dt>
                                    @if ($bugReport->hasImageDescription())
                                        <dd class="mt-1.5 whitespace-pre-line text-xs sm:text-sm leading-relaxed text-body">{{ $bugReport->image_description }}</dd>
                                        <p class="mt-2 text-[10px] text-label/60">Dihasilkan otomatis oleh LLM sebagai konteks tambahan untuk perbandingan.</p>
                                    @elseif ($bugReport->hasScreenshot())
                                        <dd class="mt-1.5 text-xs text-label">Belum ada analisis visual. Jalankan analisis ulang atau command backfill.</dd>
                                    @else
                                        <dd class="mt-1.5 text-xs text-label">Tidak ada screenshot untuk dianalisis.</dd>
                                    @endif
                                </div>
                            </div>

                            {{-- Candidate report --}}
                            <div class="space-y-3.5">
                                <div class="rounded-xl border border-slate-200/80 bg-slate-100/60 p-3.5">
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <x-auth.icon name="eye" class="size-3.5 text-slate-800" />
                                        <span class="text-xs font-bold text-slate-900 uppercase tracking-wider">Kandidat Mirip</span>
                                    </div>
                                    <dl class="space-y-1.5 text-xs">
                                        <div class="flex justify-between border-b border-slate-200 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Judul</dt>
                                            <dd class="font-semibold text-ink truncate max-w-xs text-right">{{ $selectedCandidate->title }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-slate-200 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Modul</dt>
                                            <dd class="font-mono text-ink">{{ $selectedCandidate->module ?? 'Lainnya' }}</dd>
                                        </div>
                                        <div class="flex justify-between border-b border-slate-200 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Keparahan</dt>
                                            <dd>
                                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-semibold {{ $selectedCandidate->severity->badgeClasses() }}">
                                                    {{ $selectedCandidate->severity->label() }}
                                                </span>
                                            </dd>
                                        </div>
                                        <div class="flex justify-between border-b border-slate-200 pb-1">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Status</dt>
                                            <dd class="font-semibold text-ink">{{ $selectedCandidate->status->label() }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Tanggal</dt>
                                            <dd class="text-ink">{{ $selectedCandidate->created_at->format('d M Y H:i') }}</dd>
                                        </div>
                                    </dl>
                                </div>
                                <div class="rounded-xl border border-divider/70 bg-input/30 p-3.5">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Deskripsi Kandidat</dt>
                                    <dd class="mt-1.5 whitespace-pre-line text-xs sm:text-sm leading-relaxed text-body">{{ $selectedCandidate->description }}</dd>
                                </div>
                                <div class="rounded-xl border border-divider/70 bg-white p-3.5 shadow-2xs">
                                    <div class="flex items-center justify-between gap-2">
                                        <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Screenshot</dt>
                                        @if ($selectedCandidate->hasScreenshot())
                                            <a
                                                href="{{ asset('storage/' . $selectedCandidate->screenshot_path) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1 text-[11px] font-semibold text-sage-700 transition hover:text-sage-800"
                                            >
                                                <x-auth.icon name="eye" class="size-3" />
                                                <span>Buka Penuh</span>
                                            </a>
                                        @endif
                                    </div>
                                    @if ($selectedCandidate->hasScreenshot())
                                        <a
                                            href="{{ asset('storage/' . $selectedCandidate->screenshot_path) }}"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="mt-2 block overflow-hidden rounded-xl border border-divider/80 bg-slate-100"
                                        >
                                            <img
                                                src="{{ asset('storage/' . $selectedCandidate->screenshot_path) }}"
                                                alt="Screenshot kandidat {{ $selectedCandidate->title }}"
                                                class="max-h-60 w-full object-contain"
                                            >
                                        </a>
                                    @else
                                        <p class="mt-2 rounded-xl border border-dashed border-divider bg-input/40 px-4 py-6 text-center text-xs text-label">Tidak ada screenshot</p>
                                    @endif
                                </div>
                                <div class="rounded-xl border border-divider/70 bg-white p-3.5 shadow-2xs">
                                    <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Analisis Visual Screenshot (AI)</dt>
                                    @if ($selectedCandidate->hasImageDescription())
                                        <dd class="mt-1.5 whitespace-pre-line text-xs sm:text-sm leading-relaxed text-body">{{ $selectedCandidate->image_description }}</dd>
                                        <p class="mt-2 text-[10px] text-label/60">Dihasilkan otomatis oleh LLM sebagai konteks tambahan untuk perbandingan.</p>
                                    @elseif ($selectedCandidate->hasScreenshot())
                                        <dd class="mt-1.5 text-xs text-label">Belum ada analisis visual. Jalankan analisis ulang atau command backfill.</dd>
                                    @else
                                        <dd class="mt-1.5 text-xs text-label">Tidak ada screenshot untuk dianalisis.</dd>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        @endif

    </div>

</x-layouts.app>