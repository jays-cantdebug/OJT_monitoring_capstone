@extends('layouts.student')

@section('title', 'Notifications')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-bell class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-navy">Communication &amp; Notification Hub</h2>
                <p class="text-sm text-black/60">Real time system updates, compliance tracking alerts, and academic internship notifications.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('student.notifications.mark-all-read') }}">
            @csrf
            <button type="submit" class="shrink-0 inline-flex items-center gap-1.5 rounded-md bg-gold px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-gold/90">
                <x-heroicon-o-check class="h-4 w-4" />
                Mark All as Read
            </button>
        </form>
    </div>

    <div x-data="{
        activeTab: 'All',
        items: {{ $items->values()->toJson() }},
        get filtered() {
            if (this.activeTab === 'Unread') return this.items.filter(i => i.unread);
            if (this.activeTab === 'Compliance') return this.items.filter(i => i.compliance);
            return this.items;
        }
    }">
        <div class="mb-4 flex items-center gap-2 overflow-x-auto">
            <button type="button" @click="activeTab = 'All'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'All' ? 'bg-navy text-white' : 'bg-white text-black/60 ring-1 ring-light-gray hover:bg-light-gray/40'">
                All Notifications
            </button>
            <button type="button" @click="activeTab = 'Unread'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'Unread' ? 'bg-navy text-white' : 'bg-white text-black/60 ring-1 ring-light-gray hover:bg-light-gray/40'">
                Unread
            </button>
            <button type="button" @click="activeTab = 'Compliance'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'Compliance' ? 'bg-navy text-white' : 'bg-white text-black/60 ring-1 ring-light-gray hover:bg-light-gray/40'">
                Compliance &amp; Alerts
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray divide-y divide-light-gray">
            <template x-for="item in filtered" :key="item.id">
                <div class="p-5 flex items-start gap-4">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full"
                         :class="item.unread ? 'bg-gold/10 text-gold' : 'bg-light-gray text-black/40'">
                        <x-heroicon-o-bell class="h-4.5 w-4.5" />
                    </div>
                    <div>
                        <p class="text-sm" :class="item.unread ? 'font-medium text-black' : 'text-black/60'" x-text="item.text"></p>
                        <p class="mt-1 text-xs text-black/40" x-text="item.time"></p>
                    </div>
                </div>
            </template>

            <div x-show="filtered.length === 0" class="p-12 text-center">
<x-heroicon-o-bell class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">Clear Airspace!</p>
                <p class="mt-1 text-sm text-black/60">No notifications found matching the active filters.</p>
            </div>
        </div>
    </div>
@endsection
