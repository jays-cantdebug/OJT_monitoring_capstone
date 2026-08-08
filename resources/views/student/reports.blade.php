@extends('layouts.student')

@section('title', 'Accomplishment Reports')

@section('content')
    @if (session('status'))
        <div class="mb-6 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">
            Submit Today's Report &mdash; {{ today()->format('M j, Y') }}
        </h2>

        @if ($todayReport)
            <p class="text-sm text-gray-600">
                You've already submitted today's report. Thanks!
            </p>
        @elseif (! $hasCompletedDtrEntryToday)
            <p class="text-sm text-gray-600">
                Complete your Time In and Time Out for today before you can submit a report.
            </p>
        @else
            <form method="POST" action="{{ route('student.reports') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">What did you work on today?</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700">Photo Evidence</label>
                    <input
                        type="file"
                        id="photo"
                        name="photo"
                        accept="image/*"
                        required
                        class="mt-1 block w-full text-sm text-gray-600"
                    >
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="inline-flex justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
                >
                    Submit Report
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
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
                                    class="h-14 w-14 shrink-0 rounded-md object-cover"
                                >
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection
