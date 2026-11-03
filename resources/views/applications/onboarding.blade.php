<x-layouts.app title="Hubungkan Aplikasi" meta-description="Hubungkan akun TwinBug Anda ke aplikasi reporter atau developer.">

    <div class="mx-auto flex min-h-[70vh] max-w-screen-xl items-center justify-center px-6 py-12">
        <div class="w-full max-w-xl rounded-3xl border border-white/70 bg-card p-8 shadow-card">

            <div class="mb-6 text-center">
                <span class="mx-auto grid size-12 place-items-center rounded-2xl bg-sage-500 text-white shadow-cta">
                    <x-auth.icon :name="$user->role === \App\Enums\UserRole::Developer ? 'code' : 'key'" class="size-5" />
                </span>
                <p class="mt-4 text-xs font-semibold uppercase tracking-widest text-ochre">Satu langkah lagi</p>
                <h1 class="mt-2 font-serif text-2xl font-semibold text-ink">
                    {{ $user->role === \App\Enums\UserRole::Developer ? 'Buat aplikasi Anda' : 'Gabung ke aplikasi' }}
                </h1>
                <p class="mt-2 text-sm leading-6 text-body">
                    @if ($user->role === \App\Enums\UserRole::Developer)
                        Buat aplikasi agar laporan bug dari reporter masuk ke antrean Anda. Kode undangan dibuat otomatis.
                    @else
                        Masukkan nama aplikasi atau kode undangan yang diberikan developer.
                    @endif
                </p>
            </div>

            <form method="POST" action="{{ route('applications.store') }}" class="space-y-5">
                @csrf

                @if ($user->role === \App\Enums\UserRole::Developer)
                    <x-auth.field
                        name="application_name"
                        label="Nama aplikasi"
                        icon="code"
                        placeholder="Contoh: Toko Online Kampus"
                        hint="Harus unik"
                    />
                @else
                    <x-auth.field
                        name="application_join"
                        label="Nama atau kode aplikasi"
                        icon="key"
                        placeholder="Contoh: TwinBug atau KODE1234"
                        hint="Dari developer"
                    />
                @endif

                <button type="submit" class="w-full rounded-xl bg-sage-500 px-4 py-3 text-sm font-bold text-white shadow-cta transition hover:bg-sage-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-sage-500/40 focus-visible:ring-offset-2">
                    {{ $user->role === \App\Enums\UserRole::Developer ? 'Buat aplikasi' : 'Gabung aplikasi' }}
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
