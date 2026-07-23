@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <a href="{{ route('customer.catalog') }}" class="inline-flex items-center text-pink-600 hover:text-pink-800 mb-6">
        ← Kembali ke Katalog
    </a>

    <div class="bg-white shadow-sm rounded-lg border border-pink-200 overflow-hidden">
        <div class="md:flex">
            <div class="md:w-1/2">
                @if($product->images->count() > 0)
                    <div class="aspect-square bg-pink-50">
                        <img id="main-image" src="{{ Storage::url($product->primaryImage->image_url ?? $product->first()->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    </div>
                    @if($product->images->count() > 1)
                        <div class="flex gap-2 p-4 overflow-x-auto">
                            @foreach($product->images as $image)
                                <button onclick="document.getElementById('main-image').src='{{ Storage::url($image->image_url) }}'"
                                        class="flex-shrink-0 w-16 h-16 rounded border-2 {{ $image->is_primary ? 'border-pink-500' : 'border-pink-200' }} hover:border-pink-400">
                                    <img src="{{ Storage::url($image->image_url) }}" class="w-full h-full object-cover rounded">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-square bg-pink-50 flex items-center justify-center text-6xl text-pink-300">🌸</div>
                @endif
            </div>

            <div class="md:w-1/2 p-6">
                <span class="text-sm text-pink-500 bg-pink-50 px-3 py-1 rounded-full">{{ $product->category }}</span>
                <h1 class="mt-3 text-2xl font-bold text-pink-800">{{ $product->name }}</h1>
                <p class="mt-2 text-2xl font-bold text-pink-600">{{ $product->formatted_price }}</p>

                <div class="mt-4">
                    @if($product->stock > 0)
                        <span class="text-sm text-green-600 bg-green-50 px-3 py-1 rounded-full">Stok: {{ $product->stock }} tersedia</span>
                    @else
                        <span class="text-sm text-red-600 bg-red-50 px-3 py-1 rounded-full">Stok Habis</span>
                    @endif
                </div>

                @if($product->description)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-pink-700">Deskripsi</h3>
                        <p class="mt-1 text-pink-600 whitespace-pre-line">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="mt-8 space-y-3">
                    @auth('web')
                        @if(Auth::user()->isCustomer())
                            @if($product->stock > 0)
                                <a href="{{ route('customer.orders.create', $product) }}"
                                   class="block w-full text-center bg-pink-500 text-white py-3 rounded-lg hover:bg-pink-600 font-medium transition shadow-sm">
                                    Pesan Sekarang
                                </a>
                            @else
                                <div class="block w-full text-center bg-gray-300 text-gray-500 py-3 rounded-lg font-medium cursor-not-allowed">
                                    Stok Habis
                                </div>
                            @endif

                            @php
                                $waNumber = config('app.wa_admin_number', env('WA_ADMIN_NUMBER', '6281234567890'));
                                $waText = 'Halo Admin, saya ingin bertanya tentang buket ' . $product->name . ' seharga ' . $product->formatted_price . '...';
                                $waUrl = 'https://wa.me/' . $waNumber . '?text=' . rawurlencode($waText);
                            @endphp
                            <a href="{{ $waUrl }}" target="_blank" rel="noopener noreferrer"
                               class="block w-full text-center bg-green-500 text-white py-3 rounded-lg hover:bg-green-600 font-medium transition shadow-sm">
                                💬 Tanya via WhatsApp
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full text-center bg-pink-600 text-white py-3 rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                            Login untuk Pesan
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
