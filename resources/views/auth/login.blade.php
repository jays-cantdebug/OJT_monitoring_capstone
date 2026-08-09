@extends('layouts.guest')

@section('title', 'Log In')

@section('header')
    <div class="mb-8 text-center">
        <div class="mx-auto mb-4 h-24 w-24 overflow-hidden rounded-full">
            <img src="{{ asset('images/normi-ojt-logo.jfif') }}" alt="NORMI logo" class="h-full w-full object-cover">
        </div>

        <p class="text-xs font-bold uppercase tracking-wide text-black/60">Northern Mindanao Colleges, Inc.</p>
        <h1 class="mt-2 text-2xl font-bold text-navy leading-snug">
            Web-based On-the-Job Monitoring and Reporting System with GPS
        </h1>

        <div class="mt-4 inline-flex items-center gap-1.5 rounded-full bg-light-gray px-3 py-1 text-xs font-semibold uppercase tracking-wide text-black/60">
            <x-heroicon-o-shield-check class="h-3.5 w-3.5" />
            Secure Login
        </div>

        <hr class="mt-6 border-light-gray">
    </div>
@endsection

@section('content')
    <form
        method="POST"
        action="{{ route('login') }}"
        class="space-y-5"
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        @csrf

        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wide text-black/60">Email Address</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                    <x-heroicon-o-envelope class="h-5 w-5" />
                </span>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="block w-full rounded-md border-0 bg-light-gray py-2.5 pl-10 pr-3 text-sm text-black placeholder:text-black/40 focus:ring-2 focus:ring-navy/40"
                    placeholder="dean@normi.edu.ph or intern@normi.edu.ph"
                >
            </div>
            @error('email')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <label for="password" class="block text-xs font-bold uppercase tracking-wide text-black/60">Password</label>
                <a href="#" class="text-xs font-medium text-navy hover:underline">Forgot Password?</a>
            </div>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                    <x-heroicon-o-lock-closed class="h-5 w-5" />
                </span>
                <input
                    :type="show ? 'text' : 'password'"
                    id="password"
                    name="password"
                    required
                    class="block w-full rounded-md border-0 bg-light-gray py-2.5 pl-10 pr-10 text-sm text-black placeholder:text-black/40 focus:ring-2 focus:ring-navy/40"
                    placeholder="••••••••"
                >
                <button
                    type="button"
                    @click="show = !show"
                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-black/40 hover:text-black/60"
                    :aria-label="show ? 'Hide password' : 'Show password'"
                >
                    <x-heroicon-o-eye x-show="!show" class="h-5 w-5" />
                    <x-heroicon-o-eye-slash x-show="show" x-cloak class="h-5 w-5" />
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input
                type="checkbox"
                id="remember"
                name="remember"
                class="rounded border-light-gray text-navy shadow-sm focus:ring-navy/40"
            >
            <label for="remember" class="ml-2 text-sm text-black/60">Remember Me</label>
        </div>

        <div>
            <label for="role" class="block text-xs font-bold uppercase tracking-wide text-black/60">Role</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                    <x-heroicon-o-user-circle class="h-5 w-5" />
                </span>
                <select
                    id="role"
                    name="role"
                    class="block w-full appearance-none rounded-md border-0 bg-light-gray py-2.5 pl-10 pr-10 text-sm text-black focus:ring-2 focus:ring-navy/40"
                >
                    <option>Dean</option>
                    <option>Coordinator</option>
                    <option>Intern</option>
                </select>
                <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-black/40">
                    <x-heroicon-o-chevron-down class="h-4 w-4" />
                </span>
            </div>
            <p class="mt-1.5 text-xs italic text-black/40">
                Temporary role selection for frontend demonstration only. Automatic role detection will be implemented after backend integration.
            </p>
        </div>

        <button
            type="submit"
            :disabled="submitting"
            class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-gold/90 focus:outline-none focus:ring-2 focus:ring-navy focus:ring-offset-2 disabled:opacity-75"
        >
            <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z"></path>
            </svg>
            <span x-show="!submitting">Sign In</span>
            <span x-show="submitting" x-cloak>Authenticating...</span>
        </button>

        <p class="text-center text-xs text-black/40">
            Your dashboard will be automatically assigned based on your registered account.
        </p>
    </form>
@endsection
