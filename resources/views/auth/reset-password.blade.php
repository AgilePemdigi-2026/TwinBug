<x-layouts.auth title="Atur ulang kata sandi">
    <div class="w-full max-w-[460px]">
        <div class="mb-5 flex items-center justify-center gap-2.5">
            <span class="grid size-10 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                <x-auth.icon name="bug" class="size-5" />
            </span>
            <span class="font-serif text-2xl font-semibold tracking-tight text-ink">TwinBug</span>
        </div>

        <div class="rounded-[24px] border border-white/70 bg-card p-7 shadow-card sm:p-9">
            <header class="mb-7 text-center">
                <span class="mx-auto grid size-14 place-items-center rounded-full bg-sage-100 text-sage-600">
                    <x-auth.icon name="shield" class="size-6" />
                </span>
                <h1 class="mt-5 font-serif text-[26px] font-semibold leading-tight text-ink">Atur kata sandi baru</h1>
                <p class="mt-2 text-sm leading-6 text-body">Buat kata sandi baru dengan minimal 8 karakter untuk mengamankan akun Anda.</p>
            </header>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <x-auth.field
                    name="email"
                    label="Alamat email"
                    type="email"
                    icon="mail"
                    autocomplete="email"
                    :value="$email"
                />

                <x-auth.field
                    name="password"
                    label="Kata sandi baru"
                    icon="lock"
                    toggle
                    strength
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                />

                <x-auth.field
                    name="password_confirmation"
                    label="Konfirmasi kata sandi baru"
                    icon="shield"
                    toggle
                    autocomplete="new-password"
                    placeholder="Ulangi kata sandi baru"
                />

                <div data-strength class="space-y-2">
                    <div class="flex gap-1.5">
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                    </div>
                    <div class="flex items-center justify-between gap-3 text-[11px]">
                        <span class="text-body/70">Minimal 8 karakter.</span>
                        <span data-strength-label class="font-semibold text-body/70">Belum diisi</span>
                    </div>
                </div>

                <button type="submit" class="w-full rounded-xl bg-sage-500 px-4 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">
                    Simpan kata sandi baru
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-body">
                <a href="{{ route('login') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Kembali ke halaman masuk</a>
            </p>
        </div>
    </div>
</x-layouts.auth>
