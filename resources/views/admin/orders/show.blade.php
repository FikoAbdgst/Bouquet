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

    $borderMap = [
        'menunggu_konfirmasi' => 'border-amber-400 text-amber-700',
        'dikonfirmasi' => 'border-blue-400 text-blue-700',
        'diproses' => 'border-purple-400 text-purple-700',
        'dikirim' => 'border-indigo-400 text-indigo-700',
        'selesai' => 'border-green-600 text-green-700',
        'dibatalkan' => 'border-red-400 text-red-500',
    ];
    $statusBorderClass = $borderMap[$order->status] ?? 'border-[#EFD3DE] text-[#6E8577]';
@endphp

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-xs tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:pb-1 transition-all mb-6">
        &larr; Kembali ke Daftar Pesanan
    </a>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-medium text-[#33413A]">Detail Pesanan</h1>
            <p class="text-xs tracking-wide text-[#6E8577] mt-1">{{ $order->order_code }}</p>
        </div>
        <span class="mt-2 sm:mt-0 inline-block px-3 py-1 text-xs tracking-wide border {{ $statusBorderClass }}">
            {{ $order->status_label }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Info Pesanan --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Data Pemesan --}}
            <div class="border border-[#EFD3DE] p-5">
                <h2 class="text-sm font-medium text-[#33413A] mb-3">Data Pemesan</h2>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-[11px] tracking-[0.2em] uppercase text-[#6E8577]">Nama</p>
                        <p class="text-[#33413A] mt-0.5">{{ $order->orderer_name }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] tracking-[0.2em] uppercase text-[#6E8577]">Telepon</p>
                        <p class="text-[#33413A] mt-0.5">{{ $order->orderer_phone }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] tracking-[0.2em] uppercase text-[#6E8577]">Metode</p>
                        <p class="text-[#33413A] mt-0.5">{{ $order->pickup_method === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</p>
                    </div>
                    <div>
                        <p class="text-[11px] tracking-[0.2em] uppercase text-[#6E8577]">Tanggal Dibutuhkan</p>
                        <p class="text-[#33413A] mt-0.5">{{ $order->needed_date->format('d M Y') }}</p>
                    </div>
                    @if($order->delivery_address)
                        <div class="col-span-2">
                            <p class="text-[11px] tracking-[0.2em] uppercase text-[#6E8577]">Alamat Tujuan</p>
                            <p class="text-[#33413A] mt-0.5">{{ $order->delivery_address }}</p>
                        </div>
                    @endif
                    @if($order->special_note)
                        <div class="col-span-2">
                            <p class="text-[11px] tracking-[0.2em] uppercase text-[#6E8577]">Catatan Khusus</p>
                            <p class="text-[#33413A] mt-0.5">{{ $order->special_note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Items --}}
            <div class="border border-[#EFD3DE] p-5">
                <h2 class="text-sm font-medium text-[#33413A] mb-3">Item Pesanan</h2>
                <div class="divide-y divide-[#EFD3DE]">
                    @foreach($order->items as $item)
                        <div class="flex items-start justify-between py-3 first:pt-0 last:pb-0">
                            <div class="flex items-start gap-3 min-w-0 flex-1">
                                @if($item->product && $item->product->primaryImage)
                                    <img src="{{ Storage::url($item->product->primaryImage->image_url) }}" class="w-12 h-12 object-cover border border-[#EFD3DE] flex-shrink-0 mt-0.5">
                                @else
                                    <div class="w-12 h-12 border border-[#EFD3DE] flex items-center justify-center text-xs text-[#C9A9B4] flex-shrink-0 mt-0.5">—</div>
                                @endif
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start gap-1{{ $item->custom_options ? ' cursor-pointer' : '' }}"{{ $item->custom_options ? ' onclick="this.nextElementSibling.classList.toggle(\'hidden\');this.querySelector(\'.cv\').classList.toggle(\'-rotate-180\')"' : '' }}>
                                        <div class="min-w-0">
                                            <p class="text-sm text-[#33413A] truncate">{{ $item->product_name_snapshot }}</p>
                                            <p class="text-xs text-[#6E8577]">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }} &times; {{ $item->quantity }}</p>
                                        </div>
                                        @if($item->custom_options)
                                            <svg class="cv w-2.5 h-2.5 mt-0.5 flex-shrink-0 text-[#C9A9B4] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        @endif
                                    </div>
                                    @if($item->custom_options)
                                        <div class="hidden mt-2 space-y-1 pl-2.5 border-l border-[#EFD3DE]">
                                            @foreach($item->custom_options as $label => $value)
                                                <div>
                                                    <p class="text-[10px] tracking-[0.15em] uppercase text-[#6E8577]">{{ $label }}</p>
                                                    <p class="text-xs text-[#33413A]">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <p class="text-sm font-medium text-[#33413A] flex-shrink-0 ml-3">{{ $item->formatted_subtotal }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between items-center pt-3 mt-3 border-t border-[#EFD3DE]">
                    <span class="text-sm text-[#33413A]">Total</span>
                    <span class="text-base font-medium text-[#33413A]">{{ $order->formatted_total }}</span>
                </div>
            </div>

            {{-- Bukti Pembayaran --}}
            <div class="border border-[#EFD3DE] p-5">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-medium text-[#33413A]">Bukti Pembayaran</h2>
                    @if($order->payment_verified)
                        <span class="text-xs tracking-wide border border-green-600 text-green-700 px-2.5 py-0.5">Terverifikasi</span>
                    @else
                        <span class="text-xs tracking-wide border border-amber-400 text-amber-700 px-2.5 py-0.5">Belum Diverifikasi</span>
                    @endif
                </div>
                <div class="border border-[#EFD3DE] overflow-hidden">
                    <a href="{{ Storage::url($order->payment_proof_url) }}" target="_blank">
                        <img src="{{ Storage::url($order->payment_proof_url) }}" alt="Bukti Pembayaran" class="w-full max-h-96 object-contain">
                    </a>
                </div>
                @if(!$order->payment_verified)
                    <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="w-full border border-green-600 text-green-700 hover:bg-green-50 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                            Verifikasi Pembayaran
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- Sidebar: Status + Update --}}
        <div class="space-y-6">
            {{-- Progress Status --}}
            <div class="border border-[#EFD3DE] p-5">
                <h2 class="text-sm font-medium text-[#33413A] mb-4">Status Pesanan</h2>
                <div class="space-y-3">
                    @foreach($statusFlow as $statusKey => $statusLabel)
                        @php
                            $stepIdx = array_search($statusKey, array_keys($statusFlow));
                            $isCompleted = $currentIdx !== false && $stepIdx <= $currentIdx;
                            $isCurrent = $order->status === $statusKey;
                        @endphp
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-7 h-7 flex items-center justify-center text-xs font-medium
                                {{ $isCurrent ? 'bg-[#D37897] text-white border-2 border-[#D37897]' : ($isCompleted ? 'bg-[#D37897] text-white' : 'border border-[#EFD3DE] text-[#C9A9B4]') }}">
                                @if($isCompleted && !$isCurrent)
                                    &check;
                                @else
                                    {{ $stepIdx + 1 }}
                                @endif
                            </div>
                            <span class="text-sm {{ $isCurrent ? 'text-[#33413A] font-medium' : ($isCompleted ? 'text-[#33413A]' : 'text-[#C9A9B4]') }}">
                                {{ $statusLabel }}
                            </span>
                            @if($isCurrent)
                                <span class="text-[10px] tracking-wider border border-[#D37897] text-[#D37897] px-1.5 py-0.5 ml-auto flex-shrink-0">Saat Ini</span>
                            @endif
                        </div>
                    @endforeach
                    @if($order->status === 'dibatalkan')
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-7 h-7 flex items-center justify-center text-xs font-medium bg-red-500 text-white">&times;</div>
                            <span class="text-sm font-medium text-red-600">Dibatalkan</span>
                            <span class="text-[10px] tracking-wider border border-red-400 text-red-500 px-1.5 py-0.5 ml-auto flex-shrink-0">Dibatalkan</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Update Status --}}
            @if($order->status !== 'selesai' && $order->status !== 'dibatalkan')
                <div class="border border-[#EFD3DE] p-5">
                    <h2 class="text-sm font-medium text-[#33413A] mb-3">Ubah Status</h2>
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
                                <label for="note" class="block text-[11px] tracking-[0.2em] uppercase text-[#6E8577] mb-2">Catatan (opsional)</label>
                                <textarea name="note" id="note" rows="2" placeholder="Catatan perubahan status..."
                                          class="w-full border border-[#EFD3DE] px-3 py-2 text-sm bg-transparent outline-none focus:border-[#D37897] transition-colors placeholder-[#C9A9B4]"></textarea>
                            </div>
                            <button type="submit" class="w-full border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
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
                                class="w-full border border-red-500 text-red-600 hover:bg-red-50 text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                            Batalkan Pesanan
                        </button>
                    </form>
                </div>
            @endif

            {{-- Admin Note --}}
            <div class="border border-[#EFD3DE] p-5">
                <h2 class="text-sm font-medium text-[#33413A] mb-3">Catatan Admin</h2>
                <form action="{{ route('admin.orders.update-note', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="admin_note" rows="3" placeholder="Tambahkan catatan untuk pesanan ini..."
                              class="w-full border border-[#EFD3DE] px-3 py-2 text-sm bg-transparent outline-none focus:border-[#D37897] transition-colors placeholder-[#C9A9B4] mb-3">{{ $order->admin_note }}</textarea>
                    <button type="submit" class="w-full border border-[#EFD3DE] text-[#6E8577] hover:border-[#D37897] hover:text-[#D37897] text-sm tracking-wide transition-colors duration-200 px-4 py-2">
                        Simpan Catatan
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Tracking Log --}}
    <div class="mt-6 border border-[#EFD3DE] p-5">
        <h2 class="text-sm font-medium text-[#33413A] mb-4">Riwayat Status</h2>
        @if($order->trackingLogs->isEmpty())
            <p class="text-sm text-[#6E8577]">Belum ada riwayat perubahan status.</p>
        @else
            <div class="space-y-3">
                @foreach($order->trackingLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start gap-3 p-3 border border-[#EFD3DE]">
                        <div class="flex-shrink-0 w-7 h-7 border border-[#EFD3DE] text-[#6E8577] flex items-center justify-center text-xs font-medium">
                            {{ strtoupper(substr($log->changedByUser->name ?? 'A', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-[#33413A]">{{ $log->changedByUser->name ?? 'Sistem' }}</span>
                                <span class="text-xs text-[#C9A9B4]">&bull;</span>
                                <span class="text-xs text-[#C9A9B4]">{{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                            </div>
                            <p class="text-sm text-[#33413A] mt-0.5">
                                @if($log->previous_status)
                                    {{ \App\Http\Controllers\Admin\OrderController::STATUS_LABELS[$log->previous_status] ?? $log->previous_status }}
                                    &rarr;
                                @endif
                                <span class="font-medium">{{ \App\Http\Controllers\Admin\OrderController::STATUS_LABELS[$log->new_status] ?? $log->new_status }}</span>
                            </p>
                            @if($log->note)
                                <p class="text-xs text-[#6E8577] mt-1">&ldquo;{{ $log->note }}&rdquo;</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
