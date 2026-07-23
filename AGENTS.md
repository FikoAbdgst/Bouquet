# AGENTS.md

## Stack

Laravel 13, PHP 8.3, SQLite (default), Tailwind CSS v4, Blade views. No JavaScript framework — server-rendered.

## Commands

| Action | Command |
|---|---|
| Setup from scratch | `composer setup` (installs deps, generates key, migrates, builds assets) |
| Full dev server | `composer dev` (runs `artisan serve`, queue, pail, vite concurrently) |
| Run all tests | `composer test` or `php artisan test` |
| Run single test | `php artisan test --filter=TestClassName` or `php artisan test tests/Feature/SomeTest.php` |
| Build assets | `npm run build` |
| Clear config cache | `php artisan config:clear` (runs automatically before `composer test`) |

No lint, typecheck, or formatter scripts are configured. Pint is in dev deps but has no script.

## Architecture

Server-rendered Blade app. No API routes exist — all routes in `routes/web.php`.

**Layouts:**
- `resources/views/layouts/app.blade.php` — customer-facing (rose theme, Inter font via Google Fonts CDN)
- `resources/views/layouts/admin.blade.php` — admin panel (pink theme, sidebar nav)

**Tailwind is loaded two ways:** CDN `<script>` in both layouts AND Vite plugin in `vite.config.js`. Do not add a third loading method.

**Controller groups:**
- `app/Http/Controllers/Admin/` — admin dashboard, products, orders, customers
- `app/Http/Customer/` — catalog, cart, orders (customer-facing)
- `app/Http/Controllers/Auth/` — login, register

**Middleware:** `role:admin` / `role:customer` uses custom `EnsureRole` class (registered as `role` alias in `bootstrap/app.php`). Supports variadic roles: `role:admin,customer`.

**Cart:** localStorage-based (client-side). No session or DB storage. Cart data lives in `localStorage` as `[{id, name, price, image, qty}]`. Managed via `resources/js/cart.js` (`CartStorage` API). Works for guests and logged-in users without any backend state.

## Key business rules (PRD — agents will get these wrong)

- **Prices are IDR integer, no decimals.** Never use floats for money.
- **`price_snapshot` and `product_name_snapshot` on `order_items` are mandatory** — copied at order time, must survive product edits.
- **Order total is recalculated server-side only.** Never trust `total_price` from the client.
- **Status state machine is linear, no skipping:** `menunggu_konfirmasi` → `dikonfirmasi` → `diproses` → `dikirim` → `selesai`. `dibatalkan` is terminal and can be set from any state. Enforced in `Admin\OrderController::updateStatus()`.
- **Soft delete everywhere** — `products` and `users` use `is_active` flag, never hard delete.
- **Delivery address is conditionally required** (`pickup_method == delivery`). Validate in backend.
- **`needed_date` must be ≥ H+1** from order date. Lead days configurable via `MIN_ORDER_LEAD_DAYS` env (default 1).
- **WhatsApp integration = deep link only.** `https://wa.me/{WA_ADMIN_NUMBER}?text={encoded_message}`. Admin number stored in `config/app.php` key `wa_admin_number` (from `WA_ADMIN_NUMBER` env), not hardcoded.
- **All timestamps are UTC** in DB, converted to WIB (Asia/Jakarta) in UI only.
- **Payment is manual verification** (upload proof → admin verifies). No payment gateway in v1.

## Modules (6 — do not add scope)

`auth/`, `products/`, `orders/`, `tracking/`, `dashboard/`, `customers/`

WhatsApp integration is a **feature within existing modules**, not a separate module.

## Error response format (PRD §6.2)

```json
{ "success": false, "error": { "code": "ORDER_INVALID_DATE", "message": "..." } }
```

Use specific error codes, not generic messages.

## Conventions

- **DB transactions required** for multi-table writes (order + order_items + tracking_log). See existing `Customer\OrderController::store()` and `Admin\OrderController::updateStatus()` for pattern.
- **Dual validation required:** frontend (UX) + backend (security).
- **Order code format:** `ORD-{Ymd}-{3 random uppercase chars}` (e.g. `ORD-20260723-A3F`).
- **File storage:** product images to `products/`, payment proofs to `payment-proofs/` on `public` disk.
- **Phone validation:** must match `08XXXXXXXXXX` pattern (Indonesian mobile).

## What NOT to build

Loyalty points, product ratings/reviews, multi-language, payment gateway, multi-tenancy, internal chat system — none in v1. Only the 6 modules above.

## Reference

PRD is the source of truth: `PRD_Bucket_Bunga_App.md`. Read it before implementing any module.
