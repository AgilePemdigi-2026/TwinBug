<x-layouts.app title="Create Bug Report" meta-description="Submit a new bug report to TwinBug for automatic multimodal duplicate analysis.">

    <div class="mx-auto max-w-[1540px] p-8">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-[11.5px] leading-[17px]" aria-label="Breadcrumb">
            <a href="{{ route('dashboard') }}" class="text-[#83908A] transition hover:text-[#33423B]">Dashboard</a>
            <span class="text-[#83908A]" aria-hidden="true">›</span>
            <span class="font-semibold text-[#33423B]">New Bug Report</span>
        </nav>

        {{-- Page Heading --}}
        <div class="pt-[18px]">
            <h1 class="text-[27px] font-bold leading-8 tracking-[-0.65px] text-[#142019]">Create New Bug Report</h1>
            <p class="mt-[7px] text-[13px] leading-5 text-[#67736D]">
                Give us clear, reproducible details. TwinBug will analyze your report for possible duplicates before review.
            </p>
        </div>

        {{-- Form + Sticky Sidebar --}}
        <form
            id="bug-report-form"
            method="POST"
            action="{{ route('bug-reports.store') }}"
            enctype="multipart/form-data"
            novalidate
            class="mt-[26px]"
        >
            @csrf

            {{-- Auto-captured client device metadata --}}
            <input type="hidden" name="device_metadata[browser]" id="meta_browser" value="">
            <input type="hidden" name="device_metadata[os]" id="meta_os" value="">
            <input type="hidden" name="device_metadata[screen]" id="meta_screen" value="">
            <input type="hidden" name="device_metadata[user_agent]" id="meta_user_agent" value="">

            <div class="grid grid-cols-1 items-start gap-5 xl:grid-cols-[minmax(0,780px)_270px]">

                {{-- Left Column: 3 Sections + Actions --}}
                <div class="space-y-4">

                    {{-- Section 1: Bug information --}}
                    <section class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[22px]">
                        <div class="flex items-start gap-3 border-b-[0.8px] border-[#E9EEEB] pb-[18px]">
                            <span class="grid size-[26px] shrink-0 place-items-center rounded-[7px] bg-[#123C2E] text-[11px] font-bold leading-4 text-white">1</span>
                            <div>
                                <h2 class="text-[14px] font-normal leading-[21px] text-[#09090B]">Bug information</h2>
                                <p class="pt-1 text-[10.5px] leading-4 text-[#67736D]">Start with a concise summary and where the issue happened.</p>
                            </div>
                        </div>

                        <div class="space-y-[17px] pt-[18px]">
                            {{-- Bug title --}}
                            <div class="flex flex-col gap-[6px]">
                                <label for="title" class="text-[11.5px] font-semibold leading-[17px] text-[#303D36]">
                                    Bug title <span class="font-black text-[#A84942]">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    value="{{ old('title') }}"
                                    placeholder="e.g. Checkout button becomes unresponsive"
                                    maxlength="255"
                                    autocomplete="off"
                                    aria-describedby="title-hint title-error"
                                    class="h-[41px] w-full rounded-[7px] border-[0.8px] bg-white px-[11px] text-[11.5px] text-[#24322B] placeholder:text-[#A4ADA8] focus:border-[#236B51] focus:outline-none {{ $errors->has('title') ? 'border-danger' : 'border-[#DBE2DE]' }}"
                                >
                                <p id="title-hint" class="text-[9.5px] leading-[13px] text-[#7B8781]">Summarize the issue in one clear sentence.</p>
                                @error('title')
                                    <p id="title-error" class="text-[10.5px] font-medium text-danger" role="alert">{{ $message }}</p>
                                @enderror

                                {{-- Real-time Duplicate Warning Box --}}
                                <div id="duplicate-warning-box" class="mt-2 hidden rounded-[8px] border-[0.8px] border-[#F2E4BD] bg-[#FBF4E3] p-3">
                                    <div class="flex items-start gap-2.5">
                                        <span class="grid size-6 shrink-0 place-items-center rounded-[6px] bg-[#F2E4BD] text-[#8B6418]">
                                            <x-auth.icon name="sparkles" class="size-3.5" />
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-[11px] font-bold text-[#8B6418]">
                                                TwinBug detected possible similar reports in your workspace:
                                            </h3>
                                            <ul id="duplicate-warning-list" class="mt-2 space-y-1.5 text-[11px]"></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Module / Component + Severity --}}
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="flex flex-col gap-[6px]">
                                    <label for="module" class="text-[11.5px] font-semibold leading-[17px] text-[#303D36]">
                                        Module / Component <span class="font-black text-[#A84942]">*</span>
                                    </label>
                                    <select
                                        id="module"
                                        name="module"
                                        aria-describedby="module-error"
                                        class="h-[41px] w-full rounded-[7px] border-[0.8px] bg-white px-[11px] text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none {{ $errors->has('module') ? 'border-danger' : 'border-[#DBE2DE]' }}"
                                    >
                                        <option value="" disabled {{ old('module') ? '' : 'selected' }}>Select a module</option>
                                        @foreach ($modules as $module)
                                            <option value="{{ $module }}" {{ old('module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                                        @endforeach
                                    </select>
                                    <p class="text-[9.5px] leading-[13px] text-[#7B8781]">Select the product area where you found the issue.</p>
                                    @error('module')
                                        <p id="module-error" class="text-[10.5px] font-medium text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex flex-col gap-[6px]">
                                    <label for="severity" class="text-[11.5px] font-semibold leading-[17px] text-[#303D36]">
                                        Severity <span class="font-black text-[#A84942]">*</span>
                                    </label>
                                    <select
                                        id="severity"
                                        name="severity"
                                        aria-describedby="severity-error"
                                        class="h-[41px] w-full rounded-[7px] border-[0.8px] bg-white px-[11px] text-[11.5px] text-[#24322B] focus:border-[#236B51] focus:outline-none {{ $errors->has('severity') ? 'border-danger' : 'border-[#DBE2DE]' }}"
                                    >
                                        @foreach ($severities as $severity)
                                            <option value="{{ $severity->value }}" {{ old('severity', 'medium') === $severity->value ? 'selected' : '' }}>
                                                {{ $severity->label() }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-[9.5px] leading-[13px] text-[#7B8781]">Indicate the impact of this issue.</p>
                                    @error('severity')
                                        <p id="severity-error" class="text-[10.5px] font-medium text-danger" role="alert">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Bug description --}}
                            <div class="flex flex-col gap-[6px]">
                                <label for="description" class="text-[11.5px] font-semibold leading-[17px] text-[#303D36]">
                                    Bug description <span class="font-black text-[#A84942]">*</span>
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    maxlength="10000"
                                    placeholder="Describe the issue you encountered..."
                                    aria-describedby="description-hint description-error"
                                    class="min-h-[90px] w-full rounded-[7px] border-[0.8px] bg-white px-[11px] py-[10px] text-[11.5px] leading-[18px] text-[#24322B] placeholder:text-[#A4ADA8] focus:border-[#236B51] focus:outline-none {{ $errors->has('description') ? 'border-danger' : 'border-[#DBE2DE]' }}"
                                >{{ old('description') }}</textarea>
                                <p id="description-hint" class="text-[9.5px] leading-[13px] text-[#7B8781]">Explain what went wrong and include any useful context.</p>
                                @error('description')
                                    <p id="description-error" class="text-[10.5px] font-medium text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- Section 2: Reproduction details --}}
                    <section class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[22px]">
                        <div class="flex items-start gap-3 border-b-[0.8px] border-[#E9EEEB] pb-[18px]">
                            <span class="grid size-[26px] shrink-0 place-items-center rounded-[7px] bg-[#123C2E] text-[11px] font-bold leading-4 text-white">2</span>
                            <div>
                                <h2 class="text-[14px] font-normal leading-[21px] text-[#09090B]">Reproduction details</h2>
                                <p class="pt-1 text-[10.5px] leading-4 text-[#67736D]">Help the team reproduce and validate the issue.</p>
                            </div>
                        </div>

                        <div class="space-y-[17px] pt-[18px]">
                            <div class="flex flex-col gap-[6px]">
                                <label for="steps_to_reproduce" class="text-[11.5px] font-semibold leading-[17px] text-[#303D36]">
                                    Steps to reproduce
                                </label>
                                <textarea
                                    id="steps_to_reproduce"
                                    name="steps_to_reproduce"
                                    rows="4"
                                    placeholder="1. Open the checkout page&#10;2. Add an item to the cart&#10;3. Click &quot;Continue to payment&quot;"
                                    class="min-h-[100px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] py-[10px] text-[11.5px] leading-[18px] text-[#24322B] placeholder:text-[#A4ADA8] focus:border-[#236B51] focus:outline-none"
                                >{{ old('steps_to_reproduce') }}</textarea>
                                <p class="text-[9.5px] leading-[13px] text-[#7B8781]">Use a numbered list and include the exact actions you took.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-[15px] sm:grid-cols-2">
                                <div class="flex flex-col gap-[6px]">
                                    <label for="actual_result" class="text-[11.5px] font-semibold leading-[17px] text-[#303D36]">
                                        Actual result
                                    </label>
                                    <textarea
                                        id="actual_result"
                                        name="actual_result"
                                        rows="3"
                                        placeholder="Describe the behavior you observed..."
                                        class="min-h-[90px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] py-[10px] text-[11.5px] leading-[18px] text-[#24322B] placeholder:text-[#A4ADA8] focus:border-[#236B51] focus:outline-none"
                                    >{{ old('actual_result') }}</textarea>
                                    <p class="text-[9.5px] leading-[13px] text-[#7B8781]">What happened instead?</p>
                                </div>

                                <div class="flex flex-col gap-[6px]">
                                    <label for="expected_result" class="text-[11.5px] font-semibold leading-[17px] text-[#303D36]">
                                        Expected result
                                    </label>
                                    <textarea
                                        id="expected_result"
                                        name="expected_result"
                                        rows="3"
                                        placeholder="Describe the intended behavior..."
                                        class="min-h-[90px] w-full rounded-[7px] border-[0.8px] border-[#DBE2DE] bg-white px-[11px] py-[10px] text-[11.5px] leading-[18px] text-[#24322B] placeholder:text-[#A4ADA8] focus:border-[#236B51] focus:outline-none"
                                    >{{ old('expected_result') }}</textarea>
                                    <p class="text-[9.5px] leading-[13px] text-[#7B8781]">What should have happened?</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Section 3: Visual evidence --}}
                    <section class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[22px]">
                        <div class="flex items-start gap-3 border-b-[0.8px] border-[#E9EEEB] pb-[18px]">
                            <span class="grid size-[26px] shrink-0 place-items-center rounded-[7px] bg-[#123C2E] text-[11px] font-bold leading-4 text-white">3</span>
                            <div>
                                <h2 class="text-[14px] font-normal leading-[21px] text-[#09090B]">Visual evidence</h2>
                                <p class="pt-1 text-[10.5px] leading-4 text-[#67736D]">Optional, but helpful for multimodal duplicate detection.</p>
                            </div>
                        </div>

                        <div class="pt-[18px]">
                            <div
                                id="screenshot-dropzone"
                                class="flex flex-col items-center rounded-[10px] border-[0.8px] border-dashed border-[#C8D5CF] bg-[#FAFCFB] p-[26px] text-center transition hover:border-[#236B51]"
                            >
                                <span class="grid size-[42px] place-items-center rounded-[10px] bg-[#EAF4EF] text-[#236B51]">
                                    <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                </span>

                                <p class="mt-[10px] text-[12px] font-bold leading-[18px] text-[#09090B]">Drag and drop a screenshot here</p>
                                <p class="mt-1 mb-[13px] text-[10.5px] leading-4 text-[#67736D]">or choose a file from your computer</p>

                                <input type="file" id="screenshot" name="screenshot" accept="image/png,image/jpeg,image/gif,image/webp" class="hidden">
                                <button
                                    type="button"
                                    onclick="document.getElementById('screenshot').click()"
                                    class="inline-flex h-10 cursor-pointer items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]"
                                >
                                    Browse files
                                </button>

                                <p id="screenshot-filename" class="mt-[11px] text-[9px] leading-[14px] text-[#88928D]">
                                    PNG or JPG · Maximum 5 MB · Optional
                                </p>

                                @error('screenshot')
                                    <p class="mt-2 text-[10.5px] font-medium text-danger" role="alert">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </section>

                    {{-- Action Buttons --}}
                    <div class="flex flex-wrap items-center gap-[9px] pt-1 pb-7">
                        <a
                            href="{{ route('dashboard') }}"
                            id="btn-cancel"
                            class="inline-flex h-10 items-center justify-center rounded-[8px] border-[0.8px] border-[#DCE3DF] bg-white px-[15px] text-[12px] font-semibold leading-[18px] text-[#35433C] transition hover:bg-[#F7F9F8]"
                        >
                            Cancel
                        </a>
                        <button
                            type="submit"
                            id="btn-submit"
                            class="inline-flex h-10 cursor-pointer items-center justify-center gap-[7px] rounded-[8px] bg-[#123C2E] px-[15px] text-[12px] font-semibold leading-[18px] text-white shadow-[0_3px_8px_rgba(18,60,46,0.16)] transition hover:bg-[#194E3C]"
                        >
                            <svg class="size-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.42" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="m22 2-7 20-4-9-9-4Z" />
                                <path d="M22 2 11 13" />
                            </svg>
                            <span>Submit &amp; analyze duplicates</span>
                        </button>
                    </div>
                </div>

                {{-- Right Column: Sticky Guide Sidebar --}}
                <aside class="rounded-[11px] border-[0.8px] border-[#E2E8E5] bg-white p-[19px] xl:sticky xl:top-24">
                    <div class="grid size-[38px] place-items-center rounded-[9px] bg-[#E8F3EE] text-[#236B51]">
                        <svg class="size-[22px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.83" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                            <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                            <path d="M12 18v-6" />
                            <path d="m9 15 3 3 3-3" />
                        </svg>
                    </div>

                    <h2 class="pt-[14px] text-[13px] font-normal leading-5 text-[#09090B]">Write a useful report</h2>
                    <p class="pt-[5px] text-[10.5px] leading-4 text-[#67736D]">
                        Clear reports help TwinBug find stronger matches and help developers resolve issues faster.
                    </p>

                    <ul class="mt-[14px] space-y-[10px] border-y-[0.8px] border-[#E2E8E5] py-[14px] text-[10.5px] leading-4 text-[#526059]">
                        <li class="flex items-center gap-2">
                            <x-auth.icon name="check" class="size-[15px] shrink-0 text-[#2D795B]" />
                            <span>Use a specific, descriptive title</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-auth.icon name="check" class="size-[15px] shrink-0 text-[#2D795B]" />
                            <span>List exact reproduction steps</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-auth.icon name="check" class="size-[15px] shrink-0 text-[#2D795B]" />
                            <span>Separate actual and expected results</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <x-auth.icon name="check" class="size-[15px] shrink-0 text-[#2D795B]" />
                            <span>Hide sensitive data in screenshots</span>
                        </li>
                    </ul>

                    <div class="flex items-start gap-2 pt-[14px]">
                        <x-auth.icon name="shield" class="mt-0.5 size-[17px] shrink-0 text-[#236B51]" />
                        <div>
                            <p class="text-[9.5px] font-bold leading-[14px] text-[#435149]">Your report stays private</p>
                            <p class="pt-[2px] text-[8.5px] leading-3 text-[#67736D]">Only authorized team members can access it.</p>
                        </div>
                    </div>
                </aside>

            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Capture Client Metadata
            try {
                const ua = navigator.userAgent;
                let browser = 'Unknown';
                let os = 'Unknown';

                if (ua.indexOf('Chrome') > -1) browser = 'Chrome';
                else if (ua.indexOf('Safari') > -1) browser = 'Safari';
                else if (ua.indexOf('Firefox') > -1) browser = 'Firefox';
                else if (ua.indexOf('Edge') > -1) browser = 'Edge';

                if (ua.indexOf('Win') > -1) os = 'Windows';
                else if (ua.indexOf('Mac') > -1) os = 'macOS';
                else if (ua.indexOf('Linux') > -1) os = 'Linux';
                else if (ua.indexOf('Android') > -1) os = 'Android';
                else if (ua.indexOf('like Mac') > -1) os = 'iOS';

                const screenDim = window.screen ? `${window.screen.width}×${window.screen.height}` : '';

                const metaBrowser = document.getElementById('meta_browser');
                const metaOs = document.getElementById('meta_os');
                const metaScreen = document.getElementById('meta_screen');
                const metaUa = document.getElementById('meta_user_agent');

                if (metaBrowser) metaBrowser.value = browser;
                if (metaOs) metaOs.value = os;
                if (metaScreen) metaScreen.value = screenDim;
                if (metaUa) metaUa.value = ua;
            } catch (e) {}

            // 2. Screenshot file selection & drag-and-drop
            const fileInput = document.getElementById('screenshot');
            const dropzone = document.getElementById('screenshot-dropzone');
            const filenameLabel = document.getElementById('screenshot-filename');

            if (fileInput && filenameLabel) {
                fileInput.addEventListener('change', function () {
                    if (this.files && this.files[0]) {
                        filenameLabel.textContent = `Selected: ${this.files[0].name}`;
                    }
                });
            }

            if (dropzone && fileInput) {
                dropzone.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    dropzone.classList.add('border-[#236B51]', 'bg-[#F2F8F5]');
                });
                dropzone.addEventListener('dragleave', function () {
                    dropzone.classList.remove('border-[#236B51]', 'bg-[#F2F8F5]');
                });
                dropzone.addEventListener('drop', function (e) {
                    e.preventDefault();
                    dropzone.classList.remove('border-[#236B51]', 'bg-[#F2F8F5]');
                    if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]) {
                        fileInput.files = e.dataTransfer.files;
                        fileInput.dispatchEvent(new Event('change'));
                    }
                });
            }

            // 3. Real-time Duplicate Detection
            const titleInput = document.getElementById('title');
            const moduleSelect = document.getElementById('module');
            const warningBox = document.getElementById('duplicate-warning-box');
            const warningList = document.getElementById('duplicate-warning-list');
            let debounceTimer = null;

            function checkDuplicates() {
                const titleVal = titleInput ? titleInput.value.trim() : '';
                const moduleVal = moduleSelect ? moduleSelect.value : '';

                if (titleVal.length < 4) {
                    if (warningBox) warningBox.classList.add('hidden');
                    return;
                }

                const url = new URL('{{ route('bug-reports.check-similar') }}', window.location.origin);
                url.searchParams.set('title', titleVal);
                if (moduleVal) url.searchParams.set('module', moduleVal);

                fetch(url.toString(), {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.similar || data.similar.length === 0) {
                        if (warningBox) warningBox.classList.add('hidden');
                        return;
                    }

                    warningList.innerHTML = '';
                    data.similar.forEach(item => {
                        const li = document.createElement('li');
                        li.className = 'flex items-center justify-between gap-2 rounded-[6px] bg-white px-2.5 py-1.5';
                        li.innerHTML = `
                            <div class="flex items-center gap-2 truncate">
                                <span class="font-mono font-bold text-[#236B51]">BUG-${item.id}</span>
                                <span class="truncate font-semibold text-[#26322C]">${item.title}</span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <span class="rounded px-1.5 py-0.5 text-[10px] font-semibold ${item.status_classes}">${item.status_label}</span>
                                <a href="${item.url}" target="_blank" class="text-[11px] font-semibold text-[#236B51] hover:underline">View &rarr;</a>
                            </div>
                        `;
                        warningList.appendChild(li);
                    });

                    if (warningBox) warningBox.classList.remove('hidden');
                })
                .catch(() => {});
            }

            if (titleInput) {
                titleInput.addEventListener('input', function () {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(checkDuplicates, 450);
                });
            }

            if (moduleSelect) {
                moduleSelect.addEventListener('change', checkDuplicates);
            }
        });
    </script>

</x-layouts.app>
