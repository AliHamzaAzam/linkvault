<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900">Tags</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($tags->isEmpty())
                <div class="text-center py-16 px-4 bg-white/80 backdrop-blur-md rounded-xl border border-gray-200 shadow-sm">
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-gray-100">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <p class="text-lg font-medium text-gray-900">No tags yet</p>
                    <p class="text-sm text-gray-500 mt-1">Tags are created automatically when you add them to a bookmark.</p>
                    <a href="{{ route('bookmarks.create') }}" class="text-red-500 hover:text-red-600 font-medium mt-4 inline-block transition hover:underline">
                        Add a bookmark &rarr;
                    </a>
                </div>
            @else
                <div class="bg-white/80 backdrop-blur-md rounded-xl border border-gray-200 divide-y divide-gray-100 shadow-sm overflow-hidden">
                    @foreach($tags as $tag)
                        <div class="flex items-center justify-between px-6 py-4 hover:bg-gray-50/50 transition-colors duration-150 group">
                            <div class="flex items-center gap-4">
                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center gap-1 sm:gap-3">
                                    <a href="{{ route('bookmarks.index', ['tag' => $tag->id]) }}"
                                       class="font-semibold text-gray-900 hover:text-red-600 transition-colors text-lg">
                                        {{ $tag->name }}
                                    </a>
                                    <span class="text-xs font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full border border-gray-200">
                                        {{ $tag->bookmarks_count }}
                                        {{ Str::plural('bookmark', $tag->bookmarks_count) }}
                                    </span>
                                </div>
                            </div>

                            <form action="{{ route('tags.destroy', $tag) }}" method="POST"
                                  onsubmit="return confirm('Delete tag \'{{ $tag->name }}\'? It will be removed from all bookmarks.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="opacity-0 group-hover:opacity-100 transition-opacity text-sm font-medium text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg border border-red-100">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <p class="text-sm font-medium text-gray-500 mt-5 text-center">
                    {{ $tags->count() }} {{ Str::plural('tag', $tags->count()) }} total
                </p>
            @endif

        </div>
    </div>
</x-app-layout>
