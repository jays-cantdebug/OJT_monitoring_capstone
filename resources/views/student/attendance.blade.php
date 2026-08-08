@extends('layouts.student')

@section('title', 'Attendance History')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6 mb-6 flex items-center justify-between">
        <p class="text-sm text-gray-500">Total Hours Logged (this month)</p>
        <p class="text-lg font-semibold text-gray-900">{{ $totalHoursThisMonth }}h {{ $totalMinutesThisMonth }}m</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
        @if ($entries->isEmpty())
            <p class="text-sm text-gray-500 text-center py-6">No attendance records yet.</p>
        @else
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
                    @foreach ($entries as $entry)
                        <tr>
                            <td class="py-2 text-gray-700">{{ $entry->time_in->format('M j, Y') }}</td>
                            <td class="py-2 text-gray-700">{{ $entry->time_in->format('g:i A') }}</td>
                            <td class="py-2 text-gray-700">
                                {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                            </td>
                            <td class="py-2 text-gray-700">
                                @if ($entry->time_out)
                                    @php $minutes = abs($entry->time_in->diffInMinutes($entry->time_out)); @endphp
                                    {{ intdiv($minutes, 60) }}h {{ $minutes % 60 }}m
                                @else
                                    &mdash;
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
