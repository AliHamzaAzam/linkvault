<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'LinkVault') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
    </head>
    <body class="antialiased bg-[#FDFDFC] text-gray-900 selection:bg-red-500 selection:text-white flex flex-col min-h-screen font-[Figtree] transition-colors duration-300">
        
        <!-- Navigation -->
        <header class="fixed top-0 w-full z-50 bg-[#FDFDFC]/80 backdrop-blur-md border-b border-gray-200 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-2">
                        <!-- Custom Icon Logo -->
                        <div class="w-8 h-8 rounded bg-red-500 flex items-center justify-center text-white font-bold text-xl shadow-[0_0_15px_rgba(245,48,3,0.5)]">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        </div>
                        <span class="font-bold text-xl tracking-tight text-gray-900">LinkVault</span>
                    </div>
                    
                    
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex flex-col items-center justify-center pt-24 pb-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
            <div class="absolute bottom-1/4 right-1/4 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
            
            <div class="max-w-4xl w-full text-center space-y-8 relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 text-red-600 text-sm font-medium border border-red-100 mb-4 shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                    Your personal link archiver
                </div>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-gray-900 leading-tight">
                    Never lose a <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-600 to-orange-500">link</span> again.
                </h1>
                
                <p class="mt-4 text-xl md:text-2xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Save, organize, and quickly retrieve your favorite websites, articles, and resources with a premium bookmark manager tailored for productivity.
                </p>
                
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    @auth
                        <a href="{{ url('/bookmarks') }}" class="w-full sm:w-auto px-8 py-3 rounded-lg text-base font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-[0_0_20px_rgba(245,48,3,0.4)] hover:shadow-[0_0_25px_rgba(245,48,3,0.6)] hover:-translate-y-0.5 duration-200">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-3 rounded-lg text-base font-medium text-white bg-red-600 hover:bg-red-700 transition shadow-[0_0_20px_rgba(245,48,3,0.3)] hover:shadow-[0_0_25px_rgba(245,48,3,0.5)] hover:-translate-y-0.5 duration-200">
                            Get Started Free
                        </a>
                        <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3 rounded-lg text-base font-medium text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 transition shadow-sm hover:shadow-md hover:-translate-y-0.5 duration-200">
                            Log In
                        </a>
                    @endauth
                </div>
            </div>

            <!-- App Preview/Mockup -->
            <div class="mt-16 relative mx-auto w-full max-w-5xl z-10">
                    <div class="rounded-xl bg-white/40 p-2 ring-1 ring-inset ring-gray-900/5 backdrop-blur-xl shadow-2xl">
                        <div class="rounded-lg bg-gray-50 ring-1 ring-gray-200 overflow-hidden aspect-[16/9] flex flex-col">
                            <!-- Mockup Header -->
                            <div class="h-10 border-b border-gray-200 bg-white flex items-center px-4 gap-2">
                                <div class="flex gap-1.5">
                                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-orange-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-green-400"></div>
                                </div>
                                <div class="mx-auto w-1/3 h-5 bg-gray-100 rounded flex items-center justify-center border border-gray-200">
                                    <span class="text-[10px] text-gray-400">linkvault.</span>
                                </div>
                            </div>
                            <!-- Mockup Content -->
                            <div class="flex-grow bg-gray-50 p-4 sm:p-8 flex gap-6">
                                <!-- Sidebar -->
                                <div class="w-1/4 hidden sm:flex flex-col gap-3">
                                    <div class="flex items-center gap-2 px-3 py-2 rounded-md bg-gray-200 text-gray-700">
                                        <div class="w-4 h-4 rounded-full bg-gray-400"></div>
                                        <div class="h-3 w-16 bg-gray-400 rounded"></div>
                                    </div>
                                    <div class="flex items-center gap-2 px-3 py-2">
                                        <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                                        <div class="h-3 w-20 bg-gray-300 rounded"></div>
                                    </div>
                                    <div class="flex items-center gap-2 px-3 py-2">
                                        <div class="w-4 h-4 rounded-full bg-gray-300"></div>
                                        <div class="h-3 w-14 bg-gray-300 rounded"></div>
                                    </div>
                                </div>
                                <!-- Main Grid -->
                                <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-4 auto-rows-min">
                                    <div class="h-32 bg-white border border-gray-200 rounded-lg shadow-sm p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-red-100 flex-shrink-0"></div>
                                            <div class="h-4 w-3/4 bg-gray-200 rounded"></div>
                                        </div>
                                        <div class="h-3 w-1/2 bg-gray-100 rounded mt-4"></div>
                                        <div class="h-3 w-full bg-gray-100 rounded mt-2"></div>
                                    </div>
                                    <div class="h-32 bg-white border border-gray-200 rounded-lg shadow-sm p-4 flex flex-col justify-between hidden md:flex">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-blue-100 flex-shrink-0"></div>
                                            <div class="h-4 w-2/3 bg-gray-200 rounded"></div>
                                        </div>
                                        <div class="h-3 w-1/3 bg-gray-100 rounded mt-4"></div>
                                        <div class="h-3 w-5/6 bg-gray-100 rounded mt-2"></div>
                                    </div>
                                    <div class="h-32 bg-white border border-gray-200 rounded-lg shadow-sm p-4 flex flex-col justify-between">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded bg-green-100 flex-shrink-0"></div>
                                            <div class="h-4 w-4/5 bg-gray-200 rounded"></div>
                                        </div>
                                        <div class="h-3 w-1/4 bg-gray-100 rounded mt-4"></div>
                                        <div class="h-3 w-4/5 bg-gray-100 rounded mt-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </main>
        
        <footer class="py-6 text-center text-sm text-gray-500 border-t border-gray-200">
            &copy; {{ date('Y') }} LinkVault. Elevating bookmark management.
        </footer>
    </body>
</html>
