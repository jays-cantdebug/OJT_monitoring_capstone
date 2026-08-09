@extends('layouts.dean')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-4 rounded-xl bg-gray-100 p-4">
        <div class="flex items-center gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                <x-heroicon-o-shield-check class="h-5 w-5" />
            </div>
            <div>
                <p class="font-bold text-gray-900">School of Computing Dashboard Active</p>
                <p class="text-xs text-gray-500">These stats are filtered automatically to show only the students assigned to your supervision.</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" class="rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600">
                Pause Auto Sync
            </button>
            <button type="button" class="rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 ring-1 ring-gray-300 hover:bg-gray-50">
                Reset Stats
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <x-heroicon-o-user-group class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-gray-500">Total</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Assigned Interns</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">24</p>
            <p class="mt-1 text-xs text-gray-500">Assigned by Coordinator</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-orange-100 text-orange-600">
                    <x-heroicon-o-play class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-orange-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-orange-600">Active</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Active Interns</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">18</p>
            <p class="mt-1 text-xs text-gray-500">In-progress Hours</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <x-heroicon-o-check-circle class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-green-600">480 Hrs</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Completed Interns</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">6</p>
            <p class="mt-1 text-xs text-gray-500">Met 480 Hours Goal</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                    <x-heroicon-o-calendar class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-blue-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-blue-600">Present</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Timed In Today</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">9</p>
            <p class="mt-1 text-xs text-gray-500">Active Logs Today</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                    <x-heroicon-o-document-text class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-amber-600">Action Needed</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Pending Reports</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">3</p>
            <p class="mt-1 text-xs text-gray-500">Awaiting your review</p>
        </div>

        <div class="relative rounded-xl bg-white p-4 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <x-heroicon-o-arrow-trending-up class="h-5 w-5" />
                </div>
                <span class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-green-600">Rate</span>
            </div>
            <p class="mt-3 text-xs font-bold uppercase tracking-wide text-gray-400">Avg Compliance Rate</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">93%</p>
            <p class="mt-1 text-xs text-gray-500">Across assigned interns</p>
        </div>
    </div>

    @php
        $recentActivity = [
            ['text' => 'Juan Dela Cruz submitted an accomplishment report.', 'time' => 'Today, 5:22 PM', 'icon' => 'document-text', 'badge' => 'bg-blue-100 text-blue-600'],
            ['text' => 'Maria Reyes timed in for her shift.', 'time' => 'Today, 8:04 AM', 'icon' => 'clock', 'badge' => 'bg-green-100 text-green-600'],
            ['text' => 'New Student Intern account created: Pedro Ramos.', 'time' => 'Today, 9:10 AM', 'icon' => 'user-plus', 'badge' => 'bg-amber-100 text-amber-600'],
        ];
        $recentlyAdded = [
            ['name' => 'Pedro Ramos', 'date' => 'Aug 8, 2026'],
            ['name' => 'Angela Cruz', 'date' => 'Aug 7, 2026'],
            ['name' => 'Mark Villanueva', 'date' => 'Aug 6, 2026'],
        ];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Recent Activity</h2>
            <ul class="space-y-1">
                @foreach ($recentActivity as $item)
                    <li class="flex items-start gap-3 rounded-lg px-2 py-2 -mx-2 hover:bg-gray-50 transition">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $item['badge'] }}">
                            <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-4 w-4" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm text-gray-900">{{ $item['text'] }}</p>
                            <p class="mt-0.5 text-xs text-gray-400">{{ $item['time'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-5">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Recently Added Interns</h2>
            <ul class="space-y-1">
                @foreach ($recentlyAdded as $item)
                    <li class="flex items-start gap-3 rounded-lg px-2 py-2 -mx-2 hover:bg-gray-50 transition">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600">
                            <x-heroicon-o-user-plus class="h-4 w-4" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                            <p class="mt-0.5 text-xs text-gray-400">Added {{ $item['date'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('dean.students') }}" class="mt-3 inline-flex items-center gap-1 text-sm font-medium text-gray-900 hover:underline">
                View all interns &rarr;
            </a>
        </div>
    </div>
@endsection
