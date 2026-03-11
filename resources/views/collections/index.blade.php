<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-800">Collections</h2>
            <button onclick="document.getElementById('create-form').classList.toggle('hidden')"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                + New Collection
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Inline create form --}}
            <div id="create-form" class="hidden bg-white rounded-lg border p-6">
                <h3 class="text-sm font-medium text-gray-700 mb-4">New Collection</h3>
                <form action="{{ route('collections.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="name" id="name" required
                                   value="{{ old('name') }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input type="color" name="color" id="color"
                                       value="{{ old('color', '#6366F1') }}"
                                       class="h-9 w-16 rounded border-gray-300 cursor-pointer">
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
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input type="hidden" name="is_public" value="0">
                            <input type="checkbox" name="is_public" value="1"
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                   {{ old('is_public') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Make this collection public</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button"
                                onclick="document.getElementById('create-form').classList.add('hidden')"
                                class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</button>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                            Create Collection
                        </button>
                    </div>
                </form>
            </div>

            {{-- Collection grid --}}
            @if($collections->isEmpty())
                <div class="text-center py-16 text-gray-500">
                    <p class="text-lg">No collections yet</p>
                    <p class="text-sm mt-1">Organise your bookmarks by creating a collection above.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($collections as $collection)
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
                            {{-- Colour bar --}}
                            <div class="h-1.5" style="background-color: {{ $collection->color ?? '#6B7280' }}"></div>

                            <div class="p-5">
                                <div class="flex items-start justify-between gap-2">
                                    <a href="{{ route('collections.show', $collection) }}"
                                       class="font-medium text-gray-900 hover:text-blue-600">
                                        {{ $collection->name }}
                                    </a>
                                    <a href="{{ route('collections.edit', $collection) }}"
                                       class="text-xs text-gray-400 hover:text-gray-600 flex-shrink-0">Edit</a>
                                </div>

                                @if($collection->description)
                                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $collection->description }}</p>
                                @endif

                                <div class="flex items-center justify-between mt-4 pt-3 border-t border-gray-100">
                                    <span class="text-xs text-gray-400">
                                        {{ $collection->bookmarks_count }}
                                        {{ Str::plural('bookmark', $collection->bookmarks_count) }}
                                    </span>

                                    @if($collection->is_public)
                                        <span class="text-xs text-green-600">Public</span>
                                    @else
                                        <span class="text-xs text-gray-400">Private</span>
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
