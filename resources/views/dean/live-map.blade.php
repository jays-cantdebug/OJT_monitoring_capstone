@extends('layouts.dean')

@section('title', 'Live Map')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-success/10 text-success">
                <x-heroicon-o-map-pin class="h-5 w-5" />
            </div>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-wide text-black/40">OJT Compliance Map</p>
                <h2 class="flex items-center gap-2 font-bold text-navy">
                    Live Student Location Map
                    <span class="h-2 w-2 rounded-full bg-success"></span>
                </h2>
            </div>
        </div>
    </div>

    <div x-data="liveMap({{ $onDuty->values()->toJson() }})">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-4 mb-6 flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                    <x-heroicon-o-magnifying-glass class="h-4 w-4" />
                </span>
                <input type="text" x-model="search" placeholder="Search student..." class="w-full rounded-md border-0 bg-light-gray py-2.5 pl-9 pr-3 text-sm text-black focus:ring-2 focus:ring-navy/40">
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold uppercase tracking-wide text-navy">Intern List (<span x-text="filteredStudents.length"></span>)</h3>
                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-success">
                    <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                    Live &mdash; Real-Time Ping Updates
                </span>
            </div>

            <ul class="divide-y divide-light-gray mb-6">
                <template x-for="student in filteredStudents" :key="student.userId">
                    <li class="py-3 flex items-center justify-between text-sm">
                        <a :href="profileUrl(student)" class="text-black hover:text-navy hover:underline" x-text="student.name"></a>
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-success/10 px-2.5 py-1 text-xs font-semibold text-success">
                            <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                            <span x-text="'Since ' + student.since"></span>
                            <span x-show="student.hasLivePing" x-text="'· last ping ' + student.lastPingAt"></span>
                        </span>
                        <span x-show="!student.hasLivePing" class="inline-flex items-center gap-1.5 rounded-full bg-warning/10 px-2.5 py-1 text-xs font-semibold text-warning">
                            <span class="h-1.5 w-1.5 rounded-full bg-warning"></span>
                            Awaiting first ping
                        </span>
                    </li>
                </template>
                <li x-show="students.length === 0" class="py-6 text-center text-sm text-black/60">
                    No interns are currently on duty.
                </li>
                <li x-show="students.length > 0 && filteredStudents.length === 0" class="py-6 text-center text-sm text-black/60">
                    No interns match "<span x-text="search"></span>".
                </li>
            </ul>

            <div class="relative h-96 rounded-lg bg-light-gray overflow-hidden" x-ref="map"></div>
        </div>
    </div>
@endsection
