@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold text-pink-800 mb-6">Riwayat Pesanan</h1>

    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-12 text-center">
            <p class="text-4xl mb-4">📦</p>
            <p class="text-pink-600 mb-4">Belum ada pesanan.</p>
            <a href="{{ route('customer.catalog') }}" class="inline-block bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                        <div>
                            <p class="text-sm text-pink-500">Kode Pesanan</p>
                            <p class="font-bold text-pink-800">{{ $order->order_code }}</p>
                        </div>
                        <span class="mt-2 sm:mt-0 inline-block px-3 py-1 rounded-full text-xs font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-700">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="space-y-1 text-sm text-pink-600 mb-3">
                        @foreach($order->items as $item)
                            <div class="flex justify-between">
                                <span>{{ $item->product_name_snapshot }} × {{ $item->quantity }}</span>
                                <span class="font-medium">{{ $item->formatted_subtotal }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-3 border-t border-pink-100 gap-2">
                        <span class="text-sm text-pink-500">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                        <div class="flex items-center space-x-3">
                            <span class="font-bold text-pink-800">{{ $order->formatted_total }}</span>
                            <a href="{{ route('customer.orders.show', $order) }}" class="px-3 py-1 bg-pink-100 text-pink-700 rounded-lg text-sm font-medium hover:bg-pink-200 transition">
                                Lacak Pesanan →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
