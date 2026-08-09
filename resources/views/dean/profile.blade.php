@extends('layouts.dean')

@section('title', 'Profile')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5 text-center">
                <form method="POST" action="{{ route('dean.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="relative mx-auto mb-3 h-20 w-20">
                        @if (auth()->user()->avatarUrl())
                            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="h-20 w-20 rounded-full object-cover">
                        @else
                            <div class="flex h-20 w-20 items-center justify-center rounded-full bg-success/10 text-2xl font-bold text-success">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        @endif
                        <label for="avatar" class="absolute bottom-0 right-0 flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-navy text-white ring-2 ring-white hover:bg-navy/90">
                            <x-heroicon-o-camera class="h-4 w-4" />
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                    </div>
                </form>
                @error('avatar')
                    <p class="mb-2 text-xs text-danger">{{ $message }}</p>
                @enderror
                <p class="font-bold text-navy">{{ auth()->user()->name }}</p>
                <span class="mt-2 inline-block rounded-full bg-gold/10 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-gold">Dean</span>
            </div>

            <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
                <h3 class="text-xs font-bold uppercase tracking-wide text-black/40 mb-3">School Authority Details</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-identification class="h-5 w-5 shrink-0 text-black/40" />
                        <div>
                            <p class="text-black/40 text-xs">Department</p>
                            <p class="font-medium text-black">School of Computing</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-globe-alt class="h-5 w-5 shrink-0 text-black/40" />
                        <div>
                            <p class="text-black/40 text-xs">Institutional Domain</p>
                            <p class="font-medium text-black">normi.edu</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-black/40" />
                        <div>
                            <p class="text-black/40 text-xs">Access Authority</p>
                            <p class="font-medium text-black">Authorized Session User</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
            <h2 class="text-sm font-semibold text-navy mb-4">Account Details</h2>

            @if (session('status'))
                <div class="mb-4 rounded-lg bg-success/5 px-4 py-3 text-sm text-success">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('dean.profile.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wide text-black/60">Institutional Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ auth()->user()->email }}"
                        disabled
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black/40 cursor-not-allowed"
                    >
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wide text-black/60">Phone Number</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', auth()->user()->phone) }}"
                        placeholder="09XX XXX XXXX"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 justify-center rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gold/90"
                >
                    <x-heroicon-o-check class="h-4 w-4" />
                    Save Changes
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
            <div class="flex items-center gap-3 mb-2">
                <x-heroicon-o-lock-closed class="h-5 w-5 text-black/40" />
                <h2 class="text-sm font-semibold text-navy">Security Credentials</h2>
            </div>
            <p class="text-xs text-black/60 mb-4">Change your dashboard password. Your credentials are encrypted before being stored in our database.</p>

            <form method="POST" action="{{ route('dean.profile.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wide text-black/60">Verify Current Password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('current_password')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold uppercase tracking-wide text-black/60">New Password</label>
                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('new_password')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wide text-black/60">Confirm New Password</label>
                    <input
                        type="password"
                        id="new_password_confirmation"
                        name="new_password_confirmation"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                </div>

                <p class="text-xs text-black/40">Passwords are hashed before storage and are never stored in plain text.</p>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 justify-center rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gold/90"
                >
                    <x-heroicon-o-check class="h-4 w-4" />
                    Update Password
                </button>
            </form>
        </div>
    </div>
@endsection
