<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Tags</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($tags->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-lg">No tags yet</p>
                    <p class="text-sm mt-1">Tags are created automatically when you add them to a bookmark.</p>
                    <a href="{{ route('bookmarks.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                        Add a bookmark
                    </a>
                </div>
            @else
                <div class="bg-white rounded-lg border border-gray-200 divide-y divide-gray-100">
                    @foreach($tags as $tag)
                        <div class="flex items-center justify-between px-5 py-3">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('bookmarks.index', ['tag' => $tag->id]) }}"
                                   class="font-medium text-gray-900 hover:text-blue-600">
                                    {{ $tag->name }}
                                </a>
                                <span class="text-xs text-gray-400">
                                    {{ $tag->bookmarks_count }}
                                    {{ Str::plural('bookmark', $tag->bookmarks_count) }}
                                </span>
                            </div>

                            <form action="{{ route('tags.destroy', $tag) }}" method="POST"
                                  onsubmit="return confirm('Delete tag \'{{ $tag->name }}\'? It will be removed from all bookmarks.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <p class="text-xs text-gray-400 mt-4">
                    {{ $tags->count() }} {{ Str::plural('tag', $tags->count()) }} total
                </p>
            @endif

        </div>
    </div>
</x-app-layout>
