@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <a href="{{ route('customer.cart') }}" class="inline-flex items-center text-rose-500 hover:text-rose-600 mb-8 group transition">
        <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Keranjang
    </a>

    <h1 class="text-3xl font-bold text-slate-800 mb-2">Form Pemesanan</h1>
    <p class="text-slate-400 mb-8">Periksa kembali pesanan Anda sebelum mengirim.</p>

    @if(session('warning'))
        <div class="mb-5 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-2xl text-sm shadow-sm">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="lg:flex lg:gap-8">
        {{-- Order Summary (JS-rendered from localStorage) --}}
        <div class="lg:w-5/12 mb-8 lg:mb-0">
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-5 shadow-sm lg:sticky lg:top-24">
                <h2 class="font-semibold text-slate-800 mb-4">Ringkasan Pesanan</h2>
                <div id="summary-items" class="space-y-3 mb-4"></div>
                <div id="summary-empty" class="hidden text-center py-8 text-slate-400">
                    Keranjang kosong. <a href="{{ route('customer.cart') }}" class="text-rose-500 underline">Kembali ke keranjang</a>
                </div>
                <div id="summary-footer" class="hidden border-t border-rose-100 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-600 font-medium">Total</span>
                        <span id="summary-total" class="text-xl font-bold text-slate-800"></span>
                    </div>
                </div>
                <div id="summary-stock-error" class="hidden mt-3 p-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700 font-medium">
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="lg:w-7/12">
            <form id="checkout-form" action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data" class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-7 space-y-6 shadow-sm">
                @csrf
                <input type="hidden" name="cart_payload" id="cart_payload" value="">

                <div>
                    <label for="orderer_name" class="block text-sm font-semibold text-slate-600 mb-1.5">Nama Pemesan *</label>
                    <input type="text" name="orderer_name" id="orderer_name" value="{{ old('orderer_name', $user->name) }}" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">
                    @error('orderer_name') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="orderer_phone" class="block text-sm font-semibold text-slate-600 mb-1.5">Nomor HP / WhatsApp *</label>
                    <input type="text" name="orderer_phone" id="orderer_phone" value="{{ old('orderer_phone', $user->phone) }}" required placeholder="08xxxxxxxxxx"
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">
                    @error('orderer_phone') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="needed_date" class="block text-sm font-semibold text-slate-600 mb-1.5">Tanggal Dibutuhkan *</label>
                    <input type="date" name="needed_date" id="needed_date" value="{{ old('needed_date') }}" required min="{{ $minDate }}"
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">
                    @error('needed_date') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-600 mb-2">Metode Pengambilan *</label>
                    <div class="space-y-2.5">
                        <label class="flex items-center space-x-3 p-3.5 border border-rose-200 rounded-xl cursor-pointer hover:bg-rose-50/50 transition group">
                            <input type="radio" name="pickup_method" value="self_pickup" {{ old('pickup_method') == 'self_pickup' ? 'checked' : '' }} required
                                   onchange="toggleAddress(false)" class="text-rose-500 focus:ring-rose-400">
                            <div>
                                <span class="font-medium text-slate-800 group-hover:text-rose-600 transition">Ambil Sendiri</span>
                                <p class="text-sm text-slate-400">Diambil di toko</p>
                            </div>
                        </label>
                        <label class="flex items-center space-x-3 p-3.5 border border-rose-200 rounded-xl cursor-pointer hover:bg-rose-50/50 transition group">
                            <input type="radio" name="pickup_method" value="delivery" {{ old('pickup_method') == 'delivery' ? 'checked' : '' }}
                                   onchange="toggleAddress(true)" class="text-rose-500 focus:ring-rose-400">
                            <div>
                                <span class="font-medium text-slate-800 group-hover:text-rose-600 transition">Diantar</span>
                                <p class="text-sm text-slate-400">Dikirim ke alamat</p>
                            </div>
                        </label>
                    </div>
                    @error('pickup_method') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div id="address-section" class="{{ old('pickup_method') != 'delivery' ? 'hidden' : '' }}">
                    <label for="delivery_address" class="block text-sm font-semibold text-slate-600 mb-1.5">Alamat Tujuan *</label>
                    <textarea name="delivery_address" id="delivery_address" rows="3" placeholder="Alamat lengkap pengiriman..."
                              class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">{{ old('delivery_address', $user->address) }}</textarea>
                    @error('delivery_address') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="special_note" class="block text-sm font-semibold text-slate-600 mb-1.5">Catatan Khusus</label>
                    <textarea name="special_note" id="special_note" rows="2" placeholder="Warna bunga, tulisan kartu, dll. (opsional)"
                              class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">{{ old('special_note') }}</textarea>
                    @error('special_note') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="payment_proof" class="block text-sm font-semibold text-slate-600 mb-1.5">Bukti Pembayaran *</label>
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/jpg,image/jpeg,image/png,image/webp" required
                           class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200">
                    <p class="mt-1.5 text-xs text-slate-400">Upload screenshot bukti transfer (jpg/png/webp, maks 5MB)</p>
                    @error('payment_proof') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
                </div>

                <div id="form-stock-warning" class="hidden p-4 bg-red-50 border border-red-200 rounded-2xl text-sm text-red-700 font-medium">
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('customer.cart') }}" class="flex-1 text-center px-4 py-3.5 border border-rose-200 rounded-xl text-slate-600 hover:bg-rose-50 font-semibold transition text-sm">
                        Batal
                    </a>
                    <button type="submit" id="submit-btn" class="flex-1 px-4 py-3.5 bg-rose-400 text-white rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                        Pesan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const CHECK_STOCK_URL = '{{ route("customer.cart.check-stock") }}';
