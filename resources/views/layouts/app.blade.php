<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'China Passports') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body class="h-full font-sans antialiased text-gray-900 bg-gray-50">
    <div x-data="{ sidebarOpen: false }" class="relative flex h-full min-h-screen">

        <!-- Mobile Sidebar Overlay -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-40 bg-gray-900/80 lg:hidden"></div>

        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-slate-900 to-slate-800 text-white transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 lg:inset-auto lg:flex lg:w-72 lg:flex-col shadow-xl">
            <!-- Logo -->
            <div
                class="flex h-16 shrink-0 items-center justify-center border-b border-white/10 px-6 bg-white/5 backdrop-blur-sm">
                <span class="text-xl font-bold tracking-wider text-white">VISA<span
                        class="text-blue-400">AGENT</span></span>
            </div>

            <!-- Nav -->
            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
                @php
                    $navClass = "group flex items-center rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200";
                    $activeClass = "bg-blue-600 text-white shadow-lg shadow-blue-500/30";
                    $inactiveClass = "text-gray-300 hover:bg-white/5 hover:text-white";
                @endphp

                @if(auth()->user()->role !== 'visa_applier')
                    <a href="{{ route('dashboard') }}"
                        class="{{ $navClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                        </svg>
                        Dashboard
                    </a>
                @endif

                <a href="{{ route('passports.index') }}"
                    class="{{ $navClass }} {{ request()->routeIs('passports.*') ? $activeClass : $inactiveClass }}">
                    <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Passports
                </a>

                @if(in_array(auth()->user()->role, ['admin', 'data_entry']))
                    <a href="{{ route('sales.index') }}"
                        class="{{ $navClass }} {{ request()->routeIs('sales.*') ? $activeClass : $inactiveClass }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Sales Agents
                    </a>

                    <a href="{{ route('companies.index') }}"
                        class="{{ $navClass }} {{ request()->routeIs('companies.*') ? $activeClass : $inactiveClass }}">
                        <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Companies
                    </a>
                @endif

            </nav>

            <!-- User Menu -->
            <div class="border-t border-white/10 p-4 bg-white/5 backdrop-blur-sm">
                <div class="flex items-center">
                    <div class="ml-3">
                        <p class="text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs font-medium text-gray-400 capitalize">
                            {{ str_replace('_', ' ', auth()->user()->role) }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                        class="flex w-full items-center justify-center rounded-md bg-white/10 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-white/20 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex flex-1 flex-col overflow-hidden">
            <!-- Mobile Header -->
            <div class="flex items-center gap-x-4 border-b border-gray-200 bg-white px-4 py-4 shadow-sm lg:hidden">
                <button @click="sidebarOpen = true" type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <div class="flex-1 text-sm font-semibold leading-6 text-gray-900">Dashboard</div>
            </div>

            <main class="flex-1 overflow-y-auto bg-gray-50 p-4 lg:p-8">
                @if(session('success'))
                    <div
                        class="mb-6 rounded-lg bg-green-50 p-4 text-sm text-green-800 border border-green-200 shadow-sm flex items-center">
                        <svg class="h-5 w-5 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z">
                            </path>
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-6 rounded-lg bg-red-50 p-4 text-sm text-red-800 border border-red-200 shadow-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="//unpkg.com/alpinejs" defer></script>
</body>

</html>