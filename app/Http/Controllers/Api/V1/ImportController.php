<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportBookmarksRequest;
use App\Services\BookmarkImportService;
use Illuminate\Http\JsonResponse;

class ImportController extends Controller
{
    public function __construct(
        private BookmarkImportService $importService
    ) {}

    public function store(ImportBookmarksRequest $request): JsonResponse
    {
        $file = $request->file('file');
        $html = $file->getContent();

        $result = $this->importService->import($request->user(), $html);

        return response()->json([
            'message' => 'Import completed successfully',
            'data' => $result,
        ]);
    }
}
