@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Keranjang Belanja</h1>
        <p id="cart-count-label" class="text-slate-400 mt-1"></p>
    </div>

    <div id="cart-empty" class="hidden bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-16 text-center shadow-sm">
        <p class="text-5xl mb-4">🛒</p>
        <p class="text-slate-500 text-lg">Keranjang Anda masih kosong.</p>
        <a href="{{ route('customer.catalog') }}" class="inline-block mt-4 bg-rose-400 text-white px-6 py-2.5 rounded-xl hover:bg-rose-500 font-medium transition-all duration-200 shadow-sm hover:shadow-md text-sm">
            Jelajahi Katalog
        </a>
    </div>

    <div id="cart-has-items" class="hidden">
        <div id="cart-items" class="space-y-4 mb-8"></div>

        {{-- Summary --}}
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <button type="button" onclick="clearCart()" class="text-sm text-slate-400 hover:text-rose-500 transition font-medium">
                        Kosongkan Keranjang
                    </button>
                </div>
                <div class="flex items-center gap-6">
                    <div class="text-right">
                        <p class="text-sm text-slate-400">Total</p>
                        <p id="cart-total" class="text-2xl font-bold text-slate-800"></p>
                    </div>
                    <button id="checkout-btn" type="button" onclick="goCheckout()" class="bg-rose-400 text-white px-6 py-3 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
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
    container.innerHTML = items.map(item => `
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-5 shadow-sm flex flex-col sm:flex-row sm:items-center gap-4" data-product-id="${item.id}">
            <div class="w-20 h-20 flex-shrink-0 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl overflow-hidden">
                ${item.image
                    ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">`
                    : '<div class="w-full h-full flex items-center justify-center text-2xl text-rose-200">🌸</div>'}
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-semibold text-slate-800 truncate">${item.name}</h3>
                <p class="text-rose-500 font-bold mt-1">${formatRupiah(item.price)}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center bg-rose-50 rounded-xl border border-rose-100 overflow-hidden">
                    <button type="button" onclick="changeQty(${item.id}, -1)" class="px-3 py-2 text-slate-500 hover:text-rose-600 hover:bg-rose-100 transition font-bold">−</button>
                    <span class="px-3 py-2 text-sm font-medium text-slate-600 min-w-[2.5rem] text-center">${item.qty}</span>
                    <button type="button" onclick="changeQty(${item.id}, 1)" class="px-3 py-2 text-slate-500 hover:text-rose-600 hover:bg-rose-100 transition font-bold">+</button>
                </div>
                <p class="font-bold text-slate-800 min-w-[100px] text-right">${formatRupiah(item.price * item.qty)}</p>
                <button type="button" onclick="removeItem(${item.id})" class="p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition" title="Hapus">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    `).join('');

    document.getElementById('cart-total').textContent = formatRupiah(CartStorage.total());
    checkStock();
}

function changeQty(id, delta) {
    const items = CartStorage.get();
    const item = items.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.max(1, item.qty + delta);
    CartStorage.save(items);
    renderCart();
}

function removeItem(id) {
    CartStorage.removeItem(id);
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
            const card  = document.querySelector(`[data-product-id="${item.id}"]`);
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
        checkoutBtn.classList.toggle('bg-slate-300', hasProblem);
        checkoutBtn.classList.toggle('cursor-not-allowed', hasProblem);
        checkoutBtn.classList.toggle('pointer-events-none', hasProblem);
        checkoutBtn.classList.toggle('bg-rose-400', !hasProblem);
        checkoutBtn.classList.toggle('hover:bg-rose-500', !hasProblem);

    } catch (err) {
        console.error('Stock check failed:', err);
    }
}

renderCart();
setInterval(checkStock, 12000);
</script>
@endsection
