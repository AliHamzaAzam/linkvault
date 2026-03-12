<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Collections</h2>
            <button onclick="document.getElementById('create-form').classList.toggle('hidden')"
                    class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-900 transition-colors">
                + New Collection
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Inline create form --}}
            <div id="create-form" class="hidden bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">New Collection</h3>
                <form action="{{ route('collections.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="color" name="color" id="color"
                                       value="{{ old('color', '#6366F1') }}"
                                       class="h-9 w-16 rounded-lg border-gray-300 cursor-pointer">
                                <span class="text-xs text-gray-400">Pick a colour for this collection</span>
                            </div>
                            @error('color') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">
                            Description <span class="text-gray-400 font-normal">(optional)</span>
                        </label>
                        <textarea name="description" id="description" rows="2"
                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="is_public" value="0">
                            <input type="checkbox" name="is_public" value="1"
                                   class="rounded border-gray-300 text-gray-800 focus:ring-gray-500"
                                   {{ old('is_public') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Make this collection public</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                                onclick="document.getElementById('create-form').classList.add('hidden')"
                                class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">Cancel</button>
                        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900 transition-colors">
                            Create Collection
                        </button>
                    </div>
                </form>
            </div>

            {{-- Collection grid --}}
            @if($collections->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <p class="text-lg font-medium">No collections yet</p>
                    <p class="text-sm mt-1">Organise your bookmarks by creating a collection above.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($collections as $collection)
                        <div class="group bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-gray-300 transition-all duration-200">
                            {{-- Color accent bar --}}
                            <div class="h-2" style="background-color: {{ $collection->color ?? '#6B7280' }}"></div>

                            <div class="p-6">
                                {{-- Header row --}}
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3 min-w-0">
                                        {{-- Color circle icon --}}
                                        <div class="w-10 h-10 rounded-lg flex-shrink-0 flex items-center justify-center"
                                             style="background-color: {{ $collection->color ?? '#6B7280' }}20;">
                                            <svg class="w-5 h-5" style="color: {{ $collection->color ?? '#6B7280' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        
                                        {{-- Title --}}
                                        <a href="{{ route('collections.show', $collection) }}"
                                           class="font-semibold text-gray-900 hover:text-gray-700 truncate">
                                            {{ $collection->name }}
                                        </a>
                                    </div>

                                    {{-- Edit button --}}
                                    <a href="{{ route('collections.edit', $collection) }}"
                                       class="opacity-0 group-hover:opacity-100 transition-opacity p-1.5 rounded-lg hover:bg-gray-100 flex-shrink-0"
                                       title="Edit collection">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                </div>

                                {{-- Description --}}
                                @if($collection->description)
                                    <p class="text-sm text-gray-500 mt-3 line-clamp-2">{{ $collection->description }}</p>
                                @else
                                    <p class="text-sm text-gray-400 mt-3 italic">No description</p>
                                @endif

                                {{-- Footer stats --}}
                                <div class="flex items-center justify-between mt-5 pt-4 border-t border-gray-100">
                                    <div class="flex items-center gap-2 text-sm text-gray-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        <span>{{ $collection->bookmarks_count }} {{ Str::plural('bookmark', $collection->bookmarks_count) }}</span>
                                    </div>

                                    @if($collection->is_public)
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                                            </svg>
                                            Public
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Private
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
    <script>
        // Re-open form on validation errors so the user sees them
        @if($errors->any())
            document.getElementById('create-form').classList.remove('hidden');
        @endif
    </script>
    @endpush
</x-app-layout>
