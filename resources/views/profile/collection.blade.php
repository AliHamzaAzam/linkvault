<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-gray-800 text-lg font-bold border-2"
                 style="background-color: {{ $collection->color }}30; border-color: {{ $collection->color }};">
                {{ strtoupper(substr($collection->name, 0, 1)) }}
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('profile.show', $user->username) }}" class="text-sm text-gray-500 hover:text-gray-700">
                        {{ $user->name }}
                    </a>
                    <span class="text-gray-400">/</span>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $collection->name }}</h2>
                </div>
                @if($collection->description)
                    <p class="text-sm text-gray-500 mt-0.5">{{ $collection->description }}</p>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Collections Navigation --}}
            @if($collections->count() > 1)
                <div class="mb-8">
                    <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-3">More Collections</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($collections as $col)
                            @if($col->id !== $collection->id)
                                <a href="{{ route('profile.collection', [$user->username, $col->slug]) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-colors hover:opacity-80"
                                   style="background-color: {{ $col->color }}20; color: {{ $col->color }}; border: 1px solid {{ $col->color }}40;">
                                    {{ $col->name }}
                                    <span class="text-xs opacity-75">({{ $col->bookmarks_count }})</span>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Bookmarks --}}
            <div class="mb-4">
                <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Bookmarks in this Collection</h3>
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
                    <p class="text-lg">No public bookmarks in this collection</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
