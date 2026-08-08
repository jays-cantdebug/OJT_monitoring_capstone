@extends('layouts.student')

@section('title', 'Accomplishment Reports')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6 mb-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Submit Today's Report — Aug 8, 2026</h2>

        <form class="space-y-4">
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">What did you work on today?</label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                ></textarea>
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
            </div>

            <button
                type="submit"
                class="inline-flex justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
            >
                Submit Report
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Past Reports</h2>

        <ul class="divide-y divide-gray-100">
            <li class="py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Aug 7, 2026</p>
                        <p class="mt-1 text-sm text-gray-600">Assisted in preparing weekly inventory report and organized filing system.</p>
                    </div>
                    <div class="h-14 w-14 shrink-0 rounded-md bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                        Photo
                    </div>
                </div>
            </li>
            <li class="py-4">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-900">Aug 6, 2026</p>
                        <p class="mt-1 text-sm text-gray-600">Attended orientation with the accounting team and reviewed department SOPs.</p>
                    </div>
                    <div class="h-14 w-14 shrink-0 rounded-md bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                        Photo
                    </div>
                </div>
            </li>
        </ul>
    </div>
@endsection
