@extends('layouts.app', ['hideNav' => true])

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <style>
        .font-display { font-family: "Cormorant Garamond", serif; font-optical-sizing: auto; font-weight: 500; font-style: normal; }
        .font-body { font-family: 'Inter', system-ui, sans-serif; }
    </style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="javascript:history.back()"
       class="inline-flex items-center gap-2 text-sm tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:gap-3 transition-all duration-200 mb-8">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <div class="text-center mb-10">
        <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Belanja</span>
        <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Keranjang Belanja</h2>
        <p id="cart-count-label" class="text-sm text-[#C9A9B4] mt-1"></p>
    </div>

    <div id="cart-empty" class="hidden border border-dashed border-[#EFD3DE] p-16 text-center">
        <p class="text-[#6E8577] text-lg">Keranjang Anda masih kosong.</p>
        <a href="{{ route('customer.catalog') }}" class="inline-block mt-4 border border-[#D37897] hover:bg-[#457359] hover:text-white text-[#33413A] text-sm tracking-wide px-6 py-2.5 transition-colors duration-200">
            Jelajahi Katalog
        </a>
    </div>

    <div id="cart-has-items" class="hidden">
        <div id="cart-items" class="space-y-px mb-8"></div>

        <div class="border border-[#EFD3DE] p-5">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <button type="button" onclick="clearCart()" class="text-sm text-[#C9A9B4] hover:text-[#D37897] transition font-medium tracking-wide">
                        Kosongkan Keranjang
                    </button>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577]">Total</p>
                        <p id="cart-total" class="text-2xl font-medium text-[#33413A]"></p>
                    </div>
                    <button id="checkout-btn" type="button" onclick="goCheckout()"
                            class="border border-[#D37897] hover:bg-[#457359] hover:text-white text-[#33413A] text-sm tracking-wide px-6 py-3 transition-colors duration-200">
                        Lanjut ke Pemesanan
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const CHECK_STOCK_URL = '{{ route("customer.cart.check-stock") }}';
const LOGIN_URL       = '{{ route("login") }}';
const CHECKOUT_URL    = '{{ route("orders.checkout") }}';

function goCheckout() {
    const btn = document.getElementById('checkout-btn');
    if (btn.classList.contains('pointer-events-none')) return;
    if (!window.isAuthenticated) {
        window.location.href = LOGIN_URL + '?redirect=' + encodeURIComponent(CHECKOUT_URL);
    } else {
        window.location.href = CHECKOUT_URL;
    }
}

function renderCustomOptions(opts) {
    if (!opts) return '';
    const entries = Object.entries(opts);
    if (entries.length === 0) return '';
    return entries.map(function(kv) {
        const val = Array.isArray(kv[1]) ? kv[1].join(', ') : kv[1];
        return '<div>' +
            '<p class="text-[10px] tracking-[0.15em] uppercase text-[#6E8577] leading-tight">' + kv[0] + '</p>' +
            '<p class="text-xs text-[#33413A]">' + val + '</p>' +
            '</div>';
    }).join('');
}

