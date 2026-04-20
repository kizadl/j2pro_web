# Laravel TV Admin Final

Final starter scaffold for:
- Laravel 11
- MySQL
- Filament Admin
- Sanctum auth
- Public API for Flutter mobile

## Included
- `artisan`
- Laravel-style project skeleton
- Migrations: users, tv_channels, tv_schedules, banners
- Models
- Filament Resources
- Public TV/banner API
- Admin auth API with Sanctum token login
- Seeders for admin user + sample TV data

## Important note
This zip is a **starter source scaffold**. It does **not** include `vendor/`, so you still need Composer locally.

## Setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

## Default admin login
- email: `admin@example.com`
- password: `password`

## Filament admin
After install, visit:
- `/admin`

## Public API
- `GET /api/v1/banners`
- `GET /api/v1/tv/channels`
- `GET /api/v1/tv/channels/{id}`
- `GET /api/v1/tv/channels/{id}/today-schedules`
- `GET /api/v1/tv/schedules?channel_id=1&day_index=1`
- `GET /api/v1/tv/overview`

## Admin auth API
- `POST /api/v1/admin/login`
- `GET /api/v1/admin/me`
- `POST /api/v1/admin/logout`

## Next step
Refactor Flutter repository from Firestore to REST API using `/api/v1/tv/overview`, `/api/v1/tv/channels`, and `/api/v1/tv/schedules`.
