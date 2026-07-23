@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <a href="{{ route('customer.catalog.show', $product) }}" class="inline-flex items-center text-pink-600 hover:text-pink-800 mb-6">
        ← Kembali ke Produk
    </a>

    <h1 class="text-2xl font-bold text-pink-800 mb-6">Form Pemesanan</h1>

    @if(session('warning'))
        <div class="mb-4 p-4 bg-orange-50 border border-orange-200 text-orange-700 rounded-lg">
            {{ session('warning') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($reorderItem)
        <div class="mb-4 p-4 bg-pink-50 border border-pink-200 text-pink-700 rounded-lg text-sm">
            Mengulang pesanan dari pesanan sebelumnya. Jumlah dan catatan sudah terisi otomatis.
        </div>
    @endif

    {{-- Ringkasan Produk --}}
    <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-4 mb-6">
        <div class="flex items-center space-x-4">
            @if($product->primaryImage)
                <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}" class="w-20 h-20 object-cover rounded-lg">
            @else
                <div class="w-20 h-20 bg-pink-50 rounded-lg flex items-center justify-center text-2xl text-pink-300">🌸</div>
            @endif
            <div>
                <h2 class="font-semibold text-pink-800">{{ $product->name }}</h2>
                <p class="text-pink-600 font-bold">{{ $product->formatted_price }}</p>
                <p class="text-sm text-pink-500">Stok: {{ $product->stock }}</p>
            </div>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('customer.orders.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-pink-200 p-6 space-y-5">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        {{-- Nama Pemesan --}}
        <div>
            <label for="orderer_name" class="block text-sm font-medium text-pink-700">Nama Pemesan *</label>
            <input type="text" name="orderer_name" id="orderer_name" value="{{ old('orderer_name', $user->name) }}" required
                   class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
            @error('orderer_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Nomor HP --}}
        <div>
            <label for="orderer_phone" class="block text-sm font-medium text-pink-700">Nomor HP / WhatsApp *</label>
            <input type="text" name="orderer_phone" id="orderer_phone" value="{{ old('orderer_phone', $user->phone) }}" required placeholder="08xxxxxxxxxx"
                   class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
            @error('orderer_phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Jumlah --}}
        <div>
            <label for="quantity" class="block text-sm font-medium text-pink-700">Jumlah *</label>
            <input type="number" name="quantity" id="quantity" value="{{ old('quantity', $reorderItem['quantity'] ?? 1) }}" required min="1" max="{{ $product->stock }}"
                   class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
            @error('quantity') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            <p class="mt-1 text-sm text-pink-500">Subtotal: <span id="subtotal-display" class="font-semibold">{{ $product->formatted_price }}</span></p>
        </div>

        {{-- Tanggal Dibutuhkan --}}
        <div>
            <label for="needed_date" class="block text-sm font-medium text-pink-700">Tanggal Dibutuhkan *</label>
            <input type="date" name="needed_date" id="needed_date" value="{{ old('needed_date') }}" required min="{{ $minDate }}"
                   class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
            @error('needed_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Metode Pengambilan --}}
        <div>
            <label class="block text-sm font-medium text-pink-700 mb-2">Metode Pengambilan *</label>
            <div class="space-y-2">
                <label class="flex items-center space-x-3 p-3 border border-pink-200 rounded-lg cursor-pointer hover:bg-pink-50 transition">
                    <input type="radio" name="pickup_method" value="self_pickup" {{ old('pickup_method', $reorderItem['pickup_method'] ?? '') == 'self_pickup' ? 'checked' : '' }} required
                           onchange="toggleAddress(false)" class="text-pink-600 focus:ring-pink-500">
                    <div>
                        <span class="font-medium text-pink-800">Ambil Sendiri</span>
                        <p class="text-sm text-pink-500">Diambil di toko</p>
                    </div>
                </label>
                <label class="flex items-center space-x-3 p-3 border border-pink-200 rounded-lg cursor-pointer hover:bg-pink-50 transition">
                    <input type="radio" name="pickup_method" value="delivery" {{ old('pickup_method', $reorderItem['pickup_method'] ?? '') == 'delivery' ? 'checked' : '' }}
                           onchange="toggleAddress(true)" class="text-pink-600 focus:ring-pink-500">
                    <div>
                        <span class="font-medium text-pink-800">Diantar</span>
                        <p class="text-sm text-pink-500">Dikirim ke alamat</p>
                    </div>
                </label>
            </div>
            @error('pickup_method') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Alamat --}}
        <div id="address-section" class="{{ old('pickup_method', $reorderItem['pickup_method'] ?? '') != 'delivery' ? 'hidden' : '' }}">
            <label for="delivery_address" class="block text-sm font-medium text-pink-700">Alamat Tujuan *</label>
            <textarea name="delivery_address" id="delivery_address" rows="3" placeholder="Alamat lengkap pengiriman..."
                      class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">{{ old('delivery_address', $reorderItem['delivery_address'] ?? '') }}</textarea>
            @error('delivery_address') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Catatan Khusus --}}
        <div>
            <label for="special_note" class="block text-sm font-medium text-pink-700">Catatan Khusus</label>
            <textarea name="special_note" id="special_note" rows="2" placeholder="Warna bunga, tulisan kartu, dll. (opsional)"
                      class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">{{ old('special_note', $reorderItem['special_note'] ?? '') }}</textarea>
            @error('special_note') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Bukti Pembayaran --}}
        <div>
            <label for="payment_proof" class="block text-sm font-medium text-pink-700">Bukti Pembayaran *</label>
            <input type="file" name="payment_proof" id="payment_proof" accept="image/jpg,image/jpeg,image/png,image/webp" required
                   class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
            <p class="mt-1 text-sm text-pink-500">Upload screenshot bukti transfer (jpg/png/webp, maks 5MB)</p>
            @error('payment_proof') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Total --}}
        <div class="bg-pink-50 rounded-lg p-4">
            <div class="flex justify-between items-center">
                <span class="text-pink-700 font-medium">Total Pembayaran</span>
                <span id="total-display" class="text-xl font-bold text-pink-800">{{ $product->formatted_price }}</span>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex space-x-3">
            <a href="{{ route('customer.catalog.show', $product) }}" class="flex-1 text-center px-4 py-3 border border-pink-300 rounded-lg text-pink-700 hover:bg-pink-50 font-medium transition">
                Batal
            </a>
            <button type="submit" class="flex-1 px-4 py-3 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
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

// Trigger initial calculation for pre-filled quantity
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
