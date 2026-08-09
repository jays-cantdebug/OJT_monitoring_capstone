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
<body class="h-screen overflow-hidden font-sans antialiased bg-[#f5f6f8] text-gray-900">
    <div class="flex h-full">
        <aside class="w-[260px] shrink-0 bg-[#1a2332] text-gray-300 flex flex-col">
            <div class="px-6 py-6 flex flex-col items-center text-center border-b border-white/10">
                <div class="mb-3 h-20 w-20 overflow-hidden rounded-full">
                    <img src="{{ asset('images/normi-ojt-logo.jfif') }}" alt="NORMI logo" class="h-full w-full object-cover">
                </div>
                <p class="font-bold text-amber-400 tracking-wide leading-tight">NORMI</p>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-gray-400 mt-0.5">Internship Monitoring</p>
                <p class="text-[11px] text-gray-500 mt-1">SY 2026-2027</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php
                    $navItems = [
                        'dean.dashboard' => ['label' => 'Dashboard', 'icon' => 'home'],
                        'dean.live-map' => ['label' => 'Live Map', 'icon' => 'map-pin'],
                        'dean.students' => ['label' => 'Student Interns', 'icon' => 'user-group'],
                        'dean.attendance' => ['label' => 'Attendance Records', 'icon' => 'calendar'],
                        'dean.reports' => ['label' => 'Accomplishment Reports', 'icon' => 'document-text'],
                        'dean.reports-export' => ['label' => 'Reports / Export', 'icon' => 'chart-bar'],
                        'dean.notifications' => ['label' => 'Notifications', 'icon' => 'bell'],
                        'dean.profile' => ['label' => 'Profile', 'icon' => 'user-circle'],
                    ];
                @endphp

                @foreach ($navItems as $routeName => $item)
                    <a
                        href="{{ route($routeName) }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($routeName) || request()->routeIs($routeName.'.*') ? 'bg-amber-400 text-[#1a2332] font-semibold' : 'text-gray-300 hover:bg-white/5 hover:text-white' }}"
                    >
                        <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-5 w-5 shrink-0" />
                        <span class="truncate">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-400 text-sm font-bold text-[#1a2332]">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-[11px] text-gray-500">School of Computing</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mt-1 w-full flex items-center gap-2 text-left rounded-lg px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">
                        <x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4" />
                        Sign Out Session
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-[#f5f6f8] px-8 py-4 flex items-center justify-between">
                <h1 class="text-lg font-semibold text-gray-900">@yield('title', 'Dashboard')</h1>
                <div class="text-sm text-gray-600">
                    {{ auth()->user()->name }}
                </div>
            </header>

            <main class="flex-1 px-8 py-6 overflow-y-auto">
                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
