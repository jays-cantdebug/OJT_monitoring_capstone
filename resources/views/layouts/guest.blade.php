<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NORMI OJT Monitoring')</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('images/normi-ojt-logo.jpg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-12">
        @hasSection('header')
            @yield('header')
        @else
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-semibold text-gray-900">NORMI OJT Monitoring</h1>
                <p class="text-sm text-gray-500 mt-1">On-the-Job Monitoring and Reporting System</p>
            </div>
        @endif

        <div class="w-full max-w-md bg-white rounded-md shadow-sm ring-1 ring-gray-200 p-8">
            @yield('content')
        </div>
    </div>
</body>
</html>
