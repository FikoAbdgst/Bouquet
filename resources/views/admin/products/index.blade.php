@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-pink-800">Manajemen Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
        + Tambah Produk
    </a>
</div>

<div class="bg-white shadow-sm rounded-lg border border-pink-200">
    <div class="p-6">
        @if($products->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-pink-200">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Gambar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-pink-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-pink-200">
                        @foreach($products as $product)
                            <tr class="hover:bg-pink-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->primaryImage)
                                        <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover rounded border border-pink-100">
                                    @else
                                        <div class="h-12 w-12 bg-pink-100 rounded flex items-center justify-center text-pink-400 text-sm">No</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-pink-900">
                                    {{ $product->name }}
                                    @if($bestSellerIds->contains($product->id))
                                        <span class="ml-1.5 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-orange-100 text-orange-700">🔥 Best Seller</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-pink-600">{{ $product->category }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-pink-600">{{ $product->formatted_price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-pink-600">{{ $product->stock }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($product->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="text-pink-600 hover:text-pink-800 font-medium">Edit</a>
                                    @if($product->is_active)
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-medium">Nonaktifkan</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.products.restore', $product) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-800 font-medium">Aktifkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $products->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-4xl mb-4">📦</p>
                <p class="text-pink-600 mb-4">Belum ada produk.</p>
                <a href="{{ route('admin.products.create') }}" class="inline-block bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 font-medium transition">
                    Tambah Produk Pertama
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
