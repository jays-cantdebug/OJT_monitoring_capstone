@extends('layouts.student')

@section('title', 'Time In/Out')

@section('content')
    <div class="max-w-lg" x-data="{ onDuty: false }">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-8 text-center">
            <p class="text-sm text-gray-500">Current Status</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900" x-text="onDuty ? 'On Duty' : 'Off Duty'"></p>

            <div x-show="onDuty" class="mt-4 inline-flex items-center gap-2 rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700">
                <span class="h-2 w-2 rounded-full bg-green-500"></span>
                <span>Live tracking active since 8:02 AM</span>
            </div>

            <p x-show="!onDuty" class="mt-4 text-xs text-gray-500">
                Location access is required to time in. Tracking stops immediately when you time out.
            </p>

            <button
                type="button"
                x-show="!onDuty"
                x-on:click="onDuty = true"
                class="mt-6 w-full inline-flex justify-center rounded-md bg-gray-900 px-4 py-3 text-sm font-medium text-white hover:bg-gray-700"
            >
                Time In
            </button>

            <button
                type="button"
                x-show="onDuty"
                x-on:click="onDuty = false"
                class="mt-6 w-full inline-flex justify-center rounded-md bg-gray-900 px-4 py-3 text-sm font-medium text-white hover:bg-gray-700"
            >
                Time Out
            </button>
        </div>

        <p class="mt-4 text-xs text-gray-400 text-center">
            This is a UI preview only — the button above toggles the display, it doesn't record real GPS or DTR data yet.
        </p>
    </div>
@endsection
