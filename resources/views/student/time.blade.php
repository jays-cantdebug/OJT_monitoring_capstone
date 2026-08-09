@extends('layouts.student')

@section('title', 'Time In/Out')

@section('content')
    @if (session('status'))
        <div class="max-w-lg mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="max-w-lg" x-data="timeClock()">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-8 text-center">
            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full {{ $openEntry ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                <x-heroicon-o-clock class="h-7 w-7" />
            </div>
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Current Status</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $openEntry ? 'On Duty' : 'Off Duty' }}</p>

            @if ($openEntry)
                <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-600">
                    <span class="h-2 w-2 rounded-full bg-green-500"></span>
                    <span>Live tracking active since {{ $openEntry->time_in->format('g:i A') }}</span>
                </div>
            @else
                <p class="mt-4 text-xs text-gray-500">
                    Location access is required to time in. Tracking stops immediately when you time out.
                </p>
            @endif

            <p x-show="error" x-text="error" x-cloak class="mt-4 text-sm text-red-600"></p>

            @if (! $openEntry)
                <form method="POST" action="{{ route('student.time.clock-in') }}" x-ref="clockInForm">
                    @csrf
                    <input type="hidden" name="latitude" x-model="latitude">
                    <input type="hidden" name="longitude" x-model="longitude">
                    <button
                        type="button"
                        x-on:click="requestLocationAndSubmit('clockInForm')"
                        x-bind:disabled="requesting"
                        class="mt-6 w-full inline-flex justify-center items-center gap-2 rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600 disabled:opacity-50"
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
                        x-bind:disabled="requesting"
                        class="mt-6 w-full inline-flex justify-center items-center gap-2 rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600 disabled:opacity-50"
                    >
                        <span x-show="!requesting">Time Out</span>
                        <span x-show="requesting" x-cloak>Getting your location&hellip;</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
