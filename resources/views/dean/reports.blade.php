@extends('layouts.dean')

@section('title', 'Accomplishment Reports')

@section('content')
    @php
        $reports = [
            ['name' => 'Juan Dela Cruz', 'date' => 'Aug 7, 2026', 'summary' => 'Assisted in preparing weekly inventory report and organized filing system.', 'status' => 'Approved'],
            ['name' => 'Maria Reyes', 'date' => 'Aug 7, 2026', 'summary' => 'Reviewed drainage survey data and updated the shared spreadsheet.', 'status' => 'Approved'],
            ['name' => 'Liza Fernandez', 'date' => 'Aug 6, 2026', 'summary' => 'Shadowed nursing staff during morning rounds and logged patient intake notes.', 'status' => 'Pending'],
        ];
        $pendingCount = collect($reports)->where('status', 'Pending')->count();
        $approvedCount = collect($reports)->where('status', 'Approved')->count();
    @endphp

    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                <x-heroicon-o-document-text class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-gray-900">Intern Daily Journals Evaluation</h2>
                <p class="text-sm text-gray-500">Audit activity details, problems encountered, and verified photo evidence submitted by active interns.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <span class="rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-700">Pending: {{ $pendingCount }}</span>
            <span class="rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">Approved: {{ $approvedCount }}</span>
        </div>
    </div>

    <div x-data="{
        activeTab: 'All',
        reports: {{ collect($reports)->values()->toJson() }},
        get filtered() {
            return this.activeTab === 'All' ? this.reports : this.reports.filter(r => r.status === this.activeTab);
        }
    }">
        <div class="relative mb-4">
            <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <x-heroicon-o-magnifying-glass class="h-4 w-4" />
            </span>
            <input type="text" placeholder="Search student name, email, or task..." class="w-full rounded-md border-0 bg-[#eef2f7] py-2.5 pl-9 pr-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40">
        </div>

        <div class="mb-4 flex items-center gap-2 overflow-x-auto">
            <button type="button" @click="activeTab = 'All'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'All' ? 'bg-[#1a2332] text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50'">
                All ({{ count($reports) }})
            </button>
            <button type="button" @click="activeTab = 'Pending'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'Pending' ? 'bg-[#1a2332] text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50'">
                Pending ({{ $pendingCount }})
            </button>
            <button type="button" @click="activeTab = 'Approved'" class="shrink-0 rounded-full px-4 py-2 text-xs font-semibold transition" :class="activeTab === 'Approved' ? 'bg-[#1a2332] text-white' : 'bg-white text-gray-600 ring-1 ring-gray-200 hover:bg-gray-50'">
                Approved ({{ $approvedCount }})
            </button>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 divide-y divide-gray-100">
            <template x-for="report in filtered" :key="report.name + report.date">
                <div class="p-6 flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
                            <x-heroicon-o-document-text class="h-5 w-5" />
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">
                                <span x-text="report.name"></span>
                                <span class="font-normal text-gray-400" x-text="'— ' + report.date"></span>
                            </p>
                            <p class="mt-1 text-sm text-gray-600" x-text="report.summary"></p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <span class="rounded-full px-2.5 py-1 text-[11px] font-semibold"
                              :class="report.status === 'Approved' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700'"
                              x-text="report.status"></span>
                        <div class="h-14 w-14 rounded-lg bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                            Photo
                        </div>
                        <a href="#" class="text-xs font-medium text-blue-950 hover:underline">View</a>
                    </div>
                </div>
            </template>

            <div x-show="filtered.length === 0" class="p-12 text-center">
                <x-heroicon-o-document-text class="mx-auto h-10 w-10 text-gray-300" />
                <p class="mt-3 text-sm font-bold text-gray-900">No matching journals found</p>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your filters or wait for student uploads.</p>
            </div>
        </div>
    </div>

    <div class="mt-6 rounded-xl bg-[#0d1420] p-5 font-mono text-xs text-green-400 shadow-sm">
        <p class="text-gray-400"># Dean Reports MySQL Console</p>
        <p class="mt-2">CONNECTION: root@mysql-dean-cluster | SSL: Active</p>
        <p class="mt-1 text-gray-500">// Decorative console — no live database connection is displayed here.</p>
    </div>
@endsection
