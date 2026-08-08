@extends('layouts.dean')

@section('title', 'Live Map')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-semibold text-gray-900">On-Duty Student Locations</h2>
            <span class="text-xs text-gray-400">Updates in real time once Reverb is wired up (Step 6-7)</span>
        </div>

        <div class="h-96 rounded-md border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-center px-6">
            <div>
                <p class="text-sm font-medium text-gray-500">Google Maps will render here</p>
                <p class="mt-1 text-xs text-gray-400">Waiting on Google Maps API key provisioning (open question) and Reverb broadcasting (Step 6)</p>
            </div>
        </div>

        <div class="mt-6">
            <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Currently On Duty</h3>
            <ul class="divide-y divide-gray-100">
                <li class="py-3 flex items-center justify-between text-sm">
                    <span class="text-gray-900">Juan Dela Cruz</span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-green-700">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Since 8:02 AM
                    </span>
                </li>
                <li class="py-3 flex items-center justify-between text-sm">
                    <span class="text-gray-900">Maria Reyes</span>
                    <span class="inline-flex items-center gap-1.5 text-xs text-green-700">
                        <span class="h-2 w-2 rounded-full bg-green-500"></span>
                        Since 8:04 AM
                    </span>
                </li>
            </ul>
        </div>
    </div>
@endsection
