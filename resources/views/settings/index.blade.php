@php
    $user = $user ?? auth()->user();
    $initialTab = request()->query('tab', 'general');
    $allowedTabs = ['general', 'preferences', 'notifications', 'security', 'appearance'];

    if (! in_array($initialTab, $allowedTabs, true)) {
        $initialTab = 'general';
    }

    if ($errors->has('current_password') || $errors->has('password') || session('status') === 'password-updated') {
        $initialTab = 'security';
    }

    $currentLanguage = old('language', $user?->language ?? 'en');
    $currentTimezone = old('timezone', $user?->timezone ?? 'Asia/Jakarta');
    $currentDateFormat = old('date_format', $user?->date_format ?? 'DD MMM YYYY');
    $currentTheme = old('theme', $user?->theme ?? 'light');

    $tabs = [
        ['id' => 'general', 'label' => __('General'), 'icon' => 'user'],
        ['id' => 'preferences', 'label' => __('Account Preferences'), 'icon' => 'sparkles'],
        ['id' => 'notifications', 'label' => __('Notifications'), 'icon' => 'alert'],
        ['id' => 'security', 'label' => __('Security'), 'icon' => 'shield'],
        ['id' => 'appearance', 'label' => __('Appearance'), 'icon' => 'code'],
    ];

    $notificationItems = [
        [
            'key' => 'submission_confirmation',
            'title' => __('Report submission confirmation'),
            'description' => __('Receive confirmation after a report has been submitted.'),
            'enabled' => (bool) ($notifications['submission_confirmation'] ?? true),
        ],
        [
            'key' => 'detection_completed',
            'title' => __('Detection process completed'),
            'description' => __('Know when TwinBug has completed report analysis.'),
            'enabled' => (bool) ($notifications['detection_completed'] ?? true),
        ],
        [
            'key' => 'duplicate_results',
            'title' => __('Duplicate detection results'),
            'description' => __('Receive updates when similarity candidates are available.'),
            'enabled' => (bool) ($notifications['duplicate_results'] ?? true),
        ],
        [
            'key' => 'status_updates',
            'title' => __('Report status updates'),
            'description' => __('Be notified when a report moves through the workflow.'),
            'enabled' => (bool) ($notifications['status_updates'] ?? false),
        ],
        [
            'key' => 'developer_reviews',
            'title' => __('Developer review notifications'),
            'description' => __('Receive updates about triage assignments and decisions.'),
            'enabled' => (bool) ($notifications['developer_reviews'] ?? true),
        ],
    ];
@endphp

