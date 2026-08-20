@extends('layouts.dean')

@section('title', 'Pending Approvals')

@section('content')
    <div class="mb-4 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-warning/10 text-warning">
            <x-heroicon-o-clock class="h-5 w-5" />
        </div>
        <div>
            <h2 class="font-bold text-navy">Pending Approvals</h2>
            <p class="text-sm text-black/60">Self-registered student accounts awaiting your approval.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray overflow-hidden">
        @if ($pendingStudents->isEmpty())
            <div class="p-10 text-center">
                <x-heroicon-o-clock class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">No Pending Registrations</p>
                <p class="mt-1 text-sm text-black/60">New self-registered accounts will appear here.</p>
            </div>
        @else
            <div class="sm:hidden divide-y divide-light-gray">
                @foreach ($pendingStudents as $student)
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                {{ strtoupper(substr($student->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-black">{{ $student->name }}</p>
                                <p class="truncate text-xs text-black/40">{{ $student->email }}</p>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-black/40">Registered {{ $student->created_at->format('M j, Y') }}</p>
                        <div class="mt-3 flex gap-2">
                            <x-confirm-modal
                                title="Approve this account?"
                                confirm-text="Approve"
                                confirm-class="bg-success hover:bg-success/90"
                                icon="check-circle"
                                icon-class="bg-success/10 text-success"
                                :action="route('dean.pending-approvals.approve', $student)"
                            >
                                <x-slot:trigger>
                                    <button type="button" class="w-full rounded-md bg-success/10 py-2 text-center text-xs font-semibold text-success hover:bg-success/20">
                                        Approve
                                    </button>
                                </x-slot:trigger>
                                {{ $student->name }} will be able to log in and access the system once approved.
                            </x-confirm-modal>
                            <x-confirm-modal
                                title="Reject this account?"
                                confirm-text="Reject"
                                confirm-class="bg-danger hover:bg-danger/90"
                                icon="x-circle"
                                icon-class="bg-danger/10 text-danger"
                                :action="route('dean.pending-approvals.reject', $student)"
                            >
                                <x-slot:trigger>
                                    <button type="button" class="w-full rounded-md bg-danger/10 py-2 text-center text-xs font-semibold text-danger hover:bg-danger/20">
                                        Reject
                                    </button>
                                </x-slot:trigger>
                                {{ $student->name }}'s registration will be marked as rejected. This can't be easily undone.
                            </x-confirm-modal>
                        </div>
                    </div>
                @endforeach
            </div>

            <table class="hidden w-full text-sm sm:table">
                <thead>
                    <tr class="text-left text-black/40 border-b border-light-gray bg-light-gray/40">
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Name</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Email</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Registered</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light-gray">
                    @foreach ($pendingStudents as $student)
                        <tr class="hover:bg-light-gray/40 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>
                                    <span class="text-black font-medium">{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-black/40">{{ $student->email }}</td>
                            <td class="px-6 py-4 text-black/40">{{ $student->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <x-confirm-modal
                                        title="Approve this account?"
                                        confirm-text="Approve"
                                        confirm-class="bg-success hover:bg-success/90"
                                        icon="check-circle"
                                        icon-class="bg-success/10 text-success"
                                        :action="route('dean.pending-approvals.approve', $student)"
                                    >
                                        <x-slot:trigger>
                                            <button type="button" class="rounded-md bg-success/10 px-3 py-1.5 text-xs font-semibold text-success hover:bg-success/20">
                                                Approve
                                            </button>
                                        </x-slot:trigger>
                                        {{ $student->name }} will be able to log in and access the system once approved.
                                    </x-confirm-modal>
                                    <x-confirm-modal
                                        title="Reject this account?"
                                        confirm-text="Reject"
                                        confirm-class="bg-danger hover:bg-danger/90"
                                        icon="x-circle"
                                        icon-class="bg-danger/10 text-danger"
                                        :action="route('dean.pending-approvals.reject', $student)"
                                    >
                                        <x-slot:trigger>
                                            <button type="button" class="rounded-md bg-danger/10 px-3 py-1.5 text-xs font-semibold text-danger hover:bg-danger/20">
                                                Reject
                                            </button>
                                        </x-slot:trigger>
                                        {{ $student->name }}'s registration will be marked as rejected. This can't be easily undone.
                                    </x-confirm-modal>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
