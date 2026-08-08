@extends('layouts.dean')

@section('title', 'Pending Approvals')

@section('content')
    @if ($pendingStudents->isEmpty())
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-10 text-center text-sm text-gray-500">
            No pending registrations right now.
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-gray-500 border-b border-gray-100">
                        <th class="px-6 py-3 font-medium">Name</th>
                        <th class="px-6 py-3 font-medium">Email</th>
                        <th class="px-6 py-3 font-medium">Registered</th>
                        <th class="px-6 py-3 font-medium text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($pendingStudents as $student)
                        <tr>
                            <td class="px-6 py-4 text-gray-900">{{ $student->name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $student->email }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $student->created_at->format('M j, Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-end gap-2">
                                    <x-confirm-modal
                                        title="Approve this account?"
                                        confirm-text="Approve"
                                        confirm-class="bg-green-600 hover:bg-green-700"
                                        :action="route('dean.pending-approvals.approve', $student)"
                                    >
                                        <x-slot:trigger>
                                            <button type="button" class="rounded-md bg-green-50 px-3 py-1.5 text-xs font-medium text-green-700 hover:bg-green-100">
                                                Approve
                                            </button>
                                        </x-slot:trigger>
                                        {{ $student->name }} will be able to log in and access the system once approved.
                                    </x-confirm-modal>

                                    <x-confirm-modal
                                        title="Reject this account?"
                                        confirm-text="Reject"
                                        confirm-class="bg-red-600 hover:bg-red-700"
                                        :action="route('dean.pending-approvals.reject', $student)"
                                    >
                                        <x-slot:trigger>
                                            <button type="button" class="rounded-md bg-red-50 px-3 py-1.5 text-xs font-medium text-red-700 hover:bg-red-100">
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
        </div>
    @endif
@endsection
