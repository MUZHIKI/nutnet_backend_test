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
                        $descriptionPreview = \Illuminate\Support\Str::limit($album->description, 175);
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
                            <div class="muted">{{ $descriptionPreview }}</div>

                            @if (filled($album->description) && \Illuminate\Support\Str::length($album->description) > 175)
                                <button
                                    type="button"
                                    class="btn btn-secondary album-description-button"
                                    data-description-button
                                    data-title="{{ $album->title }}"
                                    data-artist="{{ $album->artist }}"
                                    data-description="{{ e($album->description) }}"
                                >
                                    Полное описание
                                </button>
                            @endif

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
                        <span class="pager__dots"><</span>
                    @else
                        <a class="pager__link" href="{{ $albums->previousPageUrl() }}"><</a>
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
                        <a class="pager__link" href="{{ $albums->nextPageUrl() }}">></a>
                    @else
                        <span class="pager__dots">></span>
                    @endif
                </nav>
            @endif
        @else
            <div class="empty">
                По вашему запросу альбомы не найдены.
            </div>
        @endif
    </section>

    <dialog class="album-description-dialog" id="album-description-dialog">
        <div class="album-description-dialog__header">
            <div>
                <div class="album-meta" id="album-description-dialog-artist"></div>
                <h3 id="album-description-dialog-title" style="margin: 6px 0 0; font-family: 'Space Grotesk', sans-serif; font-size: 28px; letter-spacing: -0.04em;"></h3>
            </div>
            <button type="button" class="btn btn-secondary" data-description-close>Закрыть</button>
        </div>
        <div class="muted" id="album-description-dialog-text" style="line-height: 1.7;"></div>
    </dialog>
@endsection

@push('head')
    <style>
        .album-description-button {
            width: fit-content;
            padding: 10px 14px;
        }

        .album-description-dialog {
            width: min(640px, calc(100% - 24px));
            padding: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            background: rgba(12, 22, 36, 0.98);
            color: #f4f7fb;
            box-shadow: 0 24px 64px rgba(0, 0, 0, 0.45);
        }

        .album-description-dialog::backdrop {
            background: rgba(3, 8, 15, 0.72);
            backdrop-filter: blur(6px);
        }

        .album-description-dialog__header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: start;
            margin-bottom: 18px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        const descriptionDialog = document.getElementById('album-description-dialog');
        const descriptionDialogTitle = document.getElementById('album-description-dialog-title');
        const descriptionDialogArtist = document.getElementById('album-description-dialog-artist');
        const descriptionDialogText = document.getElementById('album-description-dialog-text');

        document.querySelectorAll('[data-description-button]').forEach((button) => {
            button.addEventListener('click', () => {
                descriptionDialogTitle.textContent = button.dataset.title || '';
                descriptionDialogArtist.textContent = button.dataset.artist || '';
                descriptionDialogText.textContent = button.dataset.description || '';
                descriptionDialog.showModal();
            });
        });

        descriptionDialog?.addEventListener('click', (event) => {
            const rect = descriptionDialog.getBoundingClientRect();
            const isOutside = event.clientX < rect.left
                || event.clientX > rect.right
                || event.clientY < rect.top
                || event.clientY > rect.bottom;

            if (isOutside) {
                descriptionDialog.close();
            }
        });

        document.querySelector('[data-description-close]')?.addEventListener('click', () => {
            descriptionDialog?.close();
        });
    </script>
@endpush

