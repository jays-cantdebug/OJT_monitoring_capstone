@extends('layouts.dean')

@section('title', 'Student Intern Details')

@section('content')
    @if ($resetPassword)
        <x-credentials-banner
            title="Password reset for {{ $student->name }}."
            :email="$resetPassword['email']"
            :password="$resetPassword['password']"
            password-label="New Password"
        />
    @endif

    <div class="mb-4 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            @if ($student->avatarUrl())
                <img src="{{ $student->avatarUrl() }}" alt="{{ $student->name }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
            @else
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                    {{ strtoupper(substr($student->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h2 class="font-bold text-navy">{{ $student->name }}</h2>
                <p class="text-sm text-black/60">{{ $student->email }}</p>
            </div>
        </div>
        <div class="flex shrink-0 items-center gap-4">
            <a
                href="{{ route('dean.students.edit', $student) }}"
                class="inline-flex items-center gap-1.5 rounded-md bg-navy/10 px-3 py-1.5 text-xs font-semibold text-navy hover:bg-navy/20"
            >
                <x-heroicon-o-pencil-square class="h-4 w-4" />
                Edit
            </a>
            <x-confirm-modal
                title="Reset password for {{ $student->name }}?"
                confirm-text="Reset Password"
                confirm-class="bg-danger hover:bg-danger/90"
                icon="key"
                icon-class="bg-danger/10 text-danger"
                :action="route('dean.students.reset-password', $student)"
            >
                <x-slot:trigger>
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md bg-gold/10 px-3 py-1.5 text-xs font-semibold text-gold hover:bg-gold/20"
                    >
                        <x-heroicon-o-key class="h-4 w-4" />
                        Reset Password
                    </button>
                </x-slot:trigger>

                Their current password will stop working immediately, and a new one will be generated and shown here once.
            </x-confirm-modal>
            <x-confirm-modal
                title="Delete {{ $student->name }}'s account?"
                confirm-text="Delete Account"
                confirm-class="bg-danger hover:bg-danger/90"
                icon="trash"
                icon-class="bg-danger/10 text-danger"
                method="DELETE"
                :action="route('dean.students.destroy', $student)"
            >
                <x-slot:trigger>
                    <button
                        type="button"
                        class="inline-flex items-center gap-1.5 rounded-md bg-danger/10 px-3 py-1.5 text-xs font-semibold text-danger hover:bg-danger/20"
                    >
                        <x-heroicon-o-trash class="h-4 w-4" />
                        Delete Account
                    </button>
                </x-slot:trigger>

                This removes {{ $student->name }} from the active roster. Their DTR history, reports, and audit trail are kept, and their account can no longer log in.
            </x-confirm-modal>
            <a href="{{ route('dean.students') }}" class="text-xs font-medium text-navy hover:underline">
                &larr; Back to Student Interns
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
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
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-light-gray">
            <p class="text-xs font-bold uppercase tracking-wide text-black/40">Verification</p>
            <p class="mt-1 text-lg font-bold text-navy">
                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $profile->is_verified ? 'bg-success/10 text-success' : 'bg-warning/10 text-warning' }}">
                    <span class="h-1.5 w-1.5 rounded-full {{ $profile->is_verified ? 'bg-success' : 'bg-warning' }}"></span>
                    {{ $profile->is_verified ? 'Verified' : 'Unverified' }}
                </span>
            </p>
            <p class="mt-1 text-xs text-black/40">Updated {{ $profile->updated_at?->format('M j, Y') ?? 'never' }}</p>
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

    <div class="mt-4 bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        <h3 class="text-sm font-semibold text-navy mb-4">Personal &amp; Guardian Information</h3>
        <p class="mb-4 text-xs text-black/40">Filled in by the student &mdash; read-only here.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Personal Email</p>
                <p class="mt-1 text-black">{{ $profile->personal_email ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Contact Number</p>
                <p class="mt-1 text-black">{{ $student->phone ?: 'Not yet provided' }}</p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Home Address</p>
                <p class="mt-1 text-black">{{ $profile->address ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Parent's Name</p>
                <p class="mt-1 text-black">{{ $profile->parent_name ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Parent's Contact</p>
                <p class="mt-1 text-black">{{ $profile->parent_contact ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Guardian's Name</p>
                <p class="mt-1 text-black">{{ $profile->guardian_name ?: 'Not yet provided' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold uppercase tracking-wide text-black/40">Guardian's Contact</p>
                <p class="mt-1 text-black">{{ $profile->guardian_contact ?: 'Not yet provided' }}</p>
            </div>
        </div>
    </div>

    <div class="mt-4 bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        <h3 class="text-sm font-semibold text-navy mb-4">Edit History</h3>
        @if ($auditLogs->isEmpty())
            <p class="text-sm text-black/60">No edits recorded yet.</p>
        @else
            <ul class="divide-y divide-light-gray">
                @foreach ($auditLogs as $log)
                    <li class="py-3">
                        <p class="text-sm text-black">
                            <span class="font-medium">{{ $log->actor->name }}</span>
                            {{ str($log->action->label())->lower() }}
                        </p>
                        @if (!empty($log->changes))
                            <ul class="mt-1 space-y-0.5 text-xs text-black/60">
                                @foreach ($log->changes as $field => $change)
                                    <li>
                                        <span class="font-medium">{{ str($field)->replace('_', ' ')->title() }}:</span>
                                        "{{ is_bool($change['from']) ? ($change['from'] ? 'Verified' : 'Unverified') : ($change['from'] ?? 'none') }}"
                                        &rarr;
                                        "{{ is_bool($change['to']) ? ($change['to'] ? 'Verified' : 'Unverified') : $change['to'] }}"
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        <p class="mt-1 text-xs text-black/40">{{ $log->created_at->format('M j, Y g:i A') }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
