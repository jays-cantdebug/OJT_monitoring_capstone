@extends('layouts.student')

@section('title', 'Accomplishment Reports')

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-success/5 px-4 py-3 text-sm text-success">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-6 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-warning/10 text-warning">
            <x-heroicon-o-document-text class="h-5 w-5" />
        </div>
        <div>
            <h2 class="font-bold text-navy">Daily Internship Journal &amp; Reports</h2>
            <p class="text-sm text-black/60">Submit daily journals detailing accomplishments, bottlenecks, and learnings. Reports are mapped with attendance timestamps automatically.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6 mb-6">
        <h3 class="text-xs font-bold uppercase tracking-wide text-black/40 mb-4">
            New Daily Log Entry &mdash; {{ today()->format('M j, Y') }}
        </h3>

        @if ($todayReport)
            <p class="text-sm text-black/60">
                You've already submitted today's report. Thanks!
            </p>
        @elseif (! $hasCompletedDtrEntryToday)
            <p class="text-sm text-black/60">
                A daily report must map with an existing clock session. Complete your Time In and Time Out for today before you can submit a report.
            </p>
        @else
            <form method="POST" action="{{ route('student.reports') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wide text-black/60">Activity Performed *</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        required
                        placeholder="Draft accomplishments, tasks cleared, features deployed, or assignments worked on today..."
                        class="mt-1.5 block w-full rounded-md border-0 bg-light-gray py-2.5 px-3 text-sm text-black placeholder:text-black/40 focus:ring-2 focus:ring-navy/40"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="photo" class="block text-xs font-bold uppercase tracking-wide text-black/60 mb-1.5">Photo Evidence / Attachment *</label>
                    <label for="photo" class="flex flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-light-gray bg-white px-6 py-8 text-center cursor-pointer hover:border-gold">
                        <x-heroicon-o-arrow-up-tray class="h-8 w-8 text-black/40" />
                        <span class="text-sm font-medium text-black/60">Drag and drop photo evidence here</span>
                        <span class="text-xs text-black/40">Supports PNG, JPG, GIF up to 5MB</span>
                        <span class="mt-1 rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-black ring-1 ring-light-gray">Browse Files</span>
                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept="image/*"
                            required
                            class="sr-only"
                        >
                    </label>
                    @error('photo')
                        <p class="mt-1 text-sm text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-success px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-success/90"
                >
<x-heroicon-o-check class="h-4 w-4" />
                    File Journal &amp; Queue Daily Report
                </button>
            </form>
        @endif
    </div>

    <div x-data="{ lightboxUrl: null }" class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        <h2 class="text-sm font-semibold text-navy mb-4">Past Reports</h2>

        @if ($reports->isEmpty())
            <p class="text-sm text-black/60">No reports submitted yet.</p>
        @else
            <ul class="divide-y divide-light-gray">
                @foreach ($reports as $report)
                    <li class="py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-black">{{ $report->report_date->format('M j, Y') }}</p>
                                <p class="mt-1 text-sm text-black/60">{{ $report->description }}</p>
                            </div>
                            <a href="{{ $report->photoUrl() }}" target="_blank" rel="noopener" @click.prevent="lightboxUrl = '{{ $report->photoUrl() }}'">
                                <img
                                    src="{{ $report->photoUrl() }}"
                                    alt="Photo evidence for {{ $report->report_date->format('M j, Y') }}"
                                    class="h-14 w-14 shrink-0 rounded-lg object-cover"
                                >
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <div
            x-show="lightboxUrl"
            x-cloak
            @keydown.escape.window="lightboxUrl = null"
            class="fixed inset-0 z-50 flex items-center justify-center p-4"
        >
            <div
                x-show="lightboxUrl"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-black/80"
                @click="lightboxUrl = null"
            ></div>

            <button
                type="button"
                @click="lightboxUrl = null"
                class="absolute top-4 right-4 flex h-10 w-10 items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20"
                aria-label="Close"
            >
                <x-heroicon-o-x-mark class="h-6 w-6" />
            </button>

            <img
                x-show="lightboxUrl"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                :src="lightboxUrl"
                class="relative max-h-full max-w-full rounded-lg object-contain"
                alt="Photo evidence"
            >
        </div>
    </div>
@endsection
