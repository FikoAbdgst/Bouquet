@extends('layouts.admin')

@section('content')
<div class="max-w-3xl">
    <h1 class="text-2xl font-medium text-[#33413A] mb-6">Edit Produk: {{ $product->name }}</h1>

    <div class="border border-[#EFD3DE] p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Nama Produk *</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                           class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]">
                    @error('name') <p class="text-xs text-[#D37897] mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="description" class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border border-[#EFD3DE] px-3 py-2 text-sm bg-transparent outline-none focus:border-[#D37897] transition-colors placeholder-[#C9A9B4]">{{ old('description', $product->description) }}</textarea>
                    @error('description') <p class="text-xs text-[#D37897] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Harga (Rp) *</label>
                        <input type="number" name="price" id="price" value="{{ old('price', $product->price) }}" required min="0"
                               class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]">
                        @error('price') <p class="text-xs text-[#D37897] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Stok *</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" required min="0"
                               class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]">
                        @error('stock') <p class="text-xs text-[#D37897] mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="category_id" class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Kategori *</label>
                    <select name="category_id" id="category_id" required
                            class="w-full border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors">
                        <option value="" class="text-[#C9A9B4]">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-xs text-[#D37897] mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="h-4 w-4 text-[#D37897] focus:ring-[#D37897] border-[#EFD3DE] rounded">
                    <label for="is_active" class="text-sm text-[#33413A]">Aktif</label>
                </div>

                @if($product->images->count() > 0)
                    <div>
                        <label class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Foto Saat Ini</label>
                        <div class="grid grid-cols-5 gap-2">
                            @foreach($product->images as $index => $image)
                                <div class="relative">
                                    <img src="{{ Storage::url($image->image_url) }}" class="h-20 w-20 object-cover border border-[#EFD3DE]">
                                    @if($image->is_primary)
                                        <span class="absolute top-0 left-0 bg-[#D37897] text-white text-[10px] tracking-wide px-1">Utama</span>
                                    @endif
                                    <div class="mt-1 space-y-0.5">
                                        <label class="flex items-center text-xs text-[#33413A]">
                                            <input type="radio" name="primary_image_index" value="{{ $index }}" {{ $image->is_primary ? 'checked' : '' }}
                                                   class="mr-1.5 h-3 w-3 text-[#D37897] focus:ring-[#D37897] border-[#EFD3DE]">
                                            Utama
                                        </label>
                                        <label class="flex items-center text-xs text-[#D37897]">
                                            <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"
                                                   class="mr-1.5 h-3 w-3 text-[#D37897] focus:ring-[#D37897] border-[#EFD3DE] rounded">
                                            Hapus
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Tambah Foto Baru (maks 5 file)</label>
                    <div class="border border-[#EFD3DE] px-3 py-2">
                        <input type="file" name="images[]" id="images" multiple accept="image/jpg,image/jpeg,image/png,image/webp"
                               class="w-full text-sm text-[#6E8577] file:border-0 file:bg-[#D37897] file:text-white file:text-xs file:tracking-wide file:px-3 file:py-1 file:mr-3 file:cursor-pointer">
                    </div>
                    @error('images.*') <p class="text-xs text-[#D37897] mt-1">{{ $message }}</p> @enderror
                </div>

                <div id="image-preview" class="grid grid-cols-5 gap-2 hidden"></div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#33413A] text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                    Batal
                </a>
                <button type="submit" class="border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
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
            div.innerHTML = `<img src="${event.target.result}" class="h-20 w-20 object-cover border border-[#EFD3DE]">`;
            preview.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
});
</script>
@endpush
@endsection
