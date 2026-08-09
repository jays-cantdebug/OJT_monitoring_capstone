@extends('layouts.student')

@section('title', 'Attendance History')

@section('content')
    <div class="mb-6 flex items-center gap-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-light-gray">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
            <x-heroicon-o-clock class="h-6 w-6" />
        </div>
        <div class="flex-1">
            <p class="text-xs font-bold uppercase tracking-wide text-black/40">Total Hours Logged (this month)</p>
            <p class="mt-0.5 text-xl font-bold text-navy">{{ $totalHoursThisMonth }}h {{ $totalMinutesThisMonth }}m</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        @if ($entries->isEmpty())
            <div class="text-center py-10">
                <x-heroicon-o-clock class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">No Attendance Recorded</p>
                <p class="mt-1 text-sm text-black/60">Clock in to begin your very first recorded internship session today.</p>
            </div>
        @else
            <div class="sm:hidden divide-y divide-light-gray">
                @foreach ($entries as $entry)
                    <div class="py-3">
                        <div class="flex items-center justify-between gap-2">
                            <p class="text-sm font-medium text-black">{{ $entry->time_in->format('M j, Y') }}</p>
                            <p class="shrink-0 text-sm text-black/60">
                                @if ($entry->time_out)
                                    @php $minutes = abs($entry->time_in->diffInMinutes($entry->time_out)); @endphp
                                    {{ intdiv($minutes, 60) }}h {{ $minutes % 60 }}m
                                @else
                                    &mdash;
                                @endif
                            </p>
                        </div>
                        <p class="mt-1 text-xs text-black/40">
                            {{ $entry->time_in->format('g:i A') }} – {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                        </p>
                    </div>
                @endforeach
            </div>

            <table class="hidden w-full text-sm sm:table">
                <thead>
                    <tr class="text-left text-black/40 border-b border-light-gray">
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Date</th>
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Time In</th>
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Time Out</th>
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Duration</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light-gray">
                    @foreach ($entries as $entry)
                        <tr>
                            <td class="py-2 text-black/60">{{ $entry->time_in->format('M j, Y') }}</td>
                            <td class="py-2 text-black/60">{{ $entry->time_in->format('g:i A') }}</td>
                            <td class="py-2 text-black/60">
                                {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                            </td>
                            <td class="py-2 text-black/60">
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
