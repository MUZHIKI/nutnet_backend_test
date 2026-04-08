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
            'title' => ['nullable', 'string', 'max:255', 'required_without:artist'],
            'artist' => ['nullable', 'string', 'max:255', 'required_without:title'],
        ]);

        try {
            return response()->json([
                'data' => $service->search(
                    $validated['title'] ?? null,
                    $validated['artist'] ?? null
                ),
            ]);
        } catch (RuntimeException $exception) {
            $message = $exception->getMessage() === 'Не удалось подключиться к Last.fm.'
                ? 'Не удалось подключиться к Last.fm. Скорее всего, сервис недоступен из текущей сети.'
                : $exception->getMessage();

            return response()->json([
                'message' => $message,
            ], 422);
        }
    }
}

