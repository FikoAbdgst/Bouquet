@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-pink-800 mb-6">Tambah Kategori Baru</h1>

    <div class="bg-white shadow-sm rounded-lg border border-pink-200 p-6">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-pink-700">Nama Kategori *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm"
                           placeholder="Contoh: Ulang Tahun, Mawar, dll">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Form Questions Builder --}}
            <div class="mt-8">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-pink-800">Pertanyaan Form Pesanan</h2>
                    <button type="button" onclick="addField()" class="px-3 py-1.5 bg-pink-100 text-pink-700 rounded-lg hover:bg-pink-200 font-medium text-sm transition">
                        + Tambah Pertanyaan
                    </button>
                </div>
                <p class="text-sm text-pink-500 mb-4">Pertanyaan berikut akan muncul saat pelanggan memesan produk dari kategori ini.</p>
                <div id="fields-container" class="space-y-4"></div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 border border-pink-300 rounded-lg text-pink-700 hover:bg-pink-50 font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let fieldIndex = 0;

const defaultFields = [
    { label: 'Ukuran', type: 'select', options: 'Small, Medium, Large', is_required: true },
    { label: 'Kartu Ucapan', type: 'text', options: '', is_required: false },
    { label: 'Catatan Tambahan', type: 'text', options: '', is_required: false },
];

document.addEventListener('DOMContentLoaded', function () {
    defaultFields.forEach(function (f) { addField(f); });
});

function addField(data) {
    const container = document.getElementById('fields-container');
    const index = fieldIndex++;
    const div = document.createElement('div');
    div.className = 'field-item bg-pink-50/50 border border-pink-200 rounded-lg p-4 relative';
    div.dataset.index = index;

    const id = data ? data.id : '';
    const label = data ? data.label : '';
    const type = data ? data.type : 'text';
    const options = data ? (data.options || '') : '';
    const isRequired = data ? data.is_required : false;

    div.innerHTML = `
        <input type="hidden" name="fields[${index}][id]" value="${id}">
        <div class="flex items-center justify-between mb-3">
            <span class="text-sm font-semibold text-pink-700">Pertanyaan #<span class="field-number">${index + 1}</span></span>
            <button type="button" onclick="removeField(this)" class="text-red-500 hover:text-red-700 text-sm font-medium">Hapus</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-pink-600 mb-1">Label Pertanyaan</label>
                <input type="text" name="fields[${index}][label]" value="${label}" required
                       class="w-full border border-pink-300 rounded-lg py-1.5 px-2.5 text-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"
                       placeholder="Contoh: Warna Bunga">
            </div>
            <div>
                <label class="block text-xs font-medium text-pink-600 mb-1">Tipe Jawaban</label>
                <select name="fields[${index}][type]" onchange="toggleOptions(this)" required
                        class="w-full border border-pink-300 rounded-lg py-1.5 px-2.5 text-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500">
                    <option value="text" ${type === 'text' ? 'selected' : ''}>Text (isian singkat)</option>
                    <option value="select" ${type === 'select' ? 'selected' : ''}>Select (pilih satu)</option>
                    <option value="checkbox" ${type === 'checkbox' ? 'selected' : ''}>Checkbox (pilih banyak)</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-pink-600 mb-1">Wajib Diisi</label>
                <label class="inline-flex items-center mt-1.5 cursor-pointer">
                    <input type="checkbox" name="fields[${index}][is_required]" value="1" ${isRequired ? 'checked' : ''}
                           class="rounded border-pink-300 text-pink-600 focus:ring-pink-500">
                    <span class="ml-2 text-sm text-pink-700">Ya, wajib</span>
                </label>
            </div>
        </div>
        <div class="options-field mt-3 ${type === 'text' ? 'hidden' : ''}">
            <label class="block text-xs font-medium text-pink-600 mb-1">Pilihan Jawaban</label>
            <input type="text" name="fields[${index}][options]" value="${options}"
                   class="w-full border border-pink-300 rounded-lg py-1.5 px-2.5 text-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"
                   placeholder="Pisahkan dengan koma, contoh: Merah, Putih, Pink">
            <p class="text-xs text-pink-400 mt-1">Pisahkan setiap pilihan dengan koma.</p>
        </div>
    `;

    container.appendChild(div);
    updateFieldNumbers();
}

function removeField(btn) {
    btn.closest('.field-item').remove();
    updateFieldNumbers();
}

function toggleOptions(select) {
    const container = select.closest('.field-item');
    const optionsField = container.querySelector('.options-field');
    optionsField.classList.toggle('hidden', select.value === 'text');
}

function updateFieldNumbers() {
    document.querySelectorAll('.field-number').forEach((el, i) => el.textContent = i + 1);
}
</script>
@endsection
