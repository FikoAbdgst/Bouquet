# AGENTS.md

## Stack

Laravel 13, PHP 8.3, SQLite (default), Tailwind CSS v4 (Vite plugin, no tailwind.config.js), Blade views, no JS framework.

## Setup & commands

| Action | Command |
|---|---|
| Fresh setup | `composer setup` → `php artisan db:seed` → `php artisan storage:link` |
| Dev server | `composer dev` (serve + queue + pail + vite concurrently) |
| Run all tests | `composer test` (runs `config:clear` then `artisan test`) |
| Single test | `php artisan test --filter=TestClassName` or `php artisan test tests/Feature/SomeTest.php` |
| Build assets | `npm run build` |
| Format | `./vendor/bin/pint` |

**`composer setup** copies `.env.example` → `.env`, but `.env.example` is **missing** `WA_ADMIN_NUMBER` and `MIN_ORDER_LEAD_DAYS`. After `composer setup`, copy those from `.env` backup or add manually.

**`.npmrc` has `ignore-scripts=true`** — npm lifecycle scripts are disabled.

**Seeder:** `DatabaseSeeder` (idempotent) creates admin `admin@toko.com` / `password123`, 8 categories + `CategoryField` rows, 10 products.

## Routes

All routes in `routes/web.php`. No API routes.

| Area | Prefix | Middleware |
|---|---|---|
| Auth | `/login`, `/register` | `throttle:10,1` on login POST |
| Guest cart | `/cart` | none (localStorage-based) |
| Customer | `/customer/*` | `auth`, `role:customer` |
| Admin | `/admin/*` | `auth`, `role:admin` |

**Role middleware** (`bootstrap/app.php` alias `role`) checks `$user->role`, bounces inactive users. Login redirects admin → `admin.dashboard`, customer → `customer.dashboard`.

**Guest catalog** — `CatalogController::index()` paginates at **12**, filters by category slug, price range, search. `show()` aborts 404 if inactive/out-of-stock.

## Architecture

**Routes split:** Customer → `Customer\OrderController`, `CartController`, `CatalogController`. Admin → `Admin\DashboardController`, `ProductController`, `CategoryController`, `Admin\OrderController`, `Admin\CustomerController`.

**Layouts:** `layouts/app.blade.php` (customer, rose theme, Inter CDN) and `layouts/admin.blade.php` (admin, pink, sidebar). Both load Tailwind via CDN `<script>` **and** Vite plugin (`@tailwindcss/vite`). Do not add a third Tailwind method.

**Font split:** `app.blade.php` uses Inter (Google Fonts CDN). Vite-built CSS (`resources/css/app.css`) sets Instrument Sans via Bunny CDN. Pages with Vite bundle may resolve a different font than CDN-only pages.

**Cart:** Inlined `CartStorage` JS (in `app.blade.php`) with composite `_key` (`id::JSON.stringify(custom_options)`) — same product can appear multiple times with different options. Standalone `resources/js/cart.js` is stale. Cart cleared client-side on `X-Clear-Cart` response header.

**Non-default drivers:** `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database` (`.env.example`). Actual `.env` overrides queue to `sync`.

**Env quirks:** `WA_ADMIN_NUMBER` via `config('app.wa_admin_number')` (`config/app.php:126`). `MIN_ORDER_LEAD_DAYS` consumed via raw `env()` in `Customer\OrderController::store()` — **not** config. `APP_LOCALE=id` in actual `.env` (Indonesian localization).

**Categories:** Changed from many-to-many (pivot `category_product`) to `belongsTo` via `category_id` on `products` (migration `2026_07_29_000001`). Legacy `category` string col still exists on `products`.

## Key business rules

- **Prices in IDR — integer only.** Cast `price`, `total_price`, `price_snapshot`, `subtotal` as `integer`.
- **`product_name_snapshot` and `price_snapshot` on `order_items`:** mandatory, copied at order placement, survive product edits.
- **Order total calculated server-side.** Never trust client.
- **Order status linear:** `menunggu_konfirmasi` → `dikonfirmasi` → `diproses` → `dikirim` → `selesai`. `dibatalkan` terminal from any state. Enforced in `Admin\OrderController::updateStatus()` (index must increment by 1 for non-cancel).
- **Soft delete via `is_active` boolean** on `products` and `users` — no hard deletes in normal flow. **Exception:** `CategoryController::destroy()` calls `$category->delete()` (hard delete), but protects 4 built-in category names and requires confirmation if products exist.
- **`Product::scopeActive()`** = `is_active && stock > 0`.
- **`delivery_address`** required iff `pickup_method === delivery`.
- **`needed_date`** ≥ H+`MIN_ORDER_LEAD_DAYS` (default 1).
- **Phone validation:** `/^08[0-9]{8,11}$/` (Indonesian mobile).
- **Order code:** `ORD-{Ymd}-{3 uppercase chars}`.
- **Stock atomicity:** `OrderController::store()` uses `Product::lockForUpdate()->find($id)` for stock decrement inside DB transaction.
- **WhatsApp:** deep link `https://wa.me/{WA_ADMIN_NUMBER}?text={encoded_message}`.
- **Timestamps UTC** in DB, WIB (`Asia/Jakarta`) in UI.
- **Payment:** manual proof upload → `verifyPayment()`. No payment gateway.

## Models

| Model | Key relations | Notable casts / methods |
|---|---|---|
| `User` | `orders()` HasMany | `password => hashed`, `is_active => boolean`. `isAdmin()`, `isCustomer()`. |
| `Product` | `productCategory()` BelongsTo, `images()` HasMany, `primaryImage()` HasOne | `price => integer`, `stock => integer`, `is_active => boolean`. `scopeActive()`. `topSellers(3)` — queries `selesai` orders, falls back to highest-priced active. `getFormattedPriceAttribute()`. |
| `Category` | `products()` HasMany, `fields()` HasMany | Fillable: name, slug. |
| `CategoryField` | `category()` BelongsTo | `is_required => boolean`. Options stored as plain comma-separated string (not cast to array). |
| `ProductImage` | `product()` BelongsTo | `is_primary => boolean`. Max 5 per product. |
| `Order` | `user()` BelongsTo, `items()` HasMany, `trackingLogs()` HasMany | `needed_date => date`, `total_price => integer`, `payment_verified => boolean`. Accessors: `getFormattedTotalAttribute()`, `getStatusLabelAttribute()`, `getStatusColorAttribute()`. |
| `OrderItem` | `order()` BelongsTo, `product()` BelongsTo | `price_snapshot => integer`, `quantity => integer`, `subtotal => integer`, `custom_options => array` (JSON cast). |
| `TrackingLog` | `order()` BelongsTo, `changedByUser()` BelongsTo (FK `changed_by`) | `$timestamps = false`, manual `created_at`. |

## Conventions

- **DB transactions** for multi-table writes: `Customer\OrderController::store()`, `Admin\OrderController::updateStatus()`, `ProductController::store/update()`.
- **Dual validation:** frontend (UX) + backend (security).
- **No generated code, no queue jobs, no events, no listeners, no notifications, no form requests, no custom Artisan commands.** `AppServiceProvider::register()` and `boot()` are both empty.
- **File storage:** `products/` (images), `payment-proofs/` on `public` disk. Run `php artisan storage:link`.
- **Pagination:** customer orders 10, customer catalog 12, admin orders 15, admin products 10, admin customers 15.
- **Reorder feature** (`Customer\OrderController::create()`): accepts `?reorder=ORDER_ID` — pre-fills form, warns if price changed.
- **`CartController::checkStock()`** (`POST /cart/check-stock`): returns JSON with `{id, name, price, stock, is_active}` for frontend stock validation before checkout.
- **Testing:** uses in-memory SQLite (`DB_DATABASE=:memory:`), array cache/session, sync queue. See `phpunit.xml`.
- **`resources/js/app.js`** is empty (Vite entry point only). All JS is in Blade templates.