function renderCart() {
    const items = CartStorage.get();
    const emptyEl   = document.getElementById('cart-empty');
    const hasEl     = document.getElementById('cart-has-items');
    const countLbl  = document.getElementById('cart-count-label');
    const container = document.getElementById('cart-items');

    if (items.length === 0) {
        emptyEl.classList.remove('hidden');
        hasEl.classList.add('hidden');
        countLbl.textContent = 'Keranjang Anda masih kosong.';
        return;
    }

    emptyEl.classList.add('hidden');
    hasEl.classList.remove('hidden');
    countLbl.textContent = items.length + ' item di keranjang Anda.';

    const swatches = ['#DCD6C9', '#8E9A7C', '#B9C3C6', '#D9C2B4', '#A8AC98', '#C9B4B4'];
    container.innerHTML = items.map((item, idx) => `
        <div class="border border-[#EFD3DE] p-4 flex flex-col sm:flex-row sm:items-center gap-4 transition-colors duration-200" data-cart-key="${item._key}">
            <div class="w-20 h-20 flex-shrink-0 overflow-hidden" style="background-color: ${swatches[Math.abs(idx) % swatches.length]}">
                ${item.image
                    ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">`
                    : '<div class="w-full h-full flex items-center justify-center text-2xl text-[#C9A9B4]">—</div>'}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start gap-1${item.custom_options ? ' cursor-pointer' : ''}"${item.custom_options ? ' onclick="this.nextElementSibling.classList.toggle(\'hidden\');this.querySelector(\'.cv\').classList.toggle(\'-rotate-180\')"' : ''}>
                    <div class="min-w-0">
                        <h3 class="text-sm font-medium text-[#33413A] truncate">${item.name}</h3>
                        <p class="text-[#D37897] font-medium mt-0.5">${formatRupiah(item.price)}</p>
                    </div>
                    ${item.custom_options ? `<svg class="cv w-2.5 h-2.5 mt-1 flex-shrink-0 text-[#C9A9B4] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>` : ''}
                </div>
                ${item.custom_options ? `<div class="hidden mt-2 space-y-1 pl-2.5 border-l border-[#EFD3DE]">
                    ${renderCustomOptions(item.custom_options)}
                </div>` : ''}
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center border border-[#EFD3DE]">
                    <button type="button" onclick="changeQty('${item._key}', -1)" class="px-3 py-1.5 text-[#6E8577] hover:text-[#D37897] transition font-bold text-lg leading-none">−</button>
                    <span class="px-3 py-1.5 text-sm font-medium text-[#33413A] min-w-[2.5rem] text-center">${item.qty}</span>
                    <button type="button" onclick="changeQty('${item._key}', 1)" class="px-3 py-1.5 text-[#6E8577] hover:text-[#D37897] transition font-bold text-lg leading-none">+</button>
                </div>
                <p class="font-medium text-[#33413A] min-w-[90px] text-right">${formatRupiah(item.price * item.qty)}</p>
                <button type="button" onclick="removeItem('${item._key}')" class="p-1.5 text-[#C9A9B4] hover:text-[#D37897] transition" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    `).join('');

    document.getElementById('cart-total').textContent = formatRupiah(CartStorage.total());
    checkStock();
}

function changeQty(key, delta) {
    const items = CartStorage.get();
    const item = items.find(i => i._key === key);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    CartStorage.save(items);
    renderCart();
}

function removeItem(key) {
    CartStorage.removeItem(key);
    renderCart();
}

function clearCart() {
    if (confirm('Kosongkan seluruh keranjang?')) {
        CartStorage.clear();
        renderCart();
    }
}

async function checkStock() {
    const items = CartStorage.get();
    if (items.length === 0) return;

    try {
        const resp = await fetch(CHECK_STOCK_URL, {
            method: 'POST',
            headers: {
                'Content-Type':     'application/json',
                'X-CSRF-TOKEN':     document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ ids: items.map(i => i.id) })
        });

        if (!resp.ok) return;

        const stockData = await resp.json();
        const stockMap  = Object.fromEntries(stockData.map(p => [p.id, p]));
        let hasProblem  = false;

        items.forEach(item => {
            const stock = stockMap[item.id];
            const card  = document.querySelector(`[data-cart-key="${item._key}"]`);
            if (!card) return;

            card.classList.remove('bg-red-50', 'border-red-200');
            card.querySelectorAll('.stock-warning').forEach(el => el.remove());

            if (!stock) {
                card.classList.add('bg-red-50', 'border-red-200');
                card.insertAdjacentHTML('beforeend',
                    '<p class="stock-warning w-full text-sm font-medium text-red-700 mt-1">Produk tidak ditemukan atau telah dihapus.</p>');
                hasProblem = true;
            } else if (!stock.is_active) {
                card.classList.add('bg-red-50', 'border-red-200');
                card.insertAdjacentHTML('beforeend',
                    '<p class="stock-warning w-full text-sm font-medium text-red-700 mt-1">Produk ini sudah tidak tersedia.</p>');
                hasProblem = true;
            } else if (item.qty > stock.stock) {
                card.classList.add('bg-red-50', 'border-red-200');
                const msg = stock.stock > 0
                    ? `Stok sisa ${stock.stock}!`
                    : 'Stok Habis!';
                card.insertAdjacentHTML('beforeend',
                    `<p class="stock-warning w-full text-sm font-medium text-red-700 mt-1">${msg}</p>`);
                hasProblem = true;
            }
        });

        const checkoutBtn = document.getElementById('checkout-btn');
        if (hasProblem) {
            checkoutBtn.classList.add('border-[#C9A9B4]', 'text-[#C9A9B4]', 'cursor-not-allowed', 'pointer-events-none');
            checkoutBtn.classList.remove('border-[#D37897]', 'hover:bg-[#457359]', 'hover:text-white', 'text-[#33413A]');
        } else {
            checkoutBtn.classList.remove('border-[#C9A9B4]', 'text-[#C9A9B4]', 'cursor-not-allowed', 'pointer-events-none');
            checkoutBtn.classList.add('border-[#D37897]', 'hover:bg-[#457359]', 'hover:text-white', 'text-[#33413A]');
        }

    } catch (err) {
        console.error('Stock check failed:', err);
    }
}

renderCart();
setInterval(checkStock, 12000);
</script>
@endsection
