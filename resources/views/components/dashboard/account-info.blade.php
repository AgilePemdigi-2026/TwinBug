@props(['user'])

<div class="rounded-2xl border border-divider/80 bg-white p-5 shadow-xs">
    <div class="mb-3 flex items-center gap-2">
        <x-auth.icon name="user" class="size-4 text-sage-600" />
        <h2 class="font-serif text-sm font-bold text-ink">Informasi Akun Anda</h2>
    </div>

    <dl class="grid grid-cols-1 gap-3 sm:grid-cols-3 text-sm">
        <div class="rounded-xl border border-divider/70 bg-input/60 p-3">
            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Nama Lengkap</dt>
            <dd class="mt-0.5 text-xs font-semibold text-ink">{{ $user->name }}</dd>
        </div>
        <div class="rounded-xl border border-divider/70 bg-input/60 p-3">
            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Email</dt>
            <dd class="mt-0.5 break-all text-xs font-semibold text-ink">{{ $user->email }}</dd>
        </div>
        <div class="rounded-xl border border-divider/70 bg-input/60 p-3">
            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Peran Sistem</dt>
            <dd class="mt-0.5 text-xs font-semibold text-ink">{{ $user->role->label() }}</dd>
        </div>
    </dl>
</div>
