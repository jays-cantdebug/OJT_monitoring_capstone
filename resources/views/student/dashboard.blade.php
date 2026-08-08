@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Current Status</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">Off Duty</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Days Logged This Month</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">12</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Reports Submitted</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">10</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Recent Attendance</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500">
                        <th class="pb-2 font-medium">Date</th>
                        <th class="pb-2 font-medium">Time In</th>
                        <th class="pb-2 font-medium">Time Out</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr>
                        <td class="py-2 text-gray-700">Aug 7, 2026</td>
                        <td class="py-2 text-gray-700">8:02 AM</td>
                        <td class="py-2 text-gray-700">5:14 PM</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-700">Aug 6, 2026</td>
                        <td class="py-2 text-gray-700">8:11 AM</td>
                        <td class="py-2 text-gray-700">5:03 PM</td>
                    </tr>
                    <tr>
                        <td class="py-2 text-gray-700">Aug 5, 2026</td>
                        <td class="py-2 text-gray-700">7:58 AM</td>
                        <td class="py-2 text-gray-700">5:20 PM</td>
                    </tr>
                </tbody>
            </table>
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
