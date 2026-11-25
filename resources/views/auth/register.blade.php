<x-layouts.auth title="Create account">
    <div class="auth-card w-full max-w-[720px]">
        <header class="mb-7 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-sage-600">Create your TwinBug workspace</p>
            <h1 class="mt-2 text-3xl font-semibold leading-tight text-ink">Create your account</h1>
            <p class="mt-2 text-sm leading-6 text-body">Choose your role and start organizing bug reports.</p>
        </header>

        <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
            @csrf

            <div class="grid gap-5 sm:grid-cols-2">
                <x-auth.field name="name" label="Full name" icon="user" autocomplete="name" placeholder="Your full name" />
                <x-auth.field name="email" label="Email address" type="email" icon="mail" autocomplete="email" placeholder="name@company.com" />
            </div>

            <div data-application-field="reporter" @class(['hidden' => old('role', 'reporter') !== 'reporter'])>
                <x-auth.field name="application_join" label="Work domain or invite code" icon="key" placeholder="e.g. Commerce Platform or INVITE1234" hint="Provided by your developer" :required="false" />
            </div>
            <div data-application-field="developer" @class(['hidden' => old('role', 'reporter') !== 'developer'])>
                <x-auth.field name="application_name" label="Work domain" icon="code" placeholder="e.g. Commerce Platform" hint="Your product or team workspace" :required="false" />
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
                <x-auth.field name="password" label="Password" icon="lock" toggle strength autocomplete="new-password" placeholder="Minimum 8 characters" />
                <x-auth.field name="password_confirmation" label="Confirm password" icon="shield" toggle autocomplete="new-password" placeholder="Repeat password" />
            </div>

            <div data-strength class="space-y-2">
                <div class="flex gap-1.5">
                    <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                    <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                    <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                    <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                </div>
                <div class="flex items-center justify-between gap-3 text-[11px]"><span class="text-body/70">Use 8+ characters with a number and symbol.</span><span data-strength-label class="font-semibold text-body/70">Not entered</span></div>
            </div>

            <fieldset class="space-y-2">
                <legend class="text-sm font-semibold text-ink">Choose your role <span class="ml-2 text-xs font-normal text-label">Your role determines workspace access.</span></legend>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach ([
                        ['value' => 'reporter', 'title' => 'Reporter', 'icon' => 'user', 'description' => 'Submit and monitor bug reports.'],
                        ['value' => 'developer', 'title' => 'Developer', 'icon' => 'code', 'description' => 'Review detections and triage reports.'],
                    ] as $role)
                        <label class="group relative cursor-pointer rounded-xl border border-divider bg-white p-4 transition hover:border-sage-300 has-[:checked]:border-sage-500 has-[:checked]:bg-sage-50 focus-within:ring-2 focus-within:ring-sage-500/20">
                            <input type="radio" name="role" value="{{ $role['value'] }}" class="sr-only" @checked(old('role', 'reporter') === $role['value'])>
                            <span class="flex items-center gap-3">
                                <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-sage-50 text-sage-600"><x-auth.icon :name="$role['icon']" class="size-5" /></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-bold text-ink">{{ $role['title'] }}</span>
                                    <span class="mt-1 block text-xs leading-5 text-body">{{ $role['description'] }}</span>
                                </span>
                                <span class="grid size-5 shrink-0 place-items-center rounded-full border border-divider text-transparent transition group-has-[:checked]:border-sage-600 group-has-[:checked]:bg-sage-600 group-has-[:checked]:text-white"><x-auth.icon name="check" class="size-3" /></span>
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('role')<p class="text-xs font-medium text-danger">{{ $message }}</p>@enderror
            </fieldset>

            <div>
                <label class="flex items-start gap-2.5 text-xs leading-5 text-body">
                    <input type="checkbox" name="terms" value="1" @checked(old('terms')) class="mt-0.5 size-4 shrink-0 rounded border-divider accent-sage-500" required>
                    <span>I agree to the <span class="font-semibold text-sage-600">Terms of Service</span> and <span class="font-semibold text-sage-600">Privacy Policy</span>.</span>
                </label>
                @error('terms')<p class="mt-1 text-xs font-medium text-danger">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="w-full rounded-xl bg-[#123c2e] px-4 py-4 text-base font-semibold text-white shadow-cta transition hover:bg-[#0d2d22] focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">Create new account <span aria-hidden="true">→</span></button>
        </form>

        <p class="mt-6 text-center text-sm text-body">Already have an account? <a href="{{ route('login') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Sign in here</a></p>
    </div>
</x-layouts.auth>
