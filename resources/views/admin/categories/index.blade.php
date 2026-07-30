@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-medium text-[#33413A]">Manajemen Kategori</h1>
    <a href="{{ route('admin.categories.create') }}" class="border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
        + Tambah Kategori
    </a>
</div>

<div class="border border-[#EFD3DE]">
    @if($categories->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#EFD3DE]">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Slug</th>
                        <th class="px-6 py-3 text-center text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Pertanyaan</th>
                        <th class="px-6 py-3 text-center text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Jumlah Produk</th>
                        <th class="px-6 py-3 text-right text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFD3DE]">
                    @foreach($categories as $category)
                        <tr class="hover:bg-[#F9DEE5]/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#33413A]">
                                {{ $category->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#6E8577]">
                                {{ $category->slug }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="inline-block px-2.5 py-0.5 text-xs tracking-wide border border-[#EFD3DE] text-[#6E8577]">
                                    {{ $category->fields_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                <span class="inline-block px-2.5 py-0.5 text-xs tracking-wide border border-[#EFD3DE] text-[#6E8577]">
                                    {{ $category->products_count }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-3">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-xs tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:pb-1 transition-all">Edit</a>
                                @php $coreCategories = ['Fresh Flower', 'Artificial Flower', 'Thumbelina Bouquet', 'Buket Uang']; @endphp
                                @if (in_array($category->name, $coreCategories))
                                    <span class="text-xs text-[#C9A9B4] tracking-wide">(inti)</span>
                                @else
                                    <button type="button"
                                            onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}', {{ $category->products_count }})"
                                            class="text-xs tracking-wide text-red-600 border-b border-red-400 pb-0.5 hover:pb-1 transition-all">
                                        Hapus
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-16 px-6">
            <p class="text-[#33413A] mb-4">Belum ada kategori.</p>
            <a href="{{ route('admin.categories.create') }}" class="inline-block border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                Tambah Kategori Pertama
            </a>
        </div>
    @endif
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="fixed inset-0 bg-black/40" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white border border-[#EFD3DE] p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-[#33413A] mb-2">Hapus Kategori</h3>
        <p class="text-sm text-[#33413A] mb-4">
            Apakah anda yakin ingin menghapus? Kategori <strong id="modal-category-name" class="font-medium"></strong> saat ini terhubung dengan <strong id="modal-product-count" class="font-medium"></strong> produk.
        </p>

        <form id="delete-form" method="POST" action="">
            @csrf
            @method('DELETE')

            <div id="delete-checkbox-wrapper" class="mb-4">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="agree" id="delete-agree" value="1"
                           class="h-4 w-4 text-[#D37897] focus:ring-[#D37897] border-[#EFD3DE] rounded"
                           onchange="document.getElementById('delete-submit').disabled = !this.checked;">
                    <span class="text-sm text-[#33413A]">Saya menyetujui penghapusan kategori ini</span>
                </label>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#D37897] text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                    Batal
                </button>
                <button type="submit" id="delete-submit"
                        class="border border-red-500 text-red-600 hover:bg-red-50 text-sm tracking-wide transition-colors duration-200 px-4 py-2 disabled:opacity-40 disabled:cursor-not-allowed">
                    Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openDeleteModal(id, name, count) {
        document.getElementById('delete-modal').classList.remove('hidden');
        document.getElementById('delete-form').action = '{{ url('admin/categories') }}/' + id;
        document.getElementById('modal-category-name').textContent = name;
        document.getElementById('modal-product-count').textContent = count + ' produk';

        const wrapper = document.getElementById('delete-checkbox-wrapper');
        const agree = document.getElementById('delete-agree');
        const submit = document.getElementById('delete-submit');

        if (count > 0) {
            wrapper.classList.remove('hidden');
            agree.checked = false;
            submit.disabled = true;
        } else {
            wrapper.classList.add('hidden');
            submit.disabled = false;
        }
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
    }
</script>
@endpush
@endsection
