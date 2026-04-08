@extends('layouts.app')

@section('title', 'Журнал изменений')

@section('content')
    <section class="panel">
        <div style="display: flex; justify-content: space-between; gap: 16px; align-items: start; flex-wrap: wrap; margin-bottom: 24px;">
            <div>
                <h2 style="margin: 0 0 8px; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 34px; letter-spacing: -0.04em;">Журнал изменений</h2>
                <p class="muted" style="margin: 0;">Здесь видна вся история создания, редактирования и удаления альбомов.</p>
            </div>

            <a class="btn btn-secondary" href="{{ route('albums.index') }}">К списку</a>
        </div>

        <div class="logs">
            @forelse ($logs as $log)
                @php
                    $snapshot = $log->changes ?? [];
                    $albumTitle = $log->album?->title
                        ?? data_get($snapshot, 'title.after')
                        ?? data_get($snapshot, 'title')
                        ?? 'Удалённый альбом';
                    $albumArtist = $log->album?->artist
                        ?? data_get($snapshot, 'artist.after')
                        ?? data_get($snapshot, 'artist');
                @endphp

                <article class="log-item">
                    <div style="display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
                        <div style="display: grid; gap: 6px;">
                            <strong>{{ match ($log->action) {
                                'created' => 'Создание',
                                'updated' => 'Изменение',
                                'deleted' => 'Удаление',
                                default => $log->action,
                            } }}</strong>
                            <div class="muted">
                                {{ $albumTitle }}
                                @if ($albumArtist)
                                    · {{ $albumArtist }}
                                @endif
                            </div>
                        </div>

                        <span class="muted">{{ $log->created_at?->format('d.m.Y H:i') }} @if($log->user) · {{ $log->user->name }} @endif</span>
                    </div>

                    <pre style="white-space: pre-wrap; margin: 12px 0 0; font-family: Consolas, monospace;">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                </article>
            @empty
                <div class="empty">Журнал пока пуст.</div>
            @endforelse
        </div>

        @if ($logs->hasPages())
            <nav class="pager" aria-label="Пагинация журнала">
                @if ($logs->onFirstPage())
                    <span class="pager__dots">←</span>
                @else
                    <a class="pager__link" href="{{ $logs->previousPageUrl() }}">←</a>
                @endif

                @foreach ($logs->getUrlRange(max(1, $logs->currentPage() - 1), min($logs->lastPage(), $logs->currentPage() + 1)) as $page => $url)
                    @if ($page === $logs->currentPage())
                        <span class="pager__current">{{ $page }}</span>
                    @else
                        <a class="pager__link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($logs->hasMorePages())
                    <a class="pager__link" href="{{ $logs->nextPageUrl() }}">→</a>
                @else
                    <span class="pager__dots">→</span>
                @endif
            </nav>
        @endif
    </section>
@endsection
