@extends('layouts.guest')

@section('title', 'Pending Approval')

@section('content')
    <div class="text-center">
        <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-warning/10 text-warning">
            <x-heroicon-o-clock class="h-6 w-6" />
        </div>

        <h2 class="text-lg font-bold text-navy">Account Pending Approval</h2>
        <p class="mt-2 text-sm text-black/60">
            Your registration was received. A Dean needs to approve your account before you can access the system.
            You'll be able to log in as soon as that happens — no further action is needed from you right now.
        </p>

        <form method="POST" action="{{ route('logout') }}" class="mt-6">
            @csrf
            <button type="submit" class="text-sm font-medium text-navy hover:underline">
                Log Out
            </button>
        </form>
    </div>
@endsection
