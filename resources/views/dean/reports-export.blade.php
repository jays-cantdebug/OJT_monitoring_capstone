@extends('layouts.dean')

@section('title', 'Reports / Export')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-4">Attendance Summary Report</h2>

        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">From</label>
                <input type="date" class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">To</label>
                <input type="date" class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Student</label>
                <select class="mt-1 rounded-md border-gray-300 text-sm shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option>All Students</option>
                    <option>Juan Dela Cruz</option>
                    <option>Maria Reyes</option>
                </select>
            </div>
            <button type="button" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700">
                Export PDF
            </button>
        </div>

        <p class="mt-4 text-xs text-gray-400">
            PDF generation isn't wired up yet — this button doesn't produce a file until Step 9.
        </p>
    </div>
@endsection
