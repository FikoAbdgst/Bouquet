@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <a href="{{ route('customer.catalog') }}" class="inline-flex items-center text-rose-500 hover:text-rose-600 mb-8 group transition">
        <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Katalog
    </a>

    <div class="bg-white rounded-3xl border border-rose-100 overflow-hidden shadow-sm">
        <div class="md:flex">
            {{-- Image Gallery --}}
            <div class="md:w-1/2">
                @if($product->images->count() > 0)
                    <div class="aspect-square bg-gradient-to-br from-rose-50 to-pink-50">
                        <img id="main-image" src="{{ Storage::url($product->primaryImage->image_url ?? $product->images->first()->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    @if($product->images->count() > 1)
                        <div class="flex gap-2.5 p-4 overflow-x-auto">
                            @foreach($product->images as $image)
                                <button onclick="document.getElementById('main-image').src='{{ Storage::url($image->image_url) }}'"
                                        class="flex-shrink-0 w-18 h-18 rounded-xl border-2 {{ $image->is_primary ? 'border-rose-400 ring-2 ring-rose-200' : 'border-rose-100' }} hover:border-rose-300 transition-all duration-200 overflow-hidden">
                                    <img src="{{ Storage::url($image->image_url) }}" class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-square bg-gradient-to-br from-rose-50 to-pink-50 flex items-center justify-center text-7xl text-rose-200">🌸</div>
                @endif
            </div>

            {{-- Product Info --}}
            <div class="md:w-1/2 p-8">
                <span class="inline-block text-xs font-semibold text-rose-500 bg-rose-50 px-3 py-1.5 rounded-full uppercase tracking-wider">{{ $product->category }}</span>
                <h1 class="mt-4 text-3xl font-bold text-slate-800">{{ $product->name }}</h1>
                <p class="mt-3 text-3xl font-bold text-rose-500">{{ $product->formatted_price }}</p>

                <div class="mt-5">
                    @if($product->stock > 0)
                        <span class="inline-flex items-center text-sm font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full">
                            <span class="w-2 h-2 bg-emerald-500 rounded-full mr-1.5"></span>
                            Stok: {{ $product->stock }} tersedia
                        </span>
                    @else
                        <span class="inline-flex items-center text-sm font-medium text-rose-600 bg-rose-50 px-3 py-1.5 rounded-full">
                            Stok Habis
                        </span>
                    @endif
                </div>

                @if($product->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-2">Deskripsi</h3>
                        <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="mt-8 space-y-3">
                    {{-- Quantity Selector --}}
                    @if($product->stock > 0)
                        <div class="flex items-center gap-3">
                            <label class="text-sm font-semibold text-slate-600">Jumlah:</label>
                            <div class="flex items-center bg-rose-50 rounded-xl border border-rose-100 overflow-hidden">
                                <button type="button" onclick="decrementQty()" class="px-3 py-2 text-slate-500 hover:text-rose-600 hover:bg-rose-100 transition font-bold">−</button>
                                <input type="number" id="cart-quantity" value="1" min="1" max="{{ $product->stock }}" readonly
                                       class="w-12 text-center text-sm font-medium text-slate-800 bg-transparent border-none focus:outline-none">
                                <button type="button" onclick="incrementQty()" class="px-3 py-2 text-slate-500 hover:text-rose-600 hover:bg-rose-100 transition font-bold">+</button>
                            </div>
                        </div>
                    @endif

                    {{-- Add to Cart --}}
                    @if($product->stock > 0)
                        <button type="button" id="btn-add-cart"
                                class="block w-full text-center bg-rose-400 text-white py-3.5 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                            🛒 Tambah ke Keranjang
                        </button>
                        <p id="cart-feedback" class="text-sm text-emerald-600 font-medium hidden"></p>
                    @else
                        <div class="block w-full text-center bg-slate-100 text-slate-400 py-3.5 rounded-xl font-semibold cursor-not-allowed">
                            Stok Habis
                        </div>
                    @endif

                    @php
                        $waNumber = config('app.wa_admin_number', env('WA_ADMIN_NUMBER', '6281234567890'));
                        $waText = 'Halo Admin, saya ingin bertanya tentang buket ' . $product->name . ' seharga ' . $product->formatted_price . '...';
                        $waUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText);
                    @endphp
                    <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                       class="block w-full text-center bg-emerald-500 text-white py-3.5 rounded-xl hover:bg-emerald-600 font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                        💬 Tanya via WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function incrementQty() {
    const input = document.getElementById('cart-quantity');
    const max = parseInt(input.max);
    let val = parseInt(input.value);
    if (val < max) {
        input.value = val + 1;
    }
}
function decrementQty() {
    const input = document.getElementById('cart-quantity');
    let val = parseInt(input.value);
    if (val > 1) {
        input.value = val - 1;
    }
}

document.getElementById('btn-add-cart')?.addEventListener('click', function() {
    const qty = parseInt(document.getElementById('cart-quantity').value);
    CartStorage.addItem({
        id:    {{ $product->id }},
        name:  @json($product->name),
        price: {{ $product->price }},
        image: @json($product->primaryImage ? Storage::url($product->primaryImage->image_url) : ''),
        qty:   qty
    });
    const fb = document.getElementById('cart-feedback');
    fb.textContent = 'Ditambahkan ke keranjang! 🛒';
    fb.classList.remove('hidden');
    setTimeout(() => fb.classList.add('hidden'), 2500);
});
</script>
@endsection
