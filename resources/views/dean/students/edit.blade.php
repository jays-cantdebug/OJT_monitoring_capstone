@extends('layouts.dean')

@section('title', 'Edit Student Intern')

@section('content')
    <div class="mb-4 flex items-start justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-pencil-square class="h-5 w-5" />
            </div>
            <div>
                <h2 class="font-bold text-navy">Edit Student Intern</h2>
                <p class="text-sm text-black/60">{{ $student->email }}</p>
            </div>
        </div>
        <a href="{{ route('dean.students.show', $student) }}" class="text-xs font-medium text-navy hover:underline shrink-0">
            &larr; Back to Student Intern Details
        </a>
    </div>

    <div class="max-w-lg bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-5">
        <form method="POST" action="{{ route('dean.students.update', $student) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-xs font-bold uppercase tracking-wide text-black/60">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $student->name) }}"
                    required
                    class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black focus:ring-2 focus:ring-navy/40"
                >
                @error('name')
                    <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <p class="block text-xs font-bold uppercase tracking-wide text-black/60 mb-1.5">Last Updated</p>
                <p class="text-sm text-black/60">{{ $profile->updated_at?->format('M j, Y g:i A') ?? 'Never' }}</p>
            </div>

            <div>
                <span class="block text-xs font-bold uppercase tracking-wide text-black/60 mb-1.5">Verification Status</span>
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input
                        type="checkbox"
                        name="is_verified"
                        value="1"
                        @checked(old('is_verified', $profile->is_verified))
                        class="rounded border-light-gray text-navy shadow-sm focus:ring-navy/40"
                    >
                    <span class="text-sm text-black">Mark as Verified</span>
                </label>
                <p class="mt-1 text-xs text-black/40">No workflow is attached to this yet &mdash; it's a simple status marker.</p>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button
                    type="submit"
                    class="inline-flex items-center gap-2 justify-center rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gold/90"
                >
                    <x-heroicon-o-check class="h-4 w-4" />
                    Save Changes
                </button>
                <a href="{{ route('dean.students.show', $student) }}" class="text-sm font-medium text-black/60 hover:underline">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
