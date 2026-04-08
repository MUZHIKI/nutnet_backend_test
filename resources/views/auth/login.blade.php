@extends('layouts.app')

@section('title', 'Вход')

@section('content')
    <section class="panel auth-card">
        <h2 style="margin-top: 0; font-family: 'Space Grotesk', sans-serif; font-weight: 700; font-size: 34px; letter-spacing: -0.04em;">Вход в систему</h2>
        <p class="muted">Создание, редактирование и удаление альбомов доступны только авторизованному пользователю.</p>

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

            <label style="display: flex; align-items: center; gap: 10px; font-weight: 500;">
                <input style="width: auto;" type="checkbox" name="remember" value="1">
                Запомнить меня
            </label>

            <button class="btn btn-primary" type="submit">Войти</button>
        </form>

        <div class="status" style="margin-top: 20px;">
            Тестовый пользователь: `admin@example.com` / `password`
        </div>
    </section>
@endsection
