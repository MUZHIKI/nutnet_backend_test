<?php

namespace App\Http\Controllers;

use App\Services\LastFmAlbumLookupService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class AlbumLookupController extends Controller
{
    public function __invoke(Request $request, LastFmAlbumLookupService $service): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
        ]);

        try {
            return response()->json([
                'data' => $service->searchByTitle($validated['title']),
            ]);
        } catch (RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }
}
