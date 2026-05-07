# Contributing

Thanks for helping improve Smart POS Template.

## Development setup

Prerequisites:

- PHP 8.3+
- Composer 2+
- Node.js 18+ + npm

Run locally:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link
composer run dev
```

Optional demo data:

```bash
php artisan smartpos:seed-demo
```

## Code style

- PHP: `php artisan pint`
- Keep changes small and focused.

## Pull requests

- Add a clear description of **what** and **why**.
- Include a short test plan (steps to verify).
- Avoid committing secrets (`.env`, API keys, credentials).
