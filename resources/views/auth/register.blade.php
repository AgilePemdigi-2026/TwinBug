<x-layouts.auth title="Daftar">
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
                <p class="text-xs font-semibold uppercase tracking-widest text-ochre">Bergabung dengan komunitas</p>
                <h1 class="mt-2 font-serif text-[28px] font-semibold leading-tight text-ink">Buat akun TwinBug</h1>
                <p class="mt-2 text-sm leading-6 text-body">Pilih peran Anda dan mulai berkontribusi pada perangkat lunak yang lebih baik.</p>
            </header>

            <form method="POST" action="{{ route('register.store') }}" class="space-y-5">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-auth.field
                        name="name"
                        label="Nama lengkap"
                        icon="user"
                        autocomplete="name"
                        placeholder="Nama Anda"
                    />

                    <x-auth.field
                        name="email"
                        label="Alamat email"
                        type="email"
                        icon="mail"
                        autocomplete="email"
                        placeholder="nama@perusahaan.com"
                    />
                </div>

                <fieldset class="space-y-2">
                    <legend class="text-sm font-semibold text-label">Pilih peran</legend>
                    <div class="grid gap-2 sm:grid-cols-2">
                        @foreach ([
                            ['value' => 'reporter', 'title' => 'Reporter', 'icon' => 'bug', 'description' => 'Melaporkan bug dengan detail dan langkah reproduksi.'],
                            ['value' => 'developer', 'title' => 'Developer', 'icon' => 'code', 'description' => 'Menelusuri dan memperbaiki laporan bug.'],
                        ] as $role)
                            <label class="group relative cursor-pointer rounded-xl border border-transparent bg-input p-4 transition hover:bg-input-hover has-[:checked]:border-sage-500/40 has-[:checked]:bg-sage-50 focus-within:ring-2 focus-within:ring-sage-500/20">
                                <input type="radio" name="role" value="{{ $role['value'] }}" class="sr-only" @checked(old('role', 'reporter') === $role['value'])>
                                <span class="flex items-start gap-3">
                                    <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-card text-sage-600 shadow-sm">
                                        <x-auth.icon :name="$role['icon']" />
                                    </span>
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-bold text-ink">{{ $role['title'] }}</span>
                                        <span class="mt-0.5 block text-xs leading-5 text-body">{{ $role['description'] }}</span>
                                    </span>
                                    <span class="mt-0.5 grid size-5 shrink-0 place-items-center rounded-full border border-divider text-transparent transition group-has-[:checked]:border-sage-500 group-has-[:checked]:bg-sage-500 group-has-[:checked]:text-white">
                                        <x-auth.icon name="check" class="size-3" />
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('role')
                        <p class="text-xs font-medium text-danger">{{ $message }}</p>
                    @enderror
                </fieldset>

                <div class="grid gap-5 sm:grid-cols-2">
                    <x-auth.field
                        name="password"
                        label="Kata sandi"
                        icon="lock"
                        toggle
                        strength
                        autocomplete="new-password"
                        placeholder="Minimal 8 karakter"
                    />

                    <x-auth.field
                        name="password_confirmation"
                        label="Konfirmasi kata sandi"
                        icon="shield"
                        toggle
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi"
                    />
                </div>

                <div data-strength class="space-y-2">
                    <div class="flex gap-1.5">
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                        <span data-strength-bar class="h-1.5 flex-1 rounded-full bg-divider transition-colors"></span>
                    </div>
                    <div class="flex items-center justify-between gap-3 text-[11px]">
                        <span class="text-body/70">Gunakan minimal 8 karakter.</span>
                        <span data-strength-label class="font-semibold text-body/70">Belum diisi</span>
                    </div>
                </div>

                <div>
                    <label class="flex items-start gap-2.5 text-xs leading-5 text-body">
                        <input type="checkbox" name="terms" value="1" @checked(old('terms')) class="mt-0.5 size-4 shrink-0 rounded border-divider accent-sage-500" required>
                        <span>Saya menyetujui <span class="font-semibold text-sage-600">Syarat &amp; Ketentuan</span> serta <span class="font-semibold text-sage-600">Kebijakan Privasi</span> TwinBug.</span>
                    </label>
                    @error('terms')
                        <p class="mt-1 text-xs font-medium text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full rounded-xl bg-sage-500 px-4 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">
                    Buat akun
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-body">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-sage-600 transition hover:text-sage-700 hover:underline">Masuk di sini</a>
            </p>
        </div>
    </div>
</x-layouts.auth>
