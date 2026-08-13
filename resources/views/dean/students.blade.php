@extends('layouts.dean')

@section('title', 'Student Interns')

@section('content')
    @if (isset($created))
        <x-credentials-banner
            title="Account created for {{ $created['name'] }}."
            :email="$created['email']"
            :password="$created['password']"
            password-label="Temporary Password"
        />
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
            <div class="sm:hidden divide-y divide-light-gray">
                @foreach ($students as $student)
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                {{ strtoupper(substr($student['name'], 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-black">{{ $student['name'] }}</p>
                                <p class="truncate text-xs text-black/40">{{ $student['company'] ?: 'Not yet provided' }}</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $student['onDuty'] ? 'bg-success/10 text-success' : 'bg-light-gray text-black/60' }}">
                                <span class="h-1.5 w-1.5 rounded-full {{ $student['onDuty'] ? 'bg-success' : 'bg-light-gray' }}"></span>
                                {{ $student['onDuty'] ? 'On Duty' : 'Off Duty' }}
                            </span>
                        </div>
                        <a href="{{ route('dean.students.show', $student['id']) }}" class="mt-3 block rounded-md bg-light-gray/40 py-2 text-center text-xs font-medium text-navy hover:bg-light-gray/60">
                            View Details
                        </a>
                    </div>
                @endforeach
            </div>

            <table class="hidden w-full text-sm sm:table">
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
