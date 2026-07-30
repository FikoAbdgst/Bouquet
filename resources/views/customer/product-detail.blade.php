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
    <a href="{{ route('customer.catalog') }}"
       class="inline-flex items-center gap-2 text-sm tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:gap-3 transition-all duration-200 mb-8">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Katalog
    </a>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
        {{-- Image Gallery --}}
        <div>
            @if($product->images->count() > 0)
                @php $swatchColors = ['#DCD6C9', '#8E9A7C', '#B9C3C6', '#D9C2B4', '#A8AC98', '#C9B4B4']; @endphp
                <div class="aspect-[3/4] overflow-hidden" style="background-color: {{ $swatchColors[crc32($product->id) % 6] }}">
                    <img id="main-image" src="{{ Storage::url($product->primaryImage->image_url ?? $product->images->first()->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                </div>
                @if($product->images->count() > 1)
                    <div class="flex gap-2.5 mt-4 overflow-x-auto">
                        @foreach($product->images as $image)
                            <button onclick="document.getElementById('main-image').src='{{ Storage::url($image->image_url) }}'"
                                    class="flex-shrink-0 w-16 h-16 border overflow-hidden transition-colors duration-200 {{ $image->is_primary ? 'border-[#D37897]' : 'border-[#EFD3DE] hover:border-[#D37897]' }}">
                                <img src="{{ Storage::url($image->image_url) }}" class="w-full h-full object-cover" alt="">
                            </button>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="aspect-[3/4] bg-[#F9DEE5] flex items-center justify-center text-6xl text-[#C9A9B4]">—</div>
            @endif
        </div>

        {{-- Product Info --}}
        <div>
            @if($product->productCategory)
                <span class="inline-block text-[10px] tracking-[0.2em] uppercase border border-[#EFD3DE] text-[#D37897] px-3 py-1.5">
                    {{ $product->productCategory->name }}
                </span>
            @endif

            <h1 class="font-display text-3xl sm:text-4xl font-medium text-[#33413A] mt-4 leading-tight">{{ $product->name }}</h1>

            <p class="mt-3 text-2xl text-[#D37897] font-medium">{{ $product->formatted_price }}</p>

            <div class="mt-4">
                @if($product->stock > 0)
                    <span class="text-sm text-[#5C6F5E]">Stok: {{ $product->stock }} tersedia</span>
                @else
                    <span class="text-sm text-[#C9A9B4]">Stok Habis</span>
                @endif
            </div>

            @if($product->description)
                <div class="mt-6 border-t border-[#EFD3DE] pt-6">
                    <p class="text-[#5C6F5E] leading-relaxed text-sm">{{ $product->description }}</p>
                </div>
            @endif

            <div class="mt-8 space-y-3">
                @if($product->stock > 0)
                    <div class="flex items-center gap-3">
                        <label class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577]">Jumlah:</label>
                        <div class="flex items-center border border-[#EFD3DE]">
                            <button type="button" onclick="decrementQty()" class="px-3 py-2 text-[#6E8577] hover:text-[#D37897] transition font-bold text-lg leading-none">−</button>
                            <input type="number" id="cart-quantity" value="1" min="1" max="{{ $product->stock }}" readonly
                                   class="w-12 text-center text-sm font-medium text-[#33413A] bg-transparent border-none focus:outline-none">
                            <button type="button" onclick="incrementQty()" class="px-3 py-2 text-[#6E8577] hover:text-[#D37897] transition font-bold text-lg leading-none">+</button>
                        </div>
                    </div>

                    <button type="button" id="btn-add-cart"
                            class="block w-full text-center border border-[#D37897] hover:bg-[#457359] hover:text-white text-[#33413A] text-sm tracking-wide py-3 transition-colors duration-200">
                        Tambah ke Keranjang
                    </button>
                    <p id="cart-feedback" class="text-sm text-[#5C6F5E] font-medium hidden"></p>
                @else
                    <div class="block w-full text-center border border-[#EFD3DE] text-[#C9A9B4] text-sm tracking-wide py-3 cursor-not-allowed">
                        Stok Habis
                    </div>
                @endif

                @php
                    $waNumber = config('app.wa_admin_number', env('WA_ADMIN_NUMBER', '6281234567890'));
                    $waText = 'Halo Admin, saya ingin bertanya tentang buket ' . $product->name . ' seharga ' . $product->formatted_price . '...';
                    $waUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText);
                @endphp
                <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                   class="block w-full text-center bg-[#25D366] hover:bg-[#1FBE5C] text-white text-sm tracking-wide py-3 transition-colors duration-200">
                    Tanya via WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Custom Options Modal --}}
<div id="custom-options-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4">
    <div class="bg-white max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 border border-[#EFD3DE]">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-display text-lg text-[#33413A]">Kustomisasi {{ $product->name }}</h2>
            <button type="button" onclick="closeModal()" class="p-2 text-[#C9A9B4] hover:text-[#D37897] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="custom-options-form" onsubmit="return addToCartWithOptions(event)">
            @if($product->productCategory && $product->productCategory->fields->count() > 0)
                @foreach($product->productCategory->fields as $field)
                    @php $requiredAttr = $field->is_required ? 'required' : ''; @endphp
                    <div class="mb-4">
                        <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">
                            {{ $field->label }}
                            @if($field->is_required) <span class="text-[#D37897]">*</span> @endif
                        </label>

                        @if($field->type === 'text')
                            <input type="text" name="custom_options[{{ $field->label }}]" {{ $requiredAttr }}
                                   class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors"
                                   placeholder="{{ $field->label }}">

                        @elseif($field->type === 'select')
                            <select name="custom_options[{{ $field->label }}]" {{ $requiredAttr }}
                                    class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">
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
                                        <label class="flex items-center space-x-3 p-2.5 border border-[#EFD3DE] cursor-pointer hover:bg-[#F9DEE5] transition">
                                            <input type="checkbox" name="custom_options[{{ $field->label }}][]" value="{{ $option }}"
                                                   class="text-[#D37897] focus:ring-[#D37897] rounded">
                                            <span class="text-sm text-[#33413A]">{{ $option }}</span>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                            @if($field->is_required)
                                <p class="text-xs text-[#D37897] mt-1 required-checkbox-msg hidden">Pilih setidaknya satu opsi.</p>
                            @endif
                        @endif
                    </div>
                @endforeach
            @else
                <p class="text-sm text-[#6E8577] mb-4">Tidak ada opsi kustomisasi untuk produk ini.</p>
            @endif

            <div class="mt-6 flex space-x-3">
                <button type="button" onclick="closeModal()"
                        class="flex-1 px-4 py-3 border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#33413A] text-sm tracking-wide transition-colors duration-200">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200">
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
    fb.textContent = 'Ditambahkan ke keranjang!';
    fb.classList.remove('hidden');
    setTimeout(function() { fb.classList.add('hidden'); }, 2500);

    return false;
}
</script>
@endsection
