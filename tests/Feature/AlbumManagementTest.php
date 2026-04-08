<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\AlbumLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlbumManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_open_album_list(): void
    {
        Album::factory()->create([
            'title' => 'Hybrid Theory',
            'artist' => 'Linkin Park',
        ]);

        $response = $this->get(route('albums.index'));

        $response->assertOk();
        $response->assertSee('Hybrid Theory');
        $response->assertSee('Linkin Park');
    }

    public function test_guest_can_search_album_by_russian_artist_name(): void
    {
        Album::factory()->create([
            'title' => 'Группа крови',
            'artist' => 'Кино',
        ]);

        Album::factory()->create([
            'title' => 'Nevermind',
            'artist' => 'Nirvana',
        ]);

        $response = $this->get('/?search='.urlencode('Кино'));

        $response->assertOk();
        $response->assertSee('Группа крови');
        $response->assertSee('Кино');
        $response->assertDontSee('Nevermind');
    }

    public function test_guest_cannot_open_album_create_form(): void
    {
        $response = $this->get(route('albums.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_authorized_user_can_create_album_and_change_is_logged(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('albums.store'), [
            'title' => 'Back in Black',
            'artist' => 'AC/DC',
            'description' => 'Студийный альбом австралийской рок-группы.',
            'cover_url' => 'https://example.com/back-in-black.jpg',
        ]);

        $response->assertRedirect(route('albums.index'));

        $this->assertDatabaseHas('albums', [
            'title' => 'Back in Black',
            'artist' => 'AC/DC',
        ]);

        $this->assertDatabaseHas('album_logs', [
            'action' => 'created',
            'user_id' => $user->id,
        ]);
    }

    public function test_authorized_user_can_update_album_and_audit_log_contains_changed_fields(): void
    {
        $user = User::factory()->create();
        $album = Album::factory()->create([
            'title' => 'Discovery',
            'artist' => 'Daft Punk',
        ]);

        $response = $this->actingAs($user)->put(route('albums.update', $album), [
            'title' => 'Discovery',
            'artist' => 'Daft Punk',
            'description' => 'Обновлённое описание.',
            'cover_url' => 'https://example.com/discovery.jpg',
        ]);

        $response->assertRedirect(route('albums.edit', $album));

        $log = AlbumLog::query()->where('album_id', $album->id)->where('action', 'updated')->first();

        $this->assertNotNull($log);
        $this->assertArrayHasKey('description', $log->changes);
        $this->assertSame('Обновлённое описание.', $log->changes['description']['after']);
    }

    public function test_authorized_user_can_open_global_logs_and_see_deleted_album_entry(): void
    {
        $user = User::factory()->create();
        $album = Album::factory()->create([
            'title' => 'Abbey Road',
            'artist' => 'The Beatles',
        ]);

        $this->actingAs($user)->delete(route('albums.destroy', $album));

        $response = $this->actingAs($user)->get(route('albums.logs'));

        $response->assertOk();
        $response->assertSee('Abbey Road');
        $response->assertSee('The Beatles');
        $this->assertDatabaseHas('album_logs', [
            'action' => 'deleted',
            'user_id' => $user->id,
        ]);

        $log = AlbumLog::query()->where('action', 'deleted')->latest()->first();

        $this->assertSame('Abbey Road', $log?->changes['title'] ?? null);
        $this->assertSame('The Beatles', $log?->changes['artist'] ?? null);
    }
}
