@props([
    'title',
    'message' => 'Share these credentials with the intern now — the password will not be shown again.',
    'email',
    'password',
    'passwordLabel' => 'Temporary Password',
])

<div class="mb-4 rounded-xl bg-success/5 ring-1 ring-success/20 p-5">
    <div class="flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-success/10 text-success">
            <x-heroicon-o-check-circle class="h-5 w-5" />
        </div>
        <div>
            <p class="text-sm font-semibold text-success">{{ $title }}</p>
            <p class="mt-1 text-sm text-success">{{ $message }}</p>
        </div>
    </div>
    <div class="mt-4 flex flex-wrap gap-3">
        <div class="rounded-lg bg-white/70 ring-1 ring-success/20 px-4 py-2.5">
            <p class="text-[11px] font-bold uppercase tracking-wide text-success">Email</p>
            <p class="mt-0.5 font-mono text-sm text-success">{{ $email }}</p>
        </div>
        <div class="rounded-lg bg-white/70 ring-1 ring-success/20 px-4 py-2.5">
            <p class="text-[11px] font-bold uppercase tracking-wide text-success">{{ $passwordLabel }}</p>
            <p class="mt-0.5 font-mono text-sm text-success">{{ $password }}</p>
        </div>
    </div>
</div>
