@extends('layouts.student')

@section('title', 'Notifications')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 divide-y divide-gray-100">
        <div class="p-5 flex items-start gap-3">
            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>
            <div>
                <p class="text-sm text-gray-900">Your accomplishment report for Aug 7 was received.</p>
                <p class="mt-1 text-xs text-gray-400">Today, 5:20 PM</p>
            </div>
        </div>
        <div class="p-5 flex items-start gap-3">
            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-blue-500"></span>
            <div>
                <p class="text-sm text-gray-900">Reminder: submit today's accomplishment report.</p>
                <p class="mt-1 text-xs text-gray-400">Today, 4:00 PM</p>
            </div>
        </div>
        <div class="p-5 flex items-start gap-3">
            <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-gray-300"></span>
            <div>
                <p class="text-sm text-gray-600">Your account was approved by the Dean.</p>
                <p class="mt-1 text-xs text-gray-400">Aug 1, 9:12 AM</p>
            </div>
        </div>
    </div>
@endsection
