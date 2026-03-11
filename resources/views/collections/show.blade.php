<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('collections.index') }}" class="text-gray-400 hover:text-gray-600">
                    &larr;
                </a>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full flex-shrink-0"
                          style="background-color: {{ $collection->color ?? '#6B7280' }}"></span>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $collection->name }}</h2>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('collections.edit', $collection) }}"
                   class="text-sm text-gray-500 hover:text-gray-700 border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50">
                    Edit
                </a>
                <form action="{{ route('collections.destroy', $collection) }}" method="POST"
                      onsubmit="return confirm('Delete this collection? Bookmarks will not be deleted.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-sm text-red-500 hover:text-red-700 border border-red-200 px-3 py-1.5 rounded-lg hover:bg-red-50">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Collection meta --}}
            @if($collection->description)
                <p class="text-gray-600">{{ $collection->description }}</p>
            @endif

            <div class="flex items-center gap-3 text-sm text-gray-500">
                <span>{{ $bookmarks->total() }} {{ Str::plural('bookmark', $bookmarks->total()) }}</span>
                @if($collection->is_public)
                    <span class="text-green-600">&middot; Public collection</span>
                @endif
            </div>

            {{-- Bookmark grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($bookmarks as $bookmark)
                    @include('bookmarks._card', ['bookmark' => $bookmark])
                @empty
                    <div class="col-span-full text-center py-12 text-gray-500">
                        <p class="text-lg">No bookmarks in this collection yet</p>
                        <a href="{{ route('bookmarks.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                            Add a bookmark
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-6">
                {{ $bookmarks->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
