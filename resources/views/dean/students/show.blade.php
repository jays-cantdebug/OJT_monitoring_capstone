@extends('layouts.dean')

@section('title', 'Student Intern Details')

@section('content')
    @if ($resetPassword)
        <div class="mb-4 rounded-xl bg-success/5 ring-1 ring-success/20 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-success/10 text-success">
                    <x-heroicon-o-check-circle class="h-5 w-5" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-success">Password reset for {{ $student->name }}.</p>
                    <p class="mt-1 text-sm text-success">
                        Share these credentials with the intern now — the password will not be shown again.
                    </p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <div class="rounded-lg bg-white/70 ring-1 ring-success/20 px-4 py-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-success">Email</p>
                    <p class="mt-0.5 font-mono text-sm text-success">{{ $resetPassword['email'] }}</p>
                </div>
                <div class="rounded-lg bg-white/70 ring-1 ring-success/20 px-4 py-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-success">New Password</p>
                    <p class="mt-0.5 font-mono text-sm text-success">{{ $resetPassword['password'] }}</p>
                </div>
            </div>
        </div>
    @endif

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
        <div class="flex shrink-0 items-center gap-4">
            <form method="POST" action="{{ route('dean.students.reset-password', $student) }}" onsubmit="return confirm('Generate a new temporary password for {{ $student->name }}? Their current password will stop working immediately.');">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center gap-1.5 rounded-md bg-gold/10 px-3 py-1.5 text-xs font-semibold text-gold hover:bg-gold/20"
                >
                    <x-heroicon-o-key class="h-4 w-4" />
                    Reset Password
                </button>
            </form>
            <a href="{{ route('dean.students') }}" class="text-xs font-medium text-navy hover:underline">
                &larr; Back to Student Interns
            </a>
        </div>
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
