@extends('layouts.guest')

@section('title', 'Pending Approval')

@section('content')
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100">
            <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
            </svg>
        </div>

        <h2 class="text-lg font-semibold text-gray-900">Account Pending Approval</h2>
        <p class="mt-2 text-sm text-gray-600">
            Your registration was received. A Dean needs to approve your account before you can access the system.
            You'll be able to log in as soon as that happens — no further action is needed from you right now.
        </p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="text-sm font-medium text-gray-900 hover:underline">
                Log Out
            </button>
        </form>
    </div>
@endsection
