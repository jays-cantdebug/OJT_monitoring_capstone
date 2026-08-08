@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Current Status</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">{{ $openEntry ? 'On Duty' : 'Off Duty' }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Days Logged This Month</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">{{ $daysLoggedThisMonth }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Reports Submitted</p>
            <p class="mt-1 text-xl font-semibold text-gray-400">&mdash;</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Recent Attendance</h2>
            @if ($recentEntries->isEmpty())
                <p class="text-sm text-gray-500">No attendance records yet.</p>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500">
                            <th class="pb-2 font-medium">Date</th>
                            <th class="pb-2 font-medium">Time In</th>
                            <th class="pb-2 font-medium">Time Out</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($recentEntries as $entry)
                            <tr>
                                <td class="py-2 text-gray-700">{{ $entry->time_in->format('M j, Y') }}</td>
                                <td class="py-2 text-gray-700">{{ $entry->time_in->format('g:i A') }}</td>
                                <td class="py-2 text-gray-700">
                                    {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Recent Notifications</h2>
            <ul class="space-y-3 text-sm">
                <li class="text-gray-700">Your accomplishment report for Aug 7 was received.</li>
                <li class="text-gray-700">Reminder: submit today's accomplishment report.</li>
                <li class="text-gray-700">Your account was approved by the Dean.</li>
            </ul>
        </div>
    </div>
@endsection
