@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6 flex items-center gap-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-600 text-lg font-bold">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div>
            <p class="font-bold text-gray-900">{{ auth()->user()->name }}</p>
            <p class="text-sm text-gray-500">BS in Computer Science</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $openEntry ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400' }}">
                    <x-heroicon-o-map-pin class="h-5 w-5" />
                </div>
                @if ($openEntry)
                    <span class="rounded-full bg-green-100 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-green-600">Live</span>
                @endif
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wide text-gray-400">Current Status</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $openEntry ? 'On Duty' : 'Off Duty' }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-calendar class="h-5 w-5" />
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wide text-gray-400">Days Logged This Month</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ $daysLoggedThisMonth }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-gray-100">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                <x-heroicon-o-document-text class="h-5 w-5" />
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wide text-gray-400">Reports Submitted</p>
            <p class="mt-1 text-2xl font-bold text-gray-400">&mdash;</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Recent Attendance</h2>
            @if ($recentEntries->isEmpty())
                <p class="text-sm text-gray-500">No attendance records yet.</p>
            @else
                <ul class="space-y-1">
                    @foreach ($recentEntries as $entry)
                        <li class="flex items-start gap-3 rounded-lg px-2 py-2 -mx-2 hover:bg-gray-50 transition">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $entry->time_out ? 'bg-blue-100 text-blue-600' : 'bg-green-100 text-green-600' }}">
                                <x-heroicon-o-clock class="h-4 w-4" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ $entry->time_in->format('M j, Y') }}</p>
                                <p class="mt-0.5 text-xs text-gray-400">
                                    {{ $entry->time_in->format('g:i A') }} – {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-3">Recent Notifications</h2>
            @php
                $notifications = [
                    ['text' => 'Your accomplishment report for Aug 7 was received.', 'time' => 'Today, 5:22 PM', 'icon' => 'check-circle', 'badge' => 'bg-green-100 text-green-600'],
                    ['text' => "Reminder: submit today's accomplishment report.", 'time' => 'Today, 4:00 PM', 'icon' => 'document-text', 'badge' => 'bg-amber-100 text-amber-600'],
                    ['text' => 'Welcome to NORMI — your account is ready to use.', 'time' => 'Aug 1, 9:12 AM', 'icon' => 'shield-check', 'badge' => 'bg-blue-100 text-blue-600'],
                ];
            @endphp
            <ul class="space-y-1">
                @foreach ($notifications as $item)
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
    </div>
@endsection
