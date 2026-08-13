@extends('layouts.student')

@section('title', 'Time In/Out')

@section('content')
    @if (session('status'))
        <div class="max-w-lg mb-4 rounded-lg bg-success/5 px-4 py-3 text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="max-w-lg" x-data="timeClock()">
        <div x-show="insecureContext" x-cloak class="mb-4 rounded-xl bg-danger/5 ring-1 ring-danger/20 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-danger/10 text-danger">
                    <x-heroicon-o-exclamation-triangle class="h-5 w-5" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-danger">Time In/Out isn't available on this connection.</p>
                    <p class="mt-1 text-sm text-danger">
                        This page needs to load over a secure connection (HTTPS) or from "localhost" for your phone or computer to share location. Please let your administrator know.
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full {{ $openEntry ? 'bg-success/10 text-success' : 'bg-light-gray text-black/40' }}">
                <x-heroicon-o-clock class="h-7 w-7" />
            </div>
            <p class="text-xs font-bold uppercase tracking-wide text-black/40">Current Status</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $openEntry ? 'On Duty' : 'Off Duty' }}</p>

            @if ($openEntry)
                <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-success/10 px-3 py-1.5 text-xs font-semibold text-success">
                    <span class="h-2 w-2 rounded-full bg-success"></span>
                    <span>Live tracking active since {{ $openEntry->time_in->format('g:i A') }}</span>
                </div>
            @else
                <p class="mt-4 text-xs text-black/60">
                    Location access is required to time in. Tracking stops immediately when you time out.
                </p>
            @endif

            <p x-show="error" x-text="error" x-cloak class="mt-4 text-sm text-danger"></p>

            @if (! $openEntry)
                <form method="POST" action="{{ route('student.time.clock-in') }}" x-ref="clockInForm">
                    @csrf
                    <input type="hidden" name="latitude" x-model="latitude">
                    <input type="hidden" name="longitude" x-model="longitude">
                    <button
                        type="button"
                        x-on:click="requestLocationAndSubmit('clockInForm')"
                        x-bind:disabled="requesting || insecureContext"
                        class="mt-6 w-full inline-flex justify-center items-center gap-2 rounded-md bg-gold px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-gold/90 disabled:opacity-50"
                    >
                        <span x-show="!requesting">Time In</span>
                        <span x-show="requesting" x-cloak>Getting your location&hellip;</span>
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('student.time.clock-out') }}" x-ref="clockOutForm">
                    @csrf
                    <input type="hidden" name="latitude" x-model="latitude">
                    <input type="hidden" name="longitude" x-model="longitude">
                    <button
                        type="button"
                        x-on:click="requestLocationAndSubmit('clockOutForm')"
                        x-bind:disabled="requesting || insecureContext"
                        class="mt-6 w-full inline-flex justify-center items-center gap-2 rounded-md bg-gold px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-gold/90 disabled:opacity-50"
                    >
                        <span x-show="!requesting">Time Out</span>
                        <span x-show="requesting" x-cloak>Getting your location&hellip;</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
