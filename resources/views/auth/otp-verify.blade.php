<x-layouts.auth title="Verifikasi OTP">
    <div class="w-full max-w-[720px]">
        <div class="mb-5 flex items-center justify-center gap-2.5">
            <span class="grid size-10 place-items-center rounded-xl bg-sage-500 text-white shadow-cta">
                <x-auth.icon name="bug" class="size-5" />
            </span>
            <span class="font-serif text-2xl font-semibold tracking-tight text-ink">TwinBug</span>
            <span class="rounded-full border border-sage-200 bg-sage-50 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-sage-600">Beta</span>
        </div>

        <div class="rounded-[24px] border border-white/70 bg-card p-7 shadow-card sm:p-9">
            <header class="mb-7 text-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-ochre">Verifikasi Akun</p>
                <h1 class="mt-2 font-serif text-[28px] font-semibold leading-tight text-ink">Masukkan Kode OTP</h1>
                <p class="mt-2 text-sm leading-6 text-body">Masukkan kode verifikasi yang dikirim ke email Anda</p>
            </header>

            @if ($message = Session('error'))
                <div class="rounded-xl bg-red-100 border-red-400 text-red-700 px-4 py-3 mb-4" role="alert">
                    {{ $message }}
                </div>
            @endif

            @if ($message = Session('status'))
                <div class="rounded-xl bg-green-100 border-green-400 text-green-700 px-4 py-3 mb-4" role="alert">
                    {{ $message }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-5">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-auth.field
                        name="otp_code"
                        label="Kode OTP"
                        icon="lock"
                        autocomplete="one-time-code"
                        placeholder="123456"
                    />
                </div>

                <div>
                    <button type="submit" class="w-full rounded-xl bg-sage-500 px-4 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">
                        Verifikasi
                    </button>
                </div>

                <p class="mt-6 text-center text-sm text-body">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Daftar</a>
                </p>
            </form>
        </div>
    </div>
</x-layouts.auth>