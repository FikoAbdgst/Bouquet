@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('customer.catalog.show', $product) }}" class="inline-flex items-center text-rose-500 hover:text-rose-600 mb-8 group transition">
        <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Produk
    </a>

    <h1 class="text-3xl font-bold text-slate-800 mb-8">Form Pemesanan</h1>

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

    @if($reorderItem)
        <div class="mb-5 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl text-sm shadow-sm">
            Mengulang pesanan dari pesanan sebelumnya. Jumlah dan catatan sudah terisi otomatis.
        </div>
    @endif

    {{-- Product Summary --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-5 mb-6 shadow-sm">
        <div class="flex items-center space-x-4">
            @if($product->primaryImage)
                <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-xl">
            @else
                <div class="w-20 h-20 bg-gradient-to-br from-rose-50 to-pink-50 rounded-xl flex items-center justify-center text-2xl text-rose-200">🌸</div>
            @endif
            <div>
                <h2 class="font-semibold text-slate-800">{{ $product->name }}</h2>
                <p class="text-rose-500 font-bold text-lg">{{ $product->formatted_price }}</p>
                <p class="text-xs text-slate-400">Stok: {{ $product->stock }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data" class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-7 space-y-6 shadow-sm">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

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
            <label for="quantity" class="block text-sm font-semibold text-slate-600 mb-1.5">Jumlah *</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $reorderItem['quantity'] ?? 1) }}" required min="1" max="{{ $product->stock }}"
                   class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">
            @error('quantity') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
            <p class="mt-1.5 text-sm text-slate-400">Subtotal: <span id="subtotal-display" class="font-semibold text-slate-700">{{ $product->formatted_price }}</span></p>
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
                    <input type="radio" name="pickup_method" value="self_pickup" {{ old('pickup_method', $reorderItem['pickup_method'] ?? '') == 'self_pickup' ? 'checked' : '' }} required
                           onchange="toggleAddress(false)" class="text-rose-500 focus:ring-rose-400">
                    <div>
                        <span class="font-medium text-slate-800 group-hover:text-rose-600 transition">Ambil Sendiri</span>
                        <p class="text-sm text-slate-400">Diambil di toko</p>
                    </div>
                </label>
                <label class="flex items-center space-x-3 p-3.5 border border-rose-200 rounded-xl cursor-pointer hover:bg-rose-50/50 transition group">
                    <input type="radio" name="pickup_method" value="delivery" {{ old('pickup_method', $reorderItem['pickup_method'] ?? '') == 'delivery' ? 'checked' : '' }}
                           onchange="toggleAddress(true)" class="text-rose-500 focus:ring-rose-400">
                    <div>
                        <span class="font-medium text-slate-800 group-hover:text-rose-600 transition">Diantar</span>
                        <p class="text-sm text-slate-400">Dikirim ke alamat</p>
                    </div>
                </label>
            </div>
            @error('pickup_method') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div id="address-section" class="{{ old('pickup_method', $reorderItem['pickup_method'] ?? '') != 'delivery' ? 'hidden' : '' }}">
            <label for="delivery_address" class="block text-sm font-semibold text-slate-600 mb-1.5">Alamat Tujuan *</label>
            <textarea name="delivery_address" id="delivery_address" rows="3" placeholder="Alamat lengkap pengiriman..."
                      class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">{{ old('delivery_address', $reorderItem['delivery_address'] ?? '') }}</textarea>
            @error('delivery_address') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="special_note" class="block text-sm font-semibold text-slate-600 mb-1.5">Catatan Khusus</label>
            <textarea name="special_note" id="special_note" rows="2" placeholder="Warna bunga, tulisan kartu, dll. (opsional)"
                      class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50">{{ old('special_note', $reorderItem['special_note'] ?? '') }}</textarea>
            @error('special_note') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="payment_proof" class="block text-sm font-semibold text-slate-600 mb-1.5">Bukti Pembayaran *</label>
            <input type="file" name="payment_proof" id="payment_proof" accept="image/jpg,image/jpeg,image/png,image/webp" required
                   class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-rose-50/50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-rose-100 file:text-rose-700 hover:file:bg-rose-200">
            <p class="mt-1.5 text-xs text-slate-400">Upload screenshot bukti transfer (jpg/png/webp, maks 5MB)</p>
            @error('payment_proof') <p class="mt-1 text-sm text-rose-600">{{ $message }}</p> @enderror
        </div>

        <div class="bg-gradient-to-r from-rose-50 to-pink-50 rounded-2xl p-5 border border-rose-100">
            <div class="flex justify-between items-center">
                <span class="text-slate-600 font-medium">Total Pembayaran</span>
                <span id="total-display" class="text-2xl font-bold text-slate-800">{{ $product->formatted_price }}</span>
            </div>
        </div>

        <div class="flex space-x-3">
            <a href="{{ route('customer.catalog.show', $product) }}" class="flex-1 text-center px-4 py-3.5 border border-rose-200 rounded-xl text-slate-600 hover:bg-rose-50 font-semibold transition text-sm">
                Batal
            </a>
            <button type="submit" class="flex-1 px-4 py-3.5 bg-rose-400 text-white rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                Pesan Sekarang
            </button>
        </div>
    </form>
</div>

<script>
const price = {{ $product->price }};

function formatRupiah(num) {
    return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

document.getElementById('quantity').addEventListener('input', function() {
    const qty = Math.max(1, parseInt(this.value) || 1);
    const subtotal = price * qty;
    document.getElementById('subtotal-display').textContent = formatRupiah(subtotal);
    document.getElementById('total-display').textContent = formatRupiah(subtotal);
});

(function() {
    const qtyInput = document.getElementById('quantity');
    if (qtyInput.value && parseInt(qtyInput.value) > 1) {
        qtyInput.dispatchEvent(new Event('input'));
    }
})();

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
</script>
@endsection
