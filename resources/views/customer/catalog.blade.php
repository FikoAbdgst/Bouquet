@extends('layouts.app')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap"
        rel="stylesheet">
    <style>
        .font-display {
            font-family: "Cormorant Garamond", serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }

        .font-body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        .skeleton {
            background: linear-gradient(90deg, #F1F0EA 25%, #FFFDFC 50%, #F1F0EA 75%);
            background-size: 200% 100%;
            animation: shimmer 1.2s ease-in-out infinite;
        }
    </style>
@endpush

@section('content')
    @php
        $catFields = $categories->keyBy('id')->map(function ($c) {
            return $c->fields->map(function ($field) {
                return [
                    'id' => $field->id,
                    'label' => $field->label,
                    'type' => $field->type,
                    'is_required' => $field->is_required,
                    'options' => $field->options,
                    'field_options' => $field->fieldOptions
                        ->map(function ($o) {
                            return ['id' => $o->id, 'name' => $o->name, 'price' => $o->price];
                        })
                        ->toArray(),
                ];
            });
        });
    @endphp
    <script>
        window.categoryFields = @json($catFields);
    </script>

    <div class="text-center mb-10">
        <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Katalog</span>
        <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Temukan Buket Impianmu</h2>
    </div>

    <div class="border border-[#E7E4DC] p-5 mb-8">
        <form action="{{ route('customer.catalog') }}" method="GET" class="flex flex-wrap gap-4 items-end"
            id="filter-form">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Cari Produk</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama buket..."
                    class="w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
            </div>

            <div class="min-w-[150px]">
                <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Kategori</label>
                <select name="category"
                    class="w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="min-w-[120px]">
                <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Harga Min</label>
                <input type="text" name="min_price" inputmode="numeric"
                    value="{{ request('min_price') ? number_format((int) request('min_price'), 0, ',', '.') : '' }}"
                    placeholder="Rp 0"
                    class="w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
            </div>

            <div class="min-w-[120px]">
                <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Harga Max</label>
                <input type="text" name="max_price" inputmode="numeric"
                    value="{{ request('max_price') ? number_format((int) request('max_price'), 0, ',', '.') : '' }}"
                    placeholder="Rp ..."
                    class="w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors">
            </div>
        </form>
    </div>

    <div id="product-grid">
        @include('customer.catalog-products', ['products' => $products])
    </div>

    {{-- Quick Add Modal --}}
    <div id="quick-add-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 border border-[#E7E4DC]">
            <div class="flex items-center justify-between mb-5">
                <h2 id="quick-add-title" class="font-display text-lg text-[#33413A">Tambah ke Keranjang</h2>
                <button type="button" onclick="closeQuickAdd()" class="p-2 text-[#C9A9B4] hover:text-[#D37897] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="quick-add-form" onsubmit="return submitQuickAdd(event)">
                <div class="mb-4">
                    <label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">Jumlah</label>
                    <div class="flex items-center border border-[#E7E4DC] w-fit">
                        <button type="button" onclick="quickAddDecQty()"
                            class="px-3 py-2 text-[#6E8577] hover:text-[#D37897] transition font-bold text-lg leading-none">−</button>
                        <input type="number" id="quick-add-qty" value="1" min="1" max="1" readonly
                            class="w-12 text-center text-sm font-medium text-[#33413A] bg-transparent border-none focus:outline-none">
                        <button type="button" onclick="quickAddIncQty()"
                            class="px-3 py-2 text-[#6E8577] hover:text-[#D37897] transition font-bold text-lg leading-none">+</button>
                    </div>
                    <p id="quick-add-stock-info" class="text-xs text-[#5C6F5E] mt-1"></p>
                </div>
                <div id="quick-add-fields"></div>

                <div id="quick-add-price-summary"
                    class="flex justify-between items-center py-3 px-4 bg-[#F1F0EA] mt-4 hidden">
                    <span class="text-sm text-[#33413A] font-medium">Total Harga</span>
                    <span id="quick-add-total-price" class="text-lg font-medium text-[#D37897]"></span>
                </div>

                <div class="mt-6 flex space-x-3">
                    <button type="button" onclick="closeQuickAdd()"
                        class="flex-1 px-4 py-3 border border-[#E7E4DC] text-[#6E8577] hover:border-[#D37897] hover:text-[#33413A] text-sm tracking-wide transition-colors duration-200">
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
        const UPLOAD_URL = '{{ route('customer.cart.upload-temp') }}';

        async function uploadFile(file) {
            const fd = new FormData();
            fd.append('file', file);
            const resp = await fetch(UPLOAD_URL, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: fd
            });
            if (!resp.ok) throw new Error('Upload gagal');
            return (await resp.json()).path;
        }

        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('filter-form');
            var grid = document.getElementById('product-grid');
            if (!form || !grid) return;

            function showSkeleton() {
                var html =
                    '<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-8 gap-y-12">';
                for (var i = 0; i < 8; i++) {
                    html +=
                        '<div><div class="aspect-[3/4] skeleton"></div><div class="mt-4 space-y-3"><div class="h-5 skeleton w-3/4"></div><div class="h-4 skeleton w-1/3"></div><div class="h-[42px] skeleton w-full mt-4"></div></div></div>';
                }
                html += '</div>';
                grid.innerHTML = html;
            }

            var debounceTimer;

            function updateFilter() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    form.querySelectorAll('[name="min_price"], [name="max_price"]').forEach(function(el) {
                        el.dataset.raw = el.value.replace(/[^\d]/g, '');
                    });
                    var fd = new FormData(form);
                    fd.set('min_price', form.min_price.dataset.raw || '');
                    fd.set('max_price', form.max_price.dataset.raw || '');
                    var params = new URLSearchParams(fd);
                    var url = form.action + '?' + params.toString();
                    history.replaceState(null, '', url);
                    showSkeleton();
                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) {
                            return r.text();
                        })
                        .then(function(html) {
                            grid.innerHTML = html;
                        });
                }, 400);
            }

            function formatPrice(el) {
                var raw = el.value.replace(/[^\d]/g, '');
                el.value = raw ? new Intl.NumberFormat('id-ID').format(parseInt(raw)) : '';
                updateFilter();
            }

            form.querySelector('[name="search"]').addEventListener('input', updateFilter);
            form.querySelector('[name="category"]').addEventListener('change', updateFilter);
            form.querySelectorAll('[name="min_price"], [name="max_price"]').forEach(function(el) {
                el.addEventListener('input', function() {
                    formatPrice(this);
                });
            });

            grid.addEventListener('click', function(e) {
                var link = e.target.closest('.pagination a');
                if (link) {
                    e.preventDefault();
                    var url = link.href;
                    history.replaceState(null, '', url);
                    showSkeleton();
                    fetch(url, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(function(r) {
                            return r.text();
                        })
                        .then(function(html) {
                            grid.innerHTML = html;
                        });
                }
            });
        });

        function escapeHtml(str) {
            if (typeof str !== 'string') return '';
            return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        var quickAddProduct = null;

        function openQuickAdd(btn) {
            var productId = parseInt(btn.getAttribute('data-pid'));
            var productStock = parseInt(btn.getAttribute('data-stock'));
            var inCart = CartStorage.get()
                .filter(function(i) {
                    return i.id === productId;
                })
                .reduce(function(s, i) {
                    return s + i.qty;
                }, 0);
            var remaining = Math.max(0, productStock - inCart);

            quickAddProduct = {
                id: productId,
                name: btn.getAttribute('data-name'),
                price: btn.getAttribute('data-price'),
                image: btn.getAttribute('data-image'),
                catId: btn.getAttribute('data-catid') || null,
                stock: productStock,
            };

            document.getElementById('quick-add-title').textContent = 'Kustomisasi ' + quickAddProduct.name;

            var qtyInput = document.getElementById('quick-add-qty');
            var stockInfo = document.getElementById('quick-add-stock-info');
            var submitBtn = document.querySelector('#quick-add-form button[type="submit"]');

            if (remaining <= 0) {
                qtyInput.value = 0;
                qtyInput.max = 0;
                stockInfo.textContent = 'Stok maksimal di keranjang';
                stockInfo.className = 'text-xs text-[#C9A9B4] mt-1';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
            } else {
                qtyInput.value = 1;
                qtyInput.max = remaining;
                stockInfo.textContent = 'Sisa ' + remaining + ' tersedia';
                stockInfo.className = 'text-xs text-[#5C6F5E] mt-1';
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed', 'pointer-events-none');
                }
            }

            var container = document.getElementById('quick-add-fields');
            container.innerHTML = '';

            function getFieldOptions(field) {
                if (field.field_options && field.field_options.length > 0) {
                    return field.field_options;
                }
                return (field.options || '').split(',').map(function(o) {
                    return {
                        name: o.trim(),
                        price: 0
                    };
                }).filter(function(o) {
                    return o.name;
                });
            }

            var fields = quickAddProduct.catId ? (window.categoryFields[quickAddProduct.catId] || []) : [];
            if (fields.length === 0) {
                container.innerHTML =
                    '<p class="text-sm text-[#6E8577]">Tidak ada opsi kustomisasi. Produk akan langsung ditambahkan.</p>';
                document.getElementById('quick-add-price-summary').classList.add('hidden');
            } else {
                document.getElementById('quick-add-price-summary').classList.remove('hidden');
                fields.forEach(function(field) {
                    var reqAttr = field.is_required ? 'required' : '';
                    var reqStar = field.is_required ? ' <span class="text-[#D37897]">*</span>' : '';
                    var html = '<div class="mb-4">';
                    html += '<label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">' +
                        escapeHtml(field.label) + reqStar + '</label>';

                    if (field.type === 'text') {
                        html += '<input type="text" name="custom_options[' + field.label + ']" ' + reqAttr +
                            ' class="block w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors" placeholder="' +
                            escapeHtml(field.label) + '">';
                    } else if (field.type === 'select') {
                        var opts = getFieldOptions(field);
                        html += '<select name="custom_options[' + field.label + ']" ' + reqAttr +
                            ' class="quick-add-select block w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">';
                        html += '<option value="">Pilih ' + escapeHtml(field.label) + '</option>';
                        opts.forEach(function(o) {
                            html += '<option value="' + escapeHtml(o.name || o) + '" data-option-id="' + (o
                                .id || '') + '" data-price="' + (o.price || 0) + '">';
                            html += escapeHtml(o.name || o);
                            if (o.price > 0) html += ' (+Rp ' + o.price.toString().replace(
                                /\B(?=(\d{3})+(?!\d))/g, '.') + ')';
                            html += '</option>';
                        });
                        html += '</select>';
                    } else if (field.type === 'checkbox') {
                        var opts = getFieldOptions(field);
                        html += '<div class="space-y-2">';
                        opts.forEach(function(o) {
                            html +=
                                '<label class="flex items-center space-x-3 p-2.5 border border-[#E7E4DC] cursor-pointer hover:bg-[#F1F0EA] transition">';
                            html += '<input type="checkbox" name="custom_options[' + field.label +
                                '][]" value="' + escapeHtml(o.name || o) + '" data-option-id="' + (o.id ||
                                    '') + '" data-price="' + (o.price || 0) +
                                '" class="quick-add-checkbox text-[#D37897] focus:ring-[#D37897] rounded">';
                            html += '<span class="text-sm text-[#33413A]">' + escapeHtml(o.name || o);
                            if (o.price > 0) html += ' <span class="text-[#D37897]">(+Rp ' + o.price
                                .toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ')</span>';
                            html += '</span></label>';
                        });
                        html += '</div>';
                    } else if (field.type === 'file') {
                        html +=
                            '<input type="file" accept="image/jpg,image/jpeg,image/png,image/webp" data-label="' +
                            escapeHtml(field.label) + '" data-required="' + field.is_required +
                            '" class="field-file-input block w-full text-sm text-[#33413A] file:border file:border-[#E7E4DC] file:px-4 file:py-2 file:text-sm file:tracking-wide file:bg-transparent file:text-[#33413A] hover:file:bg-[#F1F0EA] file:transition-colors file:cursor-pointer file:mr-4 transition-colors"' +
                            (field.is_required ? ' required' : '') + '>';
                        html += '<input type="hidden" name="custom_options[' + field.label +
                            ']" value="" class="file-uploaded-path">';
                        html +=
                            '<div class="file-preview mt-2 hidden"><div class="flex items-center gap-2"><img src="" class="w-14 h-14 object-cover border border-[#E7E4DC]"><span class="text-xs text-[#5C6F5E]">Terupload</span></div></div>';
                        html +=
                            '<p class="text-xs text-[#C9A9B4] mt-1">Upload gambar referensi (jpg/png/webp, maks 5MB)</p>';
                    }

                    html += '</div>';
                    container.insertAdjacentHTML('beforeend', html);
                });
            }

            document.getElementById('quick-add-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
            updateQuickAddPrice();
        }

        function getQuickAddOptionsPrice() {
            var total = 0;
            document.querySelectorAll('#quick-add-fields .quick-add-select').forEach(function(sel) {
                if (sel.value && sel.selectedOptions[0]?.dataset?.price) {
                    total += parseInt(sel.selectedOptions[0].dataset.price) || 0;
                }
            });
            document.querySelectorAll('#quick-add-fields .quick-add-checkbox:checked').forEach(function(cb) {
                total += parseInt(cb.dataset.price) || 0;
            });
            return total;
        }

        function updateQuickAddPrice() {
            if (!quickAddProduct) return;
            var base = parseInt(quickAddProduct.price);
            var optsPrice = getQuickAddOptionsPrice();
            var total = base + optsPrice;
            document.getElementById('quick-add-total-price').textContent = formatRupiah(total);
        }

        document.getElementById('quick-add-fields').addEventListener('change', function(e) {
            if (e.target.matches('.quick-add-select, .quick-add-checkbox')) {
                updateQuickAddPrice();
            }
        });

        function quickAddIncQty() {
            var input = document.getElementById('quick-add-qty');
            var max = parseInt(input.max);
            var val = parseInt(input.value);
            if (val < max) input.value = val + 1;
        }

        function quickAddDecQty() {
            var input = document.getElementById('quick-add-qty');
            var val = parseInt(input.value);
            if (val > 1) input.value = val - 1;
        }

        document.getElementById('quick-add-fields').addEventListener('change', async function(e) {
            const input = e.target.closest('.field-file-input');
            if (!input) return;
            const file = input.files[0];
            if (!file) return;
            const container = input.closest('.mb-4');
            const hiddenInput = container.querySelector('.file-uploaded-path');
            const preview = container.querySelector('.file-preview');
            try {
                const path = await uploadFile(file);
                hiddenInput.value = path;
                preview.querySelector('img').src = '/storage/' + path;
                preview.classList.remove('hidden');
            } catch (e) {
                alert('Gagal mengupload gambar. Silakan coba lagi.');
                input.value = '';
            }
        });

        function buildStructuredCustomOptions() {
            var customOptions = {};
            document.querySelectorAll('#quick-add-fields .mb-4').forEach(function(container) {
                var labelEl = container.querySelector('label.block');
                if (!labelEl) return;
                var fieldLabel = labelEl.textContent.replace('*', '').trim();

                var select = container.querySelector('select.quick-add-select');
                if (select) {
                    var opt = select.selectedOptions[0];
                    if (opt && opt.value) {
                        customOptions[fieldLabel] = {
                            value: opt.value,
                            option_id: parseInt(opt.dataset.optionId) || null,
                            price: parseInt(opt.dataset.price) || 0
                        };
                    } else {
                        customOptions[fieldLabel] = '';
                    }
                    return;
                }

                var checkboxes = container.querySelectorAll('input.quick-add-checkbox');
                if (checkboxes.length > 0) {
                    var checked = [];
                    checkboxes.forEach(function(cb) {
                        if (cb.checked) {
                            checked.push({
                                value: cb.value,
                                option_id: parseInt(cb.dataset.optionId) || null,
                                price: parseInt(cb.dataset.price) || 0
                            });
                        }
                    });
                    customOptions[fieldLabel] = checked.length > 0 ? checked : [];
                    return;
                }

                var textInput = container.querySelector('input[type="text"]');
                if (textInput) {
                    customOptions[fieldLabel] = textInput.value;
                    return;
                }

                var fileHidden = container.querySelector('.file-uploaded-path');
                if (fileHidden) {
                    customOptions[fieldLabel] = fileHidden.value;
                }
            });
            return customOptions;
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

            var qty = parseInt(document.getElementById('quick-add-qty').value) || 1;
            var inCart = CartStorage.get()
                .filter(function(i) {
                    return i.id === quickAddProduct.id;
                })
                .reduce(function(s, i) {
                    return s + i.qty;
                }, 0);
            if (inCart + qty > quickAddProduct.stock) {
                alert('Maaf, stok tidak mencukupi. Sisa ' + Math.max(0, quickAddProduct.stock - inCart) + ' tersedia.');
                return false;
            }

            var isValid = true;
            document.querySelectorAll('#quick-add-fields .field-file-input[required]').forEach(function(input) {
                var container = input.closest('.mb-4');
                var path = container.querySelector('.file-uploaded-path').value;
                if (!path) {
                    isValid = false;
                    var msg = container.querySelector('.file-error-msg');
                    if (!msg) {
                        msg = document.createElement('p');
                        msg.className = 'file-error-msg text-xs text-[#D37897] mt-1';
                        container.appendChild(msg);
                    }
                    msg.textContent = 'Harap upload gambar.';
                }
            });
            if (!isValid) return false;

            var customOptions = buildStructuredCustomOptions();
            var optsPrice = getQuickAddOptionsPrice();
            var unitPrice = parseInt(quickAddProduct.price) + optsPrice;

            CartStorage.addItem({
                id: parseInt(quickAddProduct.id),
                name: quickAddProduct.name,
                price: unitPrice,
                image: quickAddProduct.image,
                qty: qty,
                custom_options: Object.keys(customOptions).length > 0 ? customOptions : null
            });

            closeQuickAdd();

            var fb = document.getElementById('cart-feedback');
            if (!fb) {
                fb = document.createElement('p');
                fb.id = 'cart-feedback';
                fb.className =
                    'fixed bottom-6 right-6 z-50 bg-[#33413A] text-white text-sm px-5 py-3 border border-[#E7E4DC] shadow-lg transition-opacity duration-300';
                document.body.appendChild(fb);
            }
            fb.textContent = '✓ ' + quickAddProduct.name + ' ditambahkan ke keranjang!';
            fb.classList.remove('hidden', 'opacity-0');
            fb.classList.add('opacity-100');
            setTimeout(function() {
                fb.classList.add('opacity-0');
                setTimeout(function() {
                    fb.classList.add('hidden');
                }, 300);
            }, 2500);

            return false;
        }
    </script>
@endsection
