@props(['user'])

<div class="rounded-3xl bg-white p-8 shadow-sm">
    <div class="mb-4 flex items-center gap-2">
        <x-auth.icon name="user" class="size-4 text-sage-600" />
        <h2 class="font-serif text-lg font-bold text-ink">Informasi Akun Anda</h2>
    </div>

    <dl class="grid grid-cols-1 gap-6 sm:grid-cols-3 text-sm">
        <div class="rounded-2xl bg-input p-4">
            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Nama Lengkap</dt>
            <dd class="mt-1 font-semibold text-ink">{{ $user->name }}</dd>
        </div>
        <div class="rounded-2xl bg-input p-4">
            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Email</dt>
            <dd class="mt-1 break-all font-semibold text-ink">{{ $user->email }}</dd>
        </div>
        <div class="rounded-2xl bg-input p-4">
            <dt class="text-[10px] font-bold uppercase tracking-wider text-label">Peran Sistem</dt>
            <dd class="mt-1 font-semibold text-ink">{{ $user->role->label() }}</dd>
        </div>
    </dl>
</div>
