@extends('layouts.dean')

@section('title', 'Reports / Export')

@section('content')
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-gray-100 p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                <x-heroicon-o-chart-bar class="h-5 w-5" />
            </div>
            <h2 class="text-sm font-semibold text-gray-900">Attendance Summary Report</h2>
        </div>

        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">From</label>
                <input type="date" class="mt-1.5 rounded-md border-0 bg-[#eef2f7] text-sm py-2.5 px-3 text-gray-900 focus:ring-2 focus:ring-blue-950/40">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">To</label>
                <input type="date" class="mt-1.5 rounded-md border-0 bg-[#eef2f7] text-sm py-2.5 px-3 text-gray-900 focus:ring-2 focus:ring-blue-950/40">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-gray-500">Student</label>
                <select class="mt-1.5 rounded-md border-0 bg-[#eef2f7] text-sm py-2.5 px-3 text-gray-900 focus:ring-2 focus:ring-blue-950/40">
                    <option>All Students</option>
                    <option>Juan Dela Cruz</option>
                    <option>Maria Reyes</option>
                </select>
            </div>
            <button type="button" class="rounded-md bg-gradient-to-r from-amber-500 to-orange-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:from-amber-600 hover:to-orange-600">
                Export PDF
            </button>
        </div>

        <p class="mt-4 text-xs text-gray-400">
            PDF generation isn't wired up yet — this button doesn't produce a file until Step 9.
        </p>
    </div>
@endsection
