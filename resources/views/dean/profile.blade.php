@extends('layouts.dean')

@section('title', 'Profile')

@section('content')
    <div class="max-w-lg bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-6">Account Details</h2>

        <form class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="NORMI Dean"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="dean@normi.edu.ph"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >
            </div>

            <button
                type="submit"
                class="inline-flex justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
            >
                Save Changes
            </button>
        </form>
    </div>

    <div class="max-w-lg bg-white rounded-lg shadow-sm ring-1 ring-gray-200 p-6 mt-6">
        <h2 class="text-sm font-semibold text-gray-900 mb-6">Change Password</h2>

        <form class="space-y-5">
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
                <input
                    type="password"
                    id="current_password"
                    name="current_password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input
                    type="password"
                    id="new_password"
                    name="new_password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                <input
                    type="password"
                    id="new_password_confirmation"
                    name="new_password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                >
            </div>

            <button
                type="submit"
                class="inline-flex justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700"
            >
                Update Password
            </button>
        </form>
    </div>
@endsection
