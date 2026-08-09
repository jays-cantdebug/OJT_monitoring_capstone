@extends('layouts.student')

@section('title', 'My Internship')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-briefcase class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-gray-900">My Internship Workplace Area</h2>
                <p class="text-sm text-gray-500">Company details, supervisor directory, and internship document checklist.</p>
            </div>
        </div>
        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700 shrink-0">
            <span class="h-2 w-2 rounded-full bg-green-500"></span>
            Device GPS Status — Location Enabled
        </span>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 mb-6">
        <div class="flex flex-wrap items-start gap-4 mb-6">
            <div class="h-16 w-16 shrink-0 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400">
                <x-heroicon-o-building-office class="h-8 w-8" />
            </div>
            <div class="flex-1 min-w-[200px]">
                <span class="text-[11px] font-bold uppercase tracking-wide text-green-600">Assigned Workplace</span>
                <h3 class="font-bold text-gray-900">Northern Mindanao Data Solutions Inc.</h3>
                <p class="text-sm text-gray-500">Corrales Ave, Cagayan de Oro City, Misamis Oriental</p>
            </div>
            <span class="rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-700 shrink-0">In Progress</span>
        </div>

        <form class="space-y-5">
            <div>
                <label for="company_name" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Company Name</label>
                <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    value="Northern Mindanao Data Solutions Inc."
                    class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                >
            </div>

            <div>
                <label for="company_address" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Company Address</label>
                <textarea
                    id="company_address"
                    name="company_address"
                    rows="2"
                    class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                >Corrales Ave, Cagayan de Oro City, Misamis Oriental</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="supervisor_name" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Supervisor Name</label>
                    <input
                        type="text"
                        id="supervisor_name"
                        name="supervisor_name"
                        value="Engr. Maria Santos"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>
                <div>
                    <label for="supervisor_contact" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Supervisor Contact</label>
                    <input
                        type="text"
                        id="supervisor_contact"
                        name="supervisor_contact"
                        value="0917 123 4567"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Internship Start Date</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="2026-06-15"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Internship End Date</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="2026-10-15"
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="inline-flex justify-center rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600"
            >
                Save Changes
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 mb-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Internship Quick Actions</h3>
        <ul class="divide-y divide-gray-100">
            <li>
                <a href="{{ route('student.time') }}" class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg bg-green-50 hover:bg-green-100">
                    <span class="flex items-center gap-3 text-sm font-medium text-gray-900">
                        <x-heroicon-o-clock class="h-5 w-5 text-green-600" />
                        Clock-In / Clock-Out Workplace Attendance
                    </span>
                    <x-heroicon-o-arrow-right class="h-4 w-4 text-gray-400" />
                </a>
            </li>
            <li>
                <a href="{{ route('student.reports') }}" class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg hover:bg-gray-50">
                    <span class="flex items-center gap-3 text-sm font-medium text-gray-900">
                        <x-heroicon-o-document-text class="h-5 w-5 text-gray-400" />
                        Submit Daily Progress Report
                    </span>
                    <x-heroicon-o-arrow-right class="h-4 w-4 text-gray-400" />
                </a>
            </li>
            <li>
                <a href="{{ route('student.attendance') }}" class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg hover:bg-gray-50">
                    <span class="flex items-center gap-3 text-sm font-medium text-gray-900">
                        <x-heroicon-o-calendar class="h-5 w-5 text-gray-400" />
                        View Attendance History Logs
                    </span>
                    <x-heroicon-o-arrow-right class="h-4 w-4 text-gray-400" />
                </a>
            </li>
            <li>
                <span class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg bg-blue-50/60">
                    <span class="flex items-center gap-3 text-sm font-medium text-gray-900">
                        <x-heroicon-o-phone class="h-5 w-5 text-blue-500" />
                        Contact Assigned Supervisor
                    </span>
                    <span class="text-xs text-gray-400">0917 123 4567</span>
                </span>
            </li>
        </ul>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Document Checklist</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ([
                ['title' => 'Endorsement Letter', 'status' => 'Approved', 'date' => 'Jun 10, 2026'],
                ['title' => 'Memorandum of Agreement', 'status' => 'Approved', 'date' => 'Jun 10, 2026'],
                ['title' => 'Parent Consent', 'status' => 'Approved', 'date' => 'Jun 10, 2026'],
                ['title' => 'Medical Certificate', 'status' => 'Approved', 'date' => 'Jun 12, 2026'],
                ['title' => 'Resume', 'status' => 'Approved', 'date' => 'Jun 12, 2026'],
                ['title' => 'Daily Time Record', 'status' => 'Pending', 'date' => null],
                ['title' => 'Weekly Reports', 'status' => 'Submitted', 'date' => 'Aug 3, 2026'],
                ['title' => 'Final Report', 'status' => 'Pending', 'date' => null],
                ['title' => 'Certificate of Completion', 'status' => 'Pending', 'date' => null],
            ] as $doc)
                <div class="rounded-lg border border-gray-100 p-4">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-medium text-gray-900">{{ $doc['title'] }}</p>
                        <span @class([
                            'rounded-full px-2.5 py-1 text-[11px] font-semibold shrink-0',
                            'bg-green-100 text-green-700' => $doc['status'] === 'Approved',
                            'bg-blue-100 text-blue-700' => $doc['status'] === 'Submitted',
                            'bg-amber-100 text-amber-700' => $doc['status'] === 'Pending',
                        ])>{{ $doc['status'] }}</span>
                    </div>
                    @if ($doc['date'])
                        <p class="mt-2 text-xs text-gray-400">Updated: {{ $doc['date'] }}</p>
                    @else
                        <a href="#" class="mt-2 inline-block text-xs font-medium text-blue-950 hover:underline">Submit File</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
