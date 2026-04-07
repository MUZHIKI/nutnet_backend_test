@extends('layouts.app')

@section('title', 'Список альбомов')

@section('content')
    <section class="panel">
        <form method="GET" action="{{ route('albums.index') }}" class="search-form">
            <input
                type="search"
                name="search"
                value="{{ $search }}"
                placeholder="Поиск по названию или исполнителю"
            >
            <button class="btn btn-secondary" type="submit">Найти</button>
        </form>

        @if ($albums->count())
            <div class="grid">
                @foreach ($albums as $album)
                    <article class="album-card">
                        @if ($album->cover_url)
                            <img src="{{ $album->cover_url }}" alt="Обложка альбома {{ $album->title }}">
                        @else
                            <div class="album-cover-preview"></div>
                        @endif

                        <div class="album-card__body">
                            <div class="album-meta">{{ $album->artist }}</div>
                            <h2>{{ $album->title }}</h2>
                            <div class="muted">{{ \Illuminate\Support\Str::limit($album->description, 180) }}</div>

                            @auth
                                <div class="inline-actions">
                                    <a class="btn btn-secondary" href="{{ route('albums.edit', $album) }}">Редактировать</a>

                                    <form method="POST" action="{{ route('albums.destroy', $album) }}" onsubmit="return confirm('Удалить альбом?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger" type="submit">Удалить</button>
                                    </form>
                                </div>
                            @endauth
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="pagination">
                {{ $albums->links() }}
            </div>
        @else
            <div class="empty">
                По вашему запросу альбомы не найдены.
            </div>
        @endif
    </section>
@endsection
