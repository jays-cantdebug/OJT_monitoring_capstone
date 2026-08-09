@extends('layouts.dean')

@section('title', 'Attendance Records')

@section('content')
    <div class="mb-4 rounded-xl bg-gradient-to-r from-green-700 to-green-600 p-4 text-white">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <span class="inline-flex items-center rounded-full bg-white/15 px-3 py-1 text-[11px] font-bold uppercase tracking-wide">
                    Attendance Compliance
                </span>
                <h2 class="mt-2 text-xl font-bold">Real-Time Attendance Monitoring</h2>
                <p class="mt-1 text-xs text-green-50/90">
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
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-user-group class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Assigned Students</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">24</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-green-500"></span>
                <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Present Today</p>
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-900">0 Interns</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-center gap-2">
                <span class="h-2.5 w-2.5 rounded-full bg-red-500"></span>
                <p class="text-xs font-bold uppercase tracking-wide text-gray-400">Absent Today</p>
            </div>
            <p class="mt-2 text-2xl font-bold text-gray-900">0 Interns</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <x-heroicon-o-clock class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Currently Active</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">0 Timed In</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-100 text-green-600">
                <x-heroicon-o-check-circle class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Completed OJT</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">0 Finished</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-arrow-trending-up class="h-5 w-5" />
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Avg Rate</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">96%</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-3 mb-4 flex flex-wrap items-center gap-3">
        <h2 class="shrink-0 text-sm font-bold text-gray-900">Attendance Registry</h2>
        <div class="relative flex-1 min-w-[200px]">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <x-heroicon-o-magnifying-glass class="h-4 w-4" />
            </span>
            <input type="text" placeholder="Search student..." class="w-full rounded-md border-0 bg-[#eef2f7] py-2.5 pl-9 pr-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40">
        </div>
        <select class="rounded-md border-0 bg-[#eef2f7] text-sm py-2.5 px-3 text-gray-900 focus:ring-2 focus:ring-blue-950/40">
            <option>All Students</option>
            <option>Juan Dela Cruz</option>
            <option>Maria Reyes</option>
        </select>
        <input type="date" class="rounded-md border-0 bg-[#eef2f7] text-sm py-2.5 px-3 text-gray-900 focus:ring-2 focus:ring-blue-950/40">
        <button type="button" class="rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-2.5 text-xs font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600">
            Filter
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-400 border-b border-gray-100 bg-gray-50/60">
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Student</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Date</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Time In</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Time Out</th>
                    <th class="px-5 py-2.5 text-xs font-bold uppercase tracking-wide">Duration</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ([
                    ['name' => 'Juan Dela Cruz', 'date' => 'Aug 7, 2026', 'in' => '8:02 AM', 'out' => '5:14 PM', 'duration' => '9h 12m'],
                    ['name' => 'Maria Reyes', 'date' => 'Aug 7, 2026', 'in' => '8:04 AM', 'out' => '5:00 PM', 'duration' => '8h 56m'],
                    ['name' => 'Liza Fernandez', 'date' => 'Aug 6, 2026', 'in' => '9:47 AM', 'out' => '6:30 PM', 'duration' => '8h 43m'],
                ] as $row)
                    <tr>
                        <td class="px-5 py-3 text-gray-900 font-medium">{{ $row['name'] }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $row['date'] }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $row['in'] }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $row['out'] }}</td>
                        <td class="px-5 py-3 text-gray-600">{{ $row['duration'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