<x-layouts.app :title="__('Manage Settings')" :hideFooter="true">
    <div class="mx-auto w-full max-w-[1180px] space-y-6">
        {{-- Breadcrumb & Header --}}
        <div class="space-y-3">
            <nav class="flex items-center gap-2 text-[12px] text-[#8A9590]" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="transition hover:text-[#111915]">{{ __('Dashboard') }}</a>
                <span>&rsaquo;</span>
                <span class="font-semibold text-[#34423B]">{{ __('Manage Settings') }}</span>
            </nav>

            <div>
                <h1 class="text-[26px] font-bold tracking-[-0.02em] text-[#111915]">{{ __('Manage Settings') }}</h1>
                <p class="mt-1 text-[13.5px] text-[#65706B]">
                    {{ __('Control your account preferences, notifications, security, and appearance.') }}
                </p>
            </div>
        </div>

        @if (session('status') === 'settings-updated')
            <div class="rounded-[8px] border-[0.8px] border-[#BBDAC9] bg-[#EEF7F2] px-4 py-2.5 text-[12.5px] font-medium text-[#19513D]">
                {{ __('Settings saved successfully.') }}
            </div>
        @elseif (session('status') === 'settings-reset')
            <div class="rounded-[8px] border-[0.8px] border-[#BBDAC9] bg-[#EEF7F2] px-4 py-2.5 text-[12.5px] font-medium text-[#19513D]">
                {{ __('Settings have been reset to defaults.') }}
            </div>
        @endif

        {{-- Two-Column Settings Layout --}}
        <div class="flex flex-col gap-5 lg:flex-row lg:items-start" data-settings-root data-initial-tab="{{ $initialTab }}">
            {{-- Left Settings Sub-navigation --}}
            <aside class="w-full shrink-0 rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white p-[7px] lg:w-[220px]">
                <nav class="space-y-1" aria-label="Settings sections">
                    @foreach ($tabs as $tab)
                        @php($isActive = $initialTab === $tab['id'])
                        <button
                            type="button"
                            data-settings-tab="{{ $tab['id'] }}"
                            aria-selected="{{ $isActive ? 'true' : 'false' }}"
                            @class([
                                'flex w-full items-center justify-between rounded-[7px] px-2.5 py-2 text-left text-[12.5px] transition',
                                'bg-[#F2F8F5] font-semibold text-[#236B51]' => $isActive,
                                'font-medium text-[#65706B] hover:bg-[#F7F9F8] hover:text-[#111915]' => ! $isActive,
                            ])
                        >
                            <span class="flex items-center gap-2.5">
                                <x-auth.icon :name="$tab['icon']" class="size-[15px]" />
                                <span>{{ $tab['label'] }}</span>
                            </span>
                            <svg class="size-3.5 opacity-70" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                                <path d="M6 4l4 4-4 4" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    @endforeach
                </nav>
            </aside>

            {{-- Right Content Area --}}
            <div class="min-w-0 flex-1 space-y-5">
                {{-- Tab 1: General --}}
                <section
                    data-settings-panel="general"
                    @class(['rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white', 'hidden' => $initialTab !== 'general'])
                >
                    <div class="border-b-[0.8px] border-[#E2E8E5] px-6 py-4">
                        <h2 class="text-[15px] font-semibold text-[#111915]">{{ __('General settings') }}</h2>
                        <p class="mt-0.5 text-[12px] text-[#65706B]">
                            {{ __('Overview of the account currently connected to this workspace.') }}
                        </p>
                    </div>

                    <div class="divide-y divide-[#E2E8E5] px-6">
                        <div class="grid grid-cols-1 gap-2 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                            <span class="text-[12.5px] text-[#8A9590]">{{ __('Account name') }}</span>
                            <span class="text-[13px] font-medium text-[#111915]">{{ $user?->name ?? __('Not connected') }}</span>
                        </div>
                        <div class="grid grid-cols-1 gap-2 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                            <span class="text-[12.5px] text-[#8A9590]">{{ __('Email address') }}</span>
                            <span class="text-[13px] font-medium text-[#111915]">{{ $user?->email ?? __('Not connected') }}</span>
                        </div>
                        <div class="grid grid-cols-1 gap-2 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                            <span class="text-[12.5px] text-[#8A9590]">{{ __('Username') }}</span>
                            <span class="text-[13px] font-medium text-[#111915]">{{ $user?->username ?: __('Not provided') }}</span>
                        </div>
                        <div class="grid grid-cols-1 gap-2 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                            <span class="text-[12.5px] text-[#8A9590]">{{ __('Account role') }}</span>
                            <div>
                                <span class="inline-flex items-center rounded-full bg-[#EBF5F0] px-2.5 py-0.5 text-[11px] font-semibold text-[#236B51]">
                                    {{ $user?->role->label() ?? 'Reporter' }}
                                </span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 py-3.5 sm:grid-cols-[200px_1fr] sm:items-center">
                            <span class="text-[12.5px] text-[#8A9590]">{{ __('Account status') }}</span>
                            <span class="inline-flex items-center gap-1.5 text-[13px] font-medium text-[#111915]">
                                <span class="size-2 rounded-full bg-[#236B51]"></span>
                                <span>{{ __('Active') }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="px-6 pb-5 pt-2">
                        <div class="flex items-center gap-2.5 rounded-[8px] border-[0.8px] border-[#E2E8E5] bg-[#F7F9F8] px-4 py-3 text-[12px] text-[#65706B]">
                            <x-auth.icon name="user" class="size-4 shrink-0 text-[#236B51]" />
                            <span>
                                {{ __('Account information can be updated from the') }}
                                <a href="{{ route('profile') }}" class="font-semibold text-[#236B51] hover:underline">{{ __('My Profile') }}</a>
                                {{ __('page.') }}
                            </span>
                        </div>
                    </div>
                </section>

                {{-- Preferences / Notifications / Appearance Form --}}
                <form id="settings-preferences-form" method="POST" action="{{ route('settings.update') }}" class="space-y-5">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="active_tab" value="{{ $initialTab }}" data-active-tab-input />
                    <input type="hidden" name="theme" value="{{ $currentTheme }}" data-theme-input />

                    {{-- Tab 2: Account Preferences --}}
                    <section
                        data-settings-panel="preferences"
                        @class(['rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white', 'hidden' => $initialTab !== 'preferences'])
                    >
                        <div class="border-b-[0.8px] border-[#E2E8E5] px-6 py-4">
                            <h2 class="text-[15px] font-semibold text-[#111915]">{{ __('Account preferences') }}</h2>
                            <p class="mt-0.5 text-[12px] text-[#65706B]">
                                {{ __('Choose how dates and regional information should be displayed.') }}
                            </p>
                        </div>

                        <div class="space-y-4 p-6">
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label for="pref-language" class="mb-1.5 block text-[12px] font-medium text-[#34423B]">
                                        {{ __('Language preference') }}
                                    </label>
                                    <select
                                        id="pref-language"
                                        name="language"
                                        class="h-10 w-full rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-3 text-[13px] text-[#111915] outline-none transition focus:border-[#236B51]"
                                    >
                                        <option value="en" @selected($currentLanguage === 'en')>English</option>
                                        <option value="id" @selected($currentLanguage === 'id')>Bahasa Indonesia</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="pref-timezone" class="mb-1.5 block text-[12px] font-medium text-[#34423B]">
                                        {{ __('Time zone') }}
                                    </label>
                                    <select
                                        id="pref-timezone"
                                        name="timezone"
                                        class="h-10 w-full rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-3 text-[13px] text-[#111915] outline-none transition focus:border-[#236B51]"
                                    >
                                        <option value="Asia/Jakarta" @selected($currentTimezone === 'Asia/Jakarta')>Asia/Jakarta</option>
                                        <option value="UTC" @selected($currentTimezone === 'UTC')>UTC</option>
                                        <option value="Asia/Singapore" @selected($currentTimezone === 'Asia/Singapore')>Asia/Singapore</option>
                                        <option value="America/New_York" @selected($currentTimezone === 'America/New_York')>America/New_York</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="pref-date-format" class="mb-1.5 block text-[12px] font-medium text-[#34423B]">
                                        {{ __('Date format') }}
                                    </label>
                                    <select
                                        id="pref-date-format"
                                        name="date_format"
                                        class="h-10 w-full rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-3 text-[13px] text-[#111915] outline-none transition focus:border-[#236B51]"
                                    >
                                        <option value="DD MMM YYYY" @selected($currentDateFormat === 'DD MMM YYYY')>DD MMM YYYY</option>
                                        <option value="YYYY-MM-DD" @selected($currentDateFormat === 'YYYY-MM-DD')>YYYY-MM-DD</option>
                                        <option value="MM/DD/YYYY" @selected($currentDateFormat === 'MM/DD/YYYY')>MM/DD/YYYY</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center gap-2.5 rounded-[8px] border-[0.8px] border-[#E2E8E5] bg-[#F7F9F8] px-4 py-3 text-[12px] text-[#65706B]">
                                <x-auth.icon name="sparkles" class="size-4 shrink-0 text-[#236B51]" />
                                <span>{{ __('Preferences are saved to your account and applied across the workspace.') }}</span>
                            </div>
                        </div>
                    </section>

                    {{-- Tab 3: Notifications --}}
                    <section
                        data-settings-panel="notifications"
                        @class(['rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white', 'hidden' => $initialTab !== 'notifications'])
                    >
                        <div class="border-b-[0.8px] border-[#E2E8E5] px-6 py-4">
                            <h2 class="text-[15px] font-semibold text-[#111915]">{{ __('Notification settings') }}</h2>
                            <p class="mt-0.5 text-[12px] text-[#65706B]">
                                {{ __('Choose which product events should notify you.') }}
                            </p>
                        </div>

                        <div class="divide-y divide-[#E2E8E5] px-6">
                            @foreach ($notificationItems as $item)
                                <div class="flex items-center justify-between gap-4 py-4">
                                    <div>
                                        <p class="text-[13px] font-semibold text-[#111915]">{{ $item['title'] }}</p>
                                        <p class="mt-0.5 text-[12px] text-[#8A9590]">{{ $item['description'] }}</p>
                                    </div>

                                    <input
                                        type="hidden"
                                        name="notifications[{{ $item['key'] }}]"
                                        value="{{ $item['enabled'] ? '1' : '0' }}"
                                        data-pref-toggle-input="{{ $item['key'] }}"
                                    />
                                    <button
                                        type="button"
                                        role="switch"
                                        aria-checked="{{ $item['enabled'] ? 'true' : 'false' }}"
                                        aria-label="{{ $item['title'] }}"
                                        data-pref-toggle="{{ $item['key'] }}"
                                        @class([
                                            'relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center rounded-full transition-colors',
                                            'bg-[#1D5B45]' => $item['enabled'],
                                            'bg-[#DCE3DF]' => ! $item['enabled'],
                                        ])
                                    >
                                        <span
                                            @class([
                                                'inline-block size-4 rounded-full bg-white shadow-sm transition-transform',
                                                'translate-x-6' => $item['enabled'],
                                                'translate-x-1' => ! $item['enabled'],
                                            ])
                                        ></span>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- Tab 5: Appearance --}}
                    <section
                        data-settings-panel="appearance"
                        @class(['rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white', 'hidden' => $initialTab !== 'appearance'])
                    >
                        <div class="border-b-[0.8px] border-[#E2E8E5] px-6 py-4">
                            <h2 class="text-[15px] font-semibold text-[#111915]">{{ __('Appearance') }}</h2>
                            <p class="mt-0.5 text-[12px] text-[#65706B]">
                                {{ __('Select how TwinBug looks across your workspace.') }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 gap-4 p-6 md:grid-cols-3">
                            @foreach ([
                                ['id' => 'light', 'label' => __('Light mode'), 'description' => __('Clean bright surface optimized for daylight.'), 'previewBg' => 'bg-[#F7F9F8]', 'previewCard' => 'bg-white border-[#E2E8E5]'],
                                ['id' => 'dark', 'label' => __('Dark mode'), 'description' => __('Low-glare dark workspace for comfortable viewing.'), 'previewBg' => 'bg-[#111915]', 'previewCard' => 'bg-[#1B2620] border-[#2C3A32]'],
                                ['id' => 'system', 'label' => __('System default'), 'description' => __('Automatically match your OS appearance.'), 'previewBg' => 'bg-gradient-to-r from-[#F7F9F8] to-[#111915]', 'previewCard' => 'bg-white/80 border-[#DCE3DF]'],
                            ] as $theme)
                                @php($isSelected = $currentTheme === $theme['id'])
                                <button
                                    type="button"
                                    data-theme-option="{{ $theme['id'] }}"
                                    aria-pressed="{{ $isSelected ? 'true' : 'false' }}"
                                    @class([
                                        'flex cursor-pointer flex-col rounded-[10px] border p-3.5 text-left transition',
                                        'border-[#236B51] bg-[#F2F8F5]' => $isSelected,
                                        'border-[#E2E8E5] bg-white hover:border-[#BBDAC9]' => ! $isSelected,
                                    ])
                                >
                                    <div class="mb-3 h-20 w-full rounded-[7px] border border-[#E2E8E5] p-2.5 {{ $theme['previewBg'] }}">
                                        <div class="h-full w-full rounded-[5px] border p-2 {{ $theme['previewCard'] }}">
                                            <div class="h-2 w-12 rounded bg-[#236B51]/70"></div>
                                            <div class="mt-1.5 h-1.5 w-20 rounded bg-[#8A9590]/40"></div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[13px] font-semibold text-[#111915]">{{ $theme['label'] }}</span>
                                        <span
                                            data-theme-badge="{{ $theme['id'] }}"
                                            @class([
                                                'rounded-full bg-[#EBF5F0] px-2 py-0.5 text-[10.5px] font-semibold text-[#236B51]',
                                                'hidden' => ! $isSelected,
                                            ])
                                        >
                                            {{ __('Active') }}
                                        </span>
                                    </div>
                                    <p class="mt-1 text-[11.5px] text-[#65706B]">{{ $theme['description'] }}</p>
                                </button>
                            @endforeach
                        </div>
                    </section>
                </form>

                {{-- Tab 4: Security (separate forms for password & logout) --}}
                <section
                    data-settings-panel="security"
                    @class(['rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white', 'hidden' => $initialTab !== 'security'])
                >
                    <div class="border-b-[0.8px] border-[#E2E8E5] px-6 py-4">
                        <h2 class="text-[15px] font-semibold text-[#111915]">{{ __('Security settings') }}</h2>
                        <p class="mt-0.5 text-[12px] text-[#65706B]">
                            {{ __('Update your password and manage active session controls.') }}
                        </p>
                    </div>

                    <div class="divide-y divide-[#E2E8E5] px-6">
                        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4 py-5">
                            @csrf
                            @method('PATCH')

                            @if (session('status') === 'password-updated')
                                <div class="rounded-[8px] border-[0.8px] border-[#BBDAC9] bg-[#EEF7F2] px-4 py-2.5 text-[12.5px] font-medium text-[#19513D]">
                                    {{ __('Password updated successfully.') }}
                                </div>
                            @endif

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <label for="settings_current_password" class="mb-1.5 block text-[12px] font-medium text-[#34423B]">
                                        {{ __('Current password') }}
                                    </label>
                                    <input
                                        id="settings_current_password"
                                        name="current_password"
                                        type="password"
                                        autocomplete="current-password"
                                        placeholder="{{ __('Enter current password') }}"
                                        class="h-10 w-full rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-3 text-[13px] text-[#111915] outline-none transition focus:border-[#236B51]"
                                    />
                                    @error('current_password')
                                        <p class="mt-1 text-[11.5px] text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="settings_password" class="mb-1.5 block text-[12px] font-medium text-[#34423B]">
                                        {{ __('New password') }}
                                    </label>
                                    <input
                                        id="settings_password"
                                        name="password"
                                        type="password"
                                        autocomplete="new-password"
                                        placeholder="{{ __('Enter new password') }}"
                                        class="h-10 w-full rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-3 text-[13px] text-[#111915] outline-none transition focus:border-[#236B51]"
                                    />
                                    @error('password')
                                        <p class="mt-1 text-[11.5px] text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="settings_password_confirmation" class="mb-1.5 block text-[12px] font-medium text-[#34423B]">
                                        {{ __('Confirm new password') }}
                                    </label>
                                    <input
                                        id="settings_password_confirmation"
                                        name="password_confirmation"
                                        type="password"
                                        autocomplete="new-password"
                                        placeholder="{{ __('Confirm new password') }}"
                                        class="h-10 w-full rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-3 text-[13px] text-[#111915] outline-none transition focus:border-[#236B51]"
                                    />
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    type="submit"
                                    class="inline-flex h-9 cursor-pointer items-center gap-2 rounded-[8px] bg-[#123C2E] px-4 text-[12.5px] font-semibold text-white transition hover:bg-[#19513D]"
                                >
                                    <x-auth.icon name="lock" class="size-3.5" />
                                    <span>{{ __('Update password') }}</span>
                                </button>
                            </div>
                        </form>

                        <div class="flex items-center justify-between gap-4 py-4">
                            <div>
                                <p class="text-[13px] font-semibold text-[#111915]">{{ __('Active sessions') }}</p>
                                <p class="mt-0.5 text-[12px] text-[#8A9590]">
                                    {{ __('Your current authenticated browser session is active.') }}
                                </p>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-[#EBF5F0] px-2.5 py-1 text-[11px] font-semibold text-[#236B51]">
                                {{ __('Current session') }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between gap-4 py-4">
                            <div>
                                <p class="text-[13px] font-semibold text-[#111915]">{{ __('Sign out of TwinBug') }}</p>
                                <p class="mt-0.5 text-[12px] text-[#8A9590]">
                                    {{ __('End your current session and return to the login screen.') }}
                                </p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex h-9 cursor-pointer items-center gap-2 rounded-[8px] border-[0.8px] border-[#F3C5C5] bg-[#FEF2F2] px-3.5 text-[12.5px] font-semibold text-[#B42318] transition hover:bg-[#FEE4E2]"
                                >
                                    <x-auth.icon name="logout" class="size-3.5" />
                                    <span>{{ __('Logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </section>

                {{-- Bottom Action Bar --}}
                <div class="flex flex-col gap-3 rounded-[10px] border-[0.8px] border-[#E2E8E5] bg-white px-5 py-4 shadow-[0_8px_24px_rgba(17,25,21,0.06)] sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[12.5px] font-semibold text-[#111915]">
                            {{ __('Workspace preferences') }}
                        </p>
                        <p class="text-[11.5px] text-[#8A9590]">
                            {{ __('Changes are saved to your account.') }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <form method="POST" action="{{ route('settings.reset') }}">
                            @csrf
                            <input type="hidden" name="active_tab" value="{{ $initialTab }}" data-active-tab-input />
                            <button
                                type="submit"
                                class="inline-flex h-9 cursor-pointer items-center gap-1.5 rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-3.5 text-[12.5px] font-semibold text-[#34423B] transition hover:bg-[#F7F9F8]"
                            >
                                <x-auth.icon name="refresh" class="size-3.5" />
                                <span>{{ __('Reset changes') }}</span>
                            </button>
                        </form>

                        <button
                            type="submit"
                            form="settings-preferences-form"
                            class="inline-flex h-9 cursor-pointer items-center gap-1.5 rounded-[8px] bg-[#123C2E] px-4 text-[12.5px] font-semibold text-white transition hover:bg-[#19513D]"
                        >
                            <x-auth.icon name="check" class="size-3.5" />
                            <span>{{ __('Save changes') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const root = document.querySelector('[data-settings-root]');
            if (!root) {
                return;
            }

            const tabButtons = root.querySelectorAll('[data-settings-tab]');
            const panels = root.querySelectorAll('[data-settings-panel]');
            const activeTabInputs = root.querySelectorAll('[data-active-tab-input]');
            const themeInput = root.querySelector('[data-theme-input]');

            const activateTab = (tabId) => {
                tabButtons.forEach((btn) => {
                    const active = btn.dataset.settingsTab === tabId;
                    btn.setAttribute('aria-selected', active ? 'true' : 'false');
                    btn.classList.toggle('bg-[#F2F8F5]', active);
                    btn.classList.toggle('font-semibold', active);
                    btn.classList.toggle('text-[#236B51]', active);
                    btn.classList.toggle('font-medium', !active);
                    btn.classList.toggle('text-[#65706B]', !active);
                });

                panels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.settingsPanel !== tabId);
                });

                activeTabInputs.forEach((input) => {
                    input.value = tabId;
                });
            };

            tabButtons.forEach((btn) => {
                btn.addEventListener('click', () => activateTab(btn.dataset.settingsTab));
            });

            const setToggleState = (btn, enabled) => {
                btn.setAttribute('aria-checked', enabled ? 'true' : 'false');
                btn.classList.toggle('bg-[#1D5B45]', enabled);
                btn.classList.toggle('bg-[#DCE3DF]', !enabled);
                const knob = btn.querySelector('span');
                if (knob) {
                    knob.classList.toggle('translate-x-6', enabled);
                    knob.classList.toggle('translate-x-1', !enabled);
                }
                const hiddenInput = root.querySelector(`[data-pref-toggle-input="${btn.dataset.prefToggle}"]`);
                if (hiddenInput) {
                    hiddenInput.value = enabled ? '1' : '0';
                }
            };

            root.querySelectorAll('[data-pref-toggle]').forEach((toggle) => {
                toggle.addEventListener('click', () => {
                    const next = toggle.getAttribute('aria-checked') !== 'true';
                    setToggleState(toggle, next);
                });
            });

            const applyThemeToDocument = (mode) => {
                document.documentElement.setAttribute('data-theme', mode);
                const isDark = mode === 'dark' || (mode === 'system' && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
                document.documentElement.classList.toggle('dark', isDark);
            };

            const setThemeState = (selectedTheme) => {
                if (themeInput) {
                    themeInput.value = selectedTheme;
                }
                applyThemeToDocument(selectedTheme);
                root.querySelectorAll('[data-theme-option]').forEach((card) => {
                    const active = card.dataset.themeOption === selectedTheme;
                    card.setAttribute('aria-pressed', active ? 'true' : 'false');
                    card.classList.toggle('border-[#236B51]', active);
                    card.classList.toggle('bg-[#F2F8F5]', active);
                    card.classList.toggle('border-[#E2E8E5]', !active);
                    card.classList.toggle('bg-white', !active);
                    const badge = card.querySelector('[data-theme-badge]');
                    if (badge) {
                        badge.classList.toggle('hidden', !active);
                    }
                });
            };

            root.querySelectorAll('[data-theme-option]').forEach((card) => {
                card.addEventListener('click', () => {
                    setThemeState(card.dataset.themeOption);
                });
            });
        });
    </script>
</x-layouts.app>
