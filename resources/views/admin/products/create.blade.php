@extends('layouts.admin')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-pink-800 mb-6">Tambah Produk Baru</h1>

    <div class="bg-white shadow-sm rounded-lg border border-pink-200 p-6">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-pink-700">Nama Produk *</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-pink-700">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">{{ old('description') }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-pink-700">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" value="{{ old('price') }}" required min="0"
                               class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-pink-700">Stok *</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" required min="0"
                               class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                        @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-pink-700">Kategori *</label>
                    <select name="category_id" id="category_id" required
                            class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-pink-700">Foto Produk (maks 5 file, jpg/png/webp, max 5MB)</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    <p class="mt-1 text-xs text-pink-500">Gambar pertama akan dijadikan foto utama secara default.</p>
                    @error('images') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    @error('images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="image-preview" class="grid grid-cols-5 gap-2 mt-2 hidden"></div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-pink-300 rounded-lg text-pink-700 hover:bg-pink-50 font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('images').addEventListener('change', function(e) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    preview.classList.remove('hidden');

    Array.from(e.target.files).forEach(function(file, index) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `
                <img src="${event.target.result}" class="h-20 w-20 object-cover rounded border border-pink-200">
                <span class="absolute top-0 left-0 bg-pink-600 text-white text-xs px-1 rounded">${index === 0 ? 'Utama' : index + 1}</span>
            `;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection
