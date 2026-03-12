<div class="bg-white/80 backdrop-blur-md rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
    {{-- OG Image Preview --}}
    @if($bookmark->og_image_url)
        <a href="{{ $bookmark->url }}" target="_blank" rel="noopener" class="block overflow-hidden">
            <img src="{{ $bookmark->og_image_url }}"
                 alt=""
                 class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy"
                 onerror="this.style.display='none'">
        </a>
    @endif

    <div class="p-5">
        {{-- Title + Favicon --}}
        <div class="flex items-start gap-2.5">
            @if($bookmark->favicon_url)
                <img src="{{ $bookmark->favicon_url }}"
                     alt=""
                     class="w-4 h-4 mt-1 flex-shrink-0 rounded-sm bg-white"
                     onerror="this.style.display='none'">
            @endif

            <a href="{{ route('bookmarks.show', $bookmark) }}"
               class="font-medium text-gray-900 hover:text-red-600 line-clamp-2 transition-colors duration-200">
                {{ $bookmark->title ?? $bookmark->url }}
            </a>
        </div>

        {{-- URL --}}
        <a href="{{ $bookmark->url }}" target="_blank" rel="noopener"
           class="text-xs font-medium text-gray-500 truncate block mt-1.5 hover:text-gray-800 transition-colors">
            {{ parse_url($bookmark->url, PHP_URL_HOST) }}
        </a>

        {{-- Loading State --}}
        @if(is_null($bookmark->meta_scraped_at))
            <div class="flex items-center gap-2 mt-3 text-xs font-medium text-gray-500 bg-gray-50 px-3 py-1.5 rounded flex-inline w-fit">
                <svg class="w-3.5 h-3.5 animate-spin text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Fetching metadata...</span>
            </div>
        @endif

        {{-- Description --}}
        @if($bookmark->description)
            <p class="text-sm text-gray-600 mt-3 line-clamp-2 leading-relaxed">{{ $bookmark->description }}</p>
        @endif

        {{-- Tags --}}
        @if($bookmark->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1.5 mt-4">
                @foreach($bookmark->tags as $tag)
                    <a href="{{ route('bookmarks.index', ['tag' => $tag->id]) }}"
                       class="text-[11px] font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full hover:bg-gray-200 transition-colors border border-gray-200/50">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Meta row --}}
        <div class="flex items-center justify-between mt-5 pt-3 border-t border-gray-100">
            <span class="text-xs font-medium text-gray-400 flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ $bookmark->created_at->diffForHumans() }}
            </span>

            <div class="flex items-center gap-3">
                @if($bookmark->is_public)
                    <span class="text-xs font-semibold text-green-600 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        Public
                    </span>
                @else
                    <span class="text-xs font-semibold text-gray-400 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        Private
                    </span>
                @endif

                <form action="{{ route('bookmarks.archive', $bookmark) }}" method="POST" class="inline">
                    @csrf
                    <button class="text-xs font-medium text-gray-400 hover:text-gray-900 transition-colors bg-gray-50 hover:bg-gray-100 px-2 py-1 rounded" title="Archive">
                        {{ $bookmark->is_archived ? 'Unarchive' : 'Archive' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
