@extends('layouts.dean')

@section('title', 'Student Interns')

@section('content')
    <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-6 py-3 font-medium">Name</th>
                    <th class="px-6 py-3 font-medium">Company</th>
                    <th class="px-6 py-3 font-medium">Status</th>
                    <th class="px-6 py-3 font-medium text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach ([
                    ['name' => 'Juan Dela Cruz', 'company' => 'Northern Mindanao Data Solutions Inc.', 'status' => 'On Duty'],
                    ['name' => 'Maria Reyes', 'company' => 'CDO City Engineering Office', 'status' => 'On Duty'],
                    ['name' => 'Liza Fernandez', 'company' => 'Xavier University Hospital', 'status' => 'Off Duty'],
                    ['name' => 'Carlo Aquino', 'company' => 'Oro Chamber of Commerce', 'status' => 'Off Duty'],
                ] as $student)
                    <tr>
                        <td class="px-6 py-4 text-gray-900">{{ $student['name'] }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $student['company'] }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-xs {{ $student['status'] === 'On Duty' ? 'text-green-700' : 'text-gray-500' }}">
                                <span class="h-2 w-2 rounded-full {{ $student['status'] === 'On Duty' ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                                {{ $student['status'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="#" class="text-xs font-medium text-gray-900 hover:underline">View Details</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
