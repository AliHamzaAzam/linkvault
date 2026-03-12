<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    {{-- Open Graph / Social Meta --}}
    <title>{{ $collection->name }} — LinkVault</title>
    <meta property="og:title" content="{{ $collection->name }}">
    <meta property="og:description" content="{{ $collection->description ?? 'A curated collection of bookmarks on LinkVault' }}">
    <meta name="description" content="{{ $collection->description ?? 'A curated collection of bookmarks on LinkVault' }}">
    <meta property="og:type" content="website">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#FDFDFC] text-gray-900 relative min-h-screen flex flex-col font-[Figtree] selection:bg-red-500 selection:text-white transition-colors duration-300">
    
    <!-- Decorative Background Elements -->
    <div class="fixed top-1/4 left-1/4 w-96 h-96 bg-red-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="fixed bottom-1/4 right-1/4 w-80 h-80 bg-orange-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    {{-- Header --}}
    <header class="bg-[#FDFDFC]/80 backdrop-blur-md border-b border-gray-200/60 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center gap-3 mb-6">
                <a href="{{ url('/') }}" class="flex items-center gap-2 text-gray-900 hover:text-red-600 transition-colors group">
                    <div class="w-8 h-8 rounded bg-red-500 flex items-center justify-center text-white font-bold text-lg shadow-[0_0_15px_rgba(245,48,3,0.4)] group-hover:shadow-[0_0_20px_rgba(245,48,3,0.6)] group-hover:-translate-y-0.5 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <span class="font-bold text-lg tracking-tight">LinkVault</span>
                </a>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-5 bg-white/60 p-5 rounded-2xl border border-gray-100 shadow-sm backdrop-blur-sm">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center text-white font-bold text-2xl flex-shrink-0 shadow-md ring-4 ring-white"
                     style="background-color: {{ $collection->color ?? '#EF4444' }}">
                    {{ strtoupper(substr($collection->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight tracking-tight">{{ $collection->name }}</h1>
                    @if($collection->description)
                        <p class="text-gray-600 mt-2 text-base leading-relaxed max-w-2xl">{{ $collection->description }}</p>
                    @endif
                    <div class="flex items-center gap-2 mt-3 text-sm">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100/80 text-gray-600 border border-gray-200">
                            Curated by <span class="font-semibold text-gray-800">{{ $collection->user->name }}</span>
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100/80 text-gray-600 border border-gray-200">
                            {{ $bookmarks->total() }} {{ Str::plural('bookmark', $bookmarks->total()) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex-grow max-w-5xl mx-auto w-full px-4 sm:px-6 lg:px-8 py-10 relative z-10">
        @if($bookmarks->isEmpty())
            <div class="text-center py-20 bg-white/40 backdrop-blur-md rounded-2xl border border-gray-200/50 shadow-sm">
                <div class="w-16 h-16 bg-white/80 rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <p class="text-xl font-semibold text-gray-900">No bookmarks yet</p>
                <p class="text-gray-500 mt-2">This curated collection is currently empty.</p>
            </div>
        @else
            {{-- Bookmark Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($bookmarks as $bookmark)
                    <div class="bg-white/70 backdrop-blur-md rounded-xl border border-gray-200/60 overflow-hidden shadow-sm hover:shadow-xl hover:shadow-gray-200/50 hover:-translate-y-1 transition-all duration-300 group flex flex-col h-full">
                        {{-- OG Image Preview --}}
                        @if($bookmark->og_image_url)
                            <a href="{{ $bookmark->url }}" target="_blank" rel="noopener" class="block overflow-hidden h-48 flex-shrink-0 relative">
                                <div class="absolute inset-0 bg-gray-900/5 group-hover:bg-transparent transition-colors duration-300 z-10"></div>
                                <img src="{{ $bookmark->og_image_url }}"
                                     alt=""
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500"
                                     loading="lazy"
                                     onerror="this.style.display='none'">
                            </a>
                        @endif

                        <div class="p-6 flex flex-col flex-grow">
                            {{-- Title + Favicon --}}
                            <div class="flex items-start gap-3">
                                @if($bookmark->favicon_url)
                                    <div class="w-7 h-7 flex-shrink-0 rounded bg-white shadow-sm border border-gray-100 flex items-center justify-center p-1 mt-0.5">
                                        <img src="{{ $bookmark->favicon_url }}"
                                             alt=""
                                             class="w-full h-full object-contain"
                                             onerror="this.style.display='none'">
                                    </div>
                                @endif

                                <div class="flex-1 min-w-0">
                                    <h3 class="font-bold text-gray-900 text-lg leading-snug group-hover:text-red-600 transition-colors line-clamp-2">
                                        <a href="{{ $bookmark->url }}" target="_blank" rel="noopener" class="focus:outline-none">
                                            <span class="absolute inset-0" aria-hidden="true"></span>
                                            {{ $bookmark->title ?? $bookmark->url }}
                                        </a>
                                    </h3>
                                </div>
                            </div>

                            {{-- Description --}}
                            @if($bookmark->description)
                                <p class="text-sm text-gray-600 mt-3 line-clamp-2 leading-relaxed flex-grow">{{ $bookmark->description }}</p>
                            @else
                                <div class="flex-grow"></div>
                            @endif

                            {{-- Footer Info --}}
                            <div class="mt-4 pt-4 border-t border-gray-100/80 flex items-center justify-between">
                                <span class="text-xs font-medium text-gray-500 truncate max-w-[60%] flex items-center gap-1.5 opacity-80">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                                    {{ parse_url($bookmark->url, PHP_URL_HOST) }}
                                </span>
                                
                                {{-- Tags --}}
                                @if($bookmark->tags->isNotEmpty())
                                    <div class="flex items-center gap-1">
                                        @foreach($bookmark->tags->take(2) as $tag)
                                            <span class="text-[10px] uppercase tracking-wider font-bold bg-gray-100/80 text-gray-500 px-2 py-0.5 rounded-md">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                        @if($bookmark->tags->count() > 2)
                                            <span class="text-[10px] font-bold text-gray-400 pl-1">+{{ $bookmark->tags->count() - 2 }}</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-10">
                {{ $bookmarks->links() }}
            </div>
        @endif
    </main>

    {{-- Footer --}}
    <footer class="mt-auto bg-white/60 backdrop-blur-md border-t border-gray-200/60 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded bg-red-500 flex items-center justify-center text-white shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <p class="text-sm text-gray-600">
                        Provided by <a href="{{ url('/') }}" class="text-gray-900 font-semibold hover:text-red-600 transition-colors">LinkVault</a>
                    </p>
                </div>
                <p class="text-sm font-medium text-gray-500 bg-gray-100/80 px-4 py-1.5 rounded-full border border-gray-200/60">
                    Public Collection
                </p>
            </div>
        </div>
    </footer>
</body>
</html>
