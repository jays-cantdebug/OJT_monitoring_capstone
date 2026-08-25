@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6 flex items-center gap-4 rounded-xl bg-white p-5 shadow-sm ring-1 ring-light-gray">
        @if (auth()->user()->avatarUrl())
            <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="h-11 w-11 shrink-0 rounded-full object-cover">
        @else
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold text-lg font-bold">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif
        <div>
            <p class="font-bold text-navy">{{ auth()->user()->name }}</p>
            <p class="text-sm text-black/60">{{ auth()->user()->department?->programLabel() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-light-gray">
            <div class="flex items-start justify-between">
                <div class="flex h-10 w-10 items-center justify-center rounded-full {{ $openEntry ? 'bg-success/10 text-success' : 'bg-light-gray text-black/40' }}">
                    <x-heroicon-o-map-pin class="h-5 w-5" />
                </div>
                @if ($openEntry)
                    <span class="rounded-full bg-success/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-wide text-success">Live</span>
                @endif
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wide text-black/40">Current Status</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $openEntry ? 'On Duty' : 'Off Duty' }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-light-gray">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-calendar class="h-5 w-5" />
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wide text-black/40">Days Logged This Month</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $daysLoggedThisMonth }}</p>
        </div>

        <div class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-light-gray">
            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-warning/10 text-warning">
                <x-heroicon-o-document-text class="h-5 w-5" />
            </div>
            <p class="mt-4 text-xs font-bold uppercase tracking-wide text-black/40">Reports Submitted</p>
            <p class="mt-1 text-2xl font-bold text-black/20">&mdash;</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6 mb-6">
        <h2 class="text-sm font-semibold text-navy mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <a href="{{ route('student.attendance') }}" class="flex items-center gap-3 rounded-lg bg-success/5 px-4 py-3 hover:bg-success/10">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-success/10 text-success">
                    <x-heroicon-o-calendar class="h-5 w-5" />
                </div>
                <span class="text-sm font-medium text-black">Attendance</span>
            </a>
            <a href="{{ route('student.reports') }}" class="flex items-center gap-3 rounded-lg bg-gold/5 px-4 py-3 hover:bg-gold/10">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                    <x-heroicon-o-document-text class="h-5 w-5" />
                </div>
                <span class="text-sm font-medium text-black">Daily Report</span>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
            <h2 class="text-sm font-semibold text-navy mb-3">Recent Attendance</h2>
            @if ($recentEntries->isEmpty())
                <p class="text-sm text-black/60">No attendance records yet.</p>
            @else
                <ul class="space-y-1">
                    @foreach ($recentEntries as $entry)
                        <li class="flex items-start gap-3 rounded-lg px-2 py-2 -mx-2 hover:bg-light-gray/40 transition">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $entry->time_out ? 'bg-gold/10 text-gold' : 'bg-success/10 text-success' }}">
                                <x-heroicon-o-clock class="h-4 w-4" />
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-black">{{ $entry->time_in->format('M j, Y') }}</p>
                                <p class="mt-0.5 text-xs text-black/40">
                                    {{ $entry->time_in->format('g:i A') }} – {{ $entry->time_out ? $entry->time_out->format('g:i A') : 'Still on duty' }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
            <h2 class="text-sm font-semibold text-navy mb-3">Recent Notifications</h2>
            @php
                $notifications = [
                    ['text' => 'Your accomplishment report for Aug 7 was received.', 'time' => 'Today, 5:22 PM', 'icon' => 'check-circle', 'badge' => 'bg-success/10 text-success'],
                    ['text' => "Reminder: submit today's accomplishment report.", 'time' => 'Today, 4:00 PM', 'icon' => 'document-text', 'badge' => 'bg-warning/10 text-warning'],
                    ['text' => 'Welcome to NORMI — your account is ready to use.', 'time' => 'Aug 1, 9:12 AM', 'icon' => 'shield-check', 'badge' => 'bg-gold/10 text-gold'],
                ];
            @endphp
            <ul class="space-y-1">
                @foreach ($notifications as $item)
                    <li class="flex items-start gap-3 rounded-lg px-2 py-2 -mx-2 hover:bg-light-gray/40 transition">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $item['badge'] }}">
                            <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-4 w-4" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm text-black">{{ $item['text'] }}</p>
                            <p class="mt-0.5 text-xs text-black/40">{{ $item['time'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
