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

    <div x-data="locationLightbox()" class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
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
                        <button
                            type="button"
                            @click="show({{ Illuminate\Support\Js::from($entry->locationHistoryPayload()) }})"
                            class="mt-1.5 inline-flex items-center gap-1 text-xs font-medium text-navy hover:underline"
                        >
                            <x-heroicon-o-map-pin class="h-3.5 w-3.5" />
                            View Location
                        </button>
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
                        <th class="pb-2 text-xs font-bold uppercase tracking-wide">Location</th>
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
                            <td class="py-2">
                                <button
                                    type="button"
                                    @click="show({{ Illuminate\Support\Js::from($entry->locationHistoryPayload()) }})"
                                    class="inline-flex items-center gap-1 text-xs font-medium text-navy hover:underline"
                                >
                                    <x-heroicon-o-map-pin class="h-3.5 w-3.5" />
                                    View Location
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div
            x-show="open"
            x-cloak
            @keydown.escape.window="close()"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/50 backdrop-blur-sm"
                @click="close()"
            ></div>

            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-xl shadow-xl ring-1 ring-light-gray max-w-lg w-full p-5"
            >
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <h3 class="text-sm font-semibold text-navy" x-text="date"></h3>
                        <p class="mt-0.5 flex items-center gap-3 text-xs text-black/40">
                            <span class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-success"></span> Time In</span>
                            <span x-show="hasTimeOut" class="inline-flex items-center gap-1"><span class="h-2 w-2 rounded-full bg-navy"></span> Time Out</span>
                            <span x-show="!hasTimeOut" class="text-black/40">&mdash; Still on duty</span>
                        </p>
                    </div>
                    <button
                        type="button"
                        @click="close()"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-black/40 hover:bg-light-gray/60"
                        aria-label="Close"
                    >
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </button>
                </div>
                <div class="relative h-72 rounded-lg bg-light-gray overflow-hidden" x-ref="map"></div>
            </div>
        </div>
    </div>
@endsection
