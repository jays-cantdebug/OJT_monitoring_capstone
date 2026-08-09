@extends('layouts.dean')

@section('title', 'Student Intern Details')

@section('content')
    <div class="mb-4 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                {{ strtoupper(substr($student->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="font-bold text-navy">{{ $student->name }}</h2>
                <p class="text-sm text-black/60">{{ $student->email }}</p>
            </div>
        </div>
        <a href="{{ route('dean.students') }}" class="shrink-0 text-xs font-medium text-navy hover:underline">
            &larr; Back to Student Interns
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <p class="text-xs font-bold uppercase tracking-wide text-black/40">Duty Status</p>
            <p class="mt-1 text-lg font-bold text-navy">
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $onDuty ? 'bg-success/10 text-success' : 'bg-light-gray text-black/60' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $onDuty ? 'bg-success' : 'bg-light-gray' }}"></span>
                    {{ $onDuty ? 'On Duty' : 'Off Duty' }}
                </span>
            </p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <p class="text-xs font-bold uppercase tracking-wide text-black/40">Total Hours Logged</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $totalHoursLogged }} hrs</p>
        </div>
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <p class="text-xs font-bold uppercase tracking-wide text-black/40">Reports Submitted</p>
            <p class="mt-1 text-2xl font-bold text-navy">{{ $reportsSubmitted }}</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        <h3 class="text-sm font-semibold text-navy mb-4">Internship Info</h3>
        <p class="mb-4 text-xs text-black/40">Filled in by the student &mdash; read-only here.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Company Name</p>
                <p class="mt-1 text-black">{{ $profile->company_name ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Company Address</p>
                <p class="mt-1 text-black">{{ $profile->company_address ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Supervisor Name</p>
                <p class="mt-1 text-black">{{ $profile->supervisor_name ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Supervisor Contact</p>
                <p class="mt-1 text-black">{{ $profile->supervisor_contact ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Internship Start Date</p>
                <p class="mt-1 text-black">{{ optional($profile->start_date)->format('M j, Y') ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Internship End Date</p>
                <p class="mt-1 text-black">{{ optional($profile->end_date)->format('M j, Y') ?: 'Not yet provided' }}</p>
            </div>
        </div>
    </div>
@endsection
