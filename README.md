## Kafedra application

Kafedra is a web application for BSUIR to manage yearly tasks.  
Stack: [Laravel](https://laravel.com/) + [Inertia](https://inertiajs.com/) + [Vue](https://vuejs.org/).


## Requirements

- PHP / Composer
- Node.js / npm
- PostgreSQL

(Exact versions are available in `docker-compose.yml` and `Dockerfile`.)

## Run with Docker

- Install [Docker](https://www.docker.com/).
- Copy `.env.example` to `.env`.
- Configure .env (DB credentials should match values from docker-compose.yml)
- Build and start containers:
  - `docker compose build`
  - `docker compose up -d`
- Install backend dependencies:
  - `composer install` (run in container)
  - `php artisan key:generate` (run in container)
- Install and build frontend (either locally or inside a node container — pick one approach):
  - `npm install` (run in container)
  - `npm run build` (run in container)
- Run migrations and seeders:
  - `php artisan migrate --seed` (run in container)
- Create reports directory:
  - `mkdir -p storage/app/private/reports` (run in container)

## Run in Production (Nginx + PHP-FPM)

- IInstall Nginx, PHP-FPM, PostgreSQL and Node.js
- Copy `.env.example` to `.env`
- Configure .env (APP_URL, DB_*, etc.)
- Install backend dependencies:
  - `composer install --no-dev --optimize-autoloader`
  - `php artisan key:generate`
- Install and build:
    - `npm install`
    - `npm run build`
- Run migrations and seeders:
    - `php artisan migrate --seed`
- Create reports directory:
    - `mkdir -p storage/app/private/reports`
- (Recommended) Cache config/routes/views:
  - `php artisan config:cache`
  - `php artisan route:cache`
  - `php artisan view:cache`