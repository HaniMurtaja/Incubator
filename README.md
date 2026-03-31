# Incubator Lab (INCULAB)

Virtual business incubator platform built with Laravel for managing startup ideas from submission to mentorship, task execution, and evaluation.

## Core Features

- Role-based access: Admin, Mentor, Entrepreneur
- Passwordless quick role access for demo environments
- Project submission and admin review/approval workflow
- Mentor assignment and incubation stage management
- Task creation, submission, and mentor evaluation cycle
- In-app notifications for project/task updates
- Admin analytics dashboard (ApexCharts)
- Mentor command center and mentorship calendar
- Arabic/English localization with runtime language switch

## Tech Stack

- Laravel 8
- Blade + Tabler UI
- MySQL
- Laravel Fortify
- Spatie Laravel Permission
- ApexCharts + FullCalendar (CDN)

## Project Structure Notes

- `app/Http/Controllers` contains role-specific controllers
- `app/Services` contains workflow and business logic services
- `app/Support/Statuses` contains enum-like status constants
- `resources/views` contains dashboards, modules, and shared UI components
- `database/migrations` defines all platform schema tables

## Requirements

- PHP 7.4+
- Composer 2+
- MySQL 5.7+ or 8+
- Node.js + npm (for frontend asset build if needed)

## Local Setup

1. Clone the repository
2. Install dependencies
3. Configure environment
4. Generate app key
5. Run migrations and seeders
6. Start the app

```bash
git clone https://github.com/HaniMurtaja/Incubator.git
cd Incubator
composer install
cp .env.example .env
php artisan key:generate
```

Update `.env` with your MySQL connection values, then run:

```bash
php artisan migrate --seed
php artisan serve
```

Open:

- `http://127.0.0.1:8000`

## Production Run Commands

```bash
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

If file uploads are used:

```bash
php artisan storage:link
```

## Demo Access

Seeded demo users/roles are created by `RolesAndDemoSeeder`.
Quick role access is available through the login gateway cards.

## License

MIT License
