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
            </div>

            {{-- Share Section --}}
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <h3 class="text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                    </svg>
                    Share this collection
                </h3>
                @if($collection->isShared())
                    <div class="flex flex-col sm:flex-row gap-3">
                        <input type="text" value="{{ $collection->getShareUrl() }}" 
                               id="share-url" readonly
                               class="flex-1 rounded-md border-gray-300 bg-white text-sm px-3 py-2 focus:border-red-500 focus:ring-red-500">
                        <div class="flex gap-2">
                            <button onclick="copyShareUrl(this)" 
                                    class="px-4 py-2 bg-gray-900 text-white text-sm rounded-md hover:bg-gray-800 transition-colors flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                Copy
                            </button>
                            <form action="{{ route('collections.unshare', $collection) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-50 text-red-600 text-sm rounded-md hover:bg-red-100 transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                    Revoke
                                </button>
                            </form>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Anyone with this link can view this collection and its bookmarks.
                    </p>
                @else
                    <form action="{{ route('collections.share', $collection) }}" method="POST" class="flex items-center gap-4">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gray-900 text-white text-sm rounded-md hover:bg-gray-800 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            Create Share Link
                        </button>
                        <p class="text-xs text-gray-500">Anyone with the link can view this collection and its bookmarks.</p>
                    </form>
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

    @push('scripts')
    <script>
        async function copyShareUrl(button) {
            const input = document.getElementById('share-url');
            const url = input.value;
            
            try {
                // Try modern clipboard API first
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(url);
                } else {
                    // Fallback for older browsers or non-secure contexts
                    input.focus();
                    input.select();
                    const success = document.execCommand('copy');
                    if (!success) {
                        throw new Error('execCommand failed');
                    }
                }
                
                // Visual feedback
                const originalHTML = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Copied!
                `;
                button.classList.remove('bg-gray-900', 'hover:bg-gray-800');
                button.style.backgroundColor = '#16A34A';
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.style.backgroundColor = '';
                    button.classList.add('bg-gray-900', 'hover:bg-gray-800');
                }, 2000);
            } catch (err) {
                // Error feedback
                const originalHTML = button.innerHTML;
                button.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Failed
                `;
                button.classList.remove('bg-gray-900', 'hover:bg-gray-800');
                button.style.backgroundColor = '#DC2626';
                
                setTimeout(() => {
                    button.innerHTML = originalHTML;
                    button.style.backgroundColor = '';
                    button.classList.add('bg-gray-900', 'hover:bg-gray-800');
                }, 2000);
                
                console.error('Failed to copy:', err);
            }
        }
    </script>
    @endpush
</x-app-layout>
