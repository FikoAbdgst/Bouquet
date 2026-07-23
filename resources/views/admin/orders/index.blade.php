@extends('layouts.admin')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-pink-800">Kelola Pesanan</h1>
    </div>

    {{-- Filter Status --}}
    <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}"
               class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ !request('status') ? 'bg-pink-600 text-white' : 'bg-pink-50 text-pink-700 hover:bg-pink-100' }}">
                Semua
            </a>
            @foreach([
                'menunggu_konfirmasi' => 'Menunggu',
                'dikonfirmasi' => 'Dikonfirmasi',
                'diproses' => 'Diproses',
                'dikirim' => 'Dikirim',
                'selesai' => 'Selesai',
                'dibatalkan' => 'Dibatalkan',
            ] as $key => $label)
                <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
                   class="px-3 py-1.5 rounded-full text-sm font-medium transition {{ request('status') === $key ? 'bg-pink-600 text-white' : 'bg-pink-50 text-pink-700 hover:bg-pink-100' }}">
                    {{ $label }}
                    @if(isset($statusCounts[$key]))
                        <span class="ml-1 text-xs opacity-75">({{ $statusCounts[$key] }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-6">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pesanan, nama, atau no HP..."
                   class="flex-1 border border-pink-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
            <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                Cari
            </button>
        </div>
    </form>

    {{-- Tabel Pesanan --}}
    @if($orders->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-12 text-center">
            <p class="text-4xl mb-4">📦</p>
            <p class="text-pink-600">Tidak ada pesanan ditemukan.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-pink-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-pink-200">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Pemesan</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Bayar</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-pink-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-100">
                        @foreach($orders as $order)
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
                                <td class="px-4 py-3">
                                    @if($order->payment_verified)
                                        <span class="text-green-600 text-sm font-medium">✓ Terverifikasi</span>
                                    @else
                                        <span class="text-orange-500 text-sm font-medium">Belum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-pink-600">
                                    {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
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
        </div>

        <div class="mt-6">
            {{ $orders->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
