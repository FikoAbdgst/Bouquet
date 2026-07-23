# AGENTS.md

## What this is

Greenfield Indonesian florist e-commerce app (web responsive, single-tenant). PRD is the source of truth: `PRD_Bucket_Bunga_App.md`. **Read it before implementing any module.**

## Key business rules (PRD sections an agent will get wrong)

- **Prices are IDR integer (no decimals).** Never use floats for money. `price_snapshot` in `order_items` must be copied at order time.
- **Order total is recalculated server-side only.** Never trust `total_price` from the client.
- **Status state machine is linear, no skipping:** `menunggu_konfirmasi` → `dikonfirmasi` → `diproses` → `dikirim` → `selesai`. Only exception: `dibatalkan` (terminal). Enforce in backend, not just UI.
- **Soft delete everywhere** — `products` and `users` use `is_active` flag, never hard delete. Old orders must still reference deleted products.
- **Delivery address is conditionally required** (`pickup_method == delivery`). Validate explicitly in backend.
- **`needed_date` must be ≥ H+1 from order date** (configurable via env, not hardcoded).
- **Snapshot fields** on `order_items` (`product_name_snapshot`, `price_snapshot`) are mandatory — history must survive product edits.
- **WhatsApp integration = deep link only** (no internal chat). Use `https://wa.me/{WA_ADMIN_NUMBER}?text={encoded_message}`. Admin number stored in env, not hardcoded.
- **Real-time = polling or SSE/WebSocket**, not complex infra. Polling every 5-10s is acceptable for v1.
- **All timestamps are UTC** in DB, converted to WIB (Asia/Jakarta) in UI only.
- **Single store only.** No multi-tenant/multi-vendor features unless explicitly requested.
- **Payment is manual verification** (upload proof → admin verifies). No payment gateway in v1.

## Modules (6 total, do not add scope)

`auth/`, `products/`, `orders/`, `tracking/`, `dashboard/`, `customers/`

WhatsApp integration is a **feature within existing modules** (e.g. button on product detail page), not a separate module/table.

Modules must not cross-query other modules' tables directly — use service/repository layer.

## Error response format (PRD §6.2)

```json
{ "success": false, "error": { "code": "ORDER_INVALID_DATE", "message": "..." } }
```

Use specific error codes, not generic messages.

## Database transactions

Multi-table operations (e.g. create order + order_items + tracking_log) **must** use a transaction with rollback on failure.

## Validation

Dual validation required: frontend (UX) + backend (security). Never trust frontend input alone.

## API versioning

Use `/api/v1/...` prefix.

## What NOT to build

Loyalty points, product ratings/reviews, multi-language, payment gateway, multi-tenancy, internal chat system — none of these in v1. Only the 6 modules above.
