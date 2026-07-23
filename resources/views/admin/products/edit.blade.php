@extends('layouts.admin')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-bold text-pink-800 mb-6">Edit Produk: {{ $product->name }}</h1>

    <div class="bg-white shadow-sm rounded-lg border border-pink-200 p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-pink-700">Nama Produk *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                           class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-pink-700">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="price" class="block text-sm font-medium text-pink-700">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0"
                               class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-pink-700">Stok *</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                               class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                        @error('stock') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-pink-700">Kategori *</label>
                    <select name="category" id="category" required
                            class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                        <option value="">Pilih Kategori</option>
                        @foreach(['Ulang Tahun', 'Wisuda', 'Pernikahan', 'Duka Cita', 'Valentine', 'Lainnya'] as $cat)
                            <option value="{{ $cat }}" {{ old('category', $product->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                    @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-pink-300 rounded">
                    <label for="is_active" class="ml-2 block text-sm text-pink-700">Aktif</label>
                </div>

                @if($product->images->count() > 0)
                    <div>
                        <label class="block text-sm font-medium text-pink-700 mb-2">Foto Saat Ini</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $index => $image)
                                <div class="relative">
                                    <img src="{{ Storage::url($image->image_url) }}" class="h-20 w-20 object-cover rounded border border-pink-200">
                                    @if($image->is_primary)
                                        <span class="absolute top-0 left-0 bg-pink-600 text-white text-xs px-1 rounded">Utama</span>
                                    @endif
                                    <div class="mt-1">
                                        <label class="flex items-center text-xs text-pink-600">
                                            <input type="radio" name="primary_image_index" value="{{ $index }}" {{ $image->is_primary ? 'checked' : '' }} class="mr-1">
                                            Utama
                                        </label>
                                        <label class="flex items-center text-xs text-red-600">
                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}" class="mr-1">
                                            Hapus
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-pink-700">Tambah Foto Baru (maks 5 file)</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/jpg,image/jpeg,image/png,image/webp"
                           class="mt-1 block w-full border border-pink-300 rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                    @error('images.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div id="image-preview" class="grid grid-cols-5 gap-2 mt-2 hidden"></div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <a href="{{ route('admin.products.index') }}" class="px-4 py-2 border border-pink-300 rounded-lg text-pink-700 hover:bg-pink-50 font-medium transition">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                    Perbarui Produk
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

    Array.from(e.target.files).forEach(function(file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const div = document.createElement('div');
            div.className = 'relative';
            div.innerHTML = `<img src="${event.target.result}" class="h-20 w-20 object-cover rounded border border-pink-200">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection
