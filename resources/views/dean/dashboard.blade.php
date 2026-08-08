@extends('layouts.dean')

@section('title', 'Dashboard')

@section('content')
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Active Interns</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">24</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Currently On Duty</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">9</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Pending Approvals</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">3</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-5">
            <p class="text-sm text-gray-500">Reports Today</p>
            <p class="mt-1 text-xl font-semibold text-gray-900">7</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Recent Activity</h2>
            <ul class="space-y-3 text-sm text-gray-700">
                <li>Juan Dela Cruz submitted an accomplishment report.</li>
                <li>Maria Reyes timed in at 8:04 AM.</li>
                <li>New registration pending approval: Pedro Ramos.</li>
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
            <h2 class="text-sm font-semibold text-gray-900 mb-4">Pending Approvals</h2>
            <ul class="space-y-3 text-sm text-gray-700">
                <li>Pedro Ramos — registered Aug 8, 2026</li>
                <li>Angela Cruz — registered Aug 7, 2026</li>
                <li>Mark Villanueva — registered Aug 6, 2026</li>
            </ul>
            <a href="{{ route('dean.pending-approvals') }}" class="mt-4 inline-block text-sm font-medium text-gray-900 hover:underline">
                Review all &rarr;
            </a>
        </div>
    </div>
@endsection
