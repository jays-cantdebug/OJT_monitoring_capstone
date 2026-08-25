@extends('layouts.admin')

@section('title', 'Deans')

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
                <h2 class="font-bold text-navy">Deans</h2>
                <p class="text-sm text-black/60">Dean accounts across every department.</p>
            </div>
        </div>
        <a
            href="{{ route('admin.deans.create') }}"
            class="shrink-0 inline-flex items-center gap-2 rounded-md bg-gold px-3.5 py-2 text-xs font-semibold text-white shadow-sm hover:bg-gold/90"
        >
            <x-heroicon-o-user-plus class="h-4 w-4" />
            Create Dean Account
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray overflow-hidden">
        @if ($deans->isEmpty())
            <div class="p-10 text-center">
                <x-heroicon-o-user-group class="mx-auto h-10 w-10 text-light-gray" />
                <p class="mt-3 text-sm font-bold text-navy">No Deans Yet</p>
                <p class="mt-1 text-sm text-black/60">Create the first account to get started.</p>
            </div>
        @else
            <div class="sm:hidden divide-y divide-light-gray">
                @foreach ($deans as $dean)
                    <div class="p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                {{ strtoupper(substr($dean->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium text-black">{{ $dean->name }}</p>
                                <p class="truncate text-xs text-black/40">{{ $dean->email }}</p>
                            </div>
                            <span class="shrink-0 inline-flex items-center rounded-full bg-navy/10 px-2.5 py-1 text-xs font-semibold text-navy">
                                {{ $dean->department->label() }}
                            </span>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <x-confirm-modal
                                title="Delete {{ $dean->name }}'s account?"
                                confirm-text="Delete Account"
                                confirm-class="bg-danger hover:bg-danger/90"
                                icon="trash"
                                icon-class="bg-danger/10 text-danger"
                                method="DELETE"
                                :action="route('admin.deans.destroy', $dean)"
                            >
                                <x-slot:trigger>
                                    <button
                                        type="button"
                                        class="inline-flex items-center gap-1.5 rounded-md bg-danger/10 px-3 py-1.5 text-xs font-semibold text-danger hover:bg-danger/20"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                        Delete
                                    </button>
                                </x-slot:trigger>

                                This removes {{ $dean->name }} from active Dean accounts. Their historical records and audit trail are kept.
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
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide">Department</th>
                        <th class="px-6 py-3 text-xs font-bold uppercase tracking-wide"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-light-gray">
                    @foreach ($deans as $dean)
                        <tr class="hover:bg-light-gray/40 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-gold/10 text-sm font-bold text-gold">
                                        {{ strtoupper(substr($dean->name, 0, 1)) }}
                                    </div>
                                    <span class="text-black font-medium">{{ $dean->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-black/40">{{ $dean->email }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-full bg-navy/10 px-2.5 py-1 text-xs font-semibold text-navy">
                                    {{ $dean->department->label() }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <x-confirm-modal
                                    title="Delete {{ $dean->name }}'s account?"
                                    confirm-text="Delete Account"
                                    confirm-class="bg-danger hover:bg-danger/90"
                                    icon="trash"
                                    icon-class="bg-danger/10 text-danger"
                                    method="DELETE"
                                    :action="route('admin.deans.destroy', $dean)"
                                >
                                    <x-slot:trigger>
                                        <button
                                            type="button"
                                            class="inline-flex items-center gap-1.5 rounded-md bg-danger/10 px-3 py-1.5 text-xs font-semibold text-danger hover:bg-danger/20"
                                        >
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                            Delete
                                        </button>
                                    </x-slot:trigger>

                                    This removes {{ $dean->name }} from active Dean accounts. Their historical records and audit trail are kept.
                                </x-confirm-modal>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
