<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Add Bookmark</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('bookmarks.store') }}" method="POST" class="space-y-6 bg-white p-6 rounded-lg border">
                @csrf

                {{-- URL --}}
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                    <input type="url" name="url" id="url" required
                           value="{{ old('url') }}"
                           placeholder="https://example.com/article"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('url') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Title (optional — will be scraped) --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Title <span class="text-gray-400 font-normal">(auto-fetched if empty)</span>
                    </label>
                    <input type="text" name="title" id="title"
                           value="{{ old('title') }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>

                {{-- Tags --}}
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <input type="text" name="tags" id="tags"
                           value="{{ old('tags') }}"
                           placeholder="laravel, tutorial, api"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="text-xs text-gray-400 mt-1">Comma-separated. New tags are created automatically.</p>
                    @if($tags->isNotEmpty())
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($tags as $tag)
                                <button type="button"
                                        onclick="addTag('{{ $tag->name }}')"
                                        class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full hover:bg-gray-200 cursor-pointer">
                                    + {{ $tag->name }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Collections --}}
                @if($collections->isNotEmpty())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Collections</label>
                        <div class="space-y-2">
                            @foreach($collections as $collection)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="collection_ids[]" value="{{ $collection->id }}"
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                           {{ in_array($collection->id, old('collection_ids', [])) ? 'checked' : '' }}>
                                    <span class="w-3 h-3 rounded-full" style="background-color: {{ $collection->color }}"></span>
                                    <span class="text-sm text-gray-700">{{ $collection->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Visibility --}}
                <div>
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" name="is_public" value="1"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               {{ old('is_public') ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Make this bookmark public</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3">
                    <a href="{{ route('bookmarks.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</a>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Save Bookmark
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function addTag(name) {
            const input = document.getElementById('tags');
            const current = input.value.split(',').map(t => t.trim()).filter(Boolean);
            if (!current.includes(name)) {
                current.push(name);
                input.value = current.join(', ');
            }
        }
    </script>
    @endpush
</x-app-layout>
