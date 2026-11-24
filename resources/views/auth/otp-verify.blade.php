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
                <div class="rounded-xl border border-danger/30 bg-rose-50/70 p-3.5 text-xs text-danger mb-4" role="alert">
                    {{ $message }}
                </div>
            @endif

            @if ($message = Session('status'))
                <div class="rounded-xl border border-success/30 bg-emerald-50/70 p-3.5 text-xs text-success mb-4" role="alert">
                    {{ $message }}
                </div>
            @endif

            <form method="POST" action="{{ route('otp.verify.submit') }}" class="space-y-5">
                @csrf

                <div>
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
            </form>

            {{-- OTP Resend --}}
            <div class="pt-4">
                <form method="POST" action="{{ route('otp.resend') }}" id="otp-resend-form">
                    @csrf

                    <button
                        type="submit"
                        id="otp-resend-button"
                        @if ($otpResendRemaining > 0) disabled @endif
                        class="w-full rounded-xl border border-divider bg-white px-4 py-3 text-sm font-semibold text-sage-600 shadow-xs transition @if ($otpResendRemaining > 0) bg-sage-50 text-sage-400 cursor-not-allowed @else hover:bg-sage-50 @endif focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2"
                    >
                        <span id="otp-resend-label">
                            @if ($otpResendRemaining > 0)
                                Kirim ulang dalam {{ $otpResendRemaining }} detik
                            @else
                                Kirim ulang kode
                            @endif
                        </span>
                    </button>
                </form>

                <p id="otp-resend-countdown" class="mt-2 text-xs text-label/60 text-center" aria-live="polite">
                    @if ($otpResendRemaining > 0)
                        Kode OTP baru dapat dikirim dalam {{ $otpResendRemaining }} detik.
                    @endif
                </p>
            </div>

            <p class="mt-6 text-center text-sm text-body">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Daftar</a>
            </p>
        </div>
    </div>

    @if ($otpResendRemaining > 0)
    <script>
        (function () {
            var remaining = {{ $otpResendRemaining }};
            var labelEl = document.getElementById('otp-resend-label');
            var countdownEl = document.getElementById('otp-resend-countdown');
            var btn = document.getElementById('otp-resend-button');

            var timer = setInterval(function () {
                remaining -= 1;

                if (labelEl) {
                    labelEl.textContent = 'Kirim ulang dalam ' + remaining + ' detik';
                }

                if (countdownEl) {
                    countdownEl.textContent = 'Kode OTP baru dapat dikirim dalam ' + remaining + ' detik.';
                }

                if (remaining <= 0) {
                    clearInterval(timer);
                    if (labelEl) {
                        labelEl.textContent = 'Kirim ulang kode';
                    }
                    if (countdownEl) {
                        countdownEl.textContent = '';
                    }
                    if (btn) {
                        btn.disabled = false;
                        btn.className = 'w-full rounded-xl border border-divider bg-white px-4 py-3 text-sm font-semibold text-sage-600 shadow-xs transition hover:bg-sage-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2';
                    }
                }
            }, 1000);
        })();
    </script>
    @endif
</x-layouts.auth>