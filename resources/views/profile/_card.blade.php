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

            <a href="{{ $bookmark->url }}" target="_blank" rel="noopener"
               class="font-medium text-gray-900 hover:text-blue-600 line-clamp-2">
                {{ $bookmark->title ?? $bookmark->url }}
            </a>
        </div>

        {{-- URL --}}
        <a href="{{ $bookmark->url }}" target="_blank" rel="noopener"
           class="text-xs text-gray-400 truncate block mt-1">
            {{ parse_url($bookmark->url, PHP_URL_HOST) }}
        </a>

        {{-- Description --}}
        @if($bookmark->description)
            <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $bookmark->description }}</p>
        @endif

        {{-- Tags --}}
        @if($bookmark->tags->isNotEmpty())
            <div class="flex flex-wrap gap-1 mt-3">
                @foreach($bookmark->tags as $tag)
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full">
                        {{ $tag->name }}
                    </span>
                @endforeach
            </div>
        @endif

        {{-- Meta row --}}
        <div class="flex items-center justify-between mt-3 pt-3 border-t border-gray-100">
            <span class="text-xs text-gray-400">
                {{ $bookmark->created_at->diffForHumans() }}
            </span>

            {{-- Collection badges --}}
            @if($bookmark->collections->where('is_public', true)->isNotEmpty())
                <div class="flex flex-wrap gap-1">
                    @foreach($bookmark->collections->where('is_public', true)->take(2) as $col)
                        <a href="{{ route('profile.collection', [$bookmark->user->username ?? $user->username, $col->slug]) }}"
                           class="text-xs px-2 py-0.5 rounded-full"
                           style="background-color: {{ $col->color }}20; color: {{ $col->color }};">
                            {{ $col->name }}
                        </a>
                    @endforeach
                    @if($bookmark->collections->where('is_public', true)->count() > 2)
                        <span class="text-xs text-gray-400">+{{ $bookmark->collections->where('is_public', true)->count() - 2 }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
