<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900">Add Bookmark</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('bookmarks.store') }}" method="POST" class="space-y-6 bg-white/80 backdrop-blur-md p-6 sm:p-8 rounded-xl border border-gray-200 shadow-sm">
                @csrf

                {{-- URL --}}
                <div>
                    <label for="url" class="block text-sm font-medium text-gray-700">URL</label>
                    <input type="url" name="url" id="url" required
                           value="{{ old('url') }}"
                           placeholder="https://example.com/article"
                           class="mt-1.5 block w-full rounded-lg bg-white border border-gray-300 text-gray-900 shadow-sm focus:border-red-500 focus:ring-red-500 transition duration-200 placeholder-gray-400 px-4 py-2">
                    @error('url') <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p> @enderror
                </div>

                {{-- Title (optional — will be scraped) --}}
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700">
                        Title <span class="text-gray-400 font-normal text-xs ml-1">(auto-fetched if empty)</span>
                    </label>
                    <input type="text" name="title" id="title"
                           value="{{ old('title') }}"
                           class="mt-1.5 block w-full rounded-lg bg-white border border-gray-300 text-gray-900 shadow-sm focus:border-red-500 focus:ring-red-500 transition duration-200 px-4 py-2">
                </div>

                {{-- Tags --}}
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700">Tags</label>
                    <input type="text" name="tags" id="tags"
                           value="{{ old('tags') }}"
                           placeholder="laravel, tutorial, api"
                           class="mt-1.5 block w-full rounded-lg bg-white border border-gray-300 text-gray-900 shadow-sm focus:border-red-500 focus:ring-red-500 transition duration-200 px-4 py-2 placeholder-gray-400">
                    <p class="text-xs text-gray-500 mt-2">Comma-separated. New tags are created automatically.</p>
                    @if($tags->isNotEmpty())
                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach($tags as $tag)
                                <button type="button"
                                        onclick="addTag('{{ $tag->name }}')"
                                        class="text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200 px-2.5 py-1 rounded-full hover:bg-gray-200 transition-colors cursor-pointer">
                                    + {{ $tag->name }}
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Collections --}}
                @if($collections->isNotEmpty())
                    <div class="border-t border-gray-100 pt-5 mt-2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Collections</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach($collections as $collection)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors bg-white/50">
                                    <input type="checkbox" name="collection_ids[]" value="{{ $collection->id }}"
                                           class="rounded border-gray-300 text-red-600 focus:ring-red-500 focus:ring-offset-0"
                                           {{ in_array($collection->id, old('collection_ids', [])) ? 'checked' : '' }}>
                                    <span class="w-3.5 h-3.5 rounded-full ring-2 ring-transparent" style="background-color: {{ $collection->color }}"></span>
                                    <span class="text-sm font-medium text-gray-800">{{ $collection->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Visibility --}}
                <div class="border-t border-gray-100 pt-5 mt-2">
                    <label class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 hover:bg-gray-50 cursor-pointer transition-colors bg-white/50">
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" name="is_public" value="1"
                               class="rounded border-gray-300 text-red-600 focus:ring-red-500 focus:ring-offset-0"
                               {{ old('is_public') ? 'checked' : '' }}>
                        <span class="text-sm font-medium text-gray-800">Make this bookmark public</span>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('bookmarks.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">Cancel</a>
                    <button type="submit" class="bg-gray-900 text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-800 transition shadow-sm hover:shadow-md hover:-translate-y-0.5 duration-200">
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
