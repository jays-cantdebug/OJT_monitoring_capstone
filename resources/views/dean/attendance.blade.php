@extends('layouts.dean')

@section('title', 'Attendance Records')

@section('content')
    <div class="mb-4 rounded-xl bg-navy p-4 text-white">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-wide">
                    Attendance Compliance
                </span>
                <h2 class="mt-2 text-xl font-bold">Real-Time Attendance Monitoring</h2>
                <p class="mt-1 text-xs text-white/80">
                    Verify geofencing, clock-in stamps, and photo evidence for assigned interns.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-black/20 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wide whitespace-nowrap">
                <x-heroicon-o-arrow-path class="h-3.5 w-3.5" />
                Active Auditing Day {{ now()->format('Y-m-d') }}
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-user-group class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Assigned Students</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $assignedCount }}</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-success"></span>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Present Today</p>
            </div>
            <p class="mt-2 text-2xl font-bold text-navy">{{ $presentTodayCount }} Interns</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-danger"></span>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Absent Today</p>
            </div>
            <p class="mt-2 text-2xl font-bold text-navy">{{ $absentTodayCount }} Interns</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-clock class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Currently Active</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $onDutyCount }} Timed In</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-success/10 text-success">
                <x-heroicon-o-check-circle class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Hours Logged Today</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $hoursLoggedToday }} hrs</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-arrow-trending-up class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Avg Rate</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $avgComplianceRate }}%</p>
        </div>
    </div>

    <form method="GET" action="{{ route('dean.attendance') }}" class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-3 mb-4 flex flex-wrap items-center gap-3">
        <h2 class="shrink-0 text-sm font-bold text-navy">Attendance Registry</h2>
        <div class="relative flex-1 min-w-[200px]">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                <x-heroicon-o-magnifying-glass class="h-4 w-4" />
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search student..." class="w-full rounded-md border-0 bg-light-gray py-2.5 pl-9 pr-3 text-sm text-black focus:ring-2 focus:ring-navy/40">
        </div>
        <select name="student_id" class="rounded-md border-0 bg-light-gray text-sm py-2.5 px-3 text-black focus:ring-2 focus:ring-navy/40">
            <option value="">All Students</option>
            @foreach ($students as $student)
                <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>{{ $student->name }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="rounded-md border-0 bg-light-gray text-sm py-2.5 px-3 text-black focus:ring-2 focus:ring-navy/40">
        <button type="submit" class="rounded-md bg-gold px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:bg-gold/90">
            Filter
        </button>
        @if (request()->anyFilled(['search', 'student_id', 'date']))
            <a href="{{ route('dean.attendance') }}" class="text-xs font-medium text-black/60 hover:underline">Clear</a>
        @endif
    </form>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-black/40 border-b border-light-gray bg-light-gray/40">
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Student</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Date</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Time In</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Time Out</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Duration</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-light-gray">
                @forelse ($entries as $entry)
                    <tr>
                        <td class="px-5 py-3 text-black font-medium">{{ $entry->user->name }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $entry->time_in->format('M j, Y') }}</td>
                        <td class="px-5 py-3 text-black/60">{{ $entry->time_in->format('g:i A') }}</td>
                        <td class="px-5 py-3 text-black/60">
                            {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                        </td>
                        <td class="px-5 py-3 text-black/60">
                            @if ($entry->time_out)
                                @php $minutes = intdiv($entry->durationInSeconds(), 60); @endphp
                                {{ intdiv($minutes, 60) }}h {{ $minutes % 60 }}m
                            @else
                                &mdash;
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-10 text-center text-sm text-black/60">No attendance records match these filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
