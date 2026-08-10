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
<body class="h-screen overflow-hidden font-sans antialiased bg-white text-black">
    <div class="flex h-full" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">
        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-30 bg-black/40 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        <aside
            class="fixed inset-y-0 left-0 z-40 w-[260px] shrink-0 bg-navy text-light-gray flex flex-col transform transition-transform duration-200 ease-in-out lg:relative lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        >
            <div class="px-6 py-6 flex flex-col items-center text-center border-b border-white/10">
                <div class="mb-3 h-20 w-20 overflow-hidden rounded-full">
                    <img src="{{ asset('images/normi-ojt-logo.jfif') }}" alt="NORMI logo" class="h-full w-full object-cover">
                </div>
                <p class="font-bold text-gold tracking-wide leading-tight">NORMI</p>
                <p class="text-[11px] font-semibold uppercase tracking-wider text-white/60 mt-0.5">Internship Monitoring</p>
                <p class="text-[11px] text-white/40 mt-1">SY 2026-2027</p>
            </div>

            <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                @php
                    $navItems = [
                        'student.dashboard' => ['label' => 'Dashboard', 'icon' => 'home'],
                        'student.time' => ['label' => 'Time In/Out', 'icon' => 'clock'],
                        'student.attendance' => ['label' => 'Attendance', 'icon' => 'calendar'],
                        'student.reports' => ['label' => 'Daily Report', 'icon' => 'document-text'],
                        'student.internship-info' => ['label' => 'My Internship', 'icon' => 'briefcase'],
                        'student.notifications' => ['label' => 'Notifications', 'icon' => 'bell'],
                        'student.profile' => ['label' => 'Profile', 'icon' => 'user-circle'],
                    ];
                @endphp

                @foreach ($navItems as $routeName => $item)
                    <a
                        href="{{ route($routeName) }}"
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($routeName) ? 'bg-gold text-navy font-semibold' : 'text-light-gray hover:bg-white/5 hover:text-white' }}"
                    >
                        <x-dynamic-component :component="'heroicon-o-'.$item['icon']" class="h-5 w-5 shrink-0" />
                        <span class="truncate">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </nav>

            <div class="px-3 py-4 border-t border-white/10">
                <div class="flex items-center gap-3 px-2 py-2">
                    @if (auth()->user()->avatarUrl())
                        <img src="{{ auth()->user()->avatarUrl() }}" alt="{{ auth()->user()->name }}" class="h-9 w-9 shrink-0 rounded-full object-cover">
                    @else
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gold text-sm font-bold text-navy">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="truncate text-[11px] text-white/40">BS in Computer Science</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mt-1 w-full flex items-center gap-2 text-left rounded-lg px-3 py-2 text-sm font-medium text-light-gray hover:bg-white/5 hover:text-white">
                        <x-heroicon-o-arrow-right-on-rectangle class="h-4 w-4" />
                        Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col min-w-0">
            <header class="bg-white px-4 py-3 sm:px-6 lg:px-8 lg:py-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <button
                        type="button"
                        @click="sidebarOpen = true"
                        aria-label="Open menu"
                        class="lg:hidden shrink-0 flex h-11 w-11 items-center justify-center rounded-md text-black/60 hover:bg-light-gray/40"
                    >
                        <x-heroicon-o-bars-3 class="h-6 w-6" />
                    </button>
                    <h1 class="text-lg font-semibold text-navy truncate">@yield('title', 'Dashboard')</h1>
                </div>
                <div class="hidden sm:block shrink-0 text-sm text-black/60">
                    {{ auth()->user()->name }}
                </div>
            </header>

            <main class="flex-1 px-4 py-4 sm:px-6 sm:py-5 lg:px-8 lg:py-6 overflow-y-auto">
                @if (session('status'))
                    <div class="mb-6 rounded-lg bg-success/5 px-4 py-3 text-sm text-success">
                        {{ session('status') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
