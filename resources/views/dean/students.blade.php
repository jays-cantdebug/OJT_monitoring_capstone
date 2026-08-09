@extends('layouts.dean')

@section('title', 'Student Interns')

@section('content')
    @if (isset($created))
        <div class="mb-4 rounded-xl bg-green-50 ring-1 ring-green-200 p-5">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                    <x-heroicon-o-check-circle class="h-5 w-5" />
                </div>
                <div>
                    <p class="text-sm font-semibold text-green-800">Account created for {{ $created['name'] }}.</p>
                    <p class="mt-1 text-sm text-green-700">
                        Share these credentials with the intern now — the password will not be shown again.
                    </p>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap gap-3">
                <div class="rounded-lg bg-white/70 ring-1 ring-green-200 px-4 py-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-green-600">Email</p>
                    <p class="mt-0.5 font-mono text-sm text-green-900">{{ $created['email'] }}</p>
                </div>
                <div class="rounded-lg bg-white/70 ring-1 ring-green-200 px-4 py-2.5">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-green-600">Temporary Password</p>
                    <p class="mt-0.5 font-mono text-sm text-green-900">{{ $created['password'] }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-4 flex flex-wrap items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-user-group class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-gray-900">Student Interns</h2>
                <p class="text-sm text-gray-500">Accounts you've created and their current duty status.</p>
            </div>
        </div>
        <a
            href="{{ route('dean.students.create') }}"
            class="shrink-0 inline-flex items-center gap-2 rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600"
        >
            <x-heroicon-o-user-plus class="h-4 w-4" />
            Create Account
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 overflow-hidden">
        @if ($students->isEmpty())
            <div class="p-10 text-center">
                <x-heroicon-o-user-group class="mx-auto h-10 w-10 text-gray-300" />
                <p class="mt-3 text-sm font-bold text-gray-900">No Student Interns Yet</p>
                <p class="mt-1 text-sm text-gray-500">Create the first account to get started.</p>
            </div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-400 border-b border-gray-100 bg-gray-50/60">
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Name</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Company</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Status</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($students as $student)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-amber-100 text-sm font-bold text-amber-700">
                                        {{ strtoupper(substr($student['name'], 0, 1)) }}
                                    </div>
                                    <span class="text-gray-900 font-medium">{{ $student['name'] }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-400">Not yet provided</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $student['onDuty'] ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500' }}">
                                    <span class="h-1.5 w-1.5 rounded-full {{ $student['onDuty'] ? 'bg-green-500' : 'bg-gray-400' }}"></span>
                                    {{ $student['onDuty'] ? 'On Duty' : 'Off Duty' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-xs font-medium text-blue-950 hover:underline">View Details</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
