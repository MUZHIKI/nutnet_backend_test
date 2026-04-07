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
}
