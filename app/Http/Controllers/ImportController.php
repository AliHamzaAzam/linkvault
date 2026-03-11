<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportBookmarksRequest;
use App\Services\BookmarkImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ImportController extends Controller
{
    public function __construct(
        private BookmarkImportService $importService
    ) {}

    /**
     * Display the import form.
     */
    public function create(): View
    {
        return view('import.create');
    }

    /**
     * Handle the import form submission.
     */
    public function store(ImportBookmarksRequest $request): RedirectResponse
    {
        $file = $request->file('file');
        $html = $file->getContent();

        $result = $this->importService->import($request->user(), $html);

        $message = sprintf(
            'Import complete! %d bookmarks imported, %d skipped, %d collections created.',
            $result['imported'],
            $result['skipped'],
            $result['collections_created']
        );

        return redirect()->route('bookmarks.index')
            ->with('success', $message);
    }
}
