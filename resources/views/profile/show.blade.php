@php
    $user = auth()->user();

    $roleLabel = match ($user->role) {
        \App\Enums\UserRole::Reporter => 'Reporter',
        \App\Enums\UserRole::Developer => 'Developer',
        \App\Enums\UserRole::Admin => 'Administrator',
    };

    $nameParts = explode(' ', trim($user->name));
    $initials = mb_strtoupper(
        count($nameParts) >= 2
            ? mb_substr($nameParts[0], 0, 1) . mb_substr($nameParts[1], 0, 1)
            : mb_substr($nameParts[0], 0, 2)
    );
@endphp

<x-layouts.app title="My Profile" meta-description="Manage your personal information and account details on TwinBug.">

    <div class="workspace-profile-page mx-auto max-w-[1540px] px-8 pt-8 pb-[54px] space-y-[18px]">

        {{-- ======================== BREADCRUMB ======================== --}}
        <nav class="flex items-center gap-2 text-[11.5px] leading-[17px]" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="text-[#83908A] transition hover:text-[#33423B]">Dashboard</a>
            <span class="text-[#83908A]">›</span>
            <span class="font-semibold text-[#33423B]">My Profile</span>
        </nav>

        {{-- ======================== PAGE HEADING ======================== --}}
        <div>
            <h1 class="text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">My Profile</h1>
            <p class="pt-[7px] text-[13px] leading-5 text-[#67736D]">
                Manage your personal information and account details.
            </p>
        </div>

        {{-- ======================== HERO BANNER (#0D2D22) ======================== --}}
        <section class="mt-[26px] flex flex-col gap-6 rounded-[12px] bg-[#0D2D22] p-[23px] text-white lg:flex-row lg:items-center lg:justify-between">
            <div class="flex flex-col gap-[15px] sm:flex-row sm:items-center">
                <div class="grid size-[76px] shrink-0 place-items-center rounded-full border-[0.8px] border-white/[0.14] bg-white/[0.09] text-[22px] font-bold text-[#A9C2B6]">
                    {{ $initials }}
                </div>
                <div>
                    <p class="text-[7.5px] font-bold uppercase leading-[11px] tracking-[0.675px] text-[#88B39E]">Account holder</p>
                    <h2 class="pt-[5px] text-[18px] font-normal leading-[27px] text-white">{{ $user->name }}</h2>
                    <p class="pt-[3px] text-[9.5px] leading-[14px] text-[#A6BBB1]">{{ $user->email }}</p>
                    <div class="mt-[9px] flex flex-wrap items-center gap-[7px]">
                        <span class="inline-flex items-center gap-[5px] rounded-full bg-[#E7F3ED] px-[7px] py-1 text-[8px] leading-3 text-[#236B4E]">
                            <x-auth.icon name="user" class="size-[13px]" />
                            <span>{{ $roleLabel }}</span>
                        </span>
                        <span class="inline-flex items-center gap-[5px] rounded-full bg-white/[0.08] px-[7px] py-1 text-[8px] leading-3 text-[#AEBFB7]">
                            <span class="size-[5px] rounded-full {{ $user->email_verified_at ? 'bg-[#6ED4A0]' : 'bg-[#9BAAA3]' }}"></span>
                            <span>{{ $user->email_verified_at ? 'Verified account' : 'Unverified' }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-[30px]">
                <div>
                    <p class="text-[8px] leading-3 text-[#87A397]">Member since</p>
                    <p class="pt-1 text-[10px] font-semibold leading-[15px] text-[#DBE7E1]">{{ $user->created_at->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-[8px] leading-3 text-[#87A397]">Work domain</p>
                    <p class="pt-1 text-[10px] font-semibold leading-[15px] text-[#DBE7E1]">{{ $user->application?->name ?? 'Not provided' }}</p>
                </div>
                <a href="#personal-info-form"
                    class="inline-flex h-10 items-center justify-center gap-[7px] rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#194E3C]">
                    <x-auth.icon name="user" class="size-4" />
                    <span>Edit profile</span>
                </a>
            </div>
        </section>

        {{-- ======================== 2-COLUMN MAIN GRID ======================== --}}
        <div class="grid grid-cols-1 gap-[18px] lg:grid-cols-12">

            {{-- Left Column (8 cols): Personal Information Form --}}
            <section id="personal-info-form" class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white lg:col-span-8">
                <div class="flex items-center justify-between border-b-[0.8px] border-[#E2E8E5] px-[18px] py-4">
                    <div>
                        <h2 class="text-[13px] font-normal leading-5 text-[#09090B]">Personal information</h2>
                        <p class="pt-1 text-[9.5px] leading-[14px] text-[#67736D]">Update your display name and review your workspace details.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="p-[18px] space-y-4">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[10px] font-semibold leading-[15px] text-[#35423B]">Full name</span>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Enter your full name"
                                required
                                class="h-[41px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] text-[11.5px] text-[#24322B] placeholder:text-[#A4ADA8] focus:border-[#236B51] focus:outline-none">
                            @error('name')
                                <span class="text-[10px] text-danger">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[10px] font-semibold leading-[15px] text-[#35423B]">Email address</span>
                            <input
                                type="email"
                                value="{{ $user->email }}"
                                disabled
                                class="h-[41px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-[#FAFBFA] px-[11px] text-[11.5px] text-[#67736D]">
                            <span class="text-[8px] leading-3 text-[#8A958F]">Email address cannot be changed directly.</span>
                        </label>

                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[10px] font-semibold leading-[15px] text-[#35423B]">Role</span>
                            <input
                                type="text"
                                value="{{ $roleLabel }}"
                                disabled
                                class="h-[41px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-[#FAFBFA] px-[11px] text-[11.5px] text-[#67736D]">
                        </label>

                        <label class="flex flex-col gap-[6px]">
                            <span class="text-[10px] font-semibold leading-[15px] text-[#35423B]">Work domain</span>
                            <input
                                type="text"
                                value="{{ $user->application?->name ?? 'Not assigned' }}"
                                disabled
                                class="h-[41px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-[#FAFBFA] px-[11px] text-[11.5px] text-[#67736D]">
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="inline-flex h-10 cursor-pointer items-center justify-center rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#0D2D22]">
                            Save changes
                        </button>
                    </div>
                </form>
            </section>

            {{-- Right Column (4 cols): Overview Card --}}
            <section class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white lg:col-span-4">
                <div class="border-b-[0.8px] border-[#E2E8E5] px-[18px] py-4">
                    <h2 class="text-[13px] font-normal leading-5 text-[#09090B]">
                        @if ($stats['type'] === 'reporter') Statistik Laporan Saya
                        @elseif ($stats['type'] === 'developer') Statistik Bug Sistem
                        @else Statistik Platform
                        @endif
                    </h2>
                    <p class="pt-1 text-[9.5px] leading-[14px] text-[#67736D]">Live summary from your TwinBug workspace.</p>
                </div>

                <div class="grid grid-cols-2 gap-2 p-[18px]">
                    @if ($stats['type'] === 'reporter')
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Total reports</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['total']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Open</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['open']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">In progress</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['in_progress']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Resolved</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['resolved']) }}</p>
                        </div>
                    @elseif ($stats['type'] === 'developer')
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Total system bugs</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['total_system']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Open</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['open']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">In progress</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['in_progress']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Resolved</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['resolved']) }}</p>
                        </div>
                    @else
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Total users</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['total_users']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Total reports</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['total_bugs']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Open bugs</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['open_bugs']) }}</p>
                        </div>
                        <div class="rounded-[8px] bg-[#F7F9F8] p-[11px]">
                            <p class="text-[8px] leading-[11px] text-[#67736D]">Resolved bugs</p>
                            <p class="pt-[5px] text-[17px] font-bold leading-[26px] text-[#09090B]">{{ number_format($stats['resolved_bugs']) }}</p>
                        </div>
                    @endif
                </div>

                <div class="flex flex-col items-center border-t-[0.8px] border-[#E2E8E5] px-[15px] py-[25px] text-center">
                    <x-auth.icon name="sparkles" class="size-[23px] text-[#829089]" />
                    <p class="pt-2 text-[11px] leading-4 text-[#46544D]">Recent triage activity</p>
                    <p class="pt-1 text-[8.5px] leading-[13px] text-[#829089]">Joined {{ $user->created_at->diffForHumans() }}.</p>
                </div>
            </section>
        </div>

        {{-- ======================== ACCOUNT SECURITY ======================== --}}
        <section class="overflow-hidden rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white">
            <div class="flex flex-wrap items-center justify-between gap-4 border-b-[0.8px] border-[#E2E8E5] px-[18px] py-4">
                <div>
                    <h2 class="text-[13px] font-normal leading-5 text-[#09090B]">Account security</h2>
                    <p class="pt-1 text-[9.5px] leading-[14px] text-[#67736D]">Use a strong password that is unique to TwinBug.</p>
                </div>
                <button type="button"
                    onclick="document.getElementById('password-form-panel').classList.toggle('hidden')"
                    class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]">
                    <x-auth.icon name="lock" class="size-4" />
                    <span>Change password</span>
                </button>
            </div>

            <form id="password-form-panel" method="POST" action="{{ route('profile.password') }}" class="{{ $errors->hasAny(['current_password', 'password']) ? '' : 'hidden' }} p-[18px] space-y-4">
                @csrf
                @method('PATCH')
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[10px] font-semibold leading-[15px] text-[#35423B]">Current password</span>
                        <input type="password" name="current_password" required
                            class="h-[41px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                        @error('current_password')
                            <span class="text-[10px] text-danger">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[10px] font-semibold leading-[15px] text-[#35423B]">New password</span>
                        <input type="password" name="password" required
                            class="h-[41px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                        @error('password')
                            <span class="text-[10px] text-danger">{{ $message }}</span>
                        @enderror
                    </label>
                    <label class="flex flex-col gap-[6px]">
                        <span class="text-[10px] font-semibold leading-[15px] text-[#35423B]">Confirm new password</span>
                        <input type="password" name="password_confirmation" required
                            class="h-[41px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none">
                    </label>
                </div>
                <button type="submit"
                    class="inline-flex h-10 cursor-pointer items-center justify-center rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white transition hover:bg-[#0D2D22]">
                    Update password
                </button>
            </form>
        </section>

    </div>

</x-layouts.app>
