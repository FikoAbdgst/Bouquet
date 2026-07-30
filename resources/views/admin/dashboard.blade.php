@extends('layouts.admin')

@section('content')
<div>
    <div class="mb-8">
        <p class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Dashboard</p>
        <h1 class="text-2xl font-medium text-[#33413A] mt-1">Selamat datang, {{ Auth::user()->name }}</h1>
        <p class="text-sm text-[#C9A9B4] mt-1">Berikut ringkasan toko hari ini.</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-px mb-8 bg-[#EFD3DE]">
        <div class="bg-white p-6">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 flex items-center justify-center border border-[#EFD3DE]">
                    <svg class="w-4.5 h-4.5 text-[#D37897]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <div>
                    <p class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577]">Pesanan Hari Ini</p>
                    <p class="text-2xl font-medium text-[#33413A] mt-0.5">{{ $ordersToday }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 flex items-center justify-center border border-[#EFD3DE]">
                    <svg class="w-4.5 h-4.5 text-[#D37897]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577]">Belum Diproses</p>
                    <p class="text-2xl font-medium text-[#33413A] mt-0.5">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 flex items-center justify-center border border-[#EFD3DE]">
                    <svg class="w-4.5 h-4.5 text-[#D37897]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <p class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577]">Total Omzet</p>
                    <p class="text-2xl font-medium text-[#33413A] mt-0.5">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Best Seller --}}
    <div class="border border-[#EFD3DE] mb-8">
        <div class="px-6 py-4 border-b border-[#EFD3DE]">
            <h2 class="text-sm font-medium text-[#33413A]">Best Seller</h2>
        </div>
        @if($bestSellers->isEmpty())
            <div class="p-8 text-center">
                <p class="text-[#6E8577] text-sm">Belum ada data penjualan.</p>
            </div>
        @else
            <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach($bestSellers as $best)
                    <div class="flex items-center space-x-3 p-3 border border-[#EFD3DE]">
                        <div class="w-12 h-12 overflow-hidden bg-[#F9DEE5] flex-shrink-0">
                            @if($best->primaryImage)
                                <img src="{{ Storage::url($best->primaryImage->image_url) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[#C9A9B4] text-lg">—</div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-[#33413A] truncate">{{ $best->name }}</p>
                            <p class="text-xs text-[#6E8577]">{{ $best->formatted_price }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Pesanan Terbaru --}}
    <div class="border border-[#EFD3DE]">
        <div class="px-6 py-4 border-b border-[#EFD3DE] flex items-center justify-between">
            <h2 class="text-sm font-medium text-[#33413A]">Pesanan Terbaru</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-xs tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:pb-1 transition-all">Lihat Semua</a>
        </div>

        @if($recentOrders->isEmpty())
            <div class="p-12 text-center">
                <p class="text-[#6E8577] text-sm">Belum ada pesanan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#EFD3DE]">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Pemesan</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Waktu</th>
                            <th class="px-4 py-3 text-center text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFD3DE]">
                        @foreach($recentOrders as $order)
                            <tr class="hover:bg-[#F9DEE5]/50 transition">
                                <td class="px-4 py-3 text-sm font-medium text-[#33413A]">{{ $order->order_code }}</td>
                                <td class="px-4 py-3 text-sm text-[#5C6F5E]">
                                    <div>{{ $order->orderer_name }}</div>
                                    <div class="text-xs text-[#C9A9B4]">{{ $order->orderer_phone }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-[#33413A]">{{ $order->formatted_total }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2.5 py-1 text-[11px] font-medium tracking-wide uppercase border {{ $order->status === 'menunggu_konfirmasi' ? 'border-yellow-600 text-yellow-700' : ($order->status === 'dikonfirmasi' ? 'border-blue-600 text-blue-700' : ($order->status === 'diproses' ? 'border-indigo-600 text-indigo-700' : ($order->status === 'dikirim' ? 'border-purple-600 text-purple-700' : ($order->status === 'selesai' ? 'border-green-600 text-green-700' : 'border-red-600 text-red-700')))) }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-[#6E8577]">
                                    {{ $order->created_at->timezone('Asia/Jakarta')->format('d M H:i') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="inline-block text-xs tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:pb-1 transition-all">
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
