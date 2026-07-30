@extends('layouts.admin')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-medium text-[#33413A]">Kelola Pesanan</h1>
    </div>

    {{-- Filter Status --}}
    <div class="border border-[#EFD3DE] p-4 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.orders.index') }}"
               class="px-3 py-1.5 text-xs tracking-wide transition-colors duration-150 {{ !request('status') ? 'border border-[#D37897] bg-[#D37897] text-white' : 'border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#D37897]' }}">
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
                   class="px-3 py-1.5 text-xs tracking-wide transition-colors duration-150 {{ request('status') === $key ? 'border border-[#D37897] bg-[#D37897] text-white' : 'border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#D37897]' }}">
                    {{ $label }}
                    @if(isset($statusCounts[$key]))
                        <span class="ml-1 opacity-60">({{ $statusCounts[$key] }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search --}}
    <form action="{{ route('admin.orders.index') }}" method="GET" class="mb-6 max-w-md">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode pesanan, nama, atau no HP..."
                   class="flex-1 border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]">
            <button type="submit" class="border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2 flex-shrink-0">
                Cari
            </button>
        </div>
    </form>

    {{-- Tabel Pesanan --}}
    @if($orders->isEmpty())
        <div class="border border-[#EFD3DE] text-center py-16 px-6">
            <p class="text-[#33413A]">Tidak ada pesanan ditemukan.</p>
        </div>
    @else
        <div class="border border-[#EFD3DE]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#EFD3DE]">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Kode</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Pemesan</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Total</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Bayar</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-right text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFD3DE]">
                        @foreach($orders as $order)
                            <tr class="hover:bg-[#F9DEE5]/50 transition-colors duration-150">
                                <td class="px-4 py-3 text-sm font-medium text-[#33413A]">{{ $order->order_code }}</td>
                                <td class="px-4 py-3 text-sm text-[#33413A]">
                                    <div>{{ $order->orderer_name }}</div>
                                    <div class="text-xs text-[#6E8577]">{{ $order->orderer_phone }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-[#33413A]">{{ $order->formatted_total }}</td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    @php
                                        $borderMap = [
                                            'menunggu_konfirmasi' => 'border-amber-400 text-amber-700',
                                            'dikonfirmasi' => 'border-blue-400 text-blue-700',
                                            'diproses' => 'border-purple-400 text-purple-700',
                                            'dikirim' => 'border-indigo-400 text-indigo-700',
                                            'selesai' => 'border-green-600 text-green-700',
                                            'dibatalkan' => 'border-red-400 text-red-500',
                                        ];
                                        $borderClass = $borderMap[$order->status] ?? 'border-[#EFD3DE] text-[#6E8577]';
                                    @endphp
                                    <span class="inline-block px-2.5 py-0.5 text-xs tracking-wide border {{ $borderClass }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($order->payment_verified)
                                        <span class="text-xs tracking-wide text-green-700 border-b border-green-400 pb-0.5">Terverifikasi</span>
                                    @else
                                        <span class="text-xs tracking-wide text-[#6E8577]">Belum</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-[#6E8577]">
                                    {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="text-xs tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:pb-1 transition-all">
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
