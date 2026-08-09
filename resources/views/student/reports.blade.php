@extends('layouts.student')

@section('title', 'Accomplishment Reports')

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-6 flex items-start gap-3">
        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-yellow-100 text-yellow-600">
            <x-heroicon-o-document-text class="h-5 w-5" />
        </div>
        <div>
            <h2 class="font-bold text-gray-900">Daily Internship Journal &amp; Reports</h2>
            <p class="text-sm text-gray-500">Submit daily journals detailing accomplishments, bottlenecks, and learnings. Reports are mapped with attendance timestamps automatically.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6 mb-6">
        <h3 class="text-xs font-bold uppercase tracking-wide text-gray-400 mb-4">
            New Daily Log Entry &mdash; {{ today()->format('M j, Y') }}
        </h3>

        @if ($todayReport)
            <p class="text-sm text-gray-600">
                You've already submitted today's report. Thanks!
            </p>
        @elseif (! $hasCompletedDtrEntryToday)
            <p class="text-sm text-gray-600">
                A daily report must map with an existing clock session. Complete your Time In and Time Out for today before you can submit a report.
            </p>
        @else
            <form method="POST" action="{{ route('student.reports') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wide text-gray-500">Activity Performed *</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        required
                        placeholder="Draft accomplishments, tasks cleared, features deployed, or assignments worked on today..."
                        class="mt-1.5 block w-full rounded-md border-0 bg-[#eef2f7] py-2.5 px-3 text-sm text-gray-900 placeholder:text-gray-400 focus:ring-2 focus:ring-blue-950/40"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="photo" class="block text-xs font-bold uppercase tracking-wide text-gray-500 mb-1.5">Photo Evidence / Attachment *</label>
                    <label for="photo" class="flex flex-col items-center justify-center gap-2 rounded-lg border-2 border-dashed border-gray-200 bg-[#f5f6f8] px-6 py-8 text-center cursor-pointer hover:border-amber-400">
                        <x-heroicon-o-arrow-up-tray class="h-8 w-8 text-gray-400" />
                        <span class="text-sm font-medium text-gray-600">Drag and drop photo evidence here</span>
                        <span class="text-xs text-gray-400">Supports PNG, JPG, GIF up to 5MB</span>
                        <span class="mt-1 rounded-md bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 ring-1 ring-gray-200">Browse Files</span>
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
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-700"
                >
<x-heroicon-o-check class="h-4 w-4" />
                    File Journal &amp; Queue Daily Report
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Past Reports</h2>

        @if ($reports->isEmpty())
            <p class="text-sm text-gray-500">No reports submitted yet.</p>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($reports as $report)
                    <li class="py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $report->report_date->format('M j, Y') }}</p>
                                <p class="mt-1 text-sm text-gray-600">{{ $report->description }}</p>
                            </div>
                            <a href="{{ Storage::disk('public')->url($report->photo_path) }}" target="_blank" rel="noopener">
                                <img
                                    src="{{ Storage::disk('public')->url($report->photo_path) }}"
                                    alt="Photo evidence for {{ $report->report_date->format('M j, Y') }}"
                                    class="h-14 w-14 shrink-0 rounded-lg object-cover"
                                >
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
