<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900">Bookmarks</h2>
            <a href="{{ route('bookmarks.create') }}"
               class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm hover:shadow-md hover:-translate-y-0.5 duration-200">
                <span class="mr-1">+</span> Add Bookmark
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="flex gap-2 mb-6 flex-wrap">
                <a href="{{ route('bookmarks.index') }}"
                   class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-200 {{ !request('collection') && !request('tag') ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:bg-gray-50' }}">
                    All
                </a>

                @foreach($collections as $collection)
                    <a href="{{ route('bookmarks.index', ['collection' => $collection->id]) }}"
                       class="px-3 py-1 rounded-full text-sm font-medium transition-all duration-200 hover:opacity-80"
                       style="background-color: {{ $collection->color }}20; color: {{ $collection->color }}; border: 1px solid {{ $collection->color }}40; {{ request('collection') == $collection->id ? 'opacity: 1; box-shadow: 0 0 0 2px ' . $collection->color . '40;' : 'opacity: 0.85;' }}">
                        {{ $collection->name }}
                    </a>
                @endforeach
            </div>

            {{-- Bookmark Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($bookmarks as $bookmark)
                    @include('bookmarks._card', ['bookmark' => $bookmark])
                @empty
                    <div class="col-span-full text-center py-16 px-4 bg-white/80 backdrop-blur-md rounded-xl border border-gray-200 shadow-sm">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                        </div>
                        <p class="text-lg font-medium text-gray-900">No bookmarks yet</p>
                        <p class="text-gray-500 mt-1 max-w-sm mx-auto">Start building your link vault by saving your first resource.</p>
                        <a href="{{ route('bookmarks.create') }}" class="text-red-500 hover:text-red-600 font-medium mt-4 inline-block transition hover:underline">
                            Save your first bookmark &rarr;
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $bookmarks->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
