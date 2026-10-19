<x-layouts.auth title="Masuk">
    <div class="w-full max-w-[460px]">
        <div class="mb-5 flex items-center justify-center gap-2.5">
            <span class="grid size-10 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                <x-auth.icon name="bug" class="size-5" />
            </span>
            <span class="font-serif text-2xl font-semibold tracking-tight text-ink">TwinBug</span>
            <span class="rounded-full border border-sage-200 bg-sage-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-sage-600">Beta</span>
        </div>

        <div class="rounded-[24px] border border-white/70 bg-card p-7 shadow-card sm:p-9">
            <header class="mb-7 text-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-ochre">Selamat datang kembali</p>
                <h1 class="mt-2 font-serif text-[28px] font-semibold leading-tight text-ink">Masuk ke TwinBug</h1>
                <p class="mt-2 text-sm leading-6 text-body">Lanjutkan perjalanan Anda membangun perangkat lunak yang lebih baik bersama komunitas.</p>
            </header>

            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                @csrf

                <x-auth.field
                    name="email"
                    label="Alamat email"
                    type="email"
                    icon="mail"
                    autocomplete="email"
                    placeholder="nama@perusahaan.com"
                    :value="session('email')"
                />

                <x-auth.field
                    name="password"
                    label="Kata sandi"
                    icon="lock"
                    toggle
                    autocomplete="current-password"
                    placeholder="Masukkan kata sandi"
                >
                    <x-slot:labelRight>
                        <a href="{{ route('password.request') }}" class="text-xs font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Lupa kata sandi?</a>
                    </x-slot:labelRight>
                </x-auth.field>

                <label class="flex items-center gap-2.5 text-sm text-body">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember')) class="size-4 rounded border-divider accent-sage-500">
                    Ingat saya
                </label>

                <button type="submit" class="w-full rounded-xl bg-sage-500 px-4 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">
                    Masuk
                </button>
            </form>

            <div class="my-6 flex items-center gap-3 text-[10px] font-bold uppercase tracking-widest text-body/50">
                <span class="h-px flex-1 bg-divider"></span>
                Akses demo
                <span class="h-px flex-1 bg-divider"></span>
            </div>

            <div class="grid gap-2 sm:grid-cols-2">
                <button type="button" data-demo-email="reporter.demo@gmail.com" data-demo-password="TwinBug@Demo2025!" data-demo-name="Reporter" class="flex items-center justify-center gap-2 rounded-xl border border-divider bg-card px-3 py-2.5 text-xs font-semibold text-label transition hover:border-sage-300 hover:bg-sage-50">
                    <x-auth.icon name="bug" class="size-4 text-sage-600" />
                    Reporter
                </button>
                <button type="button" data-demo-email="developer.demo@gmail.com" data-demo-password="TwinBug@Demo2025!" data-demo-name="Developer" class="flex items-center justify-center gap-2 rounded-xl border border-divider bg-card px-3 py-2.5 text-xs font-semibold text-label transition hover:border-sage-300 hover:bg-sage-50">
                    <x-auth.icon name="code" class="size-4 text-sage-600" />
                    Developer
                </button>
            </div>

            <p class="mt-6 text-center text-sm text-body">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Daftar sekarang</a>
            </p>
        </div>

        <p class="mt-5 text-center text-[11px] leading-5 text-body/60">Dibuat untuk tim yang peduli kualitas perangkat lunak.</p>
    </div>
</x-layouts.auth>
