<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Bookmarks</h2>
            <a href="{{ route('bookmarks.create') }}"
               class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                + Add Bookmark
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Filters --}}
            <div class="flex gap-2 mb-6 flex-wrap">
                <a href="{{ route('bookmarks.index') }}"
                   class="px-3 py-1 rounded-full text-sm {{ !request('collection') && !request('tag') ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    All
                </a>

                @foreach($collections as $collection)
                    <a href="{{ route('bookmarks.index', ['collection' => $collection->id]) }}"
                       class="px-3 py-1 rounded-full text-sm {{ request('collection') == $collection->id ? 'text-white' : 'text-gray-700 hover:bg-gray-200' }}"
                       style="{{ request('collection') == $collection->id ? 'background-color: ' . $collection->color : '' }}">
                        {{ $collection->name }}
                    </a>
                @endforeach
            </div>

            {{-- Bookmark Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($bookmarks as $bookmark)
                    @include('bookmarks._card', ['bookmark' => $bookmark])
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <p class="text-lg">No bookmarks yet</p>
                        <a href="{{ route('bookmarks.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                            Save your first bookmark
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $bookmarks->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
