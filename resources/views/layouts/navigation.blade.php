<nav x-data="{ open: false }" class="bg-white/80 backdrop-blur-md border-b sm:border-b-0 sm:border-r border-gray-200 sm:w-64 flex-shrink-0 transition-colors duration-300 sticky top-0 z-50 sm:h-screen lg:w-72">
    <!-- Desktop Sidebar & Mobile Topbar Container -->
    <div class="flex flex-col h-full">
        <!-- Top area (Logo & Hamburger for mobile) -->
        <div class="flex justify-between items-center h-16 px-4 sm:px-6 sm:h-20 shrink-0">
            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('bookmarks.index') }}" class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded bg-red-500 flex items-center justify-center text-white font-bold text-xl shadow-[0_0_15px_rgba(245,48,3,0.5)]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-gray-900">LinkVault</span>
                </a>
            </div>

            <!-- Hamburger Button -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigation Links and Settings (Desktop & Open Mobile) -->
        <div :class="{'block': open, 'hidden': ! open}" class="sm:flex flex-col flex-1 justify-between bg-white sm:bg-transparent absolute top-16 left-0 right-0 border-b border-gray-200 sm:relative sm:top-0 sm:border-b-0 h-[calc(100vh-4rem)] sm:h-auto overflow-y-auto shadow-lg sm:shadow-none p-4 sm:p-0">
            
            <!-- Links -->
            <div class="px-2 sm:px-4 sm:pt-4 space-y-1 sm:space-y-2">
                <x-nav-link :href="route('bookmarks.index')" :active="request()->routeIs('bookmarks.index') || request()->routeIs('bookmarks.show') || request()->routeIs('bookmarks.create') || request()->routeIs('bookmarks.edit')">
                    <svg class="w-5 h-5 mr-3 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                    Bookmarks
                </x-nav-link>
                <x-nav-link :href="route('bookmarks.search')" :active="request()->routeIs('bookmarks.search')">
                    <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    Search
                </x-nav-link>
                <x-nav-link :href="route('collections.index')" :active="request()->routeIs('collections.*')">
                    <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Collections
                </x-nav-link>
                <x-nav-link :href="route('tags.index')" :active="request()->routeIs('tags.*')">
                    <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    Tags
                </x-nav-link>
                <x-nav-link :href="route('import.create')" :active="request()->routeIs('import.*')">
                    <svg class="w-5 h-5 mr-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import
                </x-nav-link>
            </div>

            <!-- Spacer for sticking to bottom -->
            <div class="flex-grow"></div>

            <!-- Settings / User Profile Footer -->
            @auth
            <div class="p-4 sm:border-t border-gray-200 mt-4 sm:p-4">
                <x-dropdown align="top" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center w-full px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-xl text-gray-700 hover:bg-white hover:shadow-sm focus:outline-none transition ease-in-out duration-150">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-700 font-bold uppercase mr-3">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 text-left truncate font-medium">{{ Auth::user()->name }}</div>
                            <div>
                                <svg class="fill-current h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
            @else
            <div class="p-4 sm:p-4 sm:border-t border-gray-200 mt-4">
                <div class="space-y-2">
                    <a href="/login" class="block w-full text-center px-4 py-2 border border-gray-200 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">Log in</a>
                    <a href="/register" class="block w-full text-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 transition">Register</a>
                </div>
            </div>
            @endauth
        </div>
    </div>
</nav>
