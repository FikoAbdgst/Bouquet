@extends('layouts.app')

@php
    $statusFlow = [
        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
    ];
    $currentIdx = array_search($order->status, array_keys($statusFlow));
    $progressPercent = $currentIdx !== false ? (($currentIdx + 1) / count($statusFlow)) * 100 : 0;
@endphp

@section('content')
<div class="max-w-3xl mx-auto">
    <a href="{{ route('customer.orders.index') }}" class="inline-flex items-center text-rose-500 hover:text-rose-600 mb-8 group transition">
        <svg class="w-4 h-4 mr-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Riwayat Pesanan
    </a>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">Lacak Pesanan</h1>
            <p class="text-slate-400 text-sm mt-1 font-medium">{{ $order->order_code }}</p>
        </div>
        <div class="flex items-center space-x-3 mt-3 sm:mt-0">
            <span id="live-indicator" class="inline-flex items-center text-xs font-medium text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse mr-1.5"></span>
                Live
            </span>
            <span id="status-badge" class="inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-700">
                {{ $order->status_label }}
            </span>
        </div>
    </div>

    {{-- Progress Bar --}}
    <div id="progress-section">
        @if($order->status !== 'dibatalkan')
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 mb-6 shadow-sm">
                <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Progres Pesanan</h2>
                <div class="w-full bg-rose-100 rounded-full h-3 mb-5">
                    <div id="progress-bar" class="bg-gradient-to-r from-rose-400 to-pink-500 h-3 rounded-full transition-all duration-700 ease-out" style="width: {{ $progressPercent }}%"></div>
                </div>
                <div class="grid grid-cols-5 gap-1" id="progress-steps">
                    @foreach($statusFlow as $statusKey => $statusLabel)
                        @php
                            $stepIdx = array_search($statusKey, array_keys($statusFlow));
                            $isCompleted = $currentIdx !== false && $stepIdx <= $currentIdx;
                            $isCurrent = $order->status === $statusKey;
                        @endphp
                        <div class="text-center step-item" data-status="{{ $statusKey }}" data-index="{{ $stepIdx }}">
                            <div class="w-9 h-9 mx-auto rounded-full flex items-center justify-center text-xs font-bold mb-1.5 step-circle transition-all duration-300
                                {{ $isCurrent ? 'bg-gradient-to-br from-rose-400 to-pink-500 text-white ring-4 ring-rose-100 shadow-md' : ($isCompleted ? 'bg-rose-400 text-white' : 'bg-rose-100 text-rose-300') }}">
                                @if($isCompleted && !$isCurrent) ✓ @else {{ $stepIdx + 1 }} @endif
                            </div>
                            <p class="text-xs step-label {{ $isCurrent ? 'text-slate-800 font-bold' : ($isCompleted ? 'text-slate-600' : 'text-slate-300') }}">
                                {{ $statusLabel }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-rose-50/80 rounded-2xl border border-rose-200 p-6 mb-6 text-center shadow-sm">
                <p class="text-3xl mb-2">✕</p>
                <p class="font-semibold text-rose-700 text-lg">Pesanan Dibatalkan</p>
            </div>
        @endif
    </div>

    {{-- Order Summary --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 mb-6 shadow-sm">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Ringkasan Pesanan</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-medium">Metode</p>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $order->pickup_method === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-medium">Tanggal Dibutuhkan</p>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $order->needed_date->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-medium">Tanggal Order</p>
                <p class="font-semibold text-slate-800 mt-0.5">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs uppercase tracking-wider font-medium">Pembayaran</p>
                <p id="payment-status" class="font-semibold mt-0.5 {{ $order->payment_verified ? 'text-emerald-600' : 'text-amber-500' }}">
                    {{ $order->payment_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                </p>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-rose-100 flex justify-between items-center">
            <span class="text-sm font-medium text-slate-500">Total</span>
            <span class="text-xl font-bold text-slate-800">{{ $order->formatted_total }}</span>
        </div>
    </div>

    {{-- Items --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 mb-6 shadow-sm">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Item Pesanan</h2>
        <div class="divide-y divide-rose-50">
            @foreach($order->items as $item)
                <div class="flex items-center justify-between py-3.5 first:pt-0 last:pb-0">
                    <div>
                        <p class="text-sm font-semibold text-slate-800">{{ $item->product_name_snapshot }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">Rp {{ number_format($item->price_snapshot, 0, ',', '.') }} × {{ $item->quantity }}</p>
                    </div>
                    <p class="text-sm font-bold text-slate-800">{{ $item->formatted_subtotal }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Tracking Log --}}
    <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 mb-6 shadow-sm">
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-4">Riwayat Perubahan Status</h2>
        <div id="tracking-logs" class="space-y-3">
            @if($order->trackingLogs->isEmpty())
                <p class="text-sm text-slate-400 tracking-empty">Belum ada riwayat.</p>
            @else
                @foreach($order->trackingLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start space-x-3 p-3.5 bg-rose-50/50 rounded-xl tracking-log border border-rose-100/50">
                        <div class="flex-shrink-0 w-2.5 h-2.5 mt-2 rounded-full bg-rose-400 shadow-sm"></div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-slate-600">
                                @if($log->previous_status)
                                    {{ match($log->previous_status) {
                                        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                        'dikonfirmasi' => 'Dikonfirmasi',
                                        'diproses' => 'Diproses',
                                        'dikirim' => 'Dikirim',
                                        'selesai' => 'Selesai',
                                        'dibatalkan' => 'Dibatalkan',
                                        default => $log->previous_status,
                                    } }}
                                    →
                                @endif
                                <span class="font-semibold text-slate-800">{{ match($log->new_status) {
                                    'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                                    'dikonfirmasi' => 'Dikonfirmasi',
                                    'diproses' => 'Diproses',
                                    'dikirim' => 'Dikirim',
                                    'selesai' => 'Selesai',
                                    'dibatalkan' => 'Dibatalkan',
                                    default => $log->new_status,
                                } }}</span>
                            </p>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                            @if($log->note)
                                <p class="text-xs text-slate-400 mt-1 italic">"{{ $log->note }}"</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    {{-- Reorder --}}
    @if(in_array($order->status, ['selesai', 'dibatalkan']) && $order->items->count() > 0)
        @php $firstItem = $order->items->first(); @endphp
        <a href="{{ route('customer.orders.create', ['product' => $firstItem->product_id, 'reorder' => $order->id]) }}"
           class="block w-full text-center bg-rose-400 text-white py-3.5 rounded-xl hover:bg-rose-500 font-semibold transition-all duration-200 shadow-sm hover:shadow-md mb-3">
            🔄 Pesan Ulang Buket Ini
        </a>
    @endif

    {{-- WhatsApp --}}
    <a id="wa-link" href="{{ $waUrl ?? '#' }}" target="_blank" rel="noopener noreferrer"
       class="block w-full text-center bg-emerald-500 text-white py-3.5 rounded-xl hover:bg-emerald-600 font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
        💬 Hubungi Admin via WhatsApp
    </a>
</div>
@endsection

@push('scripts')
<script>
(function() {
    const orderId = {{ $order->id }};
    const pollUrl = '{{ route("customer.orders.status", $order) }}';
    let currentStatus = '{{ $order->status }}';
    let currentLogCount = {{ $order->trackingLogs->count() }};
    let pollingInterval = null;

    const statusLabels = {
        'menunggu_konfirmasi': 'Menunggu Konfirmasi',
        'dikonfirmasi': 'Dikonfirmasi',
        'diproses': 'Diproses',
        'dikirim': 'Dikirim',
        'selesai': 'Selesai',
        'dibatalkan': 'Dibatalkan',
    };

    const statusColors = {
        'menunggu_konfirmasi': 'yellow',
        'dikonfirmasi': 'blue',
        'diproses': 'indigo',
        'dikirim': 'purple',
        'selesai': 'green',
        'dibatalkan': 'red',
    };

    const statusKeys = ['menunggu_konfirmasi', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai'];

    function formatRupiah(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function updateProgress(status) {
        const idx = statusKeys.indexOf(status);
        if (status === 'dibatalkan') {
            const section = document.getElementById('progress-section');
            section.innerHTML = '<div class="bg-rose-50/80 rounded-2xl border border-rose-200 p-6 mb-6 text-center shadow-sm"><p class="text-3xl mb-2">✕</p><p class="font-semibold text-rose-700 text-lg">Pesanan Dibatalkan</p></div>';
            return;
        }
        const percent = idx >= 0 ? ((idx + 1) / statusKeys.length) * 100 : 0;
        const bar = document.getElementById('progress-bar');
        if (bar) bar.style.width = percent + '%';

        document.querySelectorAll('.step-item').forEach(function(el) {
            const stepStatus = el.dataset.status;
            const stepIdx = parseInt(el.dataset.index);
            const isCompleted = idx >= 0 && stepIdx <= idx;
            const isCurrent = stepStatus === status;

            const circle = el.querySelector('.step-circle');
            const label = el.querySelector('.step-label');

            circle.className = 'w-9 h-9 mx-auto rounded-full flex items-center justify-center text-xs font-bold mb-1.5 step-circle transition-all duration-300 '
                + (isCurrent ? 'bg-gradient-to-br from-rose-400 to-pink-500 text-white ring-4 ring-rose-100 shadow-md'
                    : isCompleted ? 'bg-rose-400 text-white'
                    : 'bg-rose-100 text-rose-300');
            circle.innerHTML = (isCompleted && !isCurrent) ? '✓' : (stepIdx + 1);

            label.className = 'text-xs step-label '
                + (isCurrent ? 'text-slate-800 font-bold'
                    : isCompleted ? 'text-slate-600'
                    : 'text-slate-300');
        });
    }

    function updateStatusBadge(status, label, color) {
        const badge = document.getElementById('status-badge');
        badge.textContent = label;
        badge.className = 'inline-block px-4 py-1.5 rounded-full text-sm font-semibold bg-' + color + '-100 text-' + color + '-700';
    }

    function updatePaymentStatus(verified) {
        const el = document.getElementById('payment-status');
        if (verified) {
            el.textContent = 'Terverifikasi';
            el.className = 'font-semibold text-emerald-600 mt-0.5';
        } else {
            el.textContent = 'Menunggu Verifikasi';
            el.className = 'font-semibold text-amber-500 mt-0.5';
        }
    }

    function updateTrackingLogs(logs) {
        const container = document.getElementById('tracking-logs');
        if (logs.length === 0) {
            container.innerHTML = '<p class="text-sm text-slate-400 tracking-empty">Belum ada riwayat.</p>';
            return;
        }

        let html = '';
        logs.forEach(function(log) {
            html += '<div class="flex items-start space-x-3 p-3.5 bg-rose-50/50 rounded-xl tracking-log border border-rose-100/50">';
            html += '<div class="flex-shrink-0 w-2.5 h-2.5 mt-2 rounded-full bg-rose-400 shadow-sm"></div>';
            html += '<div class="flex-1 min-w-0">';
            html += '<p class="text-sm text-slate-600">';
            if (log.previous_status) {
                html += (statusLabels[log.previous_status] || log.previous_status) + ' → ';
            }
            html += '<span class="font-semibold text-slate-800">' + (statusLabels[log.new_status] || log.new_status) + '</span>';
            html += '</p>';
            html += '<p class="text-xs text-slate-400 mt-0.5">' + log.created_at + '</p>';
            if (log.note) {
                html += '<p class="text-xs text-slate-400 mt-1 italic">"' + log.note + '"</p>';
            }
            html += '</div></div>';
        });
        container.innerHTML = html;
    }

    function updateWaLink(statusLabel, orderCode) {
        const waNumber = '{{ config("app.wa_admin_number", env("WA_ADMIN_NUMBER", "6281234567890")) }}';
        const text = 'Halo Admin, saya ingin konfirmasi pesanan dengan kode ' + orderCode + '. Status saat ini: ' + statusLabel;
        const url = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(text);
        document.getElementById('wa-link').href = url;
    }

    function poll() {
        fetch(pollUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            credentials: 'same-origin'
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.status !== currentStatus) {
                currentStatus = data.status;
                updateStatusBadge(data.status, data.status_label, data.status_color);
                updateProgress(data.status);
                updateWaLink(data.status_label, '{{ $order->order_code }}');
            }

            if (data.payment_verified) {
                updatePaymentStatus(true);
            }

            if (data.tracking_logs.length !== currentLogCount) {
                currentLogCount = data.tracking_logs.length;
                updateTrackingLogs(data.tracking_logs);
            }

            if (data.status === 'selesai' || data.status === 'dibatalkan') {
                clearInterval(pollingInterval);
                const indicator = document.getElementById('live-indicator');
                indicator.innerHTML = '<span class="w-2 h-2 bg-slate-400 rounded-full mr-1.5"></span>Selesai';
                indicator.className = 'inline-flex items-center text-xs font-medium text-slate-400 bg-slate-50 px-2.5 py-1 rounded-full';
            }
        })
        .catch(function() {});
    }

    pollingInterval = setInterval(poll, 5000);
    poll();
})();
</script>
@endpush
