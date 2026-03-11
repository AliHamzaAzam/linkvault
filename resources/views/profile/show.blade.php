<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $user->name }}</h2>
                <p class="text-sm text-gray-500">@{{ $user->username }}</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Collections --}}
            @if($collections->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">Collections</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($collections as $collection)
                            <a href="{{ route('profile.collection', [$user->username, $collection->slug]) }}"
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-colors"
                               style="background-color: {{ $collection->color }}20; color: {{ $collection->color }}; border: 1px solid {{ $collection->color }}40;">
                                {{ $collection->name }}
                                <span class="text-xs opacity-75">({{ $collection->bookmarks_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Bookmarks --}}
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Public Bookmarks</h3>
            </div>

            @if($bookmarks->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($bookmarks as $bookmark)
                        @include('profile._card', ['bookmark' => $bookmark])
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $bookmarks->links() }}
                </div>
            @else
                <div class="text-center py-12 text-gray-500 bg-gray-50 rounded-lg border border-gray-200">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <p class="text-lg">No public bookmarks yet</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
