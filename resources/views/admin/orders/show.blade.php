@extends('layouts.admin')

@php
    $statusFlow = [
        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
    ];
    $currentIdx = array_search($order->status, array_keys($statusFlow));
@endphp

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-pink-600 hover:text-pink-800 mb-6">
        ← Kembali ke Daftar Pesanan
    </a>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-pink-800">Detail Pesanan</h1>
            <p class="text-pink-500 text-sm">{{ $order->order_code }}</p>
        </div>
        <span class="mt-2 sm:mt-0 inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-700">
            {{ $order->status_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info Pesanan --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Data Pemesan --}}
            <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
                <h2 class="text-lg font-semibold text-pink-800 mb-3">Data Pemesan</h2>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-pink-500">Nama</p>
                        <p class="font-medium text-pink-800">{{ $order->orderer_name }}</p>
                    </div>
                    <div>
                        <p class="text-pink-500">Telepon</p>
                        <p class="font-medium text-pink-800">{{ $order->orderer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-pink-500">Metode</p>
                        <p class="font-medium text-pink-800">{{ $order->pickup_method === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</p>
                    </div>
                    <div>
                        <p class="text-pink-500">Tanggal Dibutuhkan</p>
                        <p class="font-medium text-pink-800">{{ $order->needed_date->format('d M Y') }}</p>
                    </div>
                    @if($order->delivery_address)
                        <div class="col-span-2">
                            <p class="text-pink-500">Alamat Tujuan</p>
                            <p class="font-medium text-pink-800">{{ $order->delivery_address }}</p>
                        </div>
                    @endif
                    @if($order->special_note)
                        <div class="col-span-2">
                            <p class="text-pink-500">Catatan Khusus</p>
                            <p class="font-medium text-pink-800">{{ $order->special_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Items --}}
            <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
                <h2 class="text-lg font-semibold text-pink-800 mb-3">Item Pesanan</h2>
                <div class="divide-y divide-pink-100">
                    @foreach($order->items as $item)
                        <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                            <div class="flex items-center space-x-3">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ Storage::url($item->product->primaryImage->image_url) }}" class="w-12 h-12 object-cover rounded-lg">
                                @else
                                    <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center text-lg text-pink-300">🌸</div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-pink-800">{{ $item->product_name_snapshot }}</p>
                                    <p class="text-xs text-pink-500">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }} × {{ $item->quantity }}</p>
                                    @if($item->custom_options)
                                        <div class="mt-1 space-y-0.5">
                                            @foreach($item->custom_options as $label => $value)
                                                <span class="text-xs text-pink-400"><b>{{ $label }}:</b> {{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                                @if(!$loop->last)<br>@endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm font-semibold text-pink-800">{{ $item->formatted_subtotal }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center pt-3 mt-3 border-t border-pink-200">
                    <span class="font-semibold text-pink-700">Total</span>
                    <span class="text-lg font-bold text-pink-800">{{ $order->formatted_total }}</span>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-lg font-semibold text-pink-800">Bukti Pembayaran</h2>
                    @if($order->payment_verified)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-semibold">✓ Terverifikasi</span>
                    @else
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm font-semibold">Belum Diverifikasi</span>
                    @endif
                </div>
                <div class="border border-pink-200 rounded-lg overflow-hidden">
                    <a href="{{ Storage::url($order->payment_proof_url) }}" target="_blank">
                        <img src="{{ Storage::url($order->payment_proof_url) }}" alt="Bukti Pembayaran" class="w-full max-h-96 object-contain bg-pink-50">
                    </a>
                </div>
                @if(!$order->payment_verified)
                    <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition shadow-sm">
                            ✓ Verifikasi Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Sidebar: Status + Update --}}
        <div class="space-y-6">
            {{-- Progress Status --}}
            <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
                <h2 class="text-lg font-semibold text-pink-800 mb-4">Status Pesanan</h2>
                <div class="space-y-3">
                    @foreach($statusFlow as $statusKey => $statusLabel)
                        @php
                            $stepIdx = array_search($statusKey, array_keys($statusFlow));
                            $isCompleted = $currentIdx !== false && $stepIdx <= $currentIdx;
                            $isCurrent = $order->status === $statusKey;
                        @endphp
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                {{ $isCurrent ? 'bg-pink-600 text-white ring-2 ring-pink-300' : ($isCompleted ? 'bg-pink-500 text-white' : 'bg-pink-100 text-pink-400') }}">
                                @if($isCompleted && !$isCurrent)
                                    ✓
                                @else
                                    {{ $stepIdx + 1 }}
                                @endif
                            </div>
                            <span class="text-sm font-medium {{ $isCurrent ? 'text-pink-800 font-bold' : ($isCompleted ? 'text-pink-700' : 'text-pink-400') }}">
                                {{ $statusLabel }}
                            </span>
                            @if($isCurrent)
                                <span class="text-xs bg-pink-100 text-pink-600 px-2 py-0.5 rounded-full ml-auto">Saat Ini</span>
                            @endif
                        </div>
                    @endforeach
                    @if($order->status === 'dibatalkan')
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold bg-red-500 text-white">✕</div>
                            <span class="text-sm font-bold text-red-700">Dibatalkan</span>
                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full ml-auto">Dibatalkan</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Update Status --}}
            @if($order->status !== 'selesai' && $order->status !== 'dibatalkan')
                <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
                    <h2 class="text-lg font-semibold text-pink-800 mb-3">Ubah Status</h2>
                    @php
                        $nextStatus = match($order->status) {
                            'menunggu_konfirmasi' => 'dikonfirmasi',
                            'dikonfirmasi' => 'diproses',
                            'diproses' => 'dikirim',
                            'dikirim' => 'selesai',
                            default => null,
                        };
                    @endphp
                    @if($nextStatus)
                        <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="{{ $nextStatus }}">
                            <div>
                                <label for="note" class="block text-sm font-medium text-pink-700 mb-1">Catatan (opsional)</label>
                                <textarea name="note" id="note" rows="2" placeholder="Catatan perubahan status..."
                                          class="w-full border border-pink-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500"></textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                                Ubah ke "{{ \App\Http\Controllers\Admin\OrderController::STATUS_LABELS[$nextStatus] ?? $nextStatus }}"
                            </button>
                        </form>
                    @endif

                    {{-- Tombol Batal --}}
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mt-3">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="dibatalkan">
                        <button type="submit" onclick="return confirm('Yakin ingin membatalkan pesanan ini?')"
                                class="w-full px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 font-medium transition">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Admin Note --}}
            <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-5">
                <h2 class="text-lg font-semibold text-pink-800 mb-2">Catatan Admin</h2>
                <form action="{{ route('admin.orders.update-note', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="admin_note" rows="3"
                              class="w-full border border-pink-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm mb-2"
                              placeholder="Tambahkan catatan untuk pesanan ini...">{{ $order->admin_note }}</textarea>
                    <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 text-sm font-medium transition shadow-sm">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tracking Log --}}
    <div class="mt-6 bg-white rounded-lg shadow-sm border border-pink-200 p-5">
        <h2 class="text-lg font-semibold text-pink-800 mb-4">Riwayat Status</h2>
        @if($order->trackingLogs->isEmpty())
            <p class="text-sm text-pink-500">Belum ada riwayat perubahan status.</p>
        @else
            <div class="space-y-3">
                @foreach($order->trackingLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start space-x-3 p-3 bg-pink-50 rounded-lg">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-pink-200 text-pink-700 flex items-center justify-center text-sm font-bold">
                            {{ strtoupper(substr($log->changedByUser->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium text-pink-800">{{ $log->changedByUser->name ?? 'Sistem' }}</span>
                                <span class="text-xs text-pink-500">•</span>
                                <span class="text-xs text-pink-500">{{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <p class="text-sm text-pink-700 mt-0.5">
                                @if($log->previous_status)
                                    {{ \App\Http\Controllers\Admin\OrderController::STATUS_LABELS[$log->previous_status] ?? $log->previous_status }}
                                    →
                                @endif
                                <span class="font-semibold">{{ \App\Http\Controllers\Admin\OrderController::STATUS_LABELS[$log->new_status] ?? $log->new_status }}</span>
                            </p>
                            @if($log->note)
                                <p class="text-xs text-pink-500 mt-1">"{{ $log->note }}"</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
