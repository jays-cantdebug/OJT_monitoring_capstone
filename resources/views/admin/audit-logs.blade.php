@extends('layouts.admin')

@section('title', 'Audit Logs')

@section('content')
    <div class="mb-4 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
            <x-heroicon-o-clipboard-document-list class="h-5 w-5" />
        </div>
        <div>
            <h2 class="font-bold text-navy">Audit Logs</h2>
            <p class="text-sm text-black/60">System-wide activity across every department — logins, account creation, approvals, and profile changes.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        @if ($logs->isEmpty())
            <p class="text-sm text-black/60">No activity recorded yet.</p>
        @else
            <ul class="divide-y divide-light-gray">
                @foreach ($logs as $log)
                    <x-audit-log-entry :log="$log" />
                @endforeach
            </ul>

            <div class="mt-4">
                {{ $logs->links() }}
            </div>
        @endif
    </div>
@endsection
