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

**`composer setup`** copies `.env.example` → `.env`, but `.env.example` is **missing** `WA_ADMIN_NUMBER` and `MIN_ORDER_LEAD_DAYS`. After `composer setup`, copy those from `.env` backup or add manually.

**Spec:** `PRD_Bucket_Bunga_App.md` is the product spec (includes "Development Constraints & Coding Rules") — consult it before building features.

**`.npmrc` has `ignore-scripts=true`** — npm lifecycle scripts are disabled.

**Seeder:** `DatabaseSeeder` (idempotent) creates admin `admin@toko.com` / `password123`, 8 categories + `CategoryField`/`CategoryFieldOption` rows (with prices), 10 products.

## Routes

All routes in `routes/web.php`. No API routes.

| Area | Prefix | Middleware |
|---|---|---|
| Auth | `/login`, `/register` | `throttle:10,1` on login POST |
| Guest cart | `/cart` | none (localStorage-based) |
| Customer | `/customer/*` | `auth`, `role:customer` |
| Admin | `/admin/*` | `auth`, `role:admin` |

**Role middleware** (`bootstrap/app.php` alias `role`) checks `$user->role`, bounces inactive users. Login redirects admin → `admin.dashboard`, customer → `customer.dashboard`.

**Guest catalog** — `CatalogController::index()`: paginate 12, filter by category slug/price/search, returns AJAX partial or full view. `show()` aborts 404 if inactive/out-of-stock.

**Client-side polling:** `customer/cart.blade.php` polls `POST /cart/check-stock` every 12s while open (inline JS, no `@vite`). `customer/order-detail.blade.php` polls `GET /customer/orders/{order}/status` (JSON: `status`, `status_label`, `status_color`, `payment_verified`, `tracking_logs`) every 5s for live status updates. `customer/orders.blade.php` (order history) polls its own URL with `X-Requested-With: XMLHttpRequest` every 5s and swaps `#orders-container` — `Customer\OrderController::index()` returns the `customer.orders-list` partial when `$request->ajax()` (same pattern as `CatalogController::index()`).

## Architecture

**Routes split:** Customer → `Customer\OrderController`, `CartController`, `CatalogController`. Admin → `Admin\DashboardController`, `ProductController`, `CategoryController`, `Admin\OrderController`, `Admin\CustomerController`.

**Checkout route** (`orders.checkout`) is outside the customer prefix — only `auth` middleware, no `role:customer`. `create($product)` is inside the customer prefix with full gate.

**Layouts:** `layouts/app.blade.php` (customer, rose theme, Inter CDN) and `layouts/admin.blade.php` (admin, pink, sidebar). Both load Tailwind **only** via CDN `<script>`. Vite is configured (`vite.config.js`: `@tailwindcss/vite` + Instrument Sans via bunny fonts) and `npm run build` runs in setup, but **no view uses `@vite`** — the built CSS/JS is not loaded anywhere. Do not add a third Tailwind method.

**Cart:** Inlined `CartStorage` JS (in `app.blade.php`) with composite `_key` (`id::JSON.stringify(custom_options)`) — same product can appear multiple times with different options. Standalone `resources/js/cart.js` is stale (no `_key` composite logic, lacks inlined `formatRupiah`/badge update helpers). Cart cleared after a successful order via a flashed `clear_cart` session flag: `OrderController::store()` redirects with `->with('clear_cart', true)`, and `customer/orders.blade.php` calls `CartStorage.clear()` only when that flag is present. `GET /cart/edit-fields/{product}` returns JSON with the product's field options (`id`, `name`, `price`) for the edit dialog; the cart stores selected options with `option_id` so prices can be re-summed server-side.

**Non-default drivers:** `SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database` (`.env.example`). Actual `.env` overrides queue to `sync`.

**Env quirks:** `WA_ADMIN_NUMBER` via `config('app.wa_admin_number')` (`config/app.php:126`). `MIN_ORDER_LEAD_DAYS` consumed via raw `env()` in `Customer\OrderController::store()` — **not** config. `APP_LOCALE=id` in actual `.env` (Indonesian localization).

**Categories:** Changed from many-to-many (pivot `category_product`) to `belongsTo` via `category_id` on `products` (migration `2026_07_29_000001`). Legacy `category` string col still exists on `products`. **Field options moved out of `category_fields.options` (comma-separated string) into `category_field_options` table** (migration `2026_07_30_000001`): each option is a row with `name` + integer `price`. The legacy `options` col is kept but set to `null` on admin saves; only used as fallback in `CartController::editFields()` and the data-migration migration.

**Global helpers:** `app/helpers.php` is autoloaded via `composer.json` `files` — `get_custom_option_display()`, `is_custom_option_file()`, `get_custom_option_file_path()` are callable directly in Blade to render `custom_options` values (values under `temp-uploads/` render as thumbnail links).

## Key business rules

