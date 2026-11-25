<x-layouts.auth title="Sign in">
    <div class="auth-card mx-auto w-full max-w-[500px]">
        <header class="mb-8 text-center">
            <p class="text-xs font-semibold uppercase tracking-widest text-sage-600">Welcome back</p>
            <h1 class="mt-2 text-3xl font-semibold leading-tight text-ink">Sign in to TwinBug</h1>
            <p class="mt-2 text-sm leading-6 text-body">Continue to your workspace and manage bug reports.</p>
        </header>

        <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
            @csrf

            <x-auth.field name="email" label="Email address" type="email" icon="mail" autocomplete="email" placeholder="name@company.com" :value="session('email')" />

            <x-auth.field name="password" label="Password" icon="lock" toggle autocomplete="current-password" placeholder="Enter your password">
                <x-slot:labelRight><a href="{{ route('password.request') }}" class="text-xs font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Forgot password?</a></x-slot:labelRight>
            </x-auth.field>

            <label class="flex items-center gap-2.5 text-sm text-body">
                <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="size-4 rounded border-divider accent-sage-500">
                Remember me
            </label>

            <button type="submit" class="w-full rounded-xl bg-[#123c2e] px-4 py-4 text-base font-semibold text-white shadow-cta transition hover:bg-[#0d2d22] focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">Sign in <span aria-hidden="true">→</span></button>
        </form>

        <p class="mt-6 text-center text-sm text-body">New to TwinBug? <a href="{{ route('register') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Create an account</a></p>

        <div class="mt-8 rounded-xl border border-divider bg-sage-50 p-4">
            <div class="flex items-start gap-3">
                <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-white text-sage-600"><x-auth.icon name="alert" class="size-4" /></span>
                <p class="text-xs leading-5 text-body"><span class="font-semibold text-ink">Role-based access.</span> Reporter and Developer workspaces are tailored to each team's responsibilities.</p>
            </div>
        </div>

        <details class="mt-6 rounded-xl border border-divider bg-white p-4">
            <summary class="cursor-pointer text-xs font-semibold text-sage-700">Demo access</summary>
            <div class="mt-3 grid gap-2 sm:grid-cols-2">
                <button type="button" data-demo-email="reporter.demo@gmail.com" data-demo-password="TwinBug@Demo2025!" data-demo-name="Reporter" class="flex items-center justify-center gap-2 rounded-lg border border-divider bg-white px-3 py-2.5 text-xs font-semibold text-label transition hover:border-sage-300 hover:bg-sage-50"><x-auth.icon name="bug" class="size-4 text-sage-600" />Reporter</button>
                <button type="button" data-demo-email="developer.demo@gmail.com" data-demo-password="TwinBug@Demo2025!" data-demo-name="Developer" class="flex items-center justify-center gap-2 rounded-lg border border-divider bg-white px-3 py-2.5 text-xs font-semibold text-label transition hover:border-sage-300 hover:bg-sage-50"><x-auth.icon name="code" class="size-4 text-sage-600" />Developer</button>
            </div>
        </details>

        <p class="mt-5 text-center text-xs text-body/80">Need help? <a href="{{ route('faq') }}" class="font-medium text-sage-600 transition hover:text-sage-700 hover:underline">Visit the FAQ</a></p>
    </div>
</x-layouts.auth>
