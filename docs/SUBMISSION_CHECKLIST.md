# Checklist odovzdania

## Funkcne poziadavky

| Poziadavka | Stav | Kde je implementovana |
| --- | --- | --- |
| Zoznam produktov podla kategorie | OK | `backend/resources/views/storefront/shop.blade.php`, `CatalogProductController@index` |
| Filtrovanie aspon podla 3 atributov | OK | cena od-do, typ, kategorie, platformy |
| Strankovanie | OK | Laravel paginator + UI v `shop.blade.php` |
| Zoradenie podla ceny | OK | `sort=price_asc`, `sort=price_desc` |
| Detail produktu | OK | `product_details.blade.php`, `/api/products/{slug}` |
| Pridanie produktu do kosika | OK | katalog/detail + `store_data.js` |
| Plnotextove vyhladavanie | OK | parameter `q` nad nazvom a opisom |
| Zobrazenie kosika | OK | `cart.blade.php` |
| Zmena mnozstva | OK | `cart.blade.php`, `setItemQty`, `changeItemQty` |
| Odobratie produktu | OK | `cart.blade.php`, `removeItem` |
| Vyber dopravy | OK | `cart_order.blade.php`, `/api/checkout/options` |
| Vyber platby | OK | `cart_order.blade.php` |
| Udaje s validaciou | OK | Blade frontend + `StoreOrderRequest` |
| Dokoncenie objednavky | OK | `/api/orders`, `OrderController@store` |
| Nakup bez prihlasenia | OK | objednavka ma nullable `user_id` |
| Dodocne prihlasenie a prenos kosika | OK | `user_carts`, `/api/cart/merge`, `store_data.js` |
| Registracia/prihlasenie/odhlasenie zakaznika | OK | `/api/auth/*`, `login.blade.php`, `register.blade.php`, `auth_ui.js` |
| Admin login/logout | OK | rola `ADMIN`, middleware `admin`, `/admin_manage.html` redirect |
| Admin zoznam produktov | OK | `admin_manage.blade.php`, `/api/admin/products` |
| Admin vytvorenie produktu | OK | `admin_new.blade.php`, upload obrazkov, ciselniky |
| Admin uprava produktu | OK | `admin_edit.blade.php`, upload/odobranie obrazkov |
| Admin vymazanie produktu | OK | `admin_manage.blade.php`, fyzicke mazanie obrazkov |

## Subory do archivu

Pribalit:

- `backend/app`
- `backend/bootstrap`
- `backend/config`
- `backend/database`
- `backend/public`
- `backend/resources`
- `backend/routes`
- `backend/tests`
- `backend/composer.json`
- `backend/composer.lock`
- `backend/package.json`
- `backend/package-lock.json`, ak existuje
- `frontend` - shared storefront assets (`store_data.js`, `auth_ui.js`, `styles.css`)
- `docs`
- `database_dump/wtech_eshop_full.sql`
- `README.md`

Nepribalovat:

- `backend/vendor`
- `backend/node_modules`
- `.git`
- dočasne cache/log subory, ak nie su potrebne

## Pred vytvorenim ZIP

1. Skontrolovat, ze existuje `database_dump/wtech_eshop_full.sql`.
2. Skontrolovat, ze existuju screenshoty v `docs/screenshots`.
3. Spustit:

```bash
cd backend
php artisan migrate:fresh --seed
php artisan route:list
```

4. Otvorit aplikaciu cez:

```bash
cd backend
php artisan serve
```

5. Manualne prejst: katalog, detail produktu, kosik, checkout, login admina, admin vytvorenie/uprava/zmazanie produktu.

## Poznamky pre hodnotenie

Admin konto zo seedera:

- email: `admin@example.com`
- password: `admin12345`

Databaza v `.env.example`:

- database: `wtech_eshop`
- username: `postgres`
- password: `1234`
