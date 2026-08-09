@extends('layouts.dean')

@section('title', 'Create Student Intern Account')

@section('content')
    <div class="max-w-lg">
        <div class="mb-4 flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-user-plus class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-gray-900">Create Student Intern Account</h2>
                <p class="text-sm text-gray-500">The intern's account will be active immediately. A secure temporary password will be generated for you to share with them.</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-5">
            <form method="POST" action="{{ route('dean.students.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 focus:ring-2 focus:ring-blue-950/40"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 justify-center rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600"
                    >
                        <x-heroicon-o-check class="h-4 w-4" />
                        Create Account
                    </button>
                    <a href="{{ route('dean.students') }}" class="text-sm font-medium text-gray-600 hover:underline">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
