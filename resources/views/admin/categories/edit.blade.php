@extends('layouts.admin')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-medium text-[#33413A] mb-6">Edit Kategori: {{ $category->name }}</h1>

    <div class="border border-[#EFD3DE] p-6">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Nama Kategori *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                           class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]">
                    @error('name') <p class="text-xs text-[#D37897] mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Form Questions Builder --}}
            <div class="mt-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-sm font-medium text-[#33413A]">Pertanyaan Form Pesanan</h2>
                        <p class="text-xs text-[#6E8577] mt-0.5">Pertanyaan berikut akan muncul saat pelanggan memesan produk dari kategori ini.</p>
                    </div>
                    <button type="button" onclick="addField()" class="border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#D37897] text-xs tracking-wide transition-colors duration-200 px-3 py-1.5">
                        + Tambah Pertanyaan
                    </button>
                </div>
                <div id="fields-container" class="space-y-4">
                    @foreach($category->fields as $field)
                        <script>window._existingFields = window._existingFields || []; window._existingFields.push(@json($field));</script>
                    @endforeach
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#D37897] text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                    Batal
                </a>
                <button type="submit" class="border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                    Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let fieldIndex = 0;

function addField(data) {
    const container = document.getElementById('fields-container');
    const index = fieldIndex++;
    const div = document.createElement('div');
    div.className = 'field-item border border-[#EFD3DE] p-4 relative';
    div.dataset.index = index;

    const id = data ? data.id : '';
    const label = data ? data.label : '';
    const type = data ? data.type : 'text';
    const options = data ? (data.options || '') : '';
    const isRequired = data ? data.is_required : false;

    div.innerHTML = `
        <input type="hidden" name="fields[${index}][id]" value="${id}">
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs tracking-wide text-[#6E8577]">Pertanyaan #<span class="field-number">${index + 1}</span></span>
            <button type="button" onclick="removeField(this)" class="text-xs tracking-wide text-red-600 border-b border-red-400 pb-0.5 hover:pb-1 transition-all">Hapus</button>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-[#6E8577] mb-1.5">Label Pertanyaan</label>
                <input type="text" name="fields[${index}][label]" value="${label}" required
                       class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-1.5 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]"
                       placeholder="Contoh: Warna Bunga">
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-[#6E8577] mb-1.5">Tipe Jawaban</label>
                <select name="fields[${index}][type]" onchange="toggleOptions(this)" required
                        class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-1.5 text-sm bg-transparent outline-none transition-colors">
                    <option value="text" ${type === 'text' ? 'selected' : ''}>Text (isian singkat)</option>
                    <option value="select" ${type === 'select' ? 'selected' : ''}>Select (pilih satu)</option>
                    <option value="checkbox" ${type === 'checkbox' ? 'selected' : ''}>Checkbox (pilih banyak)</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] tracking-[0.2em] uppercase text-[#6E8577] mb-1.5">Wajib Diisi</label>
                <label class="inline-flex items-center gap-2 mt-2.5 cursor-pointer">
                    <input type="checkbox" name="fields[${index}][is_required]" value="1" ${isRequired ? 'checked' : ''}
                           class="h-4 w-4 text-[#D37897] focus:ring-[#D37897] border-[#EFD3DE] rounded">
                    <span class="text-sm text-[#33413A]">Ya, wajib</span>
                </label>
            </div>
        </div>
        <div class="options-field mt-4 ${type === 'text' ? 'hidden' : ''}">
            <label class="block text-[10px] tracking-[0.2em] uppercase text-[#6E8577] mb-1.5">Pilihan Jawaban</label>
            <input type="text" name="fields[${index}][options]" value="${options}"
                   class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-1.5 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]"
                   placeholder="Pisahkan dengan koma, contoh: Merah, Putih, Pink">
            <p class="text-xs text-[#C9A9B4] mt-1">Pisahkan setiap pilihan dengan koma.</p>
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

document.addEventListener('DOMContentLoaded', function() {
    if (window._existingFields) {
        window._existingFields.forEach(function(f) { addField(f); });
    }
});
</script>
@endsection
