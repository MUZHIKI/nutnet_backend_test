<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LastFmAlbumLookupService
{
    public function search(?string $title = null, ?string $artist = null): array
    {
        $apiKey = config('services.lastfm.key');
        $title = $title !== null ? trim($title) : null;
        $artist = $artist !== null ? trim($artist) : null;

        if (! $apiKey) {
            throw new RuntimeException('Не задан LASTFM_API_KEY.');
        }

        if (($title === null || $title === '') && ($artist === null || $artist === '')) {
            throw new RuntimeException('Укажите название альбома, исполнителя или оба поля.');
        }

        if ($title && $artist) {
            return $this->searchExactAlbum($title, $artist, $apiKey);
        }

        if ($title) {
            return $this->searchByTitle($title, $apiKey);
        }

        return $this->searchByArtist($artist ?? '', $apiKey);
    }

    protected function searchByTitle(string $title, string $apiKey): array
    {
        try {
            $response = Http::timeout(10)
                ->retry(2, 300)
                ->get(config('services.lastfm.base_url'), [
                    'method' => 'album.search',
                    'album' => $title,
                    'api_key' => $apiKey,
                    'format' => 'json',
                    'limit' => 10,
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

        return $this->buildAlbumPayload(
            (string) data_get($match, 'name'),
            (string) data_get($match, 'artist'),
            $apiKey,
            data_get($match, 'image', [])
        );
    }

    protected function searchByArtist(string $artist, string $apiKey): array
    {
        try {
            $response = Http::timeout(10)
                ->retry(2, 300)
                ->get(config('services.lastfm.base_url'), [
                    'method' => 'artist.gettopalbums',
                    'artist' => $artist,
                    'api_key' => $apiKey,
                    'format' => 'json',
                    'limit' => 1,
                ])
                ->throw()
                ->json();
        } catch (ConnectionException $exception) {
            throw new RuntimeException('Не удалось подключиться к Last.fm.', previous: $exception);
        }

        $match = Arr::first(Arr::wrap(data_get($response, 'topalbums.album')));

        if (! $match) {
            throw new RuntimeException('Исполнитель не найден в Last.fm.');
        }

        return $this->buildAlbumPayload(
            (string) data_get($match, 'name'),
            (string) data_get($match, 'artist.name', $artist),
            $apiKey,
            data_get($match, 'image', [])
        );
    }

    protected function searchExactAlbum(string $title, string $artist, string $apiKey): array
    {
        try {
            return $this->buildAlbumPayload($title, $artist, $apiKey);
        } catch (RuntimeException $exception) {
            return $this->searchByTitle($title, $apiKey);
        }
    }

    protected function buildAlbumPayload(string $title, string $artist, string $apiKey, array $fallbackImages = []): array
    {
        $details = Http::timeout(10)
            ->retry(2, 300)
            ->get(config('services.lastfm.base_url'), [
                'method' => 'album.getinfo',
                'artist' => $artist,
                'album' => $title,
                'api_key' => $apiKey,
                'format' => 'json',
            ])
            ->throw()
            ->json();

        return [
            'title' => (string) data_get($details, 'album.name', $title),
            'artist' => (string) data_get($details, 'album.artist', $artist),
            'description' => trim(strip_tags((string) data_get($details, 'album.wiki.summary', ''))),
            'cover_url' => $this->resolveImage(
                data_get($details, 'album.image', $fallbackImages)
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
