<x-layouts.app title="Developer Dashboard" meta-description="Developer dashboard for TwinBug to review incoming reports, validate detection results, and keep the triage queue moving.">

    {{-- Sidebar --}}
    <aside class="developer-dashboard-sidebar flex flex-col justify-between bg-white px-[14px] pb-[14px] border-r-[0.8px] border-[#E2E8E5]" aria-label="Developer workspace navigation">
        <div class="flex flex-col">
            {{-- Logo --}}
            <div class="flex h-[54px] items-center justify-between border-b-[0.8px] border-[#EDF0EE] px-2">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-[10px]">
                    <span class="grid size-[34px] place-items-center rounded-[9px] bg-[#123C2E] text-white shadow-[0_4px_12px_rgba(18,60,46,0.17)]">
                        <x-auth.icon name="bug" class="size-5 stroke-[2]" />
                    </span>
                    <span class="text-[19px] font-bold leading-[28px] tracking-[-0.45px] text-[#0D2D22]">TwinBug</span>
                </a>
            </div>

            {{-- Navigation --}}
            <div class="pt-[18px]">
                <p class="px-3 pt-[10px] text-[10px] font-bold uppercase leading-[15px] tracking-[1.1px] text-[#98A19C]">Main menu</p>
                <nav class="mt-[7px] space-y-[2px]" aria-label="Main menu">
                    <a href="{{ route('dashboard') }}" aria-current="page" class="flex h-[42px] items-center justify-between rounded-[8px] bg-[#F2F8F5] px-3 text-[13.5px] font-semibold leading-5 text-[#19513D]">
                        <span class="flex items-center gap-[11px]">
                            <x-auth.icon name="home" class="size-[18px]" />
                            <span>Dashboard</span>
                        </span>
                        <span class="h-[18px] w-[3px] rounded-[3px] bg-[#236B51]"></span>
                    </a>
                    <a href="{{ route('bug-reports.index') }}" class="flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                        <x-auth.icon name="search" class="size-[18px]" />
                        <span>Detection Results</span>
                    </a>
                    <a href="{{ route('bug-reports.index') }}" class="flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                        <x-auth.icon name="filter" class="size-[18px]" />
                        <span>Bug Reports</span>
                    </a>
                </nav>

                <p class="px-3 pt-[10px] text-[10px] font-bold uppercase leading-[15px] tracking-[1.1px] text-[#98A19C]">General</p>
                <nav class="mt-[7px] space-y-[2px]" aria-label="General">
                    <a href="{{ route('about') }}" class="flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                        <x-auth.icon name="user" class="size-[18px]" />
                        <span>About Us</span>
                    </a>
                    <a href="{{ route('faq') }}" class="flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                        <x-auth.icon name="alert" class="size-[18px]" />
                        <span>FAQ</span>
                    </a>
                </nav>

                <p class="px-3 pt-[10px] text-[10px] font-bold uppercase leading-[15px] tracking-[1.1px] text-[#98A19C]">Account</p>
                <nav class="mt-[7px] space-y-[2px]" aria-label="Account">
                    <a href="{{ route('profile') }}" class="flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                        <x-auth.icon name="user" class="size-[18px]" />
                        <span>My Profile</span>
                    </a>
                    <a href="{{ route('settings') }}" class="flex h-[42px] items-center gap-[11px] rounded-[8px] px-3 text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                        <x-auth.icon name="code" class="size-[18px]" />
                        <span>Manage Settings</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex h-[42px] w-full cursor-pointer items-center gap-[11px] rounded-[8px] px-3 text-left text-[13.5px] font-medium leading-5 text-[#65706B] transition hover:bg-[#F7F9F8]">
                            <x-auth.icon name="logout" class="size-[18px]" />
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        {{-- Bottom Need Help Card + User --}}
        <div class="mt-4 flex flex-col">
            <div class="rounded-[10px] border-[0.8px] border-[#E5EBE8] bg-[#F5F8F6] p-[14px]">
                <span class="grid size-[30px] place-items-center rounded-[8px] border-[0.8px] border-[#DFE7E3] bg-white text-[#236B51]">
                    <x-auth.icon name="shield" class="size-[18px]" />
                </span>
                <h2 class="mt-[10px] text-[13px] font-bold leading-5 text-[#09090B]">Need help?</h2>
                <p class="mt-1 text-[11.5px] leading-[17px] text-[#67736D]">Read our guide to writing high-quality bug reports.</p>
                <a href="{{ route('faq') }}" class="mt-[10px] inline-flex items-center gap-[5px] text-[11.5px] font-semibold leading-[17px] text-[#236B51] hover:underline">
                    <span>Open help center</span>
                    <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="mt-[14px] flex items-center gap-[9px] border-t-[0.8px] border-[#E2E8E5] px-1 pt-[15px]">
                <span class="grid size-[34px] shrink-0 place-items-center rounded-full bg-[#DCECE4] text-[11px] font-bold text-[#19513D]">
                    {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
                </span>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[12.5px] font-bold leading-[19px] text-[#09090B]">{{ $user->name }}</p>
                    <p class="truncate text-[10.5px] leading-4 text-[#67736D]">{{ $user->role->label() }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Workspace --}}
    <div class="developer-dashboard-main">
        {{-- Topbar --}}
        <div class="developer-dashboard-topbar h-[72px] border-b-[0.8px] border-[#E2E8E5] bg-white/96 px-8 backdrop-blur-md">
            <div class="flex items-center gap-[7px] text-[11px]">
                <span class="text-[#8A9590]">Workspace</span>
                <span class="text-[#8A9590]">›</span>
                <span class="font-semibold text-[#34423B]">Developer Dashboard</span>
            </div>
            <nav class="developer-mobile-nav" aria-label="Workspace navigation">
                <a href="{{ route('dashboard') }}" class="font-semibold text-[#19513D]">Dashboard</a>
                <a href="{{ route('bug-reports.index') }}" class="text-[#65706B]">Bug Reports</a>
                <a href="{{ route('profile') }}" class="text-[#65706B]">My Profile</a>
            </nav>
            <div class="flex items-center gap-[10px]">
                <span class="hidden h-[29.6px] items-center gap-[5px] rounded-full border-[0.8px] border-[#DFE6E2] bg-[#F7F9F8] px-[9px] text-[10.5px] text-[#718079] sm:inline-flex">
                    <x-auth.icon name="sparkles" class="size-[14px]" />
                    <span>{{ $user->application?->name ?? 'Workspace' }}</span>
                </span>
                <a href="{{ route('bug-reports.index', ['status' => 'open']) }}" class="hidden size-9 place-items-center rounded-[8px] border-[0.8px] border-[#E2E8E5] bg-white text-[#66716C] transition hover:bg-[#F7F9F8] sm:grid" aria-label="Open queue">
                    <x-auth.icon name="alert" class="size-[19px]" />
                </a>
                <a href="{{ route('profile') }}" class="flex items-center gap-[9px] px-[3px] py-[2px]">
                    <span class="grid size-[34px] place-items-center rounded-full bg-[#DCECE4] text-[11px] font-bold text-[#19513D]">
                        {{ mb_strtoupper(mb_substr($user->name, 0, 2)) }}
                    </span>
                    <span class="hidden text-left sm:block">
                        <span class="block text-[12px] font-bold leading-[18px] text-[#24302A]">{{ $user->name }}</span>
                        <span class="block text-[10px] leading-[15px] text-[#59655F]">{{ $user->role->label() }}</span>
                    </span>
                </a>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="developer-dashboard-content mx-auto max-w-[1540px] p-8 space-y-[26px]">

            {{-- Page Heading --}}
            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h1 class="text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">Developer Dashboard</h1>
                    <p class="mt-[7px] text-[13px] leading-5 text-[#67736D]">Review incoming reports, validate detection results, and keep the triage queue moving.</p>
                </div>

                <form method="GET" action="{{ route('dashboard') }}" class="shrink-0">
                    <input type="hidden" name="q" value="{{ $filters['q'] ?? '' }}">
                    <input type="hidden" name="module" value="{{ $filters['module'] ?? 'all' }}">
                    <input type="hidden" name="status" value="{{ $filters['status'] ?? 'all' }}">
                    <select name="period" onchange="this.form.submit()" aria-label="Timeframe"
                        class="h-[41px] w-[145px] rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-3 text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                        <option value="30" @selected(($filters['period'] ?? '30') === '30')>Last 30 days</option>
                        <option value="7" @selected(($filters['period'] ?? '') === '7')>Last 7 days</option>
                        <option value="all" @selected(($filters['period'] ?? '') === 'all')>All time</option>
                    </select>
                </form>
            </div>

            {{-- 3 Stat Cards --}}
            @php
                $totalReports = max(0, $stats['total']);
                $uniqueCount = $stats['unique'] ?? 0;
                $uniquePercent = $totalReports > 0 ? number_format(($uniqueCount / $totalReports) * 100, 1) : '0.0';
            @endphp
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                {{-- Card 1: Total reports --}}
                <div class="flex items-start gap-[14px] rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[18px] shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                    <div class="grid size-[39px] shrink-0 place-items-center rounded-[9px] bg-[#E9F4EF] text-[#277253]">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M10 9H8" />
                            <path d="M16 13H8" />
                            <path d="M16 17H8" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11.5px] font-medium leading-[17px] text-[#67736D]">Total reports</p>
                        <p class="pt-[5px] pb-[3px] text-[24px] font-bold leading-9 tracking-[-0.6px] text-[#09090B]">{{ number_format($stats['total']) }}</p>
                        <p class="text-[10.5px] leading-4 text-[#84908A]">{{ number_format($stats['in_progress']) }} currently in progress</p>
                    </div>
                </div>

                {{-- Card 2: Potential duplicates --}}
                <div class="flex items-start gap-[14px] rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[18px] shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                    <div class="grid size-[39px] shrink-0 place-items-center rounded-[9px] bg-[#FAF2DF] text-[#997121]">
                        <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.67" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[11.5px] font-medium leading-[17px] text-[#67736D]">Potential duplicates</p>
                        <p class="pt-[5px] pb-[3px] text-[24px] font-bold leading-9 tracking-[-0.6px] text-[#09090B]">{{ number_format($stats['potential_duplicates'] ?? 0) }}</p>
                        <p class="text-[10.5px] leading-4 text-[#84908A]">{{ number_format($stats['open']) }} awaiting review</p>
                    </div>
                </div>

                {{-- Card 3: Unique reports --}}
                <div class="flex items-start gap-[14px] rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[18px] shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                    <div class="grid size-[39px] shrink-0 place-items-center rounded-[9px] bg-[#E9F1F6] text-[#3D6E8D]">
                        <x-auth.icon name="shield" class="size-5" />
                    </div>
                    <div>
                        <p class="text-[11.5px] font-medium leading-[17px] text-[#67736D]">Unique reports</p>
                        <p class="pt-[5px] pb-[3px] text-[24px] font-bold leading-9 tracking-[-0.6px] text-[#09090B]">{{ number_format($uniqueCount) }}</p>
                        <p class="text-[10.5px] leading-4 text-[#84908A]">{{ $uniquePercent }}% of all reports</p>
                    </div>
                </div>
            </div>

            {{-- Triage Queue Card --}}
            <div class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white shadow-[0_1px_2px_rgba(12,35,25,0.024)]">
                {{-- Header --}}
                <div class="flex flex-wrap items-center justify-between gap-4 border-b-[0.8px] border-[#E9EEEB] px-5 py-[18px]">
                    <div>
                        <h2 class="text-[14px] font-normal leading-[21px] tracking-[-0.15px] text-[#09090B]">Triage queue</h2>
                        <p class="pt-1 text-[11px] leading-4 text-[#67736D]">Reports that need a developer decision.</p>
                    </div>
                    <span class="rounded-full bg-[#F3F6F4] px-[9px] py-[6px] text-[9.5px] leading-[14px] text-[#65736C]">
                        {{ number_format($stats['open']) }} reports awaiting review
                    </span>
                </div>

                {{-- Filter Toolbar --}}
                <form method="GET" action="{{ route('dashboard') }}" class="flex flex-wrap items-center gap-[9px] border-b-[0.8px] border-[#E2E8E5] px-[18px] py-[14px]">
                    <input type="hidden" name="period" value="{{ $filters['period'] ?? '30' }}">
                    <label class="flex h-10 min-w-[260px] flex-1 items-center gap-2 rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px]">
                        <x-auth.icon name="search" class="size-4 shrink-0 text-[#87928C]" />
                        <input
                            type="text"
                            name="q"
                            value="{{ $filters['q'] ?? '' }}"
                            placeholder="Search by ID or bug title"
                            class="w-full bg-transparent text-[11.5px] text-[#24322B] placeholder:text-[#A4ADA8] focus:outline-none">
                    </label>

                    <select name="module" onchange="this.form.submit()" aria-label="Filter by module"
                        class="h-[41px] w-[150px] rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-3 text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                        <option value="all" @selected(($filters['module'] ?? 'all') === 'all')>All modules</option>
                        @foreach ($modules as $mod)
                            <option value="{{ $mod }}" @selected(($filters['module'] ?? '') === $mod)>{{ $mod }}</option>
                        @endforeach
                    </select>

                    <select name="status" onchange="this.form.submit()" aria-label="Filter by status"
                        class="h-[41px] w-[150px] rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-3 text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                        <option value="all" @selected(($filters['status'] ?? 'all') === 'all')>All statuses</option>
                        @foreach ($statuses as $stat)
                            <option value="{{ $stat->value }}" @selected(($filters['status'] ?? '') === $stat->value)>{{ $stat->label() }}</option>
                        @endforeach
                    </select>

                    <a href="{{ route('bug-reports.index') }}"
                        class="inline-flex h-10 items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                        <x-auth.icon name="filter" class="size-4" />
                        <span>More filters</span>
                    </a>
                </form>

                {{-- Table --}}
                @if ($recentReports->isEmpty())
                    <div class="px-6 py-12 text-center">
                        <p class="text-[13px] font-semibold text-[#142019]">No reports in queue</p>
                        <p class="mt-1 text-[11.5px] text-[#67736D]">Incoming bug reports for your application will appear here.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[900px] border-collapse text-left">
                            <thead>
                                <tr class="h-[37.4px] border-b-[0.8px] border-[#E2E8E5] bg-[#FAFBFA] text-[10px] font-semibold uppercase tracking-[0.35px] text-[#77837D]">
                                    <th class="px-[15px]">Report ID</th>
                                    <th class="px-[15px]">Bug title</th>
                                    <th class="px-[15px]">Reporter</th>
                                    <th class="px-[15px]">Module</th>
                                    <th class="px-[15px]">Submitted</th>
                                    <th class="px-[15px]">Status</th>
                                    <th class="px-[15px]">Similarity</th>
                                    <th class="px-[15px]"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentReports as $report)
                                    @php
                                        $topResult = $report->detectionResults->first();
                                        $topScore = $topResult ? max((float) ($topResult->text_score ?? 0), (float) ($topResult->image_score ?? 0)) : null;
                                        $topPercent = $topScore !== null ? (int) round($topScore * 100) : null;

                                        $isAnalyzing = in_array($report->detection_status, [App\Enums\DetectionStatus::Pending, App\Enums\DetectionStatus::Processing]);
                                        $isPotentialDup = $report->duplicate_of_id !== null || ($topPercent !== null && $topPercent >= 65);

                                        if ($report->duplicate_of_id !== null || $isPotentialDup) {
                                            $pillBg = 'bg-[#FBF4E3] border-[#F2E4BD] text-[#8B6418]';
                                            $pillDot = 'bg-[#8B6418]';
                                            $pillLabel = 'Potential duplicate';
                                        } elseif ($isAnalyzing) {
                                            $pillBg = 'bg-[#EEF5F9] border-[#D9E9F2] text-[#356C8C]';
                                            $pillDot = 'bg-[#356C8C]';
                                            $pillLabel = 'Processing';
                                        } elseif ($report->status === App\Enums\BugStatus::Resolved) {
                                            $pillBg = 'bg-[#EDF7F2] border-[#D9EEE3] text-[#26714F]';
                                            $pillDot = 'bg-[#26714F]';
                                            $pillLabel = 'Resolved';
                                        } else {
                                            $pillBg = 'bg-[#F1F4F2] border-[#E0E5E2] text-[#58665F]';
                                            $pillDot = 'bg-[#58665F]';
                                            $pillLabel = $report->status->label();
                                        }
                                    @endphp
                                    <tr class="h-[59.58px] border-b-[0.8px] border-[#EDF0EE] last:border-b-0 hover:bg-[#FAFBFA]/70">
                                        <td class="px-[15px]">
                                            <a href="{{ route('report.detection_result', $report) }}" class="text-[11.5px] font-semibold leading-[17px] text-[#236B51] hover:underline">
                                                BUG-{{ $report->id }}
                                            </a>
                                        </td>
                                        <td class="px-[15px]">
                                            <p class="max-w-[240px] truncate text-[11.5px] font-semibold leading-4 text-[#26322C]">{{ $report->title }}</p>
                                            <p class="pt-[3px] text-[9.5px] leading-[14px] text-[#929B96]">Updated {{ $report->updated_at->diffForHumans() }}</p>
                                        </td>
                                        <td class="px-[15px] text-[11.5px] leading-[17px] text-[#536059]">
                                            {{ $report->user?->name ?? 'Unknown' }}
                                        </td>
                                        <td class="px-[15px]">
                                            <span class="inline-block rounded-[5px] border-[0.8px] border-[#E0E6E3] bg-[#FAFBFA] px-2 py-[3px] text-[10px] leading-[15px] text-[#53615A]">
                                                {{ $report->module ?? 'General' }}
                                            </span>
                                        </td>
                                        <td class="px-[15px] text-[11.5px] leading-[17px] text-[#536059]">
                                            {{ $report->created_at->format('d M') }}
                                        </td>
                                        <td class="px-[15px]">
                                            <span class="inline-flex items-center gap-[5px] rounded-full border-[0.8px] px-[7px] py-1 text-[9.5px] font-semibold leading-[14px] {{ $pillBg }}">
                                                <span class="size-[5px] rounded-full {{ $pillDot }}"></span>
                                                <span>{{ $pillLabel }}</span>
                                            </span>
                                        </td>
                                        <td class="px-[15px]">
                                            @if ($topPercent !== null)
                                                <div class="flex min-w-[100px] items-center gap-[9px]">
                                                    <div class="h-[6px] w-[88px] overflow-hidden rounded-full bg-[#E9EEEB]">
                                                        <div class="h-full rounded-full {{ $topPercent >= 65 ? 'bg-[#2C795A]' : 'bg-[#6B8CA0]' }}" style="width: {{ $topPercent }}%"></div>
                                                    </div>
                                                    <span class="w-7 text-[10px] font-bold leading-[15px] text-[#34413B]">{{ $topPercent }}%</span>
                                                </div>
                                            @elseif ($isAnalyzing)
                                                <span class="text-[10px] leading-[15px] text-[#99A29E]">Analyzing</span>
                                            @else
                                                <span class="text-[10px] leading-[15px] text-[#99A29E]">—</span>
                                            @endif
                                        </td>
                                        <td class="px-[15px] text-right">
                                            <a href="{{ route('report.detection_result', $report) }}" class="inline-flex items-center gap-1 text-[10.5px] font-semibold leading-4 text-[#236B51] hover:underline">
                                                <span>Review</span>
                                                <span aria-hidden="true">›</span>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Footer --}}
                    <div class="flex flex-wrap items-center justify-between gap-4 border-t-[0.8px] border-[#E2E8E5] px-[18px] py-[14px]">
                        <p class="text-[10px] leading-[15px] text-[#67736D]">
                            Showing {{ $recentReports->firstItem() ?? 0 }}–{{ $recentReports->lastItem() ?? 0 }} of {{ $recentReports->total() }} reports
                        </p>
                        @if ($recentReports->hasPages())
                            <div class="flex items-center gap-1">
                                {{ $recentReports->links() }}
                            </div>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>

</x-layouts.app>
