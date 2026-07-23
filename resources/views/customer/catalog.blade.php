@extends('layouts.app')

@section('content')
<div class="text-center mb-8">
    <h1 class="text-3xl font-bold text-pink-800">🌸 Katalog Buket Bunga</h1>
    <p class="mt-2 text-pink-600">Temukan buket bunga terindah untuk momen spesial Anda</p>
</div>

<div class="bg-white shadow-sm rounded-lg border border-pink-200 p-4 mb-6">
    <form action="{{ route('customer.catalog') }}" method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-pink-700 mb-1">Cari Produk</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama buket..."
                   class="w-full border border-pink-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
        </div>

        <div class="min-w-[150px]">
            <label class="block text-sm font-medium text-pink-700 mb-1">Kategori</label>
            <select name="category" class="w-full border border-pink-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </div>

        <div class="min-w-[120px]">
            <label class="block text-sm font-medium text-pink-700 mb-1">Harga Min</label>
            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Rp 0" min="0"
                   class="w-full border border-pink-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
        </div>

        <div class="min-w-[120px]">
            <label class="block text-sm font-medium text-pink-700 mb-1">Harga Max</label>
            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Rp ..." min="0"
                   class="w-full border border-pink-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
        </div>

        <button type="submit" class="bg-pink-600 text-white px-4 py-2 rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
            Filter
        </button>
    </form>
</div>

@if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
            <a href="{{ route('customer.catalog.show', $product) }}" class="bg-white rounded-lg shadow-sm border border-pink-200 overflow-hidden hover:shadow-md transition-shadow">
                <div class="aspect-square bg-pink-50">
                    @if($product->primaryImage)
                        <img src="{{ Storage::url($product->primaryImage->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-4xl text-pink-300">🌸</div>
                    @endif
                </div>
                <div class="p-4">
                    <span class="text-xs text-pink-500 bg-pink-50 px-2 py-1 rounded-full">{{ $product->category }}</span>
                    <h3 class="mt-2 text-lg font-semibold text-pink-800 line-clamp-1">{{ $product->name }}</h3>
                    <p class="mt-1 text-pink-600 font-bold">{{ $product->formatted_price }}</p>
                    @if($product->stock <= 5 && $product->stock > 0)
                        <p class="mt-1 text-xs text-orange-600">Stok tersisa {{ $product->stock }}</p>
                    @endif
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->withQueryString()->links() }}
    </div>
@else
    <div class="bg-white shadow-sm rounded-lg border border-pink-200 p-12 text-center">
        <p class="text-4xl mb-4">🌺</p>
        <p class="text-pink-600">Belum ada produk yang tersedia.</p>
    </div>
@endif
@endsection
