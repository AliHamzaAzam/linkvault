<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Search Bookmarks</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Search Form --}}
            <div class="mb-8">
                <form action="{{ route('bookmarks.search') }}" method="GET" class="flex gap-2">
                    <div class="flex-1 relative">
                        <input type="text"
                               name="q"
                               value="{{ $query }}"
                               placeholder="Search bookmarks..."
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                               autocomplete="off">
                        @if($query)
                            <a href="{{ route('bookmarks.search') }}" 
                               class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 font-medium">
                        Search
                    </button>
                </form>
            </div>

            @if($query)
                {{-- Results --}}
                <div class="mb-4">
                    <p class="text-gray-600">
                        @if($bookmarks->isEmpty())
                            No results found for "{{ $query }}"
                        @else
                            Found {{ $bookmarks->total() }} result{{ $bookmarks->total() === 1 ? '' : 's' }} for "{{ $query }}"
                        @endif
                    </p>
                </div>

                @if($bookmarks->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($bookmarks as $bookmark)
                            @include('bookmarks._card', ['bookmark' => $bookmark])
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $bookmarks->withQueryString()->links() }}
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="text-center py-12 text-gray-500">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <p class="text-lg">Enter a search term to find your bookmarks</p>
                    <p class="text-sm mt-2">Search across titles, descriptions, site names, and URLs</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
