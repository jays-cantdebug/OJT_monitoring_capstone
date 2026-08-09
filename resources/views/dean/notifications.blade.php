@extends('layouts.dean')

@section('title', 'Notifications')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-bell class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-gray-900">Communication &amp; Notification Hub</h2>
                <p class="text-sm text-gray-500">Real time system updates, compliance tracking alerts, and academic internship notifications.</p>
            </div>
        </div>
        <button type="button" class="shrink-0 inline-flex items-center gap-1.5 rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600">
            <x-heroicon-o-bolt class="h-4 w-4" />
            Simulate Trigger
        </button>
    </div>

    @php
        $items = [
            ['text' => 'New Student Intern account created: Pedro Ramos.', 'time' => 'Today, 9:10 AM', 'unread' => true, 'compliance' => false, 'type' => 'approval'],
            ['text' => 'Juan Dela Cruz submitted an accomplishment report.', 'time' => 'Yesterday, 5:22 PM', 'unread' => true, 'compliance' => false, 'type' => 'report'],
            ['text' => 'Maria Reyes did not submit a report for Aug 5.', 'time' => 'Aug 6, 8:00 AM', 'unread' => false, 'compliance' => true, 'type' => 'alert'],
        ];
        $typeMeta = [
            'approval' => ['icon' => 'user-plus', 'badge' => 'bg-amber-100 text-amber-600'],
            'report' => ['icon' => 'document-text', 'badge' => 'bg-blue-100 text-blue-600'],
            'alert' => ['icon' => 'exclamation-triangle', 'badge' => 'bg-red-100 text-red-600'],
        ];
        $counts = [
            'All' => count($items),
            'Unread' => collect($items)->where('unread', true)->count(),
            'Compliance' => collect($items)->where('compliance', true)->count(),
        ];
    @endphp

    <div x-data="{ activeTab: 'All', counts: @js($counts) }">
        <div class="mb-4 flex items-center gap-2 overflow-x-auto">
            <button type="button" @click="activeTab = 'All'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'All' ? 'bg-[#1a2332] text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50'">
                All Notifications
            </button>
            <button type="button" @click="activeTab = 'Unread'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'Unread' ? 'bg-[#1a2332] text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50'">
                Unread
            </button>
            <button type="button" @click="activeTab = 'Compliance'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'Compliance' ? 'bg-[#1a2332] text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50'">
                Compliance &amp; Alerts
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 divide-y divide-gray-100">
            @foreach ($items as $item)
                @php $meta = $typeMeta[$item['type']]; @endphp
                <div
                    x-show="activeTab === 'All' || (activeTab === 'Unread' && {{ $item['unread'] ? 'true' : 'false' }}) || (activeTab === 'Compliance' && {{ $item['compliance'] ? 'true' : 'false' }})"
                    class="flex items-start gap-4 p-5 border-l-[3px] {{ $item['unread'] ? 'border-amber-400' : 'border-transparent' }} hover:bg-gray-50 transition"
                >
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full {{ $item['unread'] ? $meta['badge'] : 'bg-gray-100 text-gray-400' }}">
                        <x-dynamic-component :component="'heroicon-o-'.$meta['icon']" class="h-4.5 w-4.5" />
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm {{ $item['unread'] ? 'font-semibold text-gray-900' : 'text-gray-600' }}">{{ $item['text'] }}</p>
                        <div class="mt-1 flex items-center gap-1.5">
                            @if ($item['unread'])
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                            @endif
                            <p class="text-xs text-gray-400">{{ $item['time'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            <div x-show="counts[activeTab] === 0" class="p-12 text-center">
                <x-heroicon-o-bell class="mx-auto h-10 w-10 text-gray-300" />
                <p class="mt-3 text-sm font-bold text-gray-900">Clear Airspace!</p>
                <p class="mt-1 text-sm text-gray-500">No notifications found matching the active filters.</p>
            </div>
        </div>
    </div>
@endsection
