# Nutnet Albums Directory

Тестовое задание на Laravel: справочник по музыкальным альбомам с авторизацией, автозаполнением данных из Last.fm и журналом изменений.

## Что реализовано

- список альбомов с пагинацией и поиском;
- карточка альбома: название, исполнитель, описание, внешняя обложка;
- создание и редактирование альбома через отдельную форму;
- доступ к созданию, редактированию и удалению только для авторизованного пользователя;
- автозаполнение исполнителя, описания и обложки по названию альбома через Last.fm API и Laravel HTTP Client;
- журнал изменений по каждой записи;
- удаление альбомов;
- сидер с тестовым пользователем и демонстрационными альбомами;
- feature-тесты на ключевые сценарии.

## Стек

- PHP 8.2+
- Laravel 10
- PostgreSQL

## Быстрый старт

1. Установить зависимости:

```bash
composer install
```

2. Подготовить окружение:

```bash
copy .env.example .env
php artisan key:generate
```

3. Настроить подключение к PostgreSQL в `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=nutnet
DB_USERNAME=postgres
DB_PASSWORD=secret
```

4. Указать ключ Last.fm в `.env`:

```env
LASTFM_API_KEY=your_key_here
```

5. Выполнить миграции и сиды:

```bash
php artisan migrate --seed
```

6. Запустить приложение:

```bash
php artisan serve
```

## Тестовые данные

- Email: `admin@example.com`
- Пароль: `password`

## Основные маршруты

- `/` — список альбомов
- `/login` — авторизация
- `/albums/create` — создание альбома
- `/albums/{id}/edit` — редактирование альбома
- `/api/albums/lookup?title=...` — поиск данных по альбому в Last.fm

## Railway

Минимальные переменные окружения для Railway:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.up.railway.app
APP_KEY=base64:...

DB_CONNECTION=pgsql
DB_HOST=...
DB_PORT=5432
DB_DATABASE=...
DB_USERNAME=...
DB_PASSWORD=...
DB_SCHEMA=public
DB_SSLMODE=prefer

LOG_CHANNEL=stderr
LOG_STDERR_FORMATTER=\Monolog\Formatter\JsonFormatter

SESSION_DRIVER=file
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=public

LASTFM_API_KEY=your_key_here
```

После первого деплоя на Railway нужно выполнить:

```bash
php artisan migrate --force --seed
```

## Что ещё можно улучшить

- загрузка собственных обложек в файловое хранилище;
- фильтрация по жанрам, годам и исполнителям;
- soft delete и отдельный экран архива удалённых записей;
- экспорт списка в CSV/Excel;
- роли и права доступа;
- docker-конфигурация для быстрого запуска;
- кэширование запросов к Last.fm;
- отдельный экран общего аудита изменений.

## Важно по текущей среде

Для локального тестирования при желании можно временно вернуть `sqlite` в личном `.env`, но для продакшен-деплоя проект подготовлен под PostgreSQL.
