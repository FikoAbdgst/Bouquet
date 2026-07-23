@extends('layouts.admin')

@section('content')
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-pink-800">Dashboard</h1>
        <p class="text-pink-500 text-sm">Selamat datang, {{ Auth::user()->name }}. Berikut ringkasan hari ini.</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-pink-100 flex items-center justify-center text-pink-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-sm text-pink-500">Pesanan Hari Ini</p>
                    <p class="text-2xl font-bold text-pink-800">{{ $ordersToday }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-yellow-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-pink-500">Belum Diproses</p>
                    <p class="text-2xl font-bold text-pink-800">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-pink-500">Total Omzet</p>
                    <p class="text-2xl font-bold text-pink-800">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Pesanan Terbaru --}}
    <div class="bg-white rounded-lg shadow-sm border border-pink-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-pink-100 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-pink-800">Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-pink-600 hover:text-pink-800 font-medium">Lihat Semua →</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="p-12 text-center">
                <p class="text-4xl mb-4">📦</p>
                <p class="text-pink-500">Belum ada pesanan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-pink-100">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Pemesan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Waktu</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-pink-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-50">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-pink-50 transition">
                                <td class="px-4 py-3 text-sm font-semibold text-pink-800">{{ $order->order_code }}</td>
                                <td class="px-4 py-3 text-sm text-pink-700">
                                    <div>{{ $order->orderer_name }}</div>
                                    <div class="text-xs text-pink-500">{{ $order->orderer_phone }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-pink-800">{{ $order->formatted_total }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-700">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-pink-600">
                                    {{ $order->created_at->timezone('Asia/Jakarta')->format('d M H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-block px-3 py-1 bg-pink-100 text-pink-700 rounded-lg text-sm font-medium hover:bg-pink-200 transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
