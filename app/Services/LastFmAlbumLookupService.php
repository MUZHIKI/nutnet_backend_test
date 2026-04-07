<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LastFmAlbumLookupService
{
    public function searchByTitle(string $title): array
    {
        $apiKey = config('services.lastfm.key');

        if (! $apiKey) {
            throw new RuntimeException('Не задан LASTFM_API_KEY.');
        }

        try {
            $response = Http::timeout(10)
                ->retry(2, 300)
                ->get(config('services.lastfm.base_url'), [
                    'method' => 'album.search',
                    'album' => $title,
                    'api_key' => $apiKey,
                    'format' => 'json',
                    'limit' => 1,
                ])
                ->throw()
                ->json();
        } catch (ConnectionException $exception) {
            throw new RuntimeException('Не удалось подключиться к Last.fm.', previous: $exception);
        }

        $match = Arr::first(Arr::wrap(data_get($response, 'results.albummatches.album')));

        if (! $match) {
            throw new RuntimeException('Альбом не найден в Last.fm.');
        }

        $artist = data_get($match, 'artist');
        $albumTitle = data_get($match, 'name');

        $details = Http::timeout(10)
            ->retry(2, 300)
            ->get(config('services.lastfm.base_url'), [
                'method' => 'album.getinfo',
                'artist' => $artist,
                'album' => $albumTitle,
                'api_key' => $apiKey,
                'format' => 'json',
            ])
            ->throw()
            ->json();

        return [
            'title' => $albumTitle,
            'artist' => $artist,
            'description' => trim(strip_tags((string) data_get($details, 'album.wiki.summary', ''))),
            'cover_url' => $this->resolveImage(
                data_get($details, 'album.image', data_get($match, 'image', []))
            ),
        ];
    }

    protected function resolveImage(array $images): ?string
    {
        $candidate = collect($images)
            ->reverse()
            ->first(fn (array $image) => filled(data_get($image, '#text')));

        return data_get($candidate, '#text');
    }
}
