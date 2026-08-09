@extends('layouts.dean')

@section('title', 'Student Interns')

@section('content')
    @if (isset($created))
        <div class="mb-4 rounded-xl bg-success/5 ring-1 ring-success/20 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-success/10 text-success">
                    <x-heroicon-o-check-circle class="h-5 w-5" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-success">Account created for {{ $created['name'] }}.</p>
                    <p class="mt-1 text-sm text-success">
                        Share these credentials with the intern now — the password will not be shown again.
                    </p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <div class="rounded-lg bg-white/70 ring-1 ring-success/20 px-4 py-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-success">Email</p>
                    <p class="mt-0.5 font-mono text-sm text-success">{{ $created['email'] }}</p>
                </div>
                <div class="rounded-lg bg-white/70 ring-1 ring-success/20 px-4 py-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-success">Temporary Password</p>
                    <p class="mt-0.5 font-mono text-sm text-success">{{ $created['password'] }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-4 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-user-group class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-navy">Student Interns</h2>
                <p class="text-sm text-black/60">Accounts you've created and their current duty status.</p>
            </div>
        </div>
        <a
            href="{{ route('dean.students.create') }}"
            class="shrink-0 inline-flex items-center gap-2 rounded-md bg-gold px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-gold/90"
        >
            <x-heroicon-o-user-plus class="h-4 w-4" />
            Create Account
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray overflow-hidden">
        @if ($students->isEmpty())
            <div class="p-10 text-center">
                <x-heroicon-o-user-group class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">No Student Interns Yet</p>
                <p class="mt-1 text-sm text-black/60">Create the first account to get started.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-black/40 border-b border-light-gray bg-light-gray/40">
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Name</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Company</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light-gray">
                    @foreach ($students as $student)
                        <tr class="hover:bg-light-gray/40 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                        {{ strtoupper(substr($student['name'], 0, 1)) }}
                                    </div>
                                    <span class="text-black font-medium">{{ $student['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-black/40">{{ $student['company'] ?: 'Not yet provided' }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $student['onDuty'] ? 'bg-success/10 text-success' : 'bg-light-gray text-black/60' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $student['onDuty'] ? 'bg-success' : 'bg-light-gray' }}"></span>
                                    {{ $student['onDuty'] ? 'On Duty' : 'Off Duty' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('dean.students.show', $student['id']) }}" class="text-xs font-medium text-navy hover:underline">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
