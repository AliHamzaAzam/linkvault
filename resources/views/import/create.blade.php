<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Import Bookmarks</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Instructions --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h3 class="text-lg font-medium text-blue-900 mb-4">How to Export Bookmarks</h3>
                
                <div class="space-y-4 text-blue-800 text-sm">
                    <div>
                        <h4 class="font-semibold">Google Chrome</h4>
                        <ol class="list-decimal list-inside ml-2 mt-1 space-y-1">
                            <li>Go to <code class="bg-blue-100 px-1 rounded">chrome://bookmarks</code></li>
                            <li>Click the three dots menu (⋮) → "Export bookmarks"</li>
                            <li>Save the HTML file</li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold">Mozilla Firefox</h4>
                        <ol class="list-decimal list-inside ml-2 mt-1 space-y-1">
                            <li>Press <code class="bg-blue-100 px-1 rounded">Ctrl+Shift+O</code> (or Cmd+Shift+O on Mac)</li>
                            <li>Click "Import and Backup" → "Export Bookmarks to HTML"</li>
                            <li>Save the HTML file</li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold">Safari</h4>
                        <ol class="list-decimal list-inside ml-2 mt-1 space-y-1">
                            <li>Go to File → Export → Bookmarks</li>
                            <li>Save the HTML file</li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-semibold">Microsoft Edge</h4>
                        <ol class="list-decimal list-inside ml-2 mt-1 space-y-1">
                            <li>Go to <code class="bg-blue-100 px-1 rounded">edge://favorites</code></li>
                            <li>Click the three dots menu (⋯) → "Export favorites"</li>
                            <li>Save the HTML file</li>
                        </ol>
                    </div>
                </div>
            </div>

            {{-- Upload Form --}}
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <form action="{{ route('import.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                            Bookmarks File
                        </label>
                        <input type="file"
                               id="file"
                               name="file"
                               accept=".html,.htm"
                               class="block w-full text-sm text-gray-500
                                      file:mr-4 file:py-2 file:px-4
                                      file:rounded-lg file:border-0
                                      file:text-sm file:font-semibold
                                      file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100
                                      @error('file') border-red-300 @enderror"
                               required>
                        
                        <p class="mt-2 text-sm text-gray-500">
                            Accepted formats: HTML, HTM
                        </p>
                        
                        @error('file')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div class="text-sm text-yellow-800">
                                <p class="font-medium">Important Notes:</p>
                                <ul class="list-disc list-inside mt-1 space-y-1">
                                    <li>Maximum file size: <strong>5MB</strong></li>
                                    <li>Maximum bookmarks per import: <strong>1,000</strong></li>
                                    <li>Bookmarks will be imported as <strong>private</strong> by default</li>
                                    <li>Duplicate URLs will be skipped but added to their folders</li>
                                    <li>Folder structure will be preserved as collections</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <button type="submit"
                                class="bg-gray-800 text-white px-6 py-2.5 rounded-lg hover:bg-gray-900 font-medium">
                            Import Bookmarks
                        </button>
                        <a href="{{ route('bookmarks.index') }}" class="text-gray-600 hover:text-gray-800">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
