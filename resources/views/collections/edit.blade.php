<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('collections.show', $collection) }}" class="text-gray-400 hover:text-gray-600">
                &larr;
            </a>
            <h2 class="text-xl font-semibold text-gray-800">Edit Collection</h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('collections.update', $collection) }}" method="POST"
                  class="space-y-6 bg-white p-6 rounded-lg border">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" required
                           value="{{ old('name', $collection->name) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">
                        Description <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $collection->description) }}</textarea>
                    @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="color" class="block text-sm font-medium text-gray-700">Color</label>
                    <div class="mt-1 flex items-center gap-3">
                        <input type="color" name="color" id="color"
                               value="{{ old('color', $collection->color ?? '#6366F1') }}"
                               class="h-9 w-16 rounded border-gray-300 cursor-pointer">
                        <span class="text-xs text-gray-400">This colour appears on collection badges and the bar above cards.</span>
                    </div>
                    @error('color') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="flex items-center gap-2">
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox" name="is_public" value="1"
                               class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                               {{ old('is_public', $collection->is_public) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Make this collection public</span>
                    </label>
                </div>

                <div class="flex justify-between items-center">
                    <form action="{{ route('collections.destroy', $collection) }}" method="POST"
                          onsubmit="return confirm('Delete this collection? Bookmarks will not be deleted.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-500 hover:text-red-700">
                            Delete collection
                        </button>
                    </form>

                    <div class="flex gap-3">
                        <a href="{{ route('collections.show', $collection) }}"
                           class="px-4 py-2 text-gray-600 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="bg-gray-800 text-white px-6 py-2 rounded-lg hover:bg-gray-900">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
