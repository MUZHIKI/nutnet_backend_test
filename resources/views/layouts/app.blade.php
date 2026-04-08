<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Справочник альбомов')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0b1320;
            --bg-soft: #101a2b;
            --surface: rgba(12, 22, 36, 0.84);
            --surface-strong: rgba(17, 29, 46, 0.96);
            --surface-soft: rgba(255, 255, 255, 0.04);
            --text: #f4f7fb;
            --muted: #a8b2c3;
            --accent: #ff8a3d;
            --accent-soft: rgba(255, 138, 61, 0.12);
            --border: rgba(255, 255, 255, 0.08);
            --danger: #ff8b8b;
            --success: #9ae6b4;
            --shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        * { box-sizing: border-box; }

        html {
            color-scheme: dark;
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            font-family: "Manrope", "Segoe UI", sans-serif;
            background:
                radial-gradient(circle at top left, rgba(255, 138, 61, 0.18), transparent 26%),
                linear-gradient(180deg, #0b1320 0%, #0e1726 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1140px, calc(100% - 32px));
            margin: 0 auto;
        }

        .topbar {
            padding: 22px 0 0;
        }

        .topbar__inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            padding: 18px 22px;
            border: 1px solid var(--border);
            border-radius: 24px;
            background: rgba(8, 16, 27, 0.74);
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
        }

        .brand {
            display: grid;
            gap: 7px;
        }

        .brand small {
            color: #ffd1aa;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .brand h1 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(30px, 4vw, 44px);
            line-height: 0.96;
            letter-spacing: -0.04em;
        }

        .brand p {
            margin: 0;
            color: var(--muted);
            font-size: 15px;
            max-width: 640px;
        }

        .actions,
        .inline-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .main {
            padding: 28px 0 40px;
        }

        .hero,
        .panel {
            border: 1px solid var(--border);
            border-radius: 28px;
            background: var(--surface);
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
        }

        .hero {
            display: grid;
            grid-template-columns: minmax(0, 1.4fr) minmax(240px, 0.8fr);
            gap: 18px;
            padding: 28px;
            margin-bottom: 24px;
        }

        .hero h2 {
            margin: 0 0 12px;
            font-family: "Space Grotesk", sans-serif;
            font-size: clamp(32px, 4vw, 52px);
            line-height: 0.95;
            letter-spacing: -0.05em;
        }

        .hero p {
            margin: 0;
            color: var(--muted);
            font-size: 16px;
            max-width: 700px;
        }

        .hero-meta {
            display: grid;
            gap: 12px;
        }

        .hero-meta__item {
            padding: 18px;
            border-radius: 22px;
            background: var(--surface-soft);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .hero-meta__item strong {
            display: block;
            font-family: "Space Grotesk", sans-serif;
            font-size: 28px;
            line-height: 1;
        }

        .hero-meta__item span {
            display: block;
            margin-top: 8px;
            color: var(--muted);
            font-size: 14px;
        }

        .panel {
            padding: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 320px));
            gap: 20px;
            justify-content: start;
            align-items: start;
        }

        .album-card {
            overflow: hidden;
            display: grid;
            min-height: 100%;
            width: 100%;
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: var(--surface-strong);
            transition: transform 0.18s ease, border-color 0.18s ease, box-shadow 0.18s ease;
        }

        .album-card:hover {
            transform: translateY(-3px);
            border-color: rgba(255, 138, 61, 0.22);
            box-shadow: 0 18px 48px rgba(0, 0, 0, 0.25);
        }

        .album-cover {
            position: relative;
            aspect-ratio: 1 / 1;
            overflow: hidden;
            background: linear-gradient(135deg, #1a2b41 0%, #121f31 100%);
        }

        .album-cover img,
        .album-cover-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .album-cover__fallback {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: flex-end;
            padding: 22px;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.03) 0%, rgba(0, 0, 0, 0.46) 100%);
        }

        .album-cover__fallback span {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 62px;
            height: 62px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-family: "Space Grotesk", sans-serif;
            font-size: 24px;
            font-weight: 700;
        }

        .album-card__body {
            display: grid;
            gap: 10px;
            padding: 18px;
        }

        .album-card h2 {
            margin: 0;
            font-family: "Space Grotesk", sans-serif;
            font-size: 26px;
            line-height: 1;
            letter-spacing: -0.04em;
        }

        .album-meta {
            color: #ffd1aa;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
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
            font: inherit;
            font-size: 14px;
            font-weight: 800;
            transition: transform 0.16s ease, opacity 0.16s ease, box-shadow 0.16s ease;
        }

        .btn:hover,
        button:hover {
            transform: translateY(-1px);
        }

        .btn-primary {
            color: #111827;
            background: linear-gradient(135deg, var(--accent), #ffba7d);
            box-shadow: 0 12px 28px rgba(255, 138, 61, 0.22);
        }

        .btn-secondary {
            color: var(--text);
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--border);
        }

        .btn-danger {
            color: #ffd0d0;
            background: rgba(255, 139, 139, 0.08);
            border: 1px solid rgba(255, 139, 139, 0.16);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 999px;
            color: #ffe3cb;
            background: var(--accent-soft);
            border: 1px solid rgba(255, 138, 61, 0.12);
            font-size: 13px;
            font-weight: 800;
        }

        .search-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 12px;
            margin-bottom: 24px;
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
            font-size: 14px;
            font-weight: 700;
            color: #eaf0f8;
        }

        input,
        textarea {
            width: 100%;
            padding: 14px 16px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            font: inherit;
            outline: none;
        }

        input:focus,
        textarea:focus {
            border-color: rgba(255, 138, 61, 0.32);
            box-shadow: 0 0 0 4px rgba(255, 138, 61, 0.08);
        }

        textarea {
            min-height: 160px;
            resize: vertical;
        }

        .status {
            margin-bottom: 18px;
            padding: 14px 18px;
            border-radius: 18px;
            color: #e6fff0;
            background: rgba(154, 230, 180, 0.08);
            border: 1px solid rgba(154, 230, 180, 0.14);
        }

        .error {
            margin-top: -4px;
            color: #ffc5c5;
            font-size: 14px;
        }

        .empty {
            padding: 36px;
            text-align: center;
            color: var(--muted);
            border: 1px dashed rgba(255, 255, 255, 0.12);
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.02);
        }

        .logs {
            margin-top: 28px;
            display: grid;
            gap: 14px;
        }

        .log-item {
            padding: 16px;
            border-radius: 18px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.03);
        }

        .auth-card {
            max-width: 520px;
            margin: 52px auto 0;
        }

        .pager {
            margin-top: 28px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pager__link,
        .pager__current,
        .pager__dots {
            min-width: 42px;
            height: 42px;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 0 14px;
            border-radius: 14px;
            border: 1px solid var(--border);
            background: rgba(255, 255, 255, 0.04);
            color: var(--text);
            font-size: 14px;
            font-weight: 700;
        }

        .pager__link:hover {
            border-color: rgba(255, 138, 61, 0.24);
            background: rgba(255, 138, 61, 0.08);
        }

        .pager__current {
            background: linear-gradient(135deg, var(--accent), #ffba7d);
            color: #111827;
            border-color: transparent;
        }

        .pager__dots {
            background: transparent;
            border-style: dashed;
            color: var(--muted);
        }

        @media (max-width: 860px) {
            .hero {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 640px) {
            .container {
                width: min(100% - 20px, 1140px);
            }

            .search-form {
                grid-template-columns: 1fr;
            }

            .topbar__inner,
            .hero,
            .panel {
                padding: 18px;
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
                        <h1>Архив пластинок</h1>
                    </a>
                    <p>Каталог музыкальных альбомов.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-cover-image]').forEach((image) => {
                image.addEventListener('error', () => {
                    image.style.display = 'none';
                    const fallback = image.closest('[data-cover-root]')?.querySelector('[data-cover-fallback]');
                    if (fallback) {
                        fallback.hidden = false;
                    }
                });

                if (image.complete && image.naturalWidth === 0) {
                    image.dispatchEvent(new Event('error'));
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
