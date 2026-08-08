@extends('layouts.dean')

@section('title', 'Notifications')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 divide-y divide-gray-100">
        <div class="p-5 flex items-start gap-3">
            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>
            <div>
                <p class="text-sm text-gray-900">New registration pending approval: Pedro Ramos.</p>
                <p class="mt-1 text-xs text-gray-400">Today, 9:10 AM</p>
            </div>
        </div>
        <div class="p-5 flex items-start gap-3">
            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>
            <div>
                <p class="text-sm text-gray-900">Juan Dela Cruz submitted an accomplishment report.</p>
                <p class="mt-1 text-xs text-gray-400">Yesterday, 5:22 PM</p>
            </div>
        </div>
        <div class="p-5 flex items-start gap-3">
            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-gray-300"></span>
            <div>
                <p class="text-sm text-gray-600">Maria Reyes did not submit a report for Aug 5.</p>
                <p class="mt-1 text-xs text-gray-400">Aug 6, 8:00 AM</p>
            </div>
        </div>
    </div>
@endsection
