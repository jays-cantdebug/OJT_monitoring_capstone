@extends('layouts.student')

@section('title', 'Time In/Out')

@section('content')
    @if (session('status'))
        <div class="max-w-lg mb-4 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="max-w-lg" x-data="timeClock()">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-8 text-center">
            <p class="text-sm text-gray-500">Current Status</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $openEntry ? 'On Duty' : 'Off Duty' }}</p>

            @if ($openEntry)
                <div class="mt-4 inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
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
                        class="mt-6 w-full inline-flex justify-center rounded-md bg-gray-900 px-4 py-3 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50"
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
                        class="mt-6 w-full inline-flex justify-center rounded-md bg-gray-900 px-4 py-3 text-sm font-medium text-white hover:bg-gray-700 disabled:opacity-50"
                    >
                        <span x-show="!requesting">Time Out</span>
                        <span x-show="requesting" x-cloak>Getting your location&hellip;</span>
                    </button>
                </form>
            @endif
        </div>
    </div>
@endsection
