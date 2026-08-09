@extends('layouts.dean')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-4 rounded-xl bg-light-gray p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-shield-check class="h-5 w-5" />
            </div>
            <div>
                <p class="font-bold text-navy">School of Computing Dashboard Active</p>
                <p class="text-xs text-black/60">These stats are filtered automatically to show only the students assigned to your supervision.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" class="rounded-md bg-gold px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-gold/90">
                Pause Auto Sync
            </button>
            <button type="button" class="rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-black ring-1 ring-light-gray hover:bg-light-gray/40">
                Reset Stats
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/10 text-gold">
                    <x-heroicon-o-user-group class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-light-gray px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-black/60">Total</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Assigned Interns</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $assignedCount }}</p>
            <p class="mt-1 text-xs text-black/60">Assigned by Coordinator</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/10 text-gold">
                    <x-heroicon-o-play class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-gold/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-gold">Active</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Active Interns</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $activeCount }}</p>
            <p class="mt-1 text-xs text-black/60">Have logged at least one shift</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-success/10 text-success">
                    <x-heroicon-o-check-circle class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-success/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-success">All Time</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Total Hours Logged</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ number_format($totalHoursLogged) }}</p>
            <p class="mt-1 text-xs text-black/60">Across all interns</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/10 text-gold">
                    <x-heroicon-o-calendar class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-gold/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-gold">Present</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Timed In Today</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $onDutyCount }}</p>
            <p class="mt-1 text-xs text-black/60">Active Logs Today</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-warning/10 text-warning">
                    <x-heroicon-o-document-text class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-warning/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-warning">Action Needed</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Pending Reports</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $pendingReportsCount }}</p>
            <p class="mt-1 text-xs text-black/60">Awaiting your review</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-success/10 text-success">
                    <x-heroicon-o-arrow-trending-up class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-success/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-success">Rate</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-black/40">Avg Compliance Rate</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $avgComplianceRate }}%</p>
            <p class="mt-1 text-xs text-black/60">Across assigned interns</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
            <h2 class="text-sm font-semibold text-navy mb-3">Recent Activity</h2>
            <ul class="space-y-1">
                @forelse ($recentActivity as $item)
                    <li class="flex items-start gap-3 rounded-lg px-2 py-2 -mx-2 hover:bg-light-gray/40 transition">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $item['badge'] }}">
                            <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-4 w-4" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm text-black">{{ $item['text'] }}</p>
                            <p class="mt-0.5 text-xs text-black/40">{{ $item['time'] }}</p>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-sm text-black/40">No recent activity yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
            <h2 class="text-sm font-semibold text-navy mb-3">Recently Added Interns</h2>
            <ul class="space-y-1">
                @forelse ($recentlyAdded as $item)
                    <li class="flex items-start gap-3 rounded-lg px-2 py-2 -mx-2 hover:bg-light-gray/40 transition">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                            <x-heroicon-o-user-plus class="h-4 w-4" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-black">{{ $item['name'] }}</p>
                            <p class="mt-0.5 text-xs text-black/40">Added {{ $item['date'] }}</p>
                        </div>
                    </li>
                @empty
                    <li class="py-4 text-sm text-black/40">No interns added yet.</li>
                @endforelse
            </ul>
            <a href="{{ route('dean.students') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-navy hover:underline">
                View all interns &rarr;
            </a>
        </div>
    </div>
@endsection
