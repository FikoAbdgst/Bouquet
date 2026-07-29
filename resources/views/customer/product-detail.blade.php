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
                <div class="flex flex-wrap gap-1.5">
                    @if($product->productCategory)
                        <span class="inline-block text-xs font-semibold text-rose-500 bg-rose-50 px-3 py-1.5 rounded-full uppercase tracking-wider">{{ $product->productCategory->name }}</span>
                    @else
                        <span class="inline-block text-xs font-semibold text-rose-500 bg-rose-50 px-3 py-1.5 rounded-full uppercase tracking-wider">{{ $product->category }}</span>
                    @endif
                </div>
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

{{-- Custom Options Modal --}}
<div id="custom-options-modal" class="fixed inset-0 z-50 hidden bg-black/40 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-lg font-bold text-slate-800">Kustomisasi {{ $product->name }}</h2>
            <button type="button" onclick="closeModal()" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-xl transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="custom-options-form" onsubmit="return addToCartWithOptions(event)">
            @if($product->productCategory && $product->productCategory->fields->count() > 0)
                @foreach($product->productCategory->fields as $field)
                    @php $requiredAttr = $field->is_required ? 'required' : ''; @endphp
                    <div class="mb-4">
                        <label class="block text-sm font-semibold text-slate-600 mb-1.5">
                            {{ $field->label }}
                            @if($field->is_required) <span class="text-red-500">*</span> @endif
                        </label>

                        @if($field->type === 'text')
                            <input type="text" name="custom_options[{{ $field->label }}]" {{ $requiredAttr }}
                                   class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm"
                                   placeholder="{{ $field->label }}">

                        @elseif($field->type === 'select')
                            <select name="custom_options[{{ $field->label }}]" {{ $requiredAttr }}
                                    class="block w-full border border-rose-200 rounded-xl py-2.5 px-3.5 focus:outline-none focus:ring-2 focus:ring-rose-300 focus:border-rose-300 text-sm bg-white">
                                <option value="">Pilih {{ $field->label }}</option>
                                @foreach(explode(',', $field->options ?? '') as $option)
                                    @php $option = trim($option); @endphp
                                    @if($option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endif
                                @endforeach
                            </select>

                        @elseif($field->type === 'checkbox')
                            <div class="space-y-2">
                                @foreach(explode(',', $field->options ?? '') as $option)
                                    @php $option = trim($option); @endphp
                                    @if($option)
                                        <label class="flex items-center space-x-3 p-2.5 border border-rose-100 rounded-xl cursor-pointer hover:bg-rose-50/50 transition">
                                            <input type="checkbox" name="custom_options[{{ $field->label }}][]" value="{{ $option }}"
                                                   class="text-rose-500 focus:ring-rose-400 rounded">
                                            <span class="text-sm text-slate-700">{{ $option }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                            @if($field->is_required)
                                <p class="text-xs text-rose-500 mt-1 required-checkbox-msg hidden">Pilih setidaknya satu opsi.</p>
                            @endif
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-sm text-slate-400 mb-4">Tidak ada opsi kustomisasi untuk produk ini.</p>
            @endif

            <div class="mt-6 flex space-x-3">
                <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-3 border border-rose-200 rounded-xl text-slate-600 hover:bg-rose-50 font-semibold transition text-sm">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-rose-400 text-white rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                    Tambah ke Keranjang
                </button>
            </div>
        </form>
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

function openModal() {
    document.getElementById('custom-options-modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModal() {
    document.getElementById('custom-options-modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
}

document.getElementById('btn-add-cart')?.addEventListener('click', function(e) {
    e.preventDefault();
    openModal();
});

document.getElementById('custom-options-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

function addToCartWithOptions(event) {
    event.preventDefault();

    const form = document.getElementById('custom-options-form');
    const formData = new FormData(form);
    const customOptions = {};
    let isValid = true;

    formData.forEach(function(value, key) {
        const match = key.match(/^custom_options\[(.+?)\]$/);
        if (match) {
            const label = match[1];
            if (customOptions[label]) {
                if (!Array.isArray(customOptions[label])) {
                    customOptions[label] = [customOptions[label]];
                }
                customOptions[label].push(value);
            } else {
                customOptions[label] = value;
            }
        }
    });

    {{-- Validate required checkboxes --}}
    document.querySelectorAll('.required-checkbox-msg').forEach(function(el) { el.classList.add('hidden'); });
    @if($product->productCategory)
        @foreach($product->productCategory->fields as $field)
            @if($field->type === 'checkbox' && $field->is_required)
                (function() {
                    const checks = document.querySelectorAll('input[name="custom_options[{{ $field->label }}][]"]:checked');
                    if (checks.length === 0) {
                        const msg = document.querySelector('#custom-options-form .required-checkbox-msg');
                        if (msg) msg.classList.remove('hidden');
                        isValid = false;
                    }
                })();
            @endif
        @endforeach
    @endif

    if (!isValid) return false;

    const qty = parseInt(document.getElementById('cart-quantity').value);
    CartStorage.addItem({
        id:    {{ $product->id }},
        name:  @json($product->name),
        price: {{ $product->price }},
        image: @json($product->primaryImage ? Storage::url($product->primaryImage->image_url) : ''),
        qty:   qty,
        custom_options: Object.keys(customOptions).length > 0 ? customOptions : null
    });

    closeModal();

    const fb = document.getElementById('cart-feedback');
    fb.textContent = 'Ditambahkan ke keranjang! 🛒';
    fb.classList.remove('hidden');
    setTimeout(function() { fb.classList.add('hidden'); }, 2500);

    return false;
}
</script>
@endsection
