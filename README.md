# WTECH-2026

## Run

The storefront frontend is now served by Laravel, so one server is enough.

1. Start PostgreSQL and create the database from `backend/.env`.
2. Open `backend/.env` and check `DB_*` values.
3. Run:

```bash
cd backend
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

4. Open `http://127.0.0.1:8000/index.html`

Auth pages:

- `http://127.0.0.1:8000/login.html`
- `http://127.0.0.1:8000/register.html`

No separate frontend server is needed.
