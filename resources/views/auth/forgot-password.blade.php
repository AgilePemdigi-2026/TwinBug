<x-layouts.auth title="Lupa kata sandi">
    <div class="w-full max-w-[460px]">
        <div class="mb-5 flex items-center justify-center gap-2.5">
            <span class="grid size-10 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                <x-auth.icon name="bug" class="size-5" />
            </span>
            <span class="font-serif text-2xl font-semibold tracking-tight text-ink">TwinBug</span>
        </div>

        <div class="rounded-[24px] border border-white/70 bg-card p-7 shadow-card sm:p-9">
            @if (session('status'))
                <div class="text-center">
                    <span class="mx-auto grid size-14 place-items-center rounded-full bg-sage-100 text-sage-600">
                        <x-auth.icon name="mail" class="size-6" />
                    </span>
                    <h1 class="mt-5 font-serif text-[26px] font-semibold leading-tight text-ink">Periksa email Anda</h1>
                    <p class="mt-3 text-sm leading-6 text-body">
                        Jika <span class="font-semibold text-label">{{ old('email') }}</span> terdaftar, kami telah mengirim tautan untuk mengatur ulang kata sandi Anda.
                    </p>
                    <p class="mt-3 text-xs leading-5 text-body/70">Tidak menemukan email? Periksa folder spam atau masuk ke kotak masuk pengembangan.</p>

                    <a href="{{ route('login') }}" class="mt-6 inline-flex items-center gap-2 rounded-xl bg-sage-500 px-5 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600">
                        <x-auth.icon name="arrow-left" />
                        Kembali ke halaman masuk
                    </a>
                </div>
            @else
                <header class="mb-7 text-center">
                    <span class="mx-auto grid size-14 place-items-center rounded-full bg-sage-100 text-sage-600">
                        <x-auth.icon name="key" class="size-6" />
                    </span>
                    <h1 class="mt-5 font-serif text-[26px] font-semibold leading-tight text-ink">Lupa kata sandi?</h1>
                    <p class="mt-2 text-sm leading-6 text-body">Masukkan alamat email akun Anda. Kami akan mengirim tautan untuk membuat kata sandi baru.</p>
                </header>

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <x-auth.field
                        name="email"
                        label="Alamat email"
                        type="email"
                        icon="mail"
                        autocomplete="email"
                        placeholder="nama@perusahaan.com"
                    />

                    <button type="submit" class="w-full rounded-xl bg-sage-500 px-4 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">
                        Kirim tautan pemulihan
                    </button>
                </form>

                <p class="mt-6 text-center text-sm text-body">
                    Ingat kata sandi Anda?
                    <a href="{{ route('login') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Kembali masuk</a>
                </p>
            @endif
        </div>
    </div>
</x-layouts.auth>
