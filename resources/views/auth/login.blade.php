@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <section class="panel auth-card">
        <h2 style="margin-top: 0;">Вход в систему</h2>
        <p class="muted">Редактирование и создание альбомов доступно только авторизованному пользователю.</p>

        <form method="POST" action="{{ route('login.store') }}" class="form-grid">
            @csrf

            <label>
                Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>
                Пароль
                <input type="password" name="password" required>
            </label>
            @error('password')
                <div class="error">{{ $message }}</div>
            @enderror

            <label style="display: flex; align-items: center; gap: 10px; font-weight: 400;">
                <input style="width: auto;" type="checkbox" name="remember" value="1">
                Запомнить меня
            </label>

            <button class="btn btn-primary" type="submit">Войти</button>
        </form>

        <div class="status" style="margin-top: 20px;">
            Тестовый пользователь после `db:seed`: `admin@example.com` / `password`
        </div>
    </section>
@endsection
