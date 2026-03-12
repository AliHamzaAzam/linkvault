<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('bookmarks.index') }}" class="text-gray-400 hover:text-gray-600">
                    &larr;
                </a>
                <h2 class="text-xl font-semibold text-gray-800 line-clamp-1">
                    {{ $bookmark->title ?? $bookmark->url }}
                </h2>
            </div>
            <a href="{{ route('bookmarks.edit', $bookmark) }}"
               class="text-sm text-gray-500 hover:text-gray-700 border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50">
                Edit
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- OG Image --}}
            @if($bookmark->og_image_url)
                <div class="rounded-lg overflow-hidden border border-gray-200">
                    <img src="{{ $bookmark->og_image_url }}"
                         alt=""
                         class="w-full max-h-64 object-cover"
                         onerror="this.parentElement.style.display='none'">
                </div>
            @endif

            {{-- Main card --}}
            <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4">

                {{-- Title + URL --}}
                <div>
                    <div class="flex items-start gap-2">
                        @if($bookmark->favicon_url)
                            <img src="{{ $bookmark->favicon_url }}"
                                 alt=""
                                 class="w-5 h-5 mt-0.5 flex-shrink-0"
                                 onerror="this.style.display='none'">
                        @endif
                        <div>
                            <h1 class="text-lg font-semibold text-gray-900">
                                {{ $bookmark->title ?? $bookmark->url }}
                            </h1>
                            @if($bookmark->site_name)
                                <p class="text-sm text-gray-400">{{ $bookmark->site_name }}</p>
                            @endif
                        </div>
                    </div>

                    <a href="{{ $bookmark->url }}" target="_blank" rel="noopener"
                       class="mt-2 inline-flex items-center gap-1 text-sm text-blue-600 hover:underline break-all">
                        {{ $bookmark->url }}
                        <svg class="w-3 h-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

                {{-- Description --}}
                @if($bookmark->description)
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $bookmark->description }}</p>
                @endif

                {{-- Tags --}}
                @if($bookmark->tags->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($bookmark->tags as $tag)
                            <a href="{{ route('bookmarks.index', ['tag' => $tag->id]) }}"
                               class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full hover:bg-gray-200">
                                {{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Collections --}}
                @if($bookmark->collections->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($bookmark->collections as $collection)
                            <a href="{{ route('collections.show', $collection) }}"
                               class="text-xs px-2.5 py-1 rounded-full border"
                               style="background-color: {{ $collection->color ?? '#6B7280' }}20; color: {{ $collection->color ?? '#6B7280' }}; border-color: {{ $collection->color ?? '#6B7280' }}40;">
                                {{ $collection->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Meta row --}}
                <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-3 text-xs text-gray-400">
                        <span>Saved {{ $bookmark->created_at->diffForHumans() }}</span>
                        @if($bookmark->updated_at->gt($bookmark->created_at))
                            <span>&middot; Updated {{ $bookmark->updated_at->diffForHumans() }}</span>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        @if($bookmark->is_public)
                            <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Public</span>
                        @else
                            <span class="text-xs text-gray-400 bg-gray-50 px-2 py-0.5 rounded-full">Private</span>
                        @endif

                        @if($bookmark->is_archived)
                            <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">Archived</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex items-center justify-between">
                <div class="flex gap-3">
                    <form action="{{ route('bookmarks.archive', $bookmark) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="text-sm text-gray-500 hover:text-gray-700 border border-gray-300 px-3 py-1.5 rounded-lg hover:bg-gray-50">
                            {{ $bookmark->is_archived ? 'Unarchive' : 'Archive' }}
                        </button>
                    </form>
                </div>

                <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST"
                      onsubmit="return confirm('Delete this bookmark? This cannot be undone.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                        Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
