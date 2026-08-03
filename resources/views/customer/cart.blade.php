@extends('layouts.app', ['hideNav' => true])

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
    </style>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto">
        <a href="javascript:history.back()"
            class="inline-flex items-center gap-2 text-sm tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:gap-3 transition-all duration-200 mb-8">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <div class="text-center mb-10">
            <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Belanja</span>
            <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Keranjang Belanja</h2>
            <p id="cart-count-label" class="text-sm text-[#C9A9B4] mt-1"></p>
        </div>

        <div id="cart-empty" class="hidden border border-dashed border-[#E7E4DC] p-16 text-center">
            <p class="text-[#6E8577] text-lg">Keranjang Anda masih kosong.</p>
            <a href="{{ route('customer.catalog') }}"
                class="inline-block mt-4 border border-[#D37897] hover:bg-[#D37897] hover:text-white text-[#33413A] text-sm tracking-wide px-6 py-2.5 transition-colors duration-200">
                Jelajahi Katalog
            </a>
        </div>

        <div id="cart-has-items" class="hidden">
            <div id="cart-items" class="space-y-px mb-8"></div>

            <div class="border border-[#E7E4DC] p-5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <button type="button" onclick="clearCart()"
                            class="text-sm text-[#C9A9B4] hover:text-[#D37897] transition font-medium tracking-wide">
                            Kosongkan Keranjang
                        </button>
                    </div>
                    <div class="flex items-center gap-6">
                        <div class="text-right">
                            <p class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577]">Total</p>
                            <p id="cart-total" class="text-2xl font-medium text-[#33413A]"></p>
                        </div>
                        <button id="checkout-btn" type="button" onclick="goCheckout()"
                            class="border border-[#D37897] hover:bg-[#D37897] hover:text-white text-[#33413A] text-sm tracking-wide px-6 py-3 transition-colors duration-200">
                            Lanjut ke Pemesanan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-options-modal" class="fixed inset-0 z-50 hidden bg-black/40 flex items-center justify-center p-4">
        <div class="bg-white max-w-lg w-full max-h-[90vh] overflow-y-auto p-6 border border-[#E7E4DC]">
            <div class="flex items-center justify-between mb-5">
                <h2 id="edit-modal-title" class="font-display text-lg text-[#33413A]">Kustomisasi Produk</h2>
                <button type="button" onclick="closeEditModal()"
                    class="p-2 text-[#C9A9B4] hover:text-[#D37897] transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="edit-options-form">
                <div id="edit-options-fields"></div>
                <div id="edit-price-summary" class="flex justify-between items-center py-3 px-4 bg-[#F1F0EA] mt-4">
                    <span class="text-sm text-[#33413A] font-medium">Total Harga</span>
                    <span id="edit-total-price" class="text-lg font-medium text-[#D37897]"></span>
                </div>
                <div class="mt-6 flex space-x-3">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 px-4 py-3 border border-[#E7E4DC] text-[#6E8577] hover:border-[#D37897] hover:text-[#33413A] text-sm tracking-wide transition-colors duration-200">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="confirm-modal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black/20">
        <div class="bg-white p-6 max-w-sm mx-4 w-full shadow-lg">
            <p id="confirm-modal-message" class="text-sm text-[#33413A] mb-6"></p>
            <div class="flex justify-end gap-3">
                <button type="button" id="confirm-modal-cancel"
                    class="px-4 py-2 text-sm text-[#6E8577] border border-[#E7E4DC] hover:bg-[#F1F0EA] transition">Batal</button>
                <button type="button" id="confirm-modal-confirm"
                    class="px-4 py-2 text-sm text-white bg-[#D37897] hover:bg-[#c06a85] transition">Ya, Hapus</button>
            </div>
        </div>
    </div>

    <script>
        const CHECK_STOCK_URL = '{{ route('customer.cart.check-stock') }}';
        const LOGIN_URL = '{{ route('login') }}';
        const CHECKOUT_URL = '{{ route('orders.checkout') }}';
        const EDIT_FIELDS_URL = '{{ url('cart/edit-fields') }}/';

        function goCheckout() {
            const btn = document.getElementById('checkout-btn');
            if (btn.classList.contains('pointer-events-none')) return;
            if (!window.isAuthenticated) {
                window.location.href = LOGIN_URL + '?redirect=' + encodeURIComponent(CHECKOUT_URL);
            } else {
                window.location.href = CHECKOUT_URL;
            }
        }

        function isFilePath(val) {
            if (typeof val === 'string') return val.startsWith('temp-uploads/');
            if (val && typeof val === 'object' && val.value && typeof val.value === 'string') return val.value.startsWith(
                'temp-uploads/');
            return false;
        }

        function getDisplayValue(val) {
            if (typeof val === 'string') return val;
            if (Array.isArray(val)) return val.map(function(v) {
                return (v && typeof v === 'object') ? v.value : v;
            }).join(', ');
            if (val && typeof val === 'object' && val.value) return val.value;
            return String(val);
        }

        function getFileUrl(val) {
            if (typeof val === 'string' && val.startsWith('temp-uploads/')) return val;
            if (val && typeof val === 'object' && val.value && typeof val.value === 'string' && val.value.startsWith(
                    'temp-uploads/')) return val.value;
            return '';
        }

        function renderCustomOptions(opts) {
            if (!opts) return '';
            const entries = Object.entries(opts);
            if (entries.length === 0) return '';
            return entries.map(function(kv) {
                var val = kv[1];
                var displayVal = getDisplayValue(val);
                var fileUrl = getFileUrl(val);
                return '<div>' +
                    '<p class="text-[10px] tracking-[0.15em] uppercase text-[#6E8577] leading-tight">' + kv[0] +
                    '</p>' +
                    (fileUrl ?
                        '<a href="/storage/' + fileUrl +
                        '" target="_blank" class="inline-block mt-1"><img src="/storage/' + fileUrl +
                        '" class="w-14 h-14 object-cover border border-[#E7E4DC] hover:opacity-80 transition"></a>' :
                        '<p class="text-xs text-[#33413A]">' + escapeHtml(displayVal) + '</p>') +
                    '</div>';
            }).join('');
        }

        function renderCart() {
            const items = CartStorage.get();
            const emptyEl = document.getElementById('cart-empty');
            const hasEl = document.getElementById('cart-has-items');
            const countLbl = document.getElementById('cart-count-label');
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

            const swatches = ['#F9DEE5', '#D6E5D3', '#E09FB3', '#C7D9C4', '#F0C9D9', '#A9C4A5'];
            container.innerHTML = items.map((item, idx) => {
                const safeKey = encodeURIComponent(item._key);
                const hasOpts = item.custom_options && Object.keys(item.custom_options).length > 0;
                const disableDec = item.qty <= 1;
                return `
        <div class="border border-[#E7E4DC] p-4 flex flex-col sm:flex-row sm:items-center gap-4 transition-colors duration-200" data-cart-key="${safeKey}">
            <div class="w-20 h-20 flex-shrink-0 overflow-hidden" style="background-color: ${swatches[Math.abs(idx) % swatches.length]}">
                ${item.image
                    ? `<img src="${item.image}" alt="${item.name}" class="w-full h-full object-cover">`
                    : '<div class="w-full h-full flex items-center justify-center text-2xl text-[#C9A9B4]">—</div>'}
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-start gap-1${hasOpts ? ' cursor-pointer toggle-options' : ''}">
                    <div class="min-w-0">
                        <h3 class="text-sm font-medium text-[#33413A] truncate">${item.name}</h3>
                        <p class="text-[#D37897] font-medium mt-0.5">${formatRupiah(item.price)}</p>
                    </div>
                    ${hasOpts ? '<svg class="cv w-2.5 h-2.5 mt-1 flex-shrink-0 text-[#C9A9B4] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>' : ''}
                </div>
                ${hasOpts ? `<div class="hidden mt-2 space-y-1 pl-2.5 border-l border-[#E7E4DC]">
                        ${renderCustomOptions(item.custom_options)}
                    </div>` : ''}
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center border border-[#E7E4DC]">
                    <button type="button" data-action="decrement" data-key="${safeKey}" class="px-3 py-1.5 transition font-bold text-lg leading-none ${disableDec ? 'text-[#C9A9B4] opacity-40 cursor-not-allowed' : 'text-[#6E8577] hover:text-[#D37897]'}">−</button>
                    <span class="px-3 py-1.5 text-sm font-medium text-[#33413A] min-w-[2.5rem] text-center">${item.qty}</span>
                    <button type="button" data-action="increment" data-key="${safeKey}" class="px-3 py-1.5 text-[#6E8577] hover:text-[#D37897] transition font-bold text-lg leading-none">+</button>
                </div>
                <p class="font-medium text-[#33413A] min-w-[90px] text-right">${formatRupiah(item.price * item.qty)}</p>
                <button type="button" data-action="edit" data-key="${safeKey}" class="p-1.5 text-[#C9A9B4] hover:text-[#D37897] transition" title="Edit">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </button>
                <button type="button" data-action="remove" data-key="${safeKey}" class="p-1.5 text-[#C9A9B4] hover:text-[#D37897] transition" title="Hapus">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>`;
            }).join('');

            document.getElementById('cart-total').textContent = formatRupiah(CartStorage.total());
            checkStock();
        }

        document.getElementById('cart-items').addEventListener('click', function(e) {
            const btn = e.target.closest('[data-action]');
            if (!btn) return;
            const key = decodeURIComponent(btn.dataset.key);
            const action = btn.dataset.action;

            if (action === 'decrement') {
                const items = CartStorage.get();
                const item = items.find(i => i._key === key);
                if (!item) return;
                item.qty = Math.max(1, item.qty - 1);
                CartStorage.save(items);
                renderCart();
            } else if (action === 'increment') {
                const items = CartStorage.get();
                const item = items.find(i => i._key === key);
                if (!item) return;
                var stockInfo = window.lastStockMap && window.lastStockMap[item.id];
                if (stockInfo && item.qty + 1 > stockInfo.stock) {
                    alert('Maaf, stok tidak mencukupi. Sisa ' + Math.max(0, stockInfo.stock) + ' tersedia.');
                    return;
                }
                item.qty += 1;
                CartStorage.save(items);
                renderCart();
            } else if (action === 'edit') {
                openEditModal(key);
            } else if (action === 'remove') {
                const items = CartStorage.get();
                const item = items.find(i => i._key === key);
                const name = item ? item.name : 'Produk';
                showConfirmModal('Hapus "' + name + '" dari keranjang?', function() {
                    CartStorage.removeItem(key);
                    renderCart();
                    if (window.BuketToast && typeof window.BuketToast.show === 'function') {
                        window.BuketToast.show('success', name + ' dihapus dari keranjang');
                    }
                });
            }
        });

        document.getElementById('cart-items').addEventListener('click', function(e) {
            const toggle = e.target.closest('.toggle-options');
            if (!toggle) return;
            const options = toggle.nextElementSibling;
            if (options) {
                options.classList.toggle('hidden');
                toggle.querySelector('.cv')?.classList.toggle('-rotate-180');
            }
        });

        function clearCart() {
            showConfirmModal('Kosongkan seluruh keranjang?', function() {
                CartStorage.clear();
                renderCart();
                if (window.BuketToast && typeof window.BuketToast.show === 'function') {
                    window.BuketToast.show('info', 'Keranjang dikosongkan');
                }
            });
        }

        function escapeHtml(str) {
            if (typeof str !== 'string') return '';
            return str.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }

        let editKey = null;
        let editItem = null;
        let editBasePrice = 0;

        function getOptionsPriceSum(customOptions) {
            var total = 0;
            Object.values(customOptions || {}).forEach(function(val) {
                if (Array.isArray(val)) {
                    val.forEach(function(v) {
                        if (v && v.price) total += parseInt(v.price) || 0;
                    });
                } else if (val && typeof val === 'object' && val.price) {
                    total += parseInt(val.price) || 0;
                }
            });
            return total;
        }

        function updateEditPrice() {
            if (editItem == null) return;
            var optionsPrice = 0;
            document.querySelectorAll('#edit-options-form select, #edit-options-form input[type="checkbox"][data-price]')
                .forEach(function(el) {
                    if (el.tagName === 'SELECT' && el.value && el.selectedOptions[0]?.dataset?.price) {
                        optionsPrice += parseInt(el.selectedOptions[0].dataset.price) || 0;
                    }
                    if (el.type === 'checkbox' && el.checked && el.dataset.price) {
                        optionsPrice += parseInt(el.dataset.price) || 0;
                    }
                });
            document.getElementById('edit-total-price').textContent = formatRupiah(editBasePrice + optionsPrice);
        }

        function closeEditModal() {
            document.getElementById('edit-options-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            editKey = null;
            editItem = null;
        }

        function getCurrentFieldValue(customOptions, fieldLabel) {
            if (!customOptions || customOptions[fieldLabel] == null) return '';
            var val = customOptions[fieldLabel];
            if (typeof val === 'string') return val;
            if (val && typeof val === 'object' && val.value != null) return val.value;
            return '';
        }

        function getCurrentCheckboxValues(customOptions, fieldLabel) {
            if (!customOptions || customOptions[fieldLabel] == null) return [];
            var val = customOptions[fieldLabel];
            if (Array.isArray(val)) return val.map(function(v) {
                return (v && typeof v === 'object') ? v.value : v;
            });
            if (val && typeof val === 'object' && val.value != null) return [val.value];
            return [];
        }

        function renderEditFields(fields, customOptions) {
            var container = document.getElementById('edit-options-fields');
            if (!fields || fields.length === 0) {
                container.innerHTML =
                    '<p class="text-sm text-[#6E8577] mb-4">Tidak ada opsi kustomisasi untuk produk ini.</p>';
                return;
            }
            container.innerHTML = fields.map(function(field) {
                var requiredAttr = field.is_required ? ' required' : '';
                var currentVal = getCurrentFieldValue(customOptions, field.label);
                var html = '<div class="mb-4">';
                html += '<label class="block text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-2">' +
                    escapeHtml(field.label) + (field.is_required ? ' <span class="text-[#D37897]">*</span>' : '') +
                    '</label>';
                if (field.type === 'text') {
                    html += '<input type="text" name="custom_options[' + field.label + ']"' + requiredAttr +
                        ' value="' + escapeHtml(currentVal) +
                        '" class="block w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent placeholder-[#C9A9B4] outline-none transition-colors" placeholder="' +
                        escapeHtml(field.label) + '">';
                } else if (field.type === 'select') {
                    html += '<select name="custom_options[' + field.label + ']"' + requiredAttr +
                        ' class="block w-full border-0 border-b border-[#E7E4DC] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">';
                    html += '<option value="">Pilih ' + escapeHtml(field.label) + '</option>';
                    (field.options || []).forEach(function(opt) {
                        var optName = (typeof opt === 'object') ? opt.name : opt;
                        var optPrice = (typeof opt === 'object') ? (opt.price || 0) : 0;
                        var selected = currentVal === optName ? ' selected' : '';
                        html += '<option value="' + escapeHtml(optName) + '" data-option-id="' + ((
                                typeof opt === 'object' && opt.id) || '') + '" data-price="' + optPrice +
                            '"' + selected + '>';
                        html += escapeHtml(optName);
                        if (optPrice > 0) html += ' (+Rp ' + optPrice.toString().replace(
                            /\B(?=(\d{3})+(?!\d))/g, '.') + ')';
                        html += '</option>';
                    });
                    html += '</select>';
                } else if (field.type === 'checkbox') {
                    var values = getCurrentCheckboxValues(customOptions, field.label);
                    html += '<div class="space-y-2">';
                    (field.options || []).forEach(function(opt) {
                        var optName = (typeof opt === 'object') ? opt.name : opt;
                        var optPrice = (typeof opt === 'object') ? (opt.price || 0) : 0;
                        var checked = values.indexOf(optName) !== -1 ? ' checked' : '';
                        html +=
                            '<label class="flex items-center space-x-3 p-2.5 border border-[#E7E4DC] cursor-pointer hover:bg-[#F1F0EA] transition">';
                        html += '<input type="checkbox" name="custom_options[' + field.label +
                            '][]" value="' + escapeHtml(optName) + '" data-option-id="' + ((typeof opt ===
                                'object' && opt.id) || '') + '" data-price="' + optPrice + '"' + checked +
                            ' class="text-[#D37897] focus:ring-[#D37897] rounded">';
                        html += '<span class="text-sm text-[#33413A]">' + escapeHtml(optName);
                        if (optPrice > 0) html += ' <span class="text-[#D37897]">(+Rp ' + optPrice
                        .toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ')</span>';
                        html += '</span></label>';
                    });
                    html += '</div>';
                    if (field.is_required) {
                        html +=
                            '<p class="text-xs text-[#D37897] mt-1 required-checkbox-msg hidden">Pilih setidaknya satu opsi.</p>';
                    }
                } else if (field.type === 'file') {
                    var fileVal = currentVal;
                    var hasFile = fileVal && fileVal.startsWith('temp-uploads/');
                    html += '<input type="file" accept="image/jpg,image/jpeg,image/png,image/webp" data-label="' +
                        escapeHtml(field.label) +
                        '" class="field-file-input block w-full text-sm text-[#33413A] file:border file:border-[#E7E4DC] file:px-4 file:py-2 file:text-sm file:tracking-wide file:bg-transparent file:text-[#33413A] hover:file:bg-[#F1F0EA] file:transition-colors file:cursor-pointer file:mr-4 transition-colors"' +
                        requiredAttr + '>';
                    html += '<input type="hidden" name="custom_options[' + field.label + ']" value="' + escapeHtml(
                        fileVal) + '" class="file-uploaded-path">';
                    html += '<div class="file-preview mt-2' + (hasFile ? '' : ' hidden') + '">';
                    html += '<div class="flex items-center gap-2">';
                    html += '<img src="' + (fileVal ? '/storage/' + fileVal : '') +
                        '" class="w-14 h-14 object-cover border border-[#E7E4DC]">';
                    html += '<span class="text-xs text-[#5C6F5E]">Terupload</span>';
                    html += '</div></div>';
                    html +=
                        '<p class="text-xs text-[#C9A9B4] mt-1">Upload gambar referensi (jpg/png/webp, maks 5MB)</p>';
                }
                html += '</div>';
                return html;
            }).join('');
        }

        async function openEditModal(key) {
            var items = CartStorage.get();
            editItem = items.find(function(i) {
                return i._key === key;
            });
            if (!editItem) return;
            editKey = key;
            editBasePrice = editItem.price - getOptionsPriceSum(editItem.custom_options);

            document.getElementById('edit-modal-title').textContent = 'Kustomisasi ' + editItem.name;
            document.getElementById('edit-options-fields').innerHTML =
                '<p class="text-sm text-[#6E8577] text-center py-8">Memuat...</p>';
            document.getElementById('edit-options-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            try {
                var resp = await fetch(EDIT_FIELDS_URL + editItem.id);
                if (!resp.ok) throw new Error('Gagal memuat data');
                var data = await resp.json();
                renderEditFields(data.fields, editItem.custom_options || {});
                updateEditPrice();
            } catch (e) {
                alert('Gagal memuat data produk.');
                closeEditModal();
            }
        }

        function collectEditFormData() {
            var customOptions = {};
            var isValid = true;
            var optionsPrice = 0;

            document.querySelectorAll('#edit-options-form .mb-4').forEach(function(container) {
                var labelEl = container.querySelector('label.block');
                if (!labelEl) return;
                var fieldLabel = labelEl.textContent.replace('*', '').trim();

                var select = container.querySelector('select[name^="custom_options["]');
                if (select) {
                    var opt = select.selectedOptions[0];
                    if (opt && opt.value) {
                        customOptions[fieldLabel] = {
                            value: opt.value,
                            option_id: parseInt(opt.dataset.optionId) || null,
                            price: parseInt(opt.dataset.price) || 0
                        };
                        optionsPrice += customOptions[fieldLabel].price;
                    } else {
                        customOptions[fieldLabel] = '';
                    }
                    return;
                }

                var checkboxes = container.querySelectorAll('input[type="checkbox"][name^="custom_options["]');
                if (checkboxes.length > 0) {
                    var checked = [];
                    checkboxes.forEach(function(cb) {
                        if (cb.checked) {
                            checked.push({
                                value: cb.value,
                                option_id: parseInt(cb.dataset.optionId) || null,
                                price: parseInt(cb.dataset.price) || 0
                            });
                            optionsPrice += parseInt(cb.dataset.price) || 0;
                        }
                    });
                    customOptions[fieldLabel] = checked.length > 0 ? checked : [];
                    return;
                }

                var textInput = container.querySelector('input[type="text"][name^="custom_options["]');
                if (textInput) {
                    customOptions[fieldLabel] = textInput.value;
                    return;
                }

                var fileHidden = container.querySelector('.file-uploaded-path');
                if (fileHidden) {
                    customOptions[fieldLabel] = fileHidden.value;
                    return;
                }
            });

            document.querySelectorAll('#edit-options-form .required-checkbox-msg').forEach(function(el) {
                el.classList.add('hidden');
            });
            document.querySelectorAll('#edit-options-form .file-error-msg').forEach(function(el) {
                el.remove();
            });

            document.querySelectorAll('#edit-options-form .mb-4').forEach(function(container) {
                var msg = container.querySelector('.required-checkbox-msg');
                if (!msg) return;
                var checked = container.querySelectorAll('input[type="checkbox"]:checked');
                if (checked.length === 0) {
                    msg.classList.remove('hidden');
                    isValid = false;
                }
            });

            document.querySelectorAll('#edit-options-form .field-file-input[required]').forEach(function(input) {
                var container = input.closest('.mb-4');
                if (!container) return;
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

            return {
                customOptions: customOptions,
                isValid: isValid,
                optionsPrice: optionsPrice
            };
        }

        document.getElementById('edit-options-form').addEventListener('submit', function(e) {
            e.preventDefault();

            var result = collectEditFormData();
            if (!result.isValid) return;

            var basePrice = editItem.price;
            var hasOptions = Object.keys(result.customOptions).length > 0;
            var newPrice = basePrice;

            if (editItem.custom_options && Object.keys(editItem.custom_options).length > 0) {
                var oldOptsPrice = 0;
                Object.values(editItem.custom_options).forEach(function(val) {
                    if (Array.isArray(val)) {
                        val.forEach(function(v) {
                            if (v && v.price) oldOptsPrice += v.price;
                        });
                    } else if (val && typeof val === 'object' && val.price) {
                        oldOptsPrice += val.price;
                    }
                });
                newPrice = basePrice - oldOptsPrice + result.optionsPrice;
            } else if (hasOptions) {
                newPrice = basePrice + result.optionsPrice;
            }

            CartStorage.removeItem(editKey);
            CartStorage.addItem({
                id: editItem.id,
                name: editItem.name,
                price: newPrice,
                image: editItem.image,
                qty: editItem.qty || 1,
                custom_options: hasOptions ? result.customOptions : null
            });

            closeEditModal();
            renderCart();
        });

        document.getElementById('edit-options-modal').addEventListener('click', function(e) {
            if (e.target === e.currentTarget) closeEditModal();
        });

        document.getElementById('edit-options-modal').addEventListener('change', function(e) {
            if (e.target.matches('select, input[type="checkbox"]')) {
                updateEditPrice();
            }
        });

        const UPLOAD_URL = '{{ route('customer.cart.upload-temp') }}';

        document.getElementById('edit-options-modal').addEventListener('change', function(e) {
            var input = e.target.closest('.field-file-input');
            if (!input) return;
            var file = input.files[0];
            if (!file) return;
            var container = input.closest('.mb-4');
            var hiddenInput = container ? container.querySelector('.file-uploaded-path') : null;
            var preview = container ? container.querySelector('.file-preview') : null;

            (async function() {
                var fd = new FormData();
                fd.append('file', file);
                try {
                    var resp = await fetch(UPLOAD_URL, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: fd
                    });
                    if (!resp.ok) throw new Error('Upload gagal');
                    var data = await resp.json();
                    if (hiddenInput) hiddenInput.value = data.path;
                    if (preview) {
                        preview.querySelector('img').src = '/storage/' + data.path;
                        preview.classList.remove('hidden');
                    }
                    var errMsg = container ? container.querySelector('.file-error-msg') : null;
                    if (errMsg) errMsg.remove();
                } catch (err) {
                    alert('Gagal mengupload gambar. Silakan coba lagi.');
                    input.value = '';
                }
            })();
        });

        let pendingAction = null;

        function showConfirmModal(message, onConfirm) {
            document.getElementById('confirm-modal-message').textContent = message;
            document.getElementById('confirm-modal').classList.remove('hidden');
            pendingAction = onConfirm;
        }

        function hideConfirmModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
            pendingAction = null;
        }

        document.getElementById('confirm-modal-confirm').addEventListener('click', function() {
            if (pendingAction) pendingAction();
            hideConfirmModal();
        });

        document.getElementById('confirm-modal-cancel').addEventListener('click', hideConfirmModal);

        document.getElementById('confirm-modal').addEventListener('click', function(e) {
            if (e.target === e.currentTarget) hideConfirmModal();
        });

        async function checkStock() {
            const items = CartStorage.get();
            if (items.length === 0) return;

            try {
                const resp = await fetch(CHECK_STOCK_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        ids: items.map(i => i.id)
                    })
                });

                if (!resp.ok) return;

                const stockData = await resp.json();
                window.lastStockMap = Object.fromEntries(stockData.map(p => [p.id, p]));
                let hasProblem = false;

                items.forEach(item => {
                    const stock = window.lastStockMap[item.id];
                    const safeKey = encodeURIComponent(item._key);
                    const card = document.querySelector(`[data-cart-key="${safeKey}"]`);
                    if (!card) return;

                    card.classList.remove('bg-red-50', 'border-red-200');
                    card.querySelectorAll('.stock-warning').forEach(el => el.remove());

                    if (!stock) {
                        card.classList.add('bg-red-50', 'border-red-200');
                        card.insertAdjacentHTML('beforeend',
                            '<p class="stock-warning w-full text-sm font-medium text-red-700 mt-1">Produk tidak ditemukan atau telah dihapus.</p>'
                            );
                        hasProblem = true;
                    } else if (!stock.is_active) {
                        card.classList.add('bg-red-50', 'border-red-200');
                        card.insertAdjacentHTML('beforeend',
                            '<p class="stock-warning w-full text-sm font-medium text-red-700 mt-1">Produk ini sudah tidak tersedia.</p>'
                            );
                        hasProblem = true;
                    } else if (item.qty > stock.stock) {
                        card.classList.add('bg-red-50', 'border-red-200');
                        const msg = stock.stock > 0 ?
                            `Stok sisa ${stock.stock}!` :
                            'Stok Habis!';
                        card.insertAdjacentHTML('beforeend',
                            `<p class="stock-warning w-full text-sm font-medium text-red-700 mt-1">${msg}</p>`
                            );
                        hasProblem = true;
                    }
                });

                const checkoutBtn = document.getElementById('checkout-btn');
                if (hasProblem) {
                    checkoutBtn.classList.add('border-[#C9A9B4]', 'text-[#C9A9B4]', 'cursor-not-allowed',
                        'pointer-events-none');
                    checkoutBtn.classList.remove('border-[#D37897]', 'hover:bg-[#D37897]', 'hover:text-white',
                        'text-[#33413A]');
                } else {
                    checkoutBtn.classList.remove('border-[#C9A9B4]', 'text-[#C9A9B4]', 'cursor-not-allowed',
                        'pointer-events-none');
                    checkoutBtn.classList.add('border-[#D37897]', 'hover:bg-[#D37897]', 'hover:text-white',
                        'text-[#33413A]');
                }

            } catch (err) {
                console.error('Stock check failed:', err);
            }
        }

        renderCart();
        setInterval(checkStock, 12000);
    </script>
@endsection
