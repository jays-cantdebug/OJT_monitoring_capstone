@extends('layouts.student')

@section('title', 'My Internship')

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-briefcase class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-navy">My Internship Workplace Area</h2>
                <p class="text-sm text-black/60">Company details, supervisor directory, and internship document checklist.</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('student.attendance') }}" class="inline-flex items-center gap-1.5 rounded-md bg-navy px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-navy/90">
                <x-heroicon-o-calendar class="h-4 w-4" />
                View Log / List
            </a>
            <span class="inline-flex items-center gap-1.5 rounded-full bg-success/10 px-3 py-1.5 text-xs font-semibold text-success">
                <span class="h-2 w-2 rounded-full bg-success"></span>
                Device GPS Status — Location Enabled
            </span>
        </div>
    </div>

    @php
        $me = ['name' => auth()->user()->name, 'avatarUrl' => auth()->user()->avatarUrl()];
    @endphp
    <div
        x-data="myLocationMap({{ Illuminate\Support\Js::from($me) }}, {{ Illuminate\Support\Js::from($lastKnownLocation) }}, {{ $openEntry ? 'true' : 'false' }})"
        class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6 mb-6"
    >
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold uppercase tracking-wide text-navy">My Location</h3>
            @if ($openEntry)
                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-success">
                    <span class="h-1.5 w-1.5 rounded-full bg-success"></span>
                    Live &mdash; Real-Time Ping Updates
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold uppercase tracking-wide text-black/40">
                    <span class="h-1.5 w-1.5 rounded-full bg-light-gray"></span>
                    Off Duty &mdash; Last Known Location
                </span>
            @endif
        </div>
        <p x-show="error" x-cloak class="mb-3 text-xs text-danger" x-text="error"></p>
        <div class="relative h-96 rounded-lg bg-light-gray overflow-hidden" x-ref="map"></div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6 mb-6">
        <div class="flex flex-wrap items-start gap-4 mb-6">
            <div class="h-16 w-16 shrink-0 rounded-lg bg-light-gray flex items-center justify-center text-black/40">
                <x-heroicon-o-building-office class="h-8 w-8" />
            </div>
            <div class="flex-1 min-w-[200px]">
                <span class="text-[11px] font-bold uppercase tracking-wide text-success">Assigned Workplace</span>
                <h3 class="font-bold text-navy">{{ $profile->company_name ?: 'Not yet provided' }}</h3>
                <p class="text-sm text-black/60">{{ $profile->company_address ?: 'Add your company address below.' }}</p>
            </div>
        </div>

        @if (session('status'))
            <div class="mb-5 rounded-lg bg-success/5 px-4 py-3 text-sm text-success">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('student.internship-info') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="company_name" class="block text-xs font-bold uppercase tracking-wide text-black/60">Company Name</label>
                <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    value="{{ old('company_name', $profile->company_name) }}"
                    class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                >
                @error('company_name')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="company_address" class="block text-xs font-bold uppercase tracking-wide text-black/60">Company Address</label>
                <textarea
                    id="company_address"
                    name="company_address"
                    rows="2"
                    class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                >{{ old('company_address', $profile->company_address) }}</textarea>
                @error('company_address')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="supervisor_name" class="block text-xs font-bold uppercase tracking-wide text-black/60">Supervisor Name</label>
                    <input
                        type="text"
                        id="supervisor_name"
                        name="supervisor_name"
                        value="{{ old('supervisor_name', $profile->supervisor_name) }}"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('supervisor_name')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="supervisor_contact" class="block text-xs font-bold uppercase tracking-wide text-black/60">Supervisor Contact</label>
                    <input
                        type="text"
                        id="supervisor_contact"
                        name="supervisor_contact"
                        value="{{ old('supervisor_contact', $profile->supervisor_contact) }}"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('supervisor_contact')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-xs font-bold uppercase tracking-wide text-black/60">Internship Start Date</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="{{ old('start_date', optional($profile->start_date)->format('Y-m-d')) }}"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('start_date')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="end_date" class="block text-xs font-bold uppercase tracking-wide text-black/60">Internship End Date</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="{{ old('end_date', optional($profile->end_date)->format('Y-m-d')) }}"
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('end_date')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <button
                type="submit"
                class="inline-flex justify-center rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gold/90"
            >
                Save Changes
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6 mb-6">
        <h3 class="text-sm font-semibold text-navy mb-4">Internship Quick Actions</h3>
        <ul class="divide-y divide-light-gray">
            <li>
                <a href="{{ route('student.time') }}" class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg bg-success/5 hover:bg-success/10">
                    <span class="flex items-center gap-3 text-sm font-medium text-black">
                        <x-heroicon-o-clock class="h-5 w-5 text-success" />
                        Clock-In / Clock-Out Workplace Attendance
                    </span>
                    <x-heroicon-o-arrow-right class="h-4 w-4 text-black/40" />
                </a>
            </li>
            <li>
                <a href="{{ route('student.reports') }}" class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg hover:bg-light-gray/40">
                    <span class="flex items-center gap-3 text-sm font-medium text-black">
                        <x-heroicon-o-document-text class="h-5 w-5 text-black/40" />
                        Submit Daily Progress Report
                    </span>
                    <x-heroicon-o-arrow-right class="h-4 w-4 text-black/40" />
                </a>
            </li>
            <li>
                <a href="{{ route('student.attendance') }}" class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg hover:bg-light-gray/40">
                    <span class="flex items-center gap-3 text-sm font-medium text-black">
                        <x-heroicon-o-calendar class="h-5 w-5 text-black/40" />
                        View Attendance History Logs
                    </span>
                    <x-heroicon-o-arrow-right class="h-4 w-4 text-black/40" />
                </a>
            </li>
            <li>
                <span class="flex items-center justify-between gap-3 py-3 px-3 -mx-3 rounded-lg bg-gold/10">
                    <span class="flex items-center gap-3 text-sm font-medium text-black">
                        <x-heroicon-o-phone class="h-5 w-5 text-gold" />
                        Contact Assigned Supervisor
                    </span>
                    <span class="text-xs text-black/40">{{ $profile->supervisor_contact ?: 'Not yet provided' }}</span>
                </span>
            </li>
        </ul>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        <h3 class="text-sm font-semibold text-navy mb-4">Document Checklist</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ([
                ['title' => 'Endorsement Letter', 'status' => 'Approved', 'date' => 'Jun 10, 2026'],
                ['title' => 'Memorandum of Agreement', 'status' => 'Approved', 'date' => 'Jun 10, 2026'],
                ['title' => 'Parent Consent', 'status' => 'Approved', 'date' => 'Jun 10, 2026'],
                ['title' => 'Medical Certificate', 'status' => 'Approved', 'date' => 'Jun 12, 2026'],
                ['title' => 'Resume', 'status' => 'Approved', 'date' => 'Jun 12, 2026'],
                ['title' => 'Daily Time Record', 'status' => 'Pending', 'date' => null],
                ['title' => 'Final Report', 'status' => 'Pending', 'date' => null],
                ['title' => 'Certificate of Completion', 'status' => 'Pending', 'date' => null],
            ] as $doc)
                <div class="rounded-lg border border-light-gray p-4">
                    <div class="flex items-start justify-between gap-2">
                        <p class="text-sm font-medium text-black">{{ $doc['title'] }}</p>
                        <span @class([
                            'rounded-full px-2.5 py-1 text-[11px] font-semibold shrink-0',
                            'bg-success/10 text-success' => $doc['status'] === 'Approved',
                            'bg-gold/10 text-gold' => $doc['status'] === 'Submitted',
                            'bg-warning/10 text-warning' => $doc['status'] === 'Pending',
                        ])>{{ $doc['status'] }}</span>
                    </div>
                    @if ($doc['date'])
                        <p class="mt-2 text-xs text-black/40">Updated: {{ $doc['date'] }}</p>
                    @else
                        <a href="#" class="mt-2 inline-block text-xs font-medium text-navy hover:underline">Submit File</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
