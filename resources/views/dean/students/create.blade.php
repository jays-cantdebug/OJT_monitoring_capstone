@extends('layouts.dean')

@section('title', 'Create Student Intern Account')

@section('content')
    <div class="max-w-lg">
        <div class="mb-4 flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-user-plus class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-navy">Create Student Intern Account</h2>
                <p class="text-sm text-black/60">The intern's account will be active immediately. A secure temporary password will be generated for you to share with them.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
            <form method="POST" action="{{ route('dean.students.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wide text-black/60">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wide text-black/60">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 justify-center rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gold/90"
                    >
                        <x-heroicon-o-check class="h-4 w-4" />
                        Create Account
                    </button>
                    <a href="{{ route('dean.students') }}" class="text-sm font-medium text-black/60 hover:underline">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
