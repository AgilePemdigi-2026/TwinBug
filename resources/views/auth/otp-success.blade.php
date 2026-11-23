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
            <div class="mt-8 text-center">
                <div class="mx-auto size-16 rounded-full bg-sage-100 flex items-center justify-center text-sage-600 shadow-sm">
                    <x-auth.icon name="check" class="size-8" aria-hidden="true" />
                </div>

                <h1 class="mt-6 font-serif text-[28px] font-bold leading-tight text-ink">Akun Sudah Aktif</h1>

                <p class="mt-3 text-body leading-6">Akun TwinBug Anda telah diverifikasi dan siap digunakan.</p>

                <a
                    href="{{ route('login') }}"
                    class="mt-8 block w-full rounded-xl bg-sage-500 px-4 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2"
                >
                    Masuk ke Akun
                </a>
            </div>

            <p class="mt-8 text-center text-sm text-body/70">
                Tidak punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Daftar</a>
            </p>
        </div>
    </div>
</x-layouts.auth>