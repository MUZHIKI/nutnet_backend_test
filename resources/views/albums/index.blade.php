@extends('layouts.app')

@section('title', 'Список альбомов')

@section('content')
    <section class="hero">
        <div>
            <h2>Коллекция культовых пластинок</h2>
            <p>Здесь собраны культовые альбомы российских и зарубежных групп.</p>
        </div>

        <div class="hero-meta">
            <div class="hero-meta__item">
                <strong>{{ $albums->total() }}</strong>
                <span>альбомов в подборке</span>
            </div>
            <div class="hero-meta__item">
                <strong>Last.fm</strong>
                <span>автозаполнение данных для формы редактирования</span>
            </div>
        </div>
    </section>

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
                    @php
                        $fallbackLetters = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($album->title, 0, 2));
                    @endphp
                    <article class="album-card">
                        <div class="album-cover" data-cover-root>
                            @if ($album->cover_url)
                                <img
                                    src="{{ $album->cover_url }}"
                                    alt="Обложка альбома {{ $album->title }}"
                                    loading="lazy"
                                    referrerpolicy="no-referrer"
                                    data-cover-image
                                >
                            @endif

                            <div class="album-cover__fallback" data-cover-fallback @if($album->cover_url) hidden @endif>
                                <span>{{ $fallbackLetters }}</span>
                            </div>
                        </div>

                        <div class="album-card__body">
                            <div class="album-meta">{{ $album->artist }}</div>
                            <h2>{{ $album->title }}</h2>
                            <div class="muted">{{ \Illuminate\Support\Str::limit($album->description, 175) }}</div>

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

            @if ($albums->hasPages())
                @php
                    $current = $albums->currentPage();
                    $last = $albums->lastPage();
                    $start = max(1, $current - 1);
                    $end = min($last, $current + 1);
                @endphp

                <nav class="pager" aria-label="Пагинация">
                    @if ($albums->onFirstPage())
                        <span class="pager__dots">←</span>
                    @else
                        <a class="pager__link" href="{{ $albums->previousPageUrl() }}">←</a>
                    @endif

                    @if ($start > 1)
                        <a class="pager__link" href="{{ $albums->url(1) }}">1</a>
                        @if ($start > 2)
                            <span class="pager__dots">…</span>
                        @endif
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page === $current)
                            <span class="pager__current">{{ $page }}</span>
                        @else
                            <a class="pager__link" href="{{ $albums->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($end < $last)
                        @if ($end < $last - 1)
                            <span class="pager__dots">…</span>
                        @endif
                        <a class="pager__link" href="{{ $albums->url($last) }}">{{ $last }}</a>
                    @endif

                    @if ($albums->hasMorePages())
                        <a class="pager__link" href="{{ $albums->nextPageUrl() }}">→</a>
                    @else
                        <span class="pager__dots">→</span>
                    @endif
                </nav>
            @endif
        @else
            <div class="empty">
                По вашему запросу альбомы не найдены.
            </div>
        @endif
    </section>
@endsection
