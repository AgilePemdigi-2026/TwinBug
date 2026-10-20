<x-layouts.auth title="Akun Terverifikasi">
    <div class="w-full max-w-[720px]">
        <div class="mb-5 flex items-center justify-center gap-2.5">
            <span class="grid size-10 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                <x-auth.icon name="check" class="size-5" />
            </span>
            <span class="font-serif text-2xl font-semibold tracking-tight text-ink">TwinBug</span>
            <span class="rounded-full border border-sage-200 bg-sage-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-sage-600">Beta</span>
        </div>

        <div class="rounded-[24px] border border-white/70 bg-card p-7 shadow-card sm:p-9">
            <header class="mb-7 text-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-ochre">Akun Berhasil Diverifikasi</p>
                <h1 class="mt-2 font-serif text-[28px] font-semibold leading-tight text-ink">Selamat Datang!</h1>
                <p class="mt-2 text-sm leading-6 text-body">Akun Anda telah berhasil diverifikasi melalui OTP. Anda sekarang dapat mengakses halaman utama.</p>
            </header>

            <div class="mt-8 text-center">
                <x-auth.icon name="bug" class="size-6 mb-4 text-sage-500" />

                <h2 class="text-3xl font-serif font-bold text-ink mb-2">Akun Sudah Aktif</h2>

                <p class="text-body mb-6">Akun TwinBug Anda telah diverifikasi dan siap digunakan.</p>

                <div class="w-48 mx-auto rounded-xl bg-sage-500 px-6 py-3 text-sm font-bold text-white shadow-cta">
                    <a href="{{ route('login') }}" class="hover:text-sage-100">
                        Masuk ke Akun
                    </a>
                </div>
            </div>

            <p class="mt-6 text-center text-sm text-body/70">
                Tidak punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</x-layouts.auth>