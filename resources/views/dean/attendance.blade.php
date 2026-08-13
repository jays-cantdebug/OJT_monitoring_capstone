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

    <div class="mb-4 rounded-xl bg-white shadow-sm ring-1 ring-light-gray overflow-hidden">
        <div class="p-4 border-b border-light-gray">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-success"></span>
                <h2 class="text-sm font-bold text-navy">Present Today &mdash; {{ $presentStudents->count() }} {{ $presentStudents->count() === 1 ? 'Intern' : 'Interns' }}</h2>
            </div>
            <p class="mt-1 text-xs text-black/50">
                Assigned students with a Time In logged today, most recent first.
            </p>
        </div>

        @if ($presentStudents->isEmpty())
            <div class="p-10 text-center">
                <x-heroicon-o-user-group class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">No One Has Timed In Yet</p>
                <p class="mt-1 text-sm text-black/60">Present students will show up here as they clock in.</p>
            </div>
        @else
            <div class="sm:hidden divide-y divide-light-gray">
                @foreach ($presentStudents as $student)
                    @php $todayEntry = $student->dtrEntries->first(); @endphp
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            @if ($student->avatarUrl())
                                <img src="{{ $student->avatarUrl() }}" alt="{{ $student->name }}" class="h-8 w-8 shrink-0 rounded-full object-cover">
                            @else
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-success/10 text-sm font-bold text-success">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-black">{{ $student->name }}</p>
                                <p class="truncate text-xs text-black/40">{{ $student->studentProfile?->company_name ?: 'Not yet provided' }}</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $todayEntry && ! $todayEntry->time_out ? 'bg-success/10 text-success' : 'bg-light-gray text-black/60' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $todayEntry && ! $todayEntry->time_out ? 'bg-success' : 'bg-light-gray' }}"></span>
                                {{ $todayEntry && ! $todayEntry->time_out ? 'On Duty' : 'Timed Out' }}
                            </span>
                        </div>
                        <p class="mt-2 text-xs text-black/40">
                            Timed in at {{ $todayEntry?->time_in->format('g:i A') }}
                        </p>
                        <a href="{{ route('dean.students.show', $student->id) }}" class="mt-3 block rounded-md bg-light-gray/40 py-2 text-center text-xs font-medium text-navy hover:bg-light-gray/60">
                            View Profile
                        </a>
                    </div>
                @endforeach
            </div>

            <table class="hidden w-full text-sm sm:table">
                <thead>
                    <tr class="text-left text-black/40 border-b border-light-gray bg-light-gray/40">
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Student</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Company</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Time In</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light-gray">
                    @foreach ($presentStudents as $student)
                        @php $todayEntry = $student->dtrEntries->first(); @endphp
                        <tr class="hover:bg-light-gray/40 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($student->avatarUrl())
                                        <img src="{{ $student->avatarUrl() }}" alt="{{ $student->name }}" class="h-8 w-8 shrink-0 rounded-full object-cover">
                                    @else
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-success/10 text-sm font-bold text-success">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-black font-medium">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-black/40">{{ $student->studentProfile?->company_name ?: 'Not yet provided' }}</td>
                            <td class="px-6 py-4 text-black/60">{{ $todayEntry?->time_in->format('g:i A') }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $todayEntry && ! $todayEntry->time_out ? 'bg-success/10 text-success' : 'bg-light-gray text-black/60' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $todayEntry && ! $todayEntry->time_out ? 'bg-success' : 'bg-light-gray' }}"></span>
                                    {{ $todayEntry && ! $todayEntry->time_out ? 'On Duty' : 'Timed Out' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('dean.students.show', $student->id) }}" class="text-xs font-medium text-navy hover:underline">View Profile</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mb-4 rounded-xl bg-white shadow-sm ring-1 ring-light-gray overflow-hidden">
        <div class="p-4 border-b border-light-gray">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-danger"></span>
                <h2 class="text-sm font-bold text-navy">Absent Today &mdash; {{ $absentStudents->count() }} {{ $absentStudents->count() === 1 ? 'Intern' : 'Interns' }}</h2>
            </div>
            <p class="mt-1 text-xs text-black/50">
                Assigned students with no Time In logged today. Doesn't account for students who've already completed their OJT hours or are on approved leave.
            </p>
        </div>

        @if ($absentStudents->isEmpty())
            <div class="p-10 text-center">
                <x-heroicon-o-check-circle class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">No Absences Today</p>
                <p class="mt-1 text-sm text-black/60">Every assigned student has timed in.</p>
            </div>
        @else
            <div class="sm:hidden divide-y divide-light-gray">
                @foreach ($absentStudents as $student)
                    @php $lastEntry = $student->dtrEntries->first(); @endphp
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            @if ($student->avatarUrl())
                                <img src="{{ $student->avatarUrl() }}" alt="{{ $student->name }}" class="h-8 w-8 shrink-0 rounded-full object-cover">
                            @else
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-danger/10 text-sm font-bold text-danger">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-black">{{ $student->name }}</p>
                                <p class="truncate text-xs text-black/40">{{ $student->studentProfile?->company_name ?: 'Not yet provided' }}</p>
                            </div>
                            <span class="shrink-0 text-xs text-black/50">
                                {{ $lastEntry ? $lastEntry->time_in->diffForHumans() : 'Never logged in' }}
                            </span>
                        </div>
                        <a href="{{ route('dean.students.show', $student->id) }}" class="mt-3 block rounded-md bg-light-gray/40 py-2 text-center text-xs font-medium text-navy hover:bg-light-gray/60">
                            View Profile
                        </a>
                    </div>
                @endforeach
            </div>

            <table class="hidden w-full text-sm sm:table">
                <thead>
                    <tr class="text-left text-black/40 border-b border-light-gray bg-light-gray/40">
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Student</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Company</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Last Time In</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light-gray">
                    @foreach ($absentStudents as $student)
                        @php $lastEntry = $student->dtrEntries->first(); @endphp
                        <tr class="hover:bg-light-gray/40 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($student->avatarUrl())
                                        <img src="{{ $student->avatarUrl() }}" alt="{{ $student->name }}" class="h-8 w-8 shrink-0 rounded-full object-cover">
                                    @else
                                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-danger/10 text-sm font-bold text-danger">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-black font-medium">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-black/40">{{ $student->studentProfile?->company_name ?: 'Not yet provided' }}</td>
                            <td class="px-6 py-4 text-black/60">{{ $lastEntry ? $lastEntry->time_in->diffForHumans() : 'Never logged in' }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('dean.students.show', $student->id) }}" class="text-xs font-medium text-navy hover:underline">View Profile</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
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
        <div class="sm:hidden divide-y divide-light-gray">
            @forelse ($entries as $entry)
                <div class="p-4">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex min-w-0 items-center gap-3">
                            @if ($entry->user->avatarUrl())
                                <img src="{{ $entry->user->avatarUrl() }}" alt="{{ $entry->user->name }}" class="h-8 w-8 shrink-0 rounded-full object-cover">
                            @else
                                <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                    {{ strtoupper(substr($entry->user->name, 0, 1)) }}
                                </div>
                            @endif
                            <p class="truncate text-sm font-medium text-black">{{ $entry->user->name }}</p>
                        </div>
                        <p class="shrink-0 text-sm text-black/60">
                            @if ($entry->time_out)
                                @php $minutes = intdiv($entry->durationInSeconds(), 60); @endphp
                                {{ intdiv($minutes, 60) }}h {{ $minutes % 60 }}m
                            @else
                                &mdash;
                            @endif
                        </p>
                    </div>
                    <p class="mt-1 text-xs text-black/40">
                        {{ $entry->time_in->format('M j, Y') }} · {{ $entry->time_in->format('g:i A') }} – {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                    </p>
                </div>
            @empty
                <div class="p-10 text-center text-sm text-black/60">No attendance records match these filters.</div>
            @endforelse
        </div>

        <table class="hidden w-full text-sm sm:table">
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
                        <td class="px-5 py-3 text-black font-medium">
                            <div class="flex items-center gap-3">
                                @if ($entry->user->avatarUrl())
                                    <img src="{{ $entry->user->avatarUrl() }}" alt="{{ $entry->user->name }}" class="h-8 w-8 shrink-0 rounded-full object-cover">
                                @else
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                        {{ strtoupper(substr($entry->user->name, 0, 1)) }}
                                    </div>
                                @endif
                                <span>{{ $entry->user->name }}</span>
                            </div>
                        </td>
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
