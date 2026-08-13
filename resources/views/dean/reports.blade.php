@extends('layouts.dean')

@section('title', 'Accomplishment Reports')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-warning/10 text-warning">
                <x-heroicon-o-document-text class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-navy">Intern Daily Journals Evaluation</h2>
                <p class="text-sm text-black/60">Audit activity details, problems encountered, and verified photo evidence submitted by active interns. Review-only &mdash; no approval action is required.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <span class="rounded-full bg-gold/10 px-3 py-1.5 text-xs font-semibold text-gold">Total Reports: {{ $totalCount }}</span>
            <span class="rounded-full bg-success/10 px-3 py-1.5 text-xs font-semibold text-success">Submitted Today: {{ $submittedTodayCount }}</span>
        </div>
    </div>

    <div x-data="{
        activeTab: 'All',
        search: '',
        lightboxUrl: null,
        reports: {{ $reports->values()->toJson() }},
        isToday(report) {
            return report.dateIso === new Date().toISOString().slice(0, 10);
        },
        isThisWeek(report) {
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 6);
            return new Date(report.dateIso) >= new Date(weekAgo.toISOString().slice(0, 10));
        },
        get filtered() {
            return this.reports
                .filter(r => this.activeTab === 'All' || (this.activeTab === 'Today' && this.isToday(r)) || (this.activeTab === 'This Week' && this.isThisWeek(r)))
                .filter(r => !this.search || r.name.toLowerCase().includes(this.search.toLowerCase()) || r.summary.toLowerCase().includes(this.search.toLowerCase()));
        },
        get todayCount() {
            return this.reports.filter(r => this.isToday(r)).length;
        },
        get weekCount() {
            return this.reports.filter(r => this.isThisWeek(r)).length;
        },
    }">
        <div class="relative mb-4">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-black/40">
                <x-heroicon-o-magnifying-glass class="h-4 w-4" />
            </span>
            <input type="text" x-model="search" placeholder="Search student name or task..." class="w-full rounded-md border-0 bg-light-gray py-2.5 pl-9 pr-3 text-sm text-black focus:ring-2 focus:ring-navy/40">
        </div>

        <div class="mb-4 flex items-center gap-2 overflow-x-auto">
            <button type="button" @click="activeTab = 'All'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'All' ? 'bg-navy text-white' : 'bg-white text-black/60 ring-1 ring-light-gray hover:bg-light-gray/40'">
                All (<span x-text="reports.length"></span>)
            </button>
            <button type="button" @click="activeTab = 'Today'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'Today' ? 'bg-navy text-white' : 'bg-white text-black/60 ring-1 ring-light-gray hover:bg-light-gray/40'">
                Today (<span x-text="todayCount"></span>)
            </button>
            <button type="button" @click="activeTab = 'This Week'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'This Week' ? 'bg-navy text-white' : 'bg-white text-black/60 ring-1 ring-light-gray hover:bg-light-gray/40'">
                This Week (<span x-text="weekCount"></span>)
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray divide-y divide-light-gray">
            <template x-for="report in filtered" :key="report.name + report.dateIso">
                <div class="p-6 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-warning/10 text-warning">
                            <x-heroicon-o-document-text class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-black">
                                <span x-text="report.name"></span>
                                <span class="font-normal text-black/40" x-text="'— ' + report.date"></span>
                            </p>
                            <p class="mt-1 text-sm text-black/60" x-text="report.summary"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a :href="report.photoUrl" target="_blank" rel="noopener" @click.prevent="lightboxUrl = report.photoUrl" class="h-14 w-14 shrink-0 rounded-lg bg-light-gray overflow-hidden block">
                            <img :src="report.photoUrl" class="h-full w-full object-cover" alt="Photo evidence">
                        </a>
                        <a :href="report.photoUrl" target="_blank" rel="noopener" @click.prevent="lightboxUrl = report.photoUrl" class="text-xs font-medium text-navy hover:underline">View</a>
                    </div>
                </div>
            </template>

            <div x-show="filtered.length === 0" class="p-12 text-center">
                <x-heroicon-o-document-text class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">No matching journals found</p>
                <p class="mt-1 text-sm text-black/60">Try adjusting your filters or wait for student uploads.</p>
            </div>
        </div>

        <x-photo-lightbox />
    </div>
@endsection
