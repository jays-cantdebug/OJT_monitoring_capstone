<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NORMI OJT Monitoring')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900">
    <div class="min-h-screen flex">
        <aside class="w-64 shrink-0 bg-gray-900 text-gray-300 flex flex-col">
            <div class="px-6 py-5 border-b border-gray-800">
                <p class="text-white font-semibold leading-tight">NORMI OJT</p>
                <p class="text-xs text-gray-400 leading-tight">Dean</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1">
                @php
                    $navItems = [
                        'dean.dashboard' => 'Dashboard',
                        'dean.live-map' => 'Live Map',
                        'dean.pending-approvals' => 'Pending Approvals',
                        'dean.students' => 'Student Interns',
                        'dean.attendance' => 'Attendance Records',
                        'dean.reports' => 'Accomplishment Reports',
                        'dean.reports-export' => 'Reports / Export',
                        'dean.notifications' => 'Notifications',
                        'dean.profile' => 'Profile',
                    ];
                @endphp

                @foreach ($navItems as $routeName => $label)
                    <a
                        href="{{ route($routeName) }}"
                        class="block rounded-md px-3 py-2 text-sm font-medium transition {{ request()->routeIs($routeName) ? 'bg-gray-800 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-800 hover:text-white">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white border-b border-gray-200 px-8 py-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                <div class="text-sm text-gray-600">
                    {{ auth()->user()->name }}
                </div>
            </header>

            <main class="flex-1 px-8 py-6">
                @if (session('status'))
                    <div class="mb-6 rounded-md bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
