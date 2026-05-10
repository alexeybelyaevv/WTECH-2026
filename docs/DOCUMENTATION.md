# Dokumentacia projektu - WTECH eshop

## Zadanie

Cielom projektu je webova aplikacia eshopu implementovana v PHP frameworku Laravel. Aplikacia obsahuje klientsku cast pre prehliadanie katalogu, kosik a vytvorenie objednavky bez povinneho prihlasenia, a administratorsku cast pre spravu produktov.

## Programove prostredie

- Backend: Laravel PHP framework
- Databaza: PostgreSQL
- Frontend: HTML5, Tailwind CSS CDN, vanilla JavaScript
- Autentifikacia: Laravel session authentication
- Ukladanie obrazkov: Laravel public storage disk
- Spustenie: `cd backend`, `composer install`, `php artisan migrate --seed`, `php artisan storage:link`, `php artisan serve`

## Fyzicky datovy model

![db](screenshots/db_tables.png)

Fyzicky datovy model je odvodený z Laravel migracii. Zdroj diagramu je v `docs/physical_data_model.svg`; pred odovzdanim ho treba exportovat aj ako PNG/JPG, ak AIS vyzaduje striktne obrazkovy format. Hlavne tabulky:

- `users` - zakaznici a administratori, rozliseni atributom `role`
- `user_carts` - serverova prenositelna kosikova data prihlaseneho pouzivatela
- `products` - katalog produktov
- `product_images` - fotografie produktov, minimalne dve fotografie na produkt pri administracnom vytvoreni
- `categories`, `platforms` - ciselniky pre filtrovanie a administracne zaradenie produktov
- `category_product`, `platform_product` - M:N vazby produktov na ciselniky
- `orders` - objednavky vratane dodacich udajov, dopravy, platby a sum
- `order_items` - polozky objednavky
- `promo_codes` - zlavove kody
- Laravel systemove tabulky: `sessions`, `cache`, `jobs`, `password_reset_tokens`

Zmena oproti povodnemu modelu: bola doplnena tabulka `user_carts`, pretoze poziadavka vyzaduje prenositelnost kosika pre prihlaseneho pouzivatela. LocalStorage ostava pre neprihlaseneho pouzivatela, ale po prihlaseni sa lokalny kosik zluci so serverovou verziou.

## Navrhove rozhodnutia

Role pouzivatelov su riesene jednoducho cez stlpec `users.role` s hodnotami `CUSTOMER` a `ADMIN`. Administratorske API a admin HTML stranky su chranene Laravel middleware `auth` a vlastnym middleware `admin`.

Opravnenia neboli modelovane ako samostatna permission tabulka, pretoze projekt rozlisuje iba dve roly a poziadavky nevyzaduju detailne ACL.

Klientske HTML subory su servovane cez Laravel `FrontendController`. Dynamicke data sa nacitavaju cez Laravel JSON API. Tento pristup ponechava sablony citatelne a zaroven splna pouzitie Laravel backendu pre aplikačnu logiku, validaciu, databazu a autentifikaciu.

Kosik neprihlaseneho pouzivatela je ulozeny v `localStorage`, aby bol nakup mozny bez registracie. Po prihlaseni sa vola `/api/cart/merge`, cim sa lokalny kosik prenesie do serverovej tabulky `user_carts`. Pri dalsich zmenach sa kosik synchronizuje cez `/api/cart`.

Platba kartou ma klientsku validaciu cisla karty cez Luhn algoritmus, expiracie a CVC. Surove kartove udaje sa neposielaju a neukladaju do databazy.

## Implementacia vybranych pripadov pouzitia

### Zoznam produktov, filtrovanie, vyhladavanie a strankovanie

Stranka `frontend/shop.html` vola `/api/products`. Backend `CatalogProductController@index` podporuje:

- plnotextove hladanie cez parameter `q`
- filtrovanie podla ceny `min_price`, `max_price`
- filtrovanie podla typu produktu `type`
- filtrovanie podla kategorii `categories[]`
- filtrovanie podla platforiem `platforms[]`
- zoradenie `price_asc`, `price_desc`, `oldest`, default `newest`
- strankovanie cez Laravel paginator

### Detail produktu

Stranka `frontend/product_details.html` nacita detail cez `/api/products/{slug}`. Detail obsahuje nazov, opis, cenu, sklad, kategorie, platformy a galeriu obrazkov.

### Pridanie produktu do kosika a zmena mnozstva

Pridanie produktu sa vykona na detaile produktu alebo v katalogu. Kosik pouziva `frontend/store_data.js`, kde su funkcie `addItem`, `setItemQty`, `changeItemQty` a `removeItem`. Na stranke `frontend/cart.html` moze pouzivatel menit mnozstvo alebo odstranit produkt.

### Nakupny kosik, doprava, platba a objednavka

Checkout je v `frontend/cart_order.html`. Doprava a platba sa nacitavaju z `/api/checkout/options`. Objednavka sa odosiela na `/api/orders`, kde `StoreOrderRequest` validuje polozky, platbu, dopravu a dodacie udaje. `OrderController@store` prepocita ceny na serveri, overi sklad a vytvori `orders` a `order_items`.

### Registracia, prihlasenie a odhlasenie

Registracia a prihlasenie pouzivaju `/api/auth/register` a `/api/auth/login`. Odhlasenie vola `/api/auth/logout`. Po prihlaseni admina vracia backend presmerovanie na `/admin_manage.html`, zakaznik ide na `/index.html`.

### Administratorska cast

Admin stranky:

- `admin_manage.html` - zoznam produktov, vyhladavanie, strankovanie, edit/delete
- `admin_new.html` - vytvorenie produktu, povinne minimalne 2 obrazky, vyber kategorii a platforiem
- `admin_edit.html` - uprava produktu, nahravanie novych obrazkov, zoznam existujucich obrazkov a ich odobratie

Admin API fyzicky maze obrazky zo storage pri zmazani produktu alebo obrazku.

## Snímky obrazoviek

![homepage](screenshots/homepage.png)
![homepage](screenshots/log_in.png)
![homepage](screenshots/cart_with_product.png)
![homepage](screenshots/order_checkout.png)
![homepage](screenshots/product_detail.png)
