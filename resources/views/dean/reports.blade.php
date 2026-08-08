@extends('layouts.dean')

@section('title', 'Accomplishment Reports')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 divide-y divide-gray-100">
        @foreach ([
            ['name' => 'Juan Dela Cruz', 'date' => 'Aug 7, 2026', 'summary' => 'Assisted in preparing weekly inventory report and organized filing system.'],
            ['name' => 'Maria Reyes', 'date' => 'Aug 7, 2026', 'summary' => 'Reviewed drainage survey data and updated the shared spreadsheet.'],
            ['name' => 'Liza Fernandez', 'date' => 'Aug 6, 2026', 'summary' => 'Shadowed nursing staff during morning rounds and logged patient intake notes.'],
        ] as $report)
            <div class="p-6 flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-900">{{ $report['name'] }} — {{ $report['date'] }}</p>
                    <p class="mt-1 text-sm text-gray-600">{{ $report['summary'] }}</p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <div class="h-14 w-14 rounded-md bg-gray-100 flex items-center justify-center text-xs text-gray-400">
                        Photo
                    </div>
                    <a href="#" class="text-xs font-medium text-gray-900 hover:underline">View</a>
                </div>
            </div>
        @endforeach
    </div>
@endsection
