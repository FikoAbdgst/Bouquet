@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-pink-800">Manajemen Kategori</h1>
    <a href="{{ route('admin.categories.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
        + Tambah Kategori
    </a>
</div>

<div class="bg-white shadow-sm rounded-lg border border-pink-200">
    <div class="p-6">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-pink-200">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-pink-700 uppercase tracking-wider">Pertanyaan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-pink-700 uppercase tracking-wider">Jumlah Produk</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-pink-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-pink-200">
                        @foreach($categories as $category)
                            <tr class="hover:bg-pink-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-pink-900">
                                    {{ $category->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-pink-600">
                                    {{ $category->slug }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-pink-600 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->fields_count > 0 ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $category->fields_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-pink-600 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $category->products_count > 0 ? 'bg-pink-100 text-pink-700' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $category->products_count }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center space-x-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="text-pink-600 hover:text-pink-800 font-medium">Edit</a>
                                    @php $coreCategories = ['Fresh Flower', 'Artificial Flower', 'Thumbelina Bouquet', 'Buket Uang']; @endphp
                                    @if (in_array($category->name, $coreCategories))
                                        <span class="text-gray-400 text-xs ml-2">(inti)</span>
                                    @else
                                        <button type="button"
                                                onclick="openDeleteModal({{ $category->id }}, '{{ $category->name }}', {{ $category->products_count }})"
                                                class="text-red-600 hover:text-red-800 font-medium ml-2">
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
            <div class="text-center py-12">
                <p class="text-4xl mb-4">🏷️</p>
                <p class="text-pink-600 mb-4">Belum ada kategori.</p>
                <a href="{{ route('admin.categories.create') }}" class="inline-block bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 font-medium transition">
                    Tambah Kategori Pertama
                </a>
            </div>
        @endif
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div id="delete-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="closeDeleteModal()"></div>
    <div class="relative bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-bold text-pink-800 mb-2">Hapus Kategori</h3>
        <p class="text-pink-700 mb-4">
            Apakah anda yakin ingin menghapus? Kategori <strong id="modal-category-name"></strong> saat ini terhubung dengan <strong id="modal-product-count"></strong> produk.
        </p>

        <form id="delete-form" method="POST" action="">
            @csrf
            @method('DELETE')

            <div id="delete-checkbox-wrapper" class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="agree" id="delete-agree" value="1"
                           class="h-4 w-4 text-pink-600 focus:ring-pink-500 border-pink-300 rounded"
                           onchange="document.getElementById('delete-submit').disabled = !this.checked;">
                    <span class="ml-2 text-sm text-pink-700">Saya menyetujui penghapusan kategori ini</span>
                </label>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeDeleteModal()"
                        class="px-4 py-2 border border-pink-300 rounded-lg text-pink-700 hover:bg-pink-50 font-medium transition">
                    Batal
                </button>
                <button type="submit" id="delete-submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition shadow-sm disabled:opacity-50 disabled:cursor-not-allowed">
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
