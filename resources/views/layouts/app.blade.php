<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Ujian CBT' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('logo/logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50 text-gray-900 flex flex-col min-h-screen">
    @auth
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center space-x-2">
                        <img src="{{ asset('logo/logo.png') }}" alt="Logo SIPENA" class="w-8 h-8 object-contain">
                        <a href="{{ route('student.dashboard') }}"
                            class="font-bold text-xl tracking-tight text-gray-900 hover:text-blue-600 transition-colors">Portal
                            Siswa CBT</a>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-sm text-gray-600 hidden sm:block">Halo, <strong
                                class="text-gray-900">{{ auth()->user()->name }}</strong></span>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <button type="submit"
                                class="text-sm font-medium text-red-600 hover:text-red-800 focus:outline-none transition-colors">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>
    @endauth

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 transition-all relative">
        @if (session()->has('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-sm text-green-800">
                <div class="flex">
                    <svg class="h-5 w-5 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-sm text-red-800">
                <div class="flex">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-sm text-gray-500">
            &copy; {{ date('Y') }} Sistem Ujian CBT. All rights reserved.
        </div>
    </footer>

    @livewireScripts
</body>

</html>