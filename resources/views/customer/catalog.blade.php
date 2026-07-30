@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap" rel="stylesheet">
    <style>
        .font-display { font-family: "Cormorant Garamond", serif; font-optical-sizing: auto; font-weight: 500; font-style: normal; }
        .font-body { font-family: 'Inter', system-ui, sans-serif; }
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
        .skeleton { background: linear-gradient(90deg, #F9DEE5 25%, #FFFDFC 50%, #F9DEE5 75%); background-size: 200% 100%; animation: shimmer 1.2s ease-in-out infinite; }
    </style>
@endpush

@section('content')
<script>window.categoryFields = @json($categories->keyBy('id')->map(function($c) { return $c->fields; }));</script>

<div class="text-center mb-10">
    <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Katalog</span>
    <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Temukan Buket Impianmu</h2>
</div>

<div class="border border-[#EFD3DE] p-5 mb-8">
    <form action="{{ route('customer.catalog') }}" method="GET" class="flex flex-wrap gap-4 items-end" id="filter-form">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama buket..."
                   class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
        </div>

        <div class="min-w-[150px]">
            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Kategori</label>
            <select name="category" class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-[120px]">
            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Harga Min</label>
            <input type="text" name="min_price" inputmode="numeric"
                   value="{{ request('min_price') ? number_format((int) request('min_price'), 0, ',', '.') : '' }}"
                   placeholder="Rp 0"
                   class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
        </div>

        <div class="min-w-[120px]">
            <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Harga Max</label>
            <input type="text" name="max_price" inputmode="numeric"
                   value="{{ request('max_price') ? number_format((int) request('max_price'), 0, ',', '.') : '' }}"
                   placeholder="Rp ..."
                   class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
        </div>
    </form>
</div>

<div id="product-grid">
    @include('customer.catalog-products', ['products' => $products])
</div>

{{-- Quick Add Modal --}}
<div id="quick-add-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4">
    <div class="bg-white max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 border border-[#EFD3DE]">
        <div class="flex items-center justify-between mb-5">
            <h2 id="quick-add-title" class="font-display text-lg text-[#33413A">Tambah ke Keranjang</h2>
            <button type="button" onclick="closeQuickAdd()" class="p-2 text-[#C9A9B4] hover:text-[#D37897] transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form id="quick-add-form" onsubmit="return submitQuickAdd(event)">
            <div id="quick-add-fields"></div>

            <div class="mt-6 flex space-x-3">
                <button type="button" onclick="closeQuickAdd()"
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
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('filter-form');
    var grid = document.getElementById('product-grid');
    if (!form || !grid) return;

    function showSkeleton() {
        var html = '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">';
        for (var i = 0; i < 8; i++) {
            html += '<div><div class="aspect-[3/4] skeleton"></div><div class="mt-4 space-y-3"><div class="h-5 skeleton w-3/4"></div><div class="h-4 skeleton w-1/3"></div><div class="h-[42px] skeleton w-full mt-4"></div></div></div>';
        }
        html += '</div>';
        grid.innerHTML = html;
    }

    var debounceTimer;

    function updateFilter() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            form.querySelectorAll('[name="min_price"], [name="max_price"]').forEach(function (el) {
                el.dataset.raw = el.value.replace(/[^\d]/g, '');
            });
            var fd = new FormData(form);
            fd.set('min_price', form.min_price.dataset.raw || '');
            fd.set('max_price', form.max_price.dataset.raw || '');
            var params = new URLSearchParams(fd);
            var url = form.action + '?' + params.toString();
            history.replaceState(null, '', url);
            showSkeleton();
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.text(); })
                .then(function (html) { grid.innerHTML = html; });
        }, 400);
    }

    function formatPrice(el) {
        var raw = el.value.replace(/[^\d]/g, '');
        el.value = raw ? new Intl.NumberFormat('id-ID').format(parseInt(raw)) : '';
        updateFilter();
    }

    form.querySelector('[name="search"]').addEventListener('input', updateFilter);
    form.querySelector('[name="category"]').addEventListener('change', updateFilter);
    form.querySelectorAll('[name="min_price"], [name="max_price"]').forEach(function (el) {
        el.addEventListener('input', function () { formatPrice(this); });
    });

    grid.addEventListener('click', function (e) {
        var link = e.target.closest('.pagination a');
        if (link) {
            e.preventDefault();
            var url = link.href;
            history.replaceState(null, '', url);
            showSkeleton();
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.text(); })
                .then(function (html) { grid.innerHTML = html; });
        }
    });
});

