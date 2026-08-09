@extends('layouts.student')

@section('title', 'Notifications')

@section('content')
    <div class="mb-6 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
            <x-heroicon-o-bell class="h-5 w-5" />
        </div>
        <div>
            <h2 class="font-bold text-gray-900">Communication &amp; Notification Hub</h2>
            <p class="text-sm text-gray-500">Real time system updates, compliance tracking alerts, and academic internship notifications.</p>
        </div>
    </div>

    <div x-data="{
        activeTab: 'All',
        items: [
            { text: 'Your accomplishment report for Aug 7 was received.', time: 'Today, 5:20 PM', unread: true, compliance: false },
            { text: 'Reminder: submit today\'s accomplishment report.', time: 'Today, 4:00 PM', unread: true, compliance: true },
            { text: 'Welcome to NORMI — your account is ready to use.', time: 'Aug 1, 9:12 AM', unread: false, compliance: false },
        ],
        get filtered() {
            if (this.activeTab === 'Unread') return this.items.filter(i => i.unread);
            if (this.activeTab === 'Compliance') return this.items.filter(i => i.compliance);
            return this.items;
        }
    }">
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
            <template x-for="item in filtered" :key="item.text">
                <div class="p-5 flex items-start gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                         :class="item.unread ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400'">
                        <x-heroicon-o-bell class="h-4.5 w-4.5" />
                    </div>
                    <div>
                        <p class="text-sm" :class="item.unread ? 'font-medium text-gray-900' : 'text-gray-600'" x-text="item.text"></p>
                        <p class="mt-1 text-xs text-gray-400" x-text="item.time"></p>
                    </div>
                </div>
            </template>

            <div x-show="filtered.length === 0" class="p-12 text-center">
<x-heroicon-o-bell class="mx-auto h-10 w-10 text-gray-300" />
                <p class="mt-3 text-sm font-bold text-gray-900">Clear Airspace!</p>
                <p class="mt-1 text-sm text-gray-500">No notifications found matching the active filters.</p>
            </div>
        </div>
    </div>
@endsection
