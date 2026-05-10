# WTECH-2026

Laravel based WTECH eshop project with a static HTML storefront served by Laravel, JSON API, PostgreSQL schema, seeded catalog data, customer checkout, session auth, server-synced cart for signed-in users, and an admin product-management interface.

## Run

The storefront frontend is now served by Laravel, so one server is enough.

1. Start PostgreSQL and create the database from `backend/.env`.
2. Open `backend/.env` and check `DB_*` values.
3. Run:

```bash
cd backend
composer install
php artisan key:generate
php artisan storage:link
php artisan migrate --seed
php artisan serve
```

4. Open `http://127.0.0.1:8000/index.html`

Auth pages:

- `http://127.0.0.1:8000/login.html`
- `http://127.0.0.1:8000/register.html`

No separate frontend server is needed.

To refresh the seeded product catalog after code changes:

```bash
cd backend
php artisan db:seed --class=ProductSeeder
```

## Submission

Project documentation is in `docs/DOCUMENTATION.md`.

Complete PostgreSQL dump with schema and data is in `database_dump/wtech_eshop_full.sql`.

Admin login seeded by default:

- email: `admin@example.com`
- password: `admin12345`

