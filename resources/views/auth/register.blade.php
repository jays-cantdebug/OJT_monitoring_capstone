@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <h2 class="text-lg font-semibold text-gray-900 mb-1">Student Intern Registration</h2>
    <p class="text-sm text-gray-500 mb-6">Your account will need Dean approval before you can log in.</p>

    <form class="space-y-5">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input
                type="text"
                id="name"
                name="name"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            >
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            >
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            >
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-400 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            >
        </div>

        <button
            type="submit"
            class="w-full inline-flex justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
        >
            Register
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('login') }}" class="font-medium text-gray-900 hover:underline">Log in</a>
    </p>
@endsection
