@extends('layouts.app')

@php
    $isEdit = $mode === 'edit';
    $title = $isEdit ? 'Редактирование альбома' : 'Новый альбом';
@endphp

@section('title', $title)

@section('content')
    <section class="panel">
        <div style="display: flex; justify-content: space-between; gap: 16px; align-items: start; flex-wrap: wrap; margin-bottom: 24px;">
            <div>
                <h2 style="margin: 0 0 8px; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 34px; letter-spacing: -0.04em;">{{ $title }}</h2>
                <p class="muted" style="margin: 0;">Поля можно заполнить вручную или подтянуть из Last.fm по названию альбома и исполнителю.</p>
            </div>

            <a class="btn btn-secondary" href="{{ route('albums.index') }}">К списку</a>
        </div>

        <form
            method="POST"
            action="{{ $isEdit ? route('albums.update', $album) : route('albums.store') }}"
            class="form-grid"
            id="album-form"
        >
            @csrf
            @if ($isEdit)
                @method('PUT')
            @endif

            <div class="form-grid form-grid--two">
                <label>
                    Название альбома
                    <input
                        type="text"
                        name="title"
                        id="title"
                        value="{{ old('title', $album->title) }}"
                        placeholder="Например, Группа крови"
                        required
                    >
                </label>

                <div style="display: flex; align-items: end;">
                    <button class="btn btn-primary" type="button" id="lookup-button">Заполнить из Last.fm</button>
                </div>
            </div>
            <p class="muted" style="margin: -8px 0 0; font-size: 14px;">
                Для автозаполнения можно указать только альбом, только исполнителя или оба поля сразу.
            </p>
            <div id="lookup-message" class="error" style="display: none; margin-top: -6px;"></div>
            @error('title')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>
                Исполнитель
                <input type="text" name="artist" id="artist" value="{{ old('artist', $album->artist) }}" required>
            </label>
            @error('artist')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>
                Ссылка на обложку
                <input type="url" name="cover_url" id="cover_url" value="{{ old('cover_url', $album->cover_url) }}">
            </label>
            @error('cover_url')
                <div class="error">{{ $message }}</div>
            @enderror

            <img
                src="{{ old('cover_url', $album->cover_url) }}"
                alt="Предпросмотр обложки"
                id="cover-preview"
                class="album-cover-preview"
                style="max-width: 320px; border-radius: 22px; border: 1px solid rgba(255, 255, 255, 0.08); {{ old('cover_url', $album->cover_url) ? '' : 'display:none;' }}"
            >

            <label>
                Описание
                <textarea name="description" id="description">{{ old('description', $album->description) }}</textarea>
            </label>
            @error('description')
                <div class="error">{{ $message }}</div>
            @enderror

            <div class="inline-actions">
                <button class="btn btn-primary" type="submit">{{ $isEdit ? 'Сохранить изменения' : 'Создать альбом' }}</button>
            </div>
        </form>

        @if ($isEdit)
            <form method="POST" action="{{ route('albums.destroy', $album) }}" onsubmit="return confirm('Удалить альбом?');" style="margin-top: 16px;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Удалить альбом</button>
            </form>
        @endif

        @if ($isEdit)
            <div class="logs">
                <h3 style="margin-bottom: 0; font-size: 22px;">История изменений</h3>

                @forelse ($album->logs as $log)
                    <article class="log-item">
                        <div style="display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap;">
                            <strong>{{ match ($log->action) {
                                'created' => 'Создание',
                                'updated' => 'Изменение',
                                'deleted' => 'Удаление',
                                default => $log->action,
                            } }}</strong>
                            <span class="muted">{{ $log->created_at?->format('d.m.Y H:i') }} @if($log->user) · {{ $log->user->name }} @endif</span>
                        </div>
                        <pre style="white-space: pre-wrap; margin: 12px 0 0; font-family: Consolas, monospace;">{{ json_encode($log->changes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                    </article>
                @empty
                    <div class="empty">Для этого альбома история изменений пока пуста.</div>
                @endforelse
            </div>
        @endif
    </section>
@endsection

@push('scripts')
    <script>
        const lookupButton = document.getElementById('lookup-button');
        const titleInput = document.getElementById('title');
        const artistInput = document.getElementById('artist');
        const descriptionInput = document.getElementById('description');
        const coverInput = document.getElementById('cover_url');
        const coverPreview = document.getElementById('cover-preview');
        const lookupMessage = document.getElementById('lookup-message');

        function syncPreview(url) {
            if (url) {
                coverPreview.src = url;
                coverPreview.style.display = 'block';
            } else {
                coverPreview.style.display = 'none';
            }
        }

        function showLookupMessage(message) {
            lookupMessage.textContent = message;
            lookupMessage.style.display = 'block';
        }

        function clearLookupMessage() {
            lookupMessage.textContent = '';
            lookupMessage.style.display = 'none';
        }

        function resetLookupFields() {
            artistInput.value = '';
            descriptionInput.value = '';
            coverInput.value = '';
            syncPreview('');
        }

        coverInput.addEventListener('input', (event) => {
            syncPreview(event.target.value);
        });

        lookupButton?.addEventListener('click', async () => {
            const title = titleInput.value.trim();
            const artist = artistInput.value.trim();

            if (!title && !artist) {
                showLookupMessage('Укажите название альбома, исполнителя или оба поля для поиска.');
                return;
            }

            clearLookupMessage();
            lookupButton.disabled = true;
            lookupButton.textContent = 'Ищем...';
            resetLookupFields();

            try {
                const params = new URLSearchParams();

                if (title) {
                    params.set('title', title);
                }

                if (artist) {
                    params.set('artist', artist);
                }

                const response = await fetch(`/api/albums/lookup?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin',
                });

                const payload = await response.json();

                if (!response.ok) {
                    throw new Error(payload.message || 'Не удалось получить данные.');
                }

                titleInput.value = payload.data.title || title;
                artistInput.value = payload.data.artist || '';
                descriptionInput.value = payload.data.description || '';
                coverInput.value = payload.data.cover_url || '';
                syncPreview(coverInput.value);
            } catch (error) {
                showLookupMessage(error.message);
            } finally {
                lookupButton.disabled = false;
                lookupButton.textContent = 'Заполнить из Last.fm';
            }
        });
    </script>
@endpush

