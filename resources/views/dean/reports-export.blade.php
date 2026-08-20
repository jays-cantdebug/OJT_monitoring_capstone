@extends('layouts.dean')

@section('title', 'Reports / Export')

@section('content')
    <div class="bg-white rounded-xl shadow-sm ring-1 ring-light-gray p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gold/10 text-gold">
                <x-heroicon-o-chart-bar class="h-5 w-5" />
            </div>
            <h2 class="text-sm font-semibold text-navy">Attendance Summary Report</h2>
        </div>

        <form method="GET" action="{{ route('dean.reports-export') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-black/60">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="mt-1.5 rounded-md border-0 bg-light-gray text-sm py-2.5 px-3 text-black focus:ring-2 focus:ring-navy/40">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-black/60">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="mt-1.5 rounded-md border-0 bg-light-gray text-sm py-2.5 px-3 text-black focus:ring-2 focus:ring-navy/40">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-black/60">Student</label>
                <select name="student_id" class="mt-1.5 rounded-md border-0 bg-light-gray text-sm py-2.5 px-3 text-black focus:ring-2 focus:ring-navy/40">
                    <option value="">All Students</option>
                    @foreach ($students as $student)
                        <option value="{{ $student->id }}" @selected(request('student_id') == $student->id)>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-black ring-1 ring-light-gray hover:bg-light-gray/40">
                Preview
            </button>
            <button type="submit" formaction="{{ route('dean.reports-export.download') }}" class="rounded-md bg-gold px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gold/90">
                Export PDF
            </button>
            <button type="submit" formaction="{{ route('dean.reports-export.download-csv') }}" class="rounded-md bg-white px-4 py-2.5 text-sm font-semibold text-navy ring-1 ring-navy/20 hover:bg-light-gray/40">
                Export CSV
            </button>
        </form>

        @error('date_to')
            <p class="mt-2 text-sm text-danger">{{ $message }}</p>
        @enderror
    </div>

    <div class="mt-6 bg-white rounded-xl shadow-sm ring-1 ring-light-gray overflow-hidden">
        <div class="px-6 py-4 border-b border-light-gray flex items-center justify-between flex-wrap gap-2">
            <p class="text-xs text-black/60">{{ $filterLabel }}</p>
            <p class="text-xs font-semibold text-navy">Total: {{ $totalHours }}h {{ $totalMinutes }}m</p>
        </div>
        <div class="p-6 overflow-x-auto">
            <x-reports.attendance-summary-table :entries="$entries" :total-hours="$totalHours" :total-minutes="$totalMinutes" />
        </div>
    </div>
@endsection
