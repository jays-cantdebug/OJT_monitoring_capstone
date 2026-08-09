@extends('layouts.student')

@section('title', 'Attendance History')

@section('content')
    <div class="mb-6 flex items-center gap-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
            <x-heroicon-o-clock class="h-6 w-6" />
        </div>
        <div class="flex-1">
            <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Total Hours Logged (this month)</p>
            <p class="mt-0.5 text-xl font-bold text-gray-900">{{ $totalHoursThisMonth }}h {{ $totalMinutesThisMonth }}m</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
        @if ($entries->isEmpty())
            <div class="text-center py-10">
                <x-heroicon-o-clock class="mx-auto h-10 w-10 text-gray-300" />
                <p class="mt-3 text-sm font-bold text-gray-900">No Attendance Recorded</p>
                <p class="mt-1 text-sm text-gray-500">Clock in to begin your very first recorded internship session today.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-100">
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Date</th>
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Time In</th>
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Time Out</th>
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Duration</th>
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
