<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'LinkVault') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 text-gray-900 selection:bg-red-500 selection:text-white transition-colors duration-300">
        <div class="min-h-screen flex flex-col sm:flex-row w-full bg-[#fbfbfb]">
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen max-w-full overflow-hidden relative">
                
                <!-- Flash Message --> 
                @if(session('success'))
                    <div class="px-4 sm:px-6 lg:px-8 mt-4 sm:mt-6 absolute top-0 w-full z-10 pointer-events-none">
                        <div class="bg-green-50/90 backdrop-blur-md border border-green-200 text-green-700 px-4 py-3 rounded-lg shadow-sm pointer-events-auto flex justify-between items-center">
                            <span>{{ session('success') }}</span>
                            <button onclick="this.parentElement.style.display='none'" class="text-green-600 hover:text-green-800 focus:outline-none">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-transparent mt-2 sm:mt-6 border-b border-gray-200 sm:border-transparent">
                        <div class="py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto w-full @if(!isset($header)) mt-16 sm:mt-8 @endif px-0 sm:px-2 box-border">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
