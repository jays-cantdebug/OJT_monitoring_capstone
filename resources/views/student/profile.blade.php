@extends('layouts.student')

@section('title', 'Profile')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="space-y-4">
            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-5 text-center">
                <div class="relative mx-auto mb-3 h-20 w-20">
                    <div class="flex h-20 w-20 items-center justify-center rounded-full bg-amber-100 text-2xl font-bold text-amber-700">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="absolute bottom-0 right-0 flex h-7 w-7 items-center justify-center rounded-full bg-[#1a2332] text-white ring-2 ring-white">
                        <x-heroicon-o-camera class="h-3.5 w-3.5" />
                    </span>
                </div>
                <p class="font-bold text-gray-900">{{ auth()->user()->name }}</p>
                <span class="mt-2 inline-block rounded-full bg-amber-100 px-3 py-1 text-[11px] font-bold uppercase tracking-wide text-amber-700">Student</span>
            </div>

            <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-5">
                <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-3">School Authority Details</h3>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-identification class="h-5 w-5 shrink-0 text-gray-400" />
                        <div>
                            <p class="text-gray-400 text-xs">Department</p>
                            <p class="font-medium text-gray-900">Computer Science, Year 3</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-globe-alt class="h-5 w-5 shrink-0 text-gray-400" />
                        <div>
                            <p class="text-gray-400 text-xs">Institutional Domain</p>
                            <p class="font-medium text-gray-900">normi.edu</p>
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <x-heroicon-o-check-circle class="h-5 w-5 shrink-0 text-gray-400" />
                        <div>
                            <p class="text-gray-400 text-xs">Access Authority</p>
                            <p class="font-medium text-gray-900">Authorized Session User</p>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Account Details</h2>

            <form class="space-y-4">
                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Institutional Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ auth()->user()->email }}"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>

                <div>
                    <label for="phone" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Phone Number</label>
                    <input
                        type="text"
                        id="phone"
                        name="phone"
                        placeholder="09XX XXX XXXX"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 justify-center rounded-md bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700"
                >
                    <x-heroicon-o-check class="h-4 w-4" />
                    Save Changes
                </button>
            </form>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-5">
            <div class="flex items-center gap-3 mb-2">
                <x-heroicon-o-lock-closed class="h-5 w-5 text-gray-400" />
                <h2 class="text-sm font-semibold text-gray-900">Security Credentials</h2>
            </div>
            <p class="text-xs text-gray-500 mb-4">Change your dashboard password. Your credentials are encrypted before being stored in our database.</p>

            <form class="space-y-4">
                <div>
                    <label for="current_password" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Verify Current Password</label>
                    <input
                        type="password"
                        id="current_password"
                        name="current_password"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>

                <div>
                    <label for="new_password" class="block text-xs font-bold uppercase tracking-wide text-gray-500">New Password</label>
                    <input
                        type="password"
                        id="new_password"
                        name="new_password"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Confirm New Password</label>
                    <input
                        type="password"
                        id="new_password_confirmation"
                        name="new_password_confirmation"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>

                <p class="text-xs text-gray-400">Passwords are hashed before storage and are never stored in plain text.</p>

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 justify-center rounded-md bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700"
                >
                    <x-heroicon-o-check class="h-4 w-4" />
                    Update Password
                </button>
            </form>
        </div>
    </div>
@endsection
