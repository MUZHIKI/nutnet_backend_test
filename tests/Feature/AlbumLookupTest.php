<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AlbumLookupTest extends TestCase
{
    use RefreshDatabase;

    public function test_authorized_user_can_fetch_album_data_from_lastfm_endpoint(): void
    {
        config()->set('services.lastfm.key', 'test-key');

        Http::fake([
            '*' => Http::sequence()
                ->push([
                    'results' => [
                        'albummatches' => [
                            'album' => [
                                [
                                    'name' => 'Rumours',
                                    'artist' => 'Fleetwood Mac',
                                    'image' => [
                                        ['#text' => ''],
                                        ['#text' => 'https://example.com/rumours.jpg'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])
                ->push([
                    'album' => [
                        'wiki' => [
                            'summary' => 'Легендарный альбом группы.',
                        ],
                        'image' => [
                            ['#text' => 'https://example.com/rumours-large.jpg'],
                        ],
                    ],
                ]),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('api.albums.lookup', ['title' => 'Rumours']));

        $response->assertOk();
        $response->assertJsonPath('data.artist', 'Fleetwood Mac');
        $response->assertJsonPath('data.cover_url', 'https://example.com/rumours-large.jpg');
    }

    public function test_authorized_user_can_fetch_album_data_by_artist_only(): void
    {
        config()->set('services.lastfm.key', 'test-key');

        Http::fake([
            '*' => Http::sequence()
                ->push([
                    'topalbums' => [
                        'album' => [
                            [
                                'name' => '25-й кадр',
                                'artist' => [
                                    'name' => 'Сплин',
                                ],
                                'image' => [
                                    ['#text' => 'https://example.com/splean.jpg'],
                                ],
                            ],
                        ],
                    ],
                ])
                ->push([
                    'album' => [
                        'name' => '25-й кадр',
                        'artist' => 'Сплин',
                        'wiki' => [
                            'summary' => 'Описание альбома.',
                        ],
                        'image' => [
                            ['#text' => 'https://example.com/splean-large.jpg'],
                        ],
                    ],
                ]),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('api.albums.lookup', ['artist' => 'Сплин']));

        $response->assertOk();
        $response->assertJsonPath('data.title', '25-й кадр');
        $response->assertJsonPath('data.artist', 'Сплин');
    }

    public function test_authorized_user_can_fetch_album_data_by_title_and_artist(): void
    {
        config()->set('services.lastfm.key', 'test-key');

        Http::fake([
            '*' => Http::response([
                'album' => [
                    'name' => 'Группа крови',
                    'artist' => 'Кино',
                    'wiki' => [
                        'summary' => 'Описание альбома Кино.',
                    ],
                    'image' => [
                        ['#text' => 'https://example.com/kino.jpg'],
                    ],
                ],
            ]),
        ]);

        $response = $this->actingAs(User::factory()->create())
            ->getJson(route('api.albums.lookup', ['title' => 'Группа крови', 'artist' => 'Кино']));

        $response->assertOk();
        $response->assertJsonPath('data.title', 'Группа крови');
        $response->assertJsonPath('data.artist', 'Кино');
        $response->assertJsonPath('data.cover_url', 'https://example.com/kino.jpg');
    }
}
