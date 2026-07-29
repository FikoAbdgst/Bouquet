# AGENTS.md

## Stack

Laravel 13, PHP 8.3, SQLite (default), Tailwind CSS v4 (Vite plugin, no tailwind.config.js), Blade views. No JS framework.

## Commands

| Action | Command |
|---|---|
| Setup from scratch | `composer setup` (installs deps, generates key, migrates, builds assets). Run `php artisan db:seed` and `php artisan storage:link` after. |
| Full dev server | `composer dev` (`artisan serve`, queue, pail, vite concurrently) |
| Run all tests | `composer test` (runs `config:clear` then `artisan test`) or `php artisan test` |
| Run single test | `php artisan test --filter=TestClassName` or `php artisan test tests/Feature/SomeTest.php` |
| Build assets | `npm run build` |
| Format | `./vendor/bin/pint` (dev dep, no npm script) |

**Seeder:** `DatabaseSeeder` creates admin `admin@toko.com` / `password123` + 8 categories (with dynamic `CategoryField` rows) + 10 products.

## Architecture

All routes in `routes/web.php`. No API routes. `Customer\OrderController::status()` returns JSON for the tracking widget.

**Layouts:** `layouts/app.blade.php` (customer, rose, Inter CDN) and `layouts/admin.blade.php` (admin, pink, sidebar).

**Tailwind loaded two ways:** CDN `<script>` (both layouts) + Vite plugin (`vite.config.js`). Do not add a third method.

**Font split:** `app.blade.php` uses Inter from Google Fonts CDN inline config. Vite-built CSS sets Instrument Sans via Bunny CDN. Pages with Vite bundle may resolve a different font than CDN-only pages.

**Middleware:** `role:admin` / `role:customer` via custom `EnsureRole` (`bootstrap/app.php` alias `role`). Checks `$user->role` and bounces inactive users.

**Cart:** localStorage `CartStorage` API (inlined in `app.blade.php` with `custom_options` + composite `_key` support; standalone `resources/js/cart.js` is stale/unused). Data `[{id, name, price, image, qty, custom_options}]`. Cleared client-side on `X-Clear-Cart` header.

**Non-default drivers:** `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database` (`.env.example`). Actual `.env` overrides queue to `sync` for dev.

**Env quirks:** `WA_ADMIN_NUMBER` (`config/app.php:126`, default `6281234567890`), `MIN_ORDER_LEAD_DAYS` (consumed via `env()` in `Customer\OrderController::store()`, not via config).

**Categories:** Changed from many-to-many (pivot `category_product`) to `belongsTo` via `category_id` on `products` (migration 11). Legacy `category` string col still exists.

## Key business rules

- **Prices IDR integer, no floats.** Cast `price`, `total_price`, `price_snapshot`, `subtotal` as `integer`.
- **`product_name_snapshot` and `price_snapshot` on `order_items` are mandatory** — copied at order, survive product edits.
- **Order total server-side only.** Never trust client `total_price`.
- **Status linear:** `menunggu_konfirmasi` → `dikonfirmasi` → `diproses` → `dikirim` → `selesai`. `dibatalkan` terminal from any state. Enforced in `Admin\OrderController::updateStatus()` (no skipping).
- **Soft delete:** `products.is_active`, `users.is_active` boolean, never hard delete. `Product::scopeActive()` = `is_active && stock > 0`.
- **`delivery_address`** required iff `pickup_method == delivery`.
- **`needed_date`** ≥ H+`MIN_ORDER_LEAD_DAYS` (default 1).
- **WhatsApp:** deep link `https://wa.me/{WA_ADMIN_NUMBER}?text={encoded_message}`. Number from `config('app.wa_admin_number')`.
- **Timestamps UTC** in DB, WIB (Asia/Jakarta) in UI.
- **Payment:** manual proof upload → admin `verifyPayment()`. No gateway.

## Modules

`auth/`, `products/` (+ categories), `orders/`, `tracking/`, `dashboard/`, `customers/`. WhatsApp is a feature within existing modules, not a separate module. Do not add scope.

## Models

Key models beyond the obvious: `CategoryField` (dynamic per-category custom options stored as JSON in `order_items.custom_options`), `ProductImage` (up to 5 images per product, `is_primary` flag), `TrackingLog` (`$timestamps = false`, manual `created_at`).

## Conventions

- **DB transactions** for multi-table writes (order + items + tracking_log). See `Customer\OrderController::store()` and `Admin\OrderController::updateStatus()`.
- **Dual validation:** frontend (UX) + backend (security).
- **Order code:** `ORD-{Ymd}-{3 random uppercase chars}` (e.g. `ORD-20260723-A3F`).
- **File storage:** product images → `products/`, payment proofs → `payment-proofs/` on `public` disk. Run `php artisan storage:link`.
- **Phone validation:** `/^08[0-9]{8,11}$/` (Indonesian mobile).
- **Pagination:** orders paginate at 10 (customer) / 15 (admin). Products at 10.
- **Error format:** `{ "success": false, "error": { "code": "...", "message": "..." } }` with specific codes.

## Reference

PRD is source of truth: `PRD_Bucket_Bunga_App.md`. Read before implementing any module.
