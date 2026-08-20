@extends('layouts.guest')

@section('title', 'Sign Up')

@section('header')
    <div class="mb-5 text-center">
        <div class="mx-auto mb-3 h-16 w-16 overflow-hidden rounded-full">
            <img src="{{ asset('images/normi-ojt-logo.jfif') }}" alt="NORMI logo" class="h-full w-full object-cover">
        </div>

        <p class="text-xs font-bold uppercase tracking-wide text-black/60">Northern Mindanao Colleges, Inc.</p>
        <h1 class="mt-2 text-xl font-bold text-navy leading-snug">
            Student Intern Sign Up
        </h1>
        <p class="mt-2 text-sm text-black/60">Your account will need Dean approval before you can log in.</p>

        <hr class="mt-4 border-light-gray">
    </div>
@endsection

@section('content')
    <form
        method="POST"
        action="{{ route('register') }}"
        class="space-y-3"
        x-data="{ submitting: false }"
        @submit="submitting = true"
    >
        @csrf

        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wide text-black/60">Full Name</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                    <x-heroicon-o-user class="h-5 w-5" />
                </span>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="block w-full rounded-md border-0 bg-light-gray py-2.5 pl-10 pr-3 text-sm text-black placeholder:text-black/40 focus:ring-2 focus:ring-navy/40"
                    placeholder="Juan Dela Cruz"
                >
            </div>
            @error('name')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

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
                    class="block w-full rounded-md border-0 bg-light-gray py-2.5 pl-10 pr-3 text-sm text-black placeholder:text-black/40 focus:ring-2 focus:ring-navy/40"
                    placeholder="intern@normi.edu.ph"
                >
            </div>
            @error('email')
                <p class="mt-1 text-sm text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div x-data="{ show: false }">
            <label for="password" class="block text-xs font-bold uppercase tracking-wide text-black/60">Password</label>
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

        <div>
            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wide text-black/60">Confirm Password</label>
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                    <x-heroicon-o-lock-closed class="h-5 w-5" />
                </span>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    class="block w-full rounded-md border-0 bg-light-gray py-2.5 pl-10 pr-3 text-sm text-black placeholder:text-black/40 focus:ring-2 focus:ring-navy/40"
                    placeholder="••••••••"
                >
            </div>
        </div>

        <button
            type="submit"
            :disabled="submitting"
            class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-gold px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-gold/90 focus:outline-none focus:ring-2 focus:ring-navy focus:ring-offset-2 disabled:opacity-75"
        >
            <svg x-show="submitting" x-cloak class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0 1 8-8V0C5.373 0 0 5.373 0 12h4Z"></path>
            </svg>
            <span x-show="!submitting">Sign Up</span>
            <span x-show="submitting" x-cloak>Creating Account...</span>
        </button>

        <p class="text-center text-xs text-black/40">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-navy hover:underline">Sign In</a>
        </p>
    </form>
@endsection
