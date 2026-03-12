<div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    {{-- OG Image Preview --}}
    @if($bookmark->og_image_url)
        <a href="{{ $bookmark->url }}" target="_blank" rel="noopener">
            <img src="{{ $bookmark->og_image_url }}"
                 alt=""
                 class="w-full h-40 object-cover"
                 loading="lazy"
                 onerror="this.style.display='none'">
        </a>
    @endif

    <div class="p-4">
        {{-- Title + Favicon --}}
        <div class="flex items-start gap-2">
            @if($bookmark->favicon_url)
                <img src="{{ $bookmark->favicon_url }}"
                     alt=""
                     class="w-4 h-4 mt-1 flex-shrink-0"
                     onerror="this.style.display='none'">
            @endif

            <a href="{{ route('bookmarks.show', $bookmark) }}"
               class="font-medium text-gray-900 hover:text-blue-600 line-clamp-2">
                {{ $bookmark->title ?? $bookmark->url }}
            </a>
        </div>

        {{-- URL --}}
        <a href="{{ $bookmark->url }}" target="_blank" rel="noopener"
           class="text-xs text-gray-400 truncate block mt-1">
            {{ parse_url($bookmark->url, PHP_URL_HOST) }}
        </a>

        {{-- Loading State --}}
        @if(is_null($bookmark->meta_scraped_at))
            <div class="flex items-center gap-2 mt-2 text-xs text-gray-400">
                <svg class="w-3 h-3 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Fetching metadata...</span>
            </div>
        @endif

        {{-- Description --}}
        @if($bookmark->description)
            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $bookmark->description }}</p>
        @endif

        {{-- Tags --}}
        @if($bookmark->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1 mt-3">
                @foreach($bookmark->tags as $tag)
                    <a href="{{ route('bookmarks.index', ['tag' => $tag->id]) }}"
                       class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full hover:bg-gray-200">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Meta row --}}
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
            <span class="text-xs text-gray-400">
                {{ $bookmark->created_at->diffForHumans() }}
            </span>

            <div class="flex items-center gap-2">
                @if($bookmark->is_public)
                    <span class="text-xs text-green-600">Public</span>
                @else
                    <span class="text-xs text-gray-400">Private</span>
                @endif

                <form action="{{ route('bookmarks.archive', $bookmark) }}" method="POST" class="inline">
                    @csrf
                    <button class="text-xs text-gray-400 hover:text-gray-600" title="Archive">
                        {{ $bookmark->is_archived ? 'Unarchive' : 'Archive' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