let stockDataMap = {};
let cartHasStockIssue = false;

function toggleAddress(show) {
    const section = document.getElementById('address-section');
    if (show) {
        section.classList.remove('hidden');
        document.getElementById('delivery_address').required = true;
    } else {
        section.classList.add('hidden');
        document.getElementById('delivery_address').required = false;
    }
}

async function loadSummary() {
    const items = CartStorage.get();
    const summaryEl  = document.getElementById('summary-items');
    const emptyEl    = document.getElementById('summary-empty');
    const footerEl   = document.getElementById('summary-footer');
    const totalEl    = document.getElementById('summary-total');
    const submitBtn  = document.getElementById('submit-btn');

    if (items.length === 0) {
        summaryEl.innerHTML = '';
        emptyEl.classList.remove('hidden');
        footerEl.classList.add('hidden');
        submitBtn.classList.add('pointer-events-none', 'bg-slate-300');
        submitBtn.classList.remove('bg-rose-400');
        return;
    }

    emptyEl.classList.add('hidden');
    footerEl.classList.remove('hidden');

    function renderSummaryOptions(opts) {
        if (!opts) return '';
        const entries = Object.entries(opts);
        if (entries.length === 0) return '';
        return '<div class="mt-1 space-y-0.5">' + entries.map(function(kv) {
            const val = Array.isArray(kv[1]) ? kv[1].join(', ') : kv[1];
            return '<span class="text-xs text-gray-400"><b>' + kv[0] + ':</b> ' + val + '</span>';
        }).join('') + '</div>';
    }

    summaryEl.innerHTML = items.map(function(item) {
        return '<div class="flex items-start gap-3" data-summary-key="' + item._key + '">\
            <div class="w-12 h-12 flex-shrink-0 bg-gradient-to-br from-rose-50 to-pink-50 rounded-lg overflow-hidden">\
                ' + (item.image
                    ? '<img src="' + item.image + '" class="w-full h-full object-cover">'
                    : '<div class="w-full h-full flex items-center justify-center text-lg text-rose-200">\u{1F338}</div>') + '\
            </div>\
            <div class="flex-1 min-w-0">\
                <p class="text-sm font-medium text-slate-800 truncate">' + item.name + '</p>\
                <p class="text-xs text-slate-400">' + item.qty + ' x ' + formatRupiah(item.price) + '</p>\
                ' + renderSummaryOptions(item.custom_options) + '\
            </div>\
            <p class="text-sm font-bold text-slate-800">' + formatRupiah(item.price * item.qty) + '</p>\
        </div>';
    }).join('');

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
        stockDataMap = Object.fromEntries(stockData.map(p => [p.id, p]));
        cartHasStockIssue = false;
        let outOfStockNames = [];

        items.forEach(item => {
            const stock = stockDataMap[item.id];
            const el = document.querySelector(`[data-summary-key="${item._key}"]`);
            if (!el) return;

            if (!stock || !stock.is_active || item.qty > stock.stock) {
                cartHasStockIssue = true;
                el.classList.add('opacity-50');
                if (stock && stock.stock > 0 && item.qty > stock.stock) {
                    outOfStockNames.push(item.name + ' (sisa ' + stock.stock + ')');
                } else {
                    outOfStockNames.push(item.name + ' (habis)');
                }
            } else {
                el.classList.remove('opacity-50');
            }
        });

        const warningEl = document.getElementById('summary-stock-error');
        if (cartHasStockIssue) {
            warningEl.textContent = 'Stok tidak mencukupi: ' + outOfStockNames.join(', ');
            warningEl.classList.remove('hidden');
            submitBtn.classList.add('pointer-events-none', 'bg-slate-300');
            submitBtn.classList.remove('bg-rose-400');
        } else {
            warningEl.classList.add('hidden');
            submitBtn.classList.remove('pointer-events-none', 'bg-slate-300');
            submitBtn.classList.add('bg-rose-400');
        }

        const total = items.reduce((sum, item) => {
            const stock = stockDataMap[item.id];
            if (stock && stock.is_active && item.qty <= stock.stock) {
                return sum + item.price * item.qty;
            }
            return sum;
        }, 0);
        totalEl.textContent = formatRupiah(total);

    } catch (err) {
        console.error('Stock check failed:', err);
    }
}

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const items = CartStorage.get();
    const payload = JSON.stringify(items);
    document.getElementById('cart_payload').value = payload;
});

loadSummary();
</script>
@endsection
