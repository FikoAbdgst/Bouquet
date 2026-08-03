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
<div class="max-w-5xl mx-auto">
    <a href="{{ route('home') }}"
       class="inline-flex items-center gap-2 text-sm tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:gap-3 transition-all duration-200 mb-8">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Beranda
    </a>

    <div class="text-center mb-10">
        <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Pemesanan</span>
        <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Form Pemesanan</h2>
        <p class="text-sm text-[#C9A9B4] mt-1">Periksa kembali pesanan Anda sebelum mengirim.</p>
    </div>

    @if(session('warning'))
        <div class="mb-5 p-4 border border-[#D9C3B4] text-[#5C6F5E] text-sm">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 p-4 border border-[#D9C2B4] text-[#D37897] text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="lg:flex lg:gap-8">
        {{-- Order Summary (JS-rendered from localStorage) --}}
        <div class="lg:w-5/12 mb-8 lg:mb-0">
            <div class="border border-[#EFD3DE] p-5 lg:sticky lg:top-24">
                <h3 class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-4">Ringkasan Pesanan</h3>
                <div id="summary-items" class="space-y-3 mb-4"></div>
                <div id="summary-empty" class="hidden text-center py-8 text-[#6E8577] text-sm">
                    Keranjang kosong. <a href="{{ route('customer.cart') }}" class="text-[#D37897] underline">Kembali ke keranjang</a>
                </div>
                <div id="summary-footer" class="hidden border-t border-[#EFD3DE] pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-[#6E8577]">Total</span>
                        <span id="summary-total" class="text-xl font-medium text-[#33413A]"></span>
                    </div>
                </div>
                <div id="summary-stock-error" class="hidden mt-3 p-3 border border-red-200 bg-red-50 text-sm text-red-700 font-medium">
                </div>
            </div>
        </div>

        {{-- Form --}}
        <div class="lg:w-7/12">
            <form id="checkout-form" action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data" class="border border-[#EFD3DE] p-7 space-y-6">
                @csrf
                <input type="hidden" name="cart_payload" id="cart_payload" value="">

                <div>
                    <label for="orderer_name" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Nama Pemesan *</label>
                    <input type="text" name="orderer_name" id="orderer_name" value="{{ old('orderer_name', $user->name) }}" required
                           class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
                    @error('orderer_name') <p class="mt-1 text-xs text-[#D37897]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="orderer_phone" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Nomor HP / WhatsApp *</label>
                    <input type="text" name="orderer_phone" id="orderer_phone" value="{{ old('orderer_phone', $user->phone) }}" required placeholder="08xxxxxxxxxx"
                           class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
                    @error('orderer_phone') <p class="mt-1 text-xs text-[#D37897]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="needed_date" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Tanggal Dibutuhkan *</label>
                    <input type="date" name="needed_date" id="needed_date" value="{{ old('needed_date') }}" required min="{{ $minDate }}"
                           class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">
                    @error('needed_date') <p class="mt-1 text-xs text-[#D37897]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-3">Metode Pengambilan *</label>
                    <div class="space-y-px">
                        <label class="flex items-center gap-3 p-3.5 border border-[#EFD3DE] cursor-pointer hover:bg-[#F9DEE5] transition">
                            <input type="radio" name="pickup_method" value="self_pickup" {{ old('pickup_method') == 'self_pickup' ? 'checked' : '' }} required
                                   onchange="toggleAddress(false)" class="text-[#D37897] focus:ring-[#D37897]">
                            <div>
                                <span class="text-sm font-medium text-[#33413A]">Ambil Sendiri</span>
                                <p class="text-xs text-[#6E8577]">Diambil di toko</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3.5 border border-[#EFD3DE] cursor-pointer hover:bg-[#F9DEE5] transition">
                            <input type="radio" name="pickup_method" value="delivery" {{ old('pickup_method') == 'delivery' ? 'checked' : '' }}
                                   onchange="toggleAddress(true)" class="text-[#D37897] focus:ring-[#D37897]">
                            <div>
                                <span class="text-sm font-medium text-[#33413A]">Diantar</span>
                                <p class="text-xs text-[#6E8577]">Dikirim ke alamat</p>
                            </div>
                        </label>
                    </div>
                    @error('pickup_method') <p class="mt-1 text-xs text-[#D37897]">{{ $message }}</p> @enderror
                </div>

                <div id="address-section" class="{{ old('pickup_method') != 'delivery' ? 'hidden' : '' }}">
                    <label for="delivery_address" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Alamat Tujuan *</label>
                    <textarea name="delivery_address" id="delivery_address" rows="3" placeholder="Alamat lengkap pengiriman..."
                              class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors resize-none">{{ old('delivery_address', $user->address) }}</textarea>
                    @error('delivery_address') <p class="mt-1 text-xs text-[#D37897]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="special_note" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Catatan Khusus</label>
                    <textarea name="special_note" id="special_note" rows="2" placeholder="Warna bunga, tulisan kartu, dll. (opsional)"
                              class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors resize-none">{{ old('special_note') }}</textarea>
                    @error('special_note') <p class="mt-1 text-xs text-[#D37897]">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="payment_proof" class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Bukti Pembayaran *</label>
                    <input type="file" name="payment_proof" id="payment_proof" accept="image/jpg,image/jpeg,image/png,image/webp" required
                           class="block w-full text-sm text-[#33413A] file:border file:border-[#EFD3DE] file:px-4 file:py-2 file:text-sm file:tracking-wide file:bg-transparent file:text-[#33413A] hover:file:bg-[#F9DEE5] file:transition-colors file:cursor-pointer file:mr-4 transition-colors">
                    <p class="mt-1.5 text-xs text-[#C9A9B4]">Upload screenshot bukti transfer (jpg/png/webp, maks 5MB)</p>
                    @error('payment_proof') <p class="mt-1 text-xs text-[#D37897]">{{ $message }}</p> @enderror
                </div>

                <div id="form-stock-warning" class="hidden p-4 border border-red-200 bg-red-50 text-sm text-red-700 font-medium">
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('customer.cart') }}"
                       class="flex-1 text-center px-4 py-3 border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#33413A] text-sm tracking-wide transition-colors duration-200">
                        Batal
                    </a>
                    <button type="submit" id="submit-btn"
                            class="flex-1 px-4 py-3 border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200">
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
        submitBtn.classList.add('pointer-events-none', 'opacity-50');
        return;
    }

    emptyEl.classList.add('hidden');
    footerEl.classList.remove('hidden');

    function isFilePath(val) {
        if (typeof val === 'string') return val.startsWith('temp-uploads/');
        if (val && typeof val === 'object' && val.value && typeof val.value === 'string') return val.value.startsWith('temp-uploads/');
        return false;
    }

    function getDisplayValue(val) {
        if (typeof val === 'string') return val;
        if (Array.isArray(val)) return val.map(function(v) { return (v && typeof v === 'object') ? v.value : v; }).join(', ');
        if (val && typeof val === 'object' && val.value) return val.value;
        return String(val);
    }

    function getFileUrl(val) {
        if (typeof val === 'string' && val.startsWith('temp-uploads/')) return val;
        if (val && typeof val === 'object' && val.value && typeof val.value === 'string' && val.value.startsWith('temp-uploads/')) return val.value;
        return '';
    }

    function renderSummaryOptions(opts) {
        if (!opts) return '';
        const entries = Object.entries(opts);
        if (entries.length === 0) return '';
        return entries.map(function(kv) {
            var val = kv[1];
            var fileUrl = getFileUrl(val);
            return '<div>' +
                '<p class="text-[10px] tracking-[0.15em] uppercase text-[#6E8577]">' + kv[0] + '</p>' +
                (fileUrl
                    ? '<a href="/storage/' + fileUrl + '" target="_blank" class="inline-block mt-1"><img src="/storage/' + fileUrl + '" class="w-14 h-14 object-cover border border-[#EFD3DE] hover:opacity-80 transition"></a>'
                    : '<p class="text-xs text-[#33413A]">' + getDisplayValue(val) + '</p>') +
                '</div>';
        }).join('');
    }

    summaryEl.innerHTML = items.map(function(item) {
        var safeKey = encodeURIComponent(item._key);
        var hasOpts = item.custom_options && Object.keys(item.custom_options).length > 0;
        return '<div class="flex items-start gap-3 pb-3 border-b border-[#EFD3DE]/50 last:border-0 last:pb-0" data-summary-key="' + safeKey + '">\
            <div class="w-10 h-10 flex-shrink-0 overflow-hidden bg-[#F9DEE5]">\
                ' + (item.image
                    ? '<img src="' + item.image + '" class="w-full h-full object-cover">'
                    : '<div class="w-full h-full flex items-center justify-center text-sm text-[#C9A9B4]">—</div>') + '\
            </div>\
            <div class="flex-1 min-w-0">\
                <div class="flex items-start gap-1' + (hasOpts ? ' cursor-pointer toggle-options' : '') + '">\
                    <div class="min-w-0">\
                        <p class="text-sm text-[#33413A] truncate">' + item.name + '</p>\
                        <p class="text-xs text-[#6E8577]">' + item.qty + ' x ' + formatRupiah(item.price) + '</p>\
                    </div>\
                    ' + (hasOpts
                        ? '<svg class="cv w-2.5 h-2.5 mt-0.5 flex-shrink-0 text-[#C9A9B4] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>'
                        : '') + '\
                </div>\
                ' + (hasOpts
                    ? '<div class="hidden mt-2 space-y-1 pl-2.5 border-l border-[#EFD3DE]">' + renderSummaryOptions(item.custom_options) + '</div>'
                    : '') + '\
            </div>\
            <p class="text-sm font-medium text-[#33413A]">' + formatRupiah(item.price * item.qty) + '</p>\
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
            const el = document.querySelector(`[data-summary-key="${encodeURIComponent(item._key)}"]`);
            if (!el) return;

            if (!stock || !stock.is_active || item.qty > stock.stock) {
                cartHasStockIssue = true;
                el.classList.add('opacity-40');
                if (stock && stock.stock > 0 && item.qty > stock.stock) {
                    outOfStockNames.push(item.name + ' (sisa ' + stock.stock + ')');
                } else {
                    outOfStockNames.push(item.name + ' (habis)');
                }
            } else {
                el.classList.remove('opacity-40');
            }
        });

        const warningEl = document.getElementById('summary-stock-error');
        if (cartHasStockIssue) {
            warningEl.textContent = 'Stok tidak mencukupi: ' + outOfStockNames.join(', ');
            warningEl.classList.remove('hidden');
            submitBtn.classList.add('pointer-events-none', 'opacity-50');
            submitBtn.classList.remove('opacity-100');
        } else {
            warningEl.classList.add('hidden');
            submitBtn.classList.remove('pointer-events-none', 'opacity-50');
            submitBtn.classList.add('opacity-100');
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

document.getElementById('summary-items').addEventListener('click', function (e) {
    const toggle = e.target.closest('.toggle-options');
    if (!toggle) return;
    const options = toggle.nextElementSibling;
    if (options) {
        options.classList.toggle('hidden');
        toggle.querySelector('.cv')?.classList.toggle('-rotate-180');
    }
});

document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const items = CartStorage.get();
    const payload = JSON.stringify(items);
    document.getElementById('cart_payload').value = payload;
});

loadSummary();
</script>
@endsection
