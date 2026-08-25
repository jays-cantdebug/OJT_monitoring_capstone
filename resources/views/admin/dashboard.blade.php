@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
            <x-heroicon-o-shield-check class="h-5 w-5" />
        </div>
        <div>
            <h2 class="font-bold text-navy">All-Departments Overview</h2>
            <p class="text-sm text-black/60">System-wide totals across all {{ count($departmentRows) }} departments.</p>
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4 mb-6 sm:grid-cols-4">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-black/40">Departments</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ count($departmentRows) }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-4">
            <p class="text-[11px] font-bold uppercase tracking-wide text-black/40">Total Deans</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $totalDeans }}</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-4 col-span-2 sm:col-span-2">
            <p class="text-[11px] font-bold uppercase tracking-wide text-black/40">Total Student Interns</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $totalStudents }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray overflow-hidden mb-6">
        <div class="p-4 border-b border-light-gray">
            <h3 class="text-sm font-semibold text-navy">By Department</h3>
        </div>

        <div class="sm:hidden divide-y divide-light-gray">
            @foreach ($departmentRows as $row)
                <div class="p-4 flex items-center justify-between">
                    <span class="inline-flex items-center rounded-full bg-navy/10 px-2.5 py-1 text-xs font-semibold text-navy">
                        {{ $row['department']->label() }}
                    </span>
                    <div class="text-right text-sm">
                        <p class="text-black">{{ $row['studentCount'] }} students</p>
                        <p class="text-black/40 text-xs">{{ $row['deanCount'] }} dean(s)</p>
                    </div>
                </div>
            @endforeach
        </div>

        <table class="hidden w-full text-sm sm:table">
            <thead>
                <tr class="text-left text-black/40 border-b border-light-gray bg-light-gray/40">
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Department</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Student Interns</th>
                    <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Deans</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-light-gray">
                @foreach ($departmentRows as $row)
                    <tr class="hover:bg-light-gray/40 transition">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-full bg-navy/10 px-2.5 py-1 text-xs font-semibold text-navy">
                                {{ $row['department']->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-black">{{ $row['studentCount'] }}</td>
                        <td class="px-6 py-4 text-black">{{ $row['deanCount'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-sm font-semibold text-navy">Recent System Activity</h3>
            <a href="{{ route('admin.audit-logs') }}" class="text-xs font-medium text-navy hover:underline">View all</a>
        </div>

        @if ($recentActivity->isEmpty())
            <p class="text-sm text-black/60">No activity recorded yet.</p>
        @else
            <ul class="divide-y divide-light-gray">
                @foreach ($recentActivity as $log)
                    <x-audit-log-entry :log="$log" />
                @endforeach
            </ul>
        @endif
    </div>
@endsection