var quickAddProduct = null;

function openQuickAdd(btn) {
    quickAddProduct = {
        id:    btn.getAttribute('data-pid'),
        name:  btn.getAttribute('data-name'),
        price: btn.getAttribute('data-price'),
        image: btn.getAttribute('data-image'),
        catId: btn.getAttribute('data-catid') || null,
    };

    document.getElementById('quick-add-title').textContent = 'Kustomisasi ' + quickAddProduct.name;

    var container = document.getElementById('quick-add-fields');
    container.innerHTML = '';

    var fields = quickAddProduct.catId ? (window.categoryFields[quickAddProduct.catId] || []) : [];
    if (fields.length === 0) {
        container.innerHTML = '<p class="text-sm text-[#6E8577]">Tidak ada opsi kustomisasi. Produk akan langsung ditambahkan.</p>';
    } else {
        fields.forEach(function(field) {
            var reqAttr = field.is_required ? 'required' : '';
            var reqStar = field.is_required ? ' <span class="text-[#D37897]">*</span>' : '';
            var html = '<div class="mb-4">';
            html += '<label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">' + field.label + reqStar + '</label>';

            if (field.type === 'text') {
                html += '<input type="text" name="custom_options[' + field.label + ']" ' + reqAttr + ' class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors" placeholder="' + field.label + '">';
            } else if (field.type === 'select') {
                var opts = (field.options || '').split(',').map(function(o) { return o.trim(); }).filter(Boolean);
                html += '<select name="custom_options[' + field.label + ']" ' + reqAttr + ' class="block w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">';
                html += '<option value="">Pilih ' + field.label + '</option>';
                opts.forEach(function(o) { html += '<option value="' + o + '">' + o + '</option>'; });
                html += '</select>';
            } else if (field.type === 'checkbox') {
                var opts = (field.options || '').split(',').map(function(o) { return o.trim(); }).filter(Boolean);
                html += '<div class="space-y-2">';
                opts.forEach(function(o) {
                    html += '<label class="flex items-center space-x-3 p-2.5 border border-[#EFD3DE] cursor-pointer hover:bg-[#F9DEE5] transition">';
                    html += '<input type="checkbox" name="custom_options[' + field.label + '][]" value="' + o + '" class="text-[#D37897] focus:ring-[#D37897] rounded">';
                    html += '<span class="text-sm text-[#33413A]">' + o + '</span>';
                    html += '</label>';
                });
                html += '</div>';
            }

            html += '</div>';
            container.insertAdjacentHTML('beforeend', html);
        });
    }

    document.getElementById('quick-add-modal').classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeQuickAdd() {
    document.getElementById('quick-add-modal').classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    quickAddProduct = null;
}

document.getElementById('quick-add-modal')?.addEventListener('click', function(e) {
    if (e.target === this) closeQuickAdd();
});

function submitQuickAdd(event) {
    event.preventDefault();
    if (!quickAddProduct) return false;

    var form = document.getElementById('quick-add-form');
    var formData = new FormData(form);
    var customOptions = {};

    formData.forEach(function(value, key) {
        var match = key.match(/^custom_options\[(.+?)\]$/);
        if (match) {
            var label = match[1];
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

    CartStorage.addItem({
        id:    parseInt(quickAddProduct.id),
        name:  quickAddProduct.name,
        price: parseInt(quickAddProduct.price),
        image: quickAddProduct.image,
        qty:   1,
        custom_options: Object.keys(customOptions).length > 0 ? customOptions : null
    });

    closeQuickAdd();

    var fb = document.getElementById('cart-feedback');
    if (!fb) {
        fb = document.createElement('p');
        fb.id = 'cart-feedback';
        fb.className = 'fixed bottom-6 right-6 z-50 bg-[#33413A] text-white text-sm px-5 py-3 border border-[#EFD3DE] shadow-lg transition-opacity duration-300';
        document.body.appendChild(fb);
    }
    fb.textContent = '✓ ' + quickAddProduct.name + ' ditambahkan ke keranjang!';
    fb.classList.remove('hidden', 'opacity-0');
    fb.classList.add('opacity-100');
    setTimeout(function() {
        fb.classList.add('opacity-0');
        setTimeout(function() { fb.classList.add('hidden'); }, 300);
    }, 2500);

    return false;
}
</script>
@endsection