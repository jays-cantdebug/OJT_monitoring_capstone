@extends('layouts.student')

@section('title', 'Internship Info')

@section('content')
    <div class="max-w-2xl bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-1">Company & Internship Details</h2>
        <p class="text-sm text-gray-500 mb-6">You can update these details yourself at any time.</p>

        <form class="space-y-5">
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
                <input
                    type="text"
                    id="company_name"
                    name="company_name"
                    value="Northern Mindanao Data Solutions Inc."
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >
            </div>

            <div>
                <label for="company_address" class="block text-sm font-medium text-gray-700">Company Address</label>
                <textarea
                    id="company_address"
                    name="company_address"
                    rows="2"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >Corrales Ave, Cagayan de Oro City, Misamis Oriental</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="supervisor_name" class="block text-sm font-medium text-gray-700">Supervisor Name</label>
                    <input
                        type="text"
                        id="supervisor_name"
                        name="supervisor_name"
                        value="Engr. Maria Santos"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                </div>
                <div>
                    <label for="supervisor_contact" class="block text-sm font-medium text-gray-700">Supervisor Contact</label>
                    <input
                        type="text"
                        id="supervisor_contact"
                        name="supervisor_contact"
                        value="0917 123 4567"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="start_date" class="block text-sm font-medium text-gray-700">Internship Start Date</label>
                    <input
                        type="date"
                        id="start_date"
                        name="start_date"
                        value="2026-06-15"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                </div>
                <div>
                    <label for="end_date" class="block text-sm font-medium text-gray-700">Internship End Date</label>
                    <input
                        type="date"
                        id="end_date"
                        name="end_date"
                        value="2026-10-15"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                    >
                </div>
            </div>

            <button
                type="submit"
                class="inline-flex justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
            >
                Save Changes
            </button>
        </form>
    </div>
@endsection
