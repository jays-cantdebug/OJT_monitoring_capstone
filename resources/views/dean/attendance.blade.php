@extends('layouts.dean')

@section('title', 'Attendance Records')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-4 mb-6 flex flex-wrap items-center gap-3">
        <select class="rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            <option>All Students</option>
            <option>Juan Dela Cruz</option>
            <option>Maria Reyes</option>
        </select>
        <input type="date" class="rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
        <button type="button" class="rounded-md bg-gray-900 px-3 py-2 text-xs font-medium text-white hover:bg-gray-700">
            Filter
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Student</th>
                    <th class="px-6 py-3 font-medium">Date</th>
                    <th class="px-6 py-3 font-medium">Time In</th>
                    <th class="px-6 py-3 font-medium">Time Out</th>
                    <th class="px-6 py-3 font-medium">Duration</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ([
                    ['name' => 'Juan Dela Cruz', 'date' => 'Aug 7, 2026', 'in' => '8:02 AM', 'out' => '5:14 PM', 'duration' => '9h 12m'],
                    ['name' => 'Maria Reyes', 'date' => 'Aug 7, 2026', 'in' => '8:04 AM', 'out' => '5:00 PM', 'duration' => '8h 56m'],
                    ['name' => 'Liza Fernandez', 'date' => 'Aug 6, 2026', 'in' => '9:47 AM', 'out' => '6:30 PM', 'duration' => '8h 43m'],
                ] as $row)
                    <tr>
                        <td class="px-6 py-4 text-gray-900">{{ $row['name'] }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $row['date'] }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $row['in'] }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $row['out'] }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $row['duration'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