- **Prices in IDR — integer only.** Cast `price`, `total_price`, `price_snapshot`, `subtotal` as `integer`.
- **`product_name_snapshot` and `price_snapshot` on `order_items`:** mandatory, copied at order placement, survive product edits. **`price_snapshot` = product price + sum of selected option prices** (`CategoryFieldOption::whereIn('id', $optionIds)->sum('price')` in `OrderController::store()`).
- **Order total calculated server-side.** Never trust client.
- **Order status linear:** `menunggu_konfirmasi` → `dikonfirmasi` → `diproses` → `dikirim` → `selesai`. `dibatalkan` terminal from any state. Enforced in `Admin\OrderController::updateStatus()` (index must increment by 1 for non-cancel).
- **Customer cancellation:** `Customer\OrderController::cancel()` (POST `/customer/orders/{order}/cancel`). Allowed while `menunggu_konfirmasi` (always) or `dikonfirmasi` (only if admin set per-order `orders.allow_customer_cancel`). Stock is restored (incremented) on cancel. Rule centralized in `Order::canBeCancelledByCustomer()`. Admin toggles the flag per order via `Admin\OrderController::toggleCancelable()` (PATCH `/admin/orders/{order}/cancelable`), shown only at status `dikonfirmasi`.
- **Soft delete via `is_active` boolean** on `products` and `users` — no hard deletes in normal flow. **Exception:** `CategoryController::destroy()` calls `$category->delete()` (hard delete), but protects 4 built-in category names and requires confirmation if products exist.
- **`Product::scopeActive()`** = `is_active && stock > 0`.
- **`delivery_address`** required iff `pickup_method === delivery`.
- **`needed_date`** ≥ H+`MIN_ORDER_LEAD_DAYS` (default 1).
- **Phone validation:** `/^08[0-9]{8,11}$/` (Indonesian mobile).
- **Order code:** `ORD-{Ymd}-{3 uppercase chars}`.
- **Stock atomicity:** `OrderController::store()` uses `Product::lockForUpdate()->find($id)` for stock decrement inside DB transaction.
- **WhatsApp:** deep link `https://wa.me/{WA_ADMIN_NUMBER}?text={encoded_message}`.
- **Timestamps UTC** in DB, WIB (`Asia/Jakarta`) in UI.
- **Payment:** manual proof upload (`image, mimes:jpg,jpeg,png,webp, max:5120`) → `verifyPayment()`. No payment gateway.
- **File-type CategoryField** (`type=file`): reference image uploaded via AJAX to `POST /cart/upload-temp` at selection time, stored in `temp-uploads/` on public disk. Path stored in cart's `custom_options`. Display views detect `temp-uploads/` prefix and render as thumbnail link.

## Models

| Model | Key relations | Notable casts / methods |
|---|---|---|
| `User` | `orders()` HasMany | `password => hashed`, `is_active => boolean`. `isAdmin()`, `isCustomer()`. |
| `Product` | `productCategory()` BelongsTo, `images()` HasMany, `primaryImage()` HasOne | `price => integer`, `stock => integer`, `is_active => boolean`. `scopeActive()`. `topSellers(3)` — queries `selesai` orders, falls back to highest-priced active. `getFormattedPriceAttribute()`. |
| `Category` | `products()` HasMany, `fields()` HasMany | Fillable: name, slug. |
| `CategoryField` | `category()` BelongsTo, `fieldOptions()` HasMany | `is_required => boolean`. Types: `text`, `select`, `checkbox`, `file`. Legacy `options` string col kept but null on admin saves — real options live in `category_field_options`. |
| `CategoryFieldOption` | `categoryField()` BelongsTo | `name`, `price => integer`. Selected options add to `price_snapshot`. |
| `ProductImage` | `product()` BelongsTo | `is_primary => boolean`. Max 5 per product. |
| `Order` | `user()` BelongsTo, `items()` HasMany, `trackingLogs()` HasMany | `needed_date => date`, `total_price => integer`, `payment_verified => boolean`. Fillable includes `admin_note`. Accessors: `getFormattedTotalAttribute()`, `getStatusLabelAttribute()`, `getStatusColorAttribute()`. |
| `OrderItem` | `order()` BelongsTo, `product()` BelongsTo | `price_snapshot => integer`, `quantity => integer`, `subtotal => integer`, `custom_options => array` (JSON cast). |
| `TrackingLog` | `order()` BelongsTo, `changedByUser()` BelongsTo (FK `changed_by`) | `$timestamps = false`, manual `created_at`. |

## Conventions

- **DB transactions** for multi-table writes: `Customer\OrderController::store()`, `Admin\OrderController::updateStatus()`, `ProductController::store/update()`.
- **Dual validation:** frontend (UX) + backend (security).
- **No generated code, no queue jobs, no events, no listeners, no notifications, no form requests, no custom Artisan commands.** `AppServiceProvider::register()` is empty; `boot()` is empty (https forcing is commented out).
- **File storage:** `products/` (images), `payment-proofs/` on `public` disk. Run `php artisan storage:link`.
- **Pagination:** customer orders 10, customer catalog 12, admin orders 15, admin products 10, admin customers 15.
- **Reorder feature** (`Customer\OrderController::create()`): accepts `?reorder=ORDER_ID` — pre-fills form, warns if price changed.
- **`CartController::checkStock()`** (`POST /cart/check-stock`): returns JSON with `{id, name, price, stock, is_active}` for frontend stock validation before checkout.
- **Testing:** uses in-memory SQLite (`DB_DATABASE=:memory:`), array cache/session, sync queue. See `phpunit.xml`. Only the default `ExampleTest` stubs exist — no real tests.
- **`resources/js/app.js`** is empty (Vite entry point only). All JS is in Blade templates.
