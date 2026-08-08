@extends('layouts.student')

@section('title', 'Attendance History')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6 mb-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total Hours Logged (this month)</p>
        <p class="text-lg font-semibold text-gray-900">86h 42m</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="pb-2 font-medium">Date</th>
                    <th class="pb-2 font-medium">Time In</th>
                    <th class="pb-2 font-medium">Time Out</th>
                    <th class="pb-2 font-medium">Duration</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="py-2 text-gray-700">Aug 7, 2026</td>
                    <td class="py-2 text-gray-700">8:02 AM</td>
                    <td class="py-2 text-gray-700">5:14 PM</td>
                    <td class="py-2 text-gray-700">9h 12m</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-700">Aug 6, 2026</td>
                    <td class="py-2 text-gray-700">8:11 AM</td>
                    <td class="py-2 text-gray-700">5:03 PM</td>
                    <td class="py-2 text-gray-700">8h 52m</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-700">Aug 5, 2026</td>
                    <td class="py-2 text-gray-700">7:58 AM</td>
                    <td class="py-2 text-gray-700">5:20 PM</td>
                    <td class="py-2 text-gray-700">9h 22m</td>
                </tr>
                <tr>
                    <td class="py-2 text-gray-700">Aug 4, 2026</td>
                    <td class="py-2 text-gray-700">9:47 AM</td>
                    <td class="py-2 text-gray-700">6:30 PM</td>
                    <td class="py-2 text-gray-700">8h 43m</td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
