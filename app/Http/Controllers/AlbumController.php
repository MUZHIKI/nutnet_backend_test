<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlbumStoreRequest;
use App\Http\Requests\AlbumUpdateRequest;
use App\Models\Album;
use App\Models\AlbumLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlbumController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $albums = Album::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('artist', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('albums.index', compact('albums', 'search'));
    }

    public function create(): View
    {
        return view('albums.form', [
            'album' => new Album(),
            'mode' => 'create',
        ]);
    }

    public function store(AlbumStoreRequest $request): RedirectResponse
    {
        $album = Album::create($request->validated());

        $this->logChanges($album, 'created', $album->toArray());

        return redirect()->route('albums.index')
            ->with('status', 'Альбом успешно добавлен.');
    }

    public function edit(Album $album): View
    {
        $album->load('logs.user');

        return view('albums.form', [
            'album' => $album,
            'mode' => 'edit',
        ]);
    }

    public function update(AlbumUpdateRequest $request, Album $album): RedirectResponse
    {
        $before = $album->only(['title', 'artist', 'description', 'cover_url']);

        $album->update($request->validated());

        $changes = collect($album->only(['title', 'artist', 'description', 'cover_url']))
            ->map(function ($value, $field) use ($before) {
                return [
                    'before' => $before[$field] ?? null,
                    'after' => $value,
                ];
            })
            ->filter(fn (array $change) => $change['before'] !== $change['after'])
            ->all();

        if ($changes !== []) {
            $this->logChanges($album, 'updated', $changes);
        }

        return redirect()->route('albums.edit', $album)
            ->with('status', 'Изменения сохранены.');
    }

    public function destroy(Album $album): RedirectResponse
    {
        $snapshot = $album->only(['title', 'artist', 'description', 'cover_url']);

        $this->logChanges($album, 'deleted', $snapshot);
        $album->delete();

        return redirect()->route('albums.index')
            ->with('status', 'Альбом удалён.');
    }

    protected function logChanges(Album $album, string $action, array $changes): void
    {
        AlbumLog::create([
            'album_id' => $album->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'changes' => $changes,
        ]);
    }
}
