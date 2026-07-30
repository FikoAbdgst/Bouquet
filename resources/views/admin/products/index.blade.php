@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-medium text-[#33413A]">Manajemen Produk</h1>
    <a href="{{ route('admin.products.create') }}" class="border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
        + Tambah Produk
    </a>
</div>

<div class="border border-[#EFD3DE]">
    @if($products->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-[#EFD3DE]">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Gambar</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#EFD3DE]">
                    @foreach($products as $product)
                        <tr class="hover:bg-[#F9DEE5]/50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->primaryImage)
                                    <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover border border-[#EFD3DE]">
                                @else
                                    <div class="h-12 w-12 border border-[#EFD3DE] flex items-center justify-center text-[#C9A9B4] text-xs">—</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#33413A]">
                                {{ $product->name }}
                                @if($bestSellerIds->contains($product->id))
                                    <span class="ml-1.5 inline-block px-1.5 py-0.5 text-[10px] tracking-wider border border-amber-400 text-amber-700">Best Seller</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-[#33413A]">
                                @if($product->productCategory)
                                    <span class="text-xs tracking-wide text-[#6E8577]">{{ $product->productCategory->name }}</span>
                                @else
                                    <span class="text-xs tracking-wide text-[#6E8577]">{{ $product->category }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#33413A]">{{ $product->formatted_price }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-[#33413A]">{{ $product->stock }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($product->is_active)
                                    <span class="inline-block px-2.5 py-0.5 text-xs tracking-wide border border-green-600 text-green-700">Aktif</span>
                                @else
                                    <span class="inline-block px-2.5 py-0.5 text-xs tracking-wide border border-red-400 text-red-500">Nonaktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-3">
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-xs tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:pb-1 transition-all">Edit</a>
                                @if($product->is_active)
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Nonaktifkan produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs tracking-wide text-red-600 border-b border-red-400 pb-0.5 hover:pb-1 transition-all">Nonaktifkan</button>
                                    </form>
                                @else
                                    <form action="{{ route('admin.products.restore', $product) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs tracking-wide text-green-600 border-b border-green-400 pb-0.5 hover:pb-1 transition-all">Aktifkan</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-[#EFD3DE]">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-16 px-6">
            <p class="text-[#33413A] mb-4">Belum ada produk.</p>
            <a href="{{ route('admin.products.create') }}" class="inline-block border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                Tambah Produk Pertama
            </a>
        </div>
    @endif
</div>
@endsection
