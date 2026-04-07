<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Справочник альбомов')</title>
    <style>
        :root {
            --bg: #f4f1ea;
            --surface: rgba(255, 255, 255, 0.92);
            --surface-strong: #ffffff;
            --text: #1f2933;
            --muted: #52606d;
            --accent: #b45309;
            --accent-dark: #7c2d12;
            --border: rgba(31, 41, 51, 0.12);
            --danger: #b91c1c;
            --shadow: 0 20px 50px rgba(31, 41, 51, 0.12);
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Georgia, "Times New Roman", serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(180, 83, 9, 0.18), transparent 32%),
                radial-gradient(circle at bottom right, rgba(124, 45, 18, 0.12), transparent 28%),
                linear-gradient(180deg, #f8f4ec 0%, var(--bg) 100%);
            min-height: 100vh;
        }

        a { color: inherit; text-decoration: none; }

        .container {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
        }

        .topbar {
            padding: 20px 0;
        }

        .topbar__inner {
            display: flex;
            gap: 16px;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            padding: 18px 22px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
        }

        .brand h1 {
            margin: 0;
            font-size: clamp(28px, 4vw, 42px);
            letter-spacing: 0.02em;
        }

        .brand p {
            margin: 6px 0 0;
            color: var(--muted);
        }

        .actions, .inline-actions {
            display: flex;
            gap: 12px;
            align-items: center;
            flex-wrap: wrap;
        }

        .main {
            padding-bottom: 40px;
        }

        .panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: var(--shadow);
            padding: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 20px;
        }

        .album-card {
            display: grid;
            gap: 14px;
            background: var(--surface-strong);
            border-radius: 22px;
            overflow: hidden;
            border: 1px solid var(--border);
            min-height: 100%;
        }

        .album-card img,
        .album-cover-preview {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            background: linear-gradient(135deg, #e5d3b3, #f7efe1);
        }

        .album-card__body {
            padding: 0 18px 18px;
            display: grid;
            gap: 8px;
        }

        .album-card h2 {
            margin: 0;
            font-size: 24px;
        }

        .album-meta {
            color: var(--accent-dark);
            font-weight: 700;
        }

        .muted {
            color: var(--muted);
        }

        .btn,
        button {
            appearance: none;
            border: 0;
            cursor: pointer;
            border-radius: 999px;
            padding: 12px 18px;
            font-size: 15px;
            font-weight: 700;
            transition: transform 0.16s ease, opacity 0.16s ease;
        }

        .btn:hover,
        button:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #d97706);
            color: #fff;
        }

        .btn-secondary {
            background: #fff;
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-danger {
            background: rgba(185, 28, 28, 0.12);
            color: var(--danger);
            border: 1px solid rgba(185, 28, 28, 0.2);
        }

        .form-grid {
            display: grid;
            gap: 18px;
        }

        .form-grid--two {
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        }

        label {
            display: grid;
            gap: 8px;
            font-weight: 700;
        }

        input,
        textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid var(--border);
            font: inherit;
            color: var(--text);
            background: #fffdf9;
        }

        textarea {
            min-height: 160px;
            resize: vertical;
        }

        .error {
            color: var(--danger);
            font-size: 14px;
            margin-top: -4px;
        }

        .status {
            margin-bottom: 18px;
            padding: 14px 18px;
            border-radius: 16px;
            background: rgba(180, 83, 9, 0.12);
            color: var(--accent-dark);
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            margin-bottom: 24px;
        }

        .logs {
            margin-top: 28px;
            display: grid;
            gap: 14px;
        }

        .log-item {
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.8);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(124, 45, 18, 0.08);
            color: var(--accent-dark);
            font-size: 14px;
            font-weight: 700;
        }

        .empty {
            padding: 32px;
            text-align: center;
            color: var(--muted);
            border: 1px dashed var(--border);
            border-radius: 20px;
        }

        .auth-card {
            max-width: 480px;
            margin: 60px auto;
        }

        .pagination {
            margin-top: 24px;
        }

        .pagination nav > div:first-child {
            display: none;
        }

        .pagination svg {
            width: 16px;
        }

        @media (max-width: 640px) {
            .search-form {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('head')
</head>
<body>
    <header class="topbar">
        <div class="container">
            <div class="topbar__inner">
                <div class="brand">
                    <a href="{{ route('albums.index') }}">
                        <h1>Vinyl Archive</h1>
                    </a>
                    <p>Справочник музыкальных альбомов для тестового задания Nutnet.</p>
                </div>

                <div class="actions">
                    @auth
                        <span class="badge">Авторизован: {{ auth()->user()->name }}</span>
                        <a class="btn btn-primary" href="{{ route('albums.create') }}">Добавить альбом</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="btn btn-secondary" type="submit">Выйти</button>
                        </form>
                    @else
                        <a class="btn btn-primary" href="{{ route('login') }}">Войти</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            @if (session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            @yield('content')
        </div>
    </main>

    @stack('scripts')
</body>
</html>
