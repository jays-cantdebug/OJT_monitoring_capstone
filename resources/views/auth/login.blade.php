@extends('layouts.guest')

@section('title', 'Log In')

@section('content')
    <h2 class="text-lg font-semibold text-gray-900 mb-6">Log In</h2>

    <form class="space-y-5">
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

        <div class="flex items-center">
            <input
                type="checkbox"
                id="remember"
                name="remember"
                class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-200"
            >
            <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
        </div>

        <button
            type="submit"
            class="w-full inline-flex justify-center rounded-md bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2"
        >
            Log In
        </button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-600">
        Don't have an account?
        <a href="{{ route('register') }}" class="font-medium text-gray-900 hover:underline">Register here</a>
    </p>
@endsection
