@extends('layouts.app', ['hideNav' => true])

@php
    $statusFlow = [
        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
        'dikonfirmasi' => 'Dikonfirmasi',
        'diproses' => 'Diproses',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
    ];
    $allStatusLabels = $statusFlow + ['dibatalkan' => 'Dibatalkan'];
    $currentIdx = array_search($order->status, array_keys($statusFlow));
    $progressPercent = $currentIdx !== false ? (($currentIdx + 1) / count($statusFlow)) * 100 : 0;

    // Palet status diselaraskan dengan tema Mint Julep, fallback netral kalau ada status_color yang belum terdaftar.
    $statusPalette = [
        'yellow' => ['bg' => '#FBF3DE', 'text' => '#9C7A1C'],
        'blue' => ['bg' => '#E7EEF6', 'text' => '#3C6A96'],
        'indigo' => ['bg' => '#EAEDF6', 'text' => '#4C5C9C'],
        'purple' => ['bg' => '#F1EAF6', 'text' => '#7A4C9C'],
        'green' => ['bg' => '#EAF3E8', 'text' => '#457359'],
        'red' => ['bg' => '#FBEAEE', 'text' => '#B33F5C'],
        'gray' => ['bg' => '#F1F0EA', 'text' => '#6E8577'],
    ];
    $statusStyle = $statusPalette[$order->status_color] ?? $statusPalette['gray'];
@endphp

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&display=swap"
        rel="stylesheet">
    <style>
        .font-display {
            font-family: "Cormorant Garamond", serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }

        .font-body {
            font-family: 'Inter', system-ui, sans-serif;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('customer.orders.index') }}"
            class="inline-flex items-center gap-2 text-sm tracking-wide text-[#D37897] border-b border-[#D37897] pb-0.5 hover:gap-3 transition-all duration-200 mb-8">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Riwayat Pesanan
        </a>

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
            <div>
                <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Lacak Pesanan</span>
                <p class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-1">{{ $order->order_code }}</p>
            </div>
            <div class="flex items-center gap-2">
                <span id="live-indicator"
                    class="inline-flex items-center text-[11px] tracking-[0.1em] uppercase font-medium px-2.5 py-1"
                    style="background-color: #EAF3E8; color: #457359;">
                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 animate-pulse" style="background-color: #457359;"></span>
                    Live
                </span>
                <span id="status-badge" class="inline-block px-4 py-1.5 text-sm font-medium"
                    style="background-color: {{ $statusStyle['bg'] }}; color: {{ $statusStyle['text'] }};">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>

        {{-- Progress Bar --}}
        <div id="progress-section">
            @if ($order->status !== 'dibatalkan')
                <div class="border border-[#E7E4DC] p-6 mb-6">
                    <h2 class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-4">Progres Pesanan</h2>
                    <div class="w-full bg-[#F1F0EA] h-1.5 mb-6">
                        <div id="progress-bar" class="bg-[#D37897] h-1.5 transition-all duration-700 ease-out"
                            style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <div class="grid grid-cols-5 gap-1" id="progress-steps">
                        @foreach ($statusFlow as $statusKey => $statusLabel)
                            @php
                                $stepIdx = array_search($statusKey, array_keys($statusFlow));
                                $isCompleted = $currentIdx !== false && $stepIdx <= $currentIdx;
                                $isCurrent = $order->status === $statusKey;
                            @endphp
                            <div class="text-center step-item" data-status="{{ $statusKey }}"
                                data-index="{{ $stepIdx }}">
                                <div
                                    class="w-8 h-8 mx-auto flex items-center justify-center text-xs font-medium mb-2 step-circle transition-all duration-300
                                {{ $isCurrent ? 'bg-[#D37897] text-white ring-4 ring-[#F9DEE5]' : ($isCompleted ? 'bg-[#D37897] text-white' : 'bg-[#F1F0EA] text-[#C9A9B4]') }}">
                                    @if ($isCompleted && !$isCurrent)
                                        ✓
                                    @else
                                        {{ $stepIdx + 1 }}
                                    @endif
                                </div>
                                <p
                                    class="text-[11px] leading-tight step-label {{ $isCurrent ? 'text-[#33413A] font-semibold' : ($isCompleted ? 'text-[#6E8577]' : 'text-[#C9A9B4]') }}">
                                    {{ $statusLabel }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="border border-[#E9B9C6] bg-[#FBEAEE] p-6 mb-6 text-center">
                    <p class="text-2xl mb-2 text-[#B33F5C]">✕</p>
                    <p class="font-medium text-[#B33F5C] text-lg">Pesanan Dibatalkan</p>
                </div>
            @endif
        </div>

        {{-- Order Summary --}}
        <div class="border border-[#E7E4DC] p-6 mb-6">
            <h2 class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-4">Ringkasan Pesanan</h2>
            <div class="grid grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-[#6E8577] text-[11px] uppercase tracking-[0.1em]">Metode</p>
                    <p class="font-medium text-[#33413A] mt-0.5">
                        {{ $order->pickup_method === 'delivery' ? 'Diantar' : 'Ambil Sendiri' }}</p>
                </div>
                <div>
                    <p class="text-[#6E8577] text-[11px] uppercase tracking-[0.1em]">Tanggal Dibutuhkan</p>
                    <p class="font-medium text-[#33413A] mt-0.5">{{ $order->needed_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-[#6E8577] text-[11px] uppercase tracking-[0.1em]">Tanggal Order</p>
                    <p class="font-medium text-[#33413A] mt-0.5">
                        {{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                </div>
                <div>
                    <p class="text-[#6E8577] text-[11px] uppercase tracking-[0.1em]">Pembayaran</p>
                    <p id="payment-status" class="font-medium mt-0.5"
                        style="color: {{ $order->payment_verified ? '#457359' : '#9C7A1C' }};">
                        {{ $order->payment_verified ? 'Terverifikasi' : 'Menunggu Verifikasi' }}
                    </p>
                </div>
            </div>
            <div class="mt-5 pt-4 border-t border-[#E7E4DC] flex justify-between items-center">
                <span class="text-sm text-[#6E8577]">Total</span>
                <span class="text-xl font-medium text-[#33413A]">{{ $order->formatted_total }}</span>
            </div>
        </div>

        {{-- Items --}}
        <div class="border border-[#E7E4DC] p-6 mb-6">
            <h2 class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-4">Item Pesanan</h2>
            <div class="divide-y divide-[#E7E4DC]">
                @foreach ($order->items as $item)
                    <div class="flex items-start justify-between py-3.5 first:pt-0 last:pb-0">
                        <div class="flex-1 min-w-0">
                            <div
                                class="flex items-start gap-1{{ $item->custom_options ? ' cursor-pointer' : '' }}"{!! $item->custom_options
                                    ? ' onclick="this.nextElementSibling.classList.toggle(\'hidden\');this.querySelector(\'.cv\').classList.toggle(\'-rotate-180\')"'
                                    : '' !!}>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-[#33413A] truncate">
                                        {{ $item->product_name_snapshot }}</p>
                                    <p class="text-xs text-[#6E8577] mt-0.5">Rp
                                        {{ number_format($item->price_snapshot, 0, ',', '.') }} × {{ $item->quantity }}
                                    </p>
                                </div>
                                @if ($item->custom_options)
                                    <svg class="cv w-2.5 h-2.5 mt-1 flex-shrink-0 text-[#C9A9B4] transition-transform duration-200"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                @endif
                            </div>
                            @if ($item->custom_options)
                                <div class="hidden mt-2 space-y-1 pl-2.5 border-l border-[#E7E4DC]">
                                    @foreach ($item->custom_options as $label => $value)
                                        <div>
                                            <p class="text-[10px] tracking-[0.15em] uppercase text-[#6E8577]">
                                                {{ $label }}</p>
                                            @php $isFile = is_custom_option_file($value); @endphp
                                            @if ($isFile)
                                                <a href="{{ Storage::url(get_custom_option_file_path($value)) }}"
                                                    target="_blank" class="inline-block mt-1">
                                                    <img src="{{ Storage::url(get_custom_option_file_path($value)) }}"
                                                        alt="Referensi"
                                                        class="w-16 h-16 object-cover border border-[#E7E4DC] hover:opacity-80 transition">
                                                </a>
                                            @else
                                                <p class="text-xs text-[#33413A]">{{ get_custom_option_display($value) }}
                                                </p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-[#33413A] flex-shrink-0 ml-3">{{ $item->formatted_subtotal }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tracking Log --}}
        <div class="border border-[#E7E4DC] p-6 mb-6">
            <h2 class="text-[11px] tracking-[0.15em] uppercase text-[#6E8577] mb-4">Riwayat Perubahan Status</h2>
            <div id="tracking-logs" class="space-y-2">
                @if ($order->trackingLogs->isEmpty())
                    <p class="text-sm text-[#6E8577] tracking-empty">Belum ada riwayat.</p>
                @else
                    @foreach ($order->trackingLogs->sortByDesc('created_at') as $log)
                        <div class="flex items-start gap-3 p-3.5 border border-[#E7E4DC] tracking-log">
                            <div class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full" style="background-color: #D37897;"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-[#6E8577]">
                                    @if ($log->previous_status)
                                        {{ $allStatusLabels[$log->previous_status] ?? $log->previous_status }}
                                        →
                                    @endif
                                    <span
                                        class="font-medium text-[#33413A]">{{ $allStatusLabels[$log->new_status] ?? $log->new_status }}</span>
                                </p>
                                <p class="text-xs text-[#C9A9B4] mt-0.5">
                                    {{ $log->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                                @if ($log->note)
                                    <p class="text-xs text-[#C9A9B4] mt-1 italic">"{{ $log->note }}"</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        {{-- Reorder --}}
        @if (in_array($order->status, ['selesai', 'dibatalkan']) && $order->items->count() > 0)
            @php $firstItem = $order->items->first(); @endphp
            <a href="{{ route('customer.orders.create', ['product' => $firstItem->product_id, 'reorder' => $order->id]) }}"
                class="block w-full text-center border border-[#D37897] hover:bg-[#D37897] hover:text-white text-[#33413A] text-sm tracking-wide py-3.5 transition-colors duration-200 mb-3">
                Pesan Ulang Buket Ini
            </a>
        @endif

        {{-- Cancel --}}
        @if ($order->canBeCancelledByCustomer())
            <form action="{{ route('customer.orders.cancel', $order) }}" method="POST"
                onsubmit="return confirm('Yakin ingin membatalkan pesanan {{ $order->order_code }}? Stok akan dikembalikan dan pembatalan tidak dapat dibatalkan.')">
                @csrf
                <button type="submit"
                    class="block w-full text-center bg-[#D37897] text-white py-3.5 hover:bg-[#C06A85] text-sm tracking-wide transition-colors duration-200 mb-3">
                    Batalkan Pesanan
                </button>
            </form>
        @elseif($order->status === 'dikonfirmasi')
            <p class="text-xs text-[#6E8577] text-center mb-3">Pesanan telah dikonfirmasi dan tidak dapat dibatalkan.
                Hubungi admin via WhatsApp jika ada kendala.</p>
        @endif

        {{-- WhatsApp --}}
        <a id="wa-link" href="{{ $waUrl ?? '#' }}" target="_blank" rel="noopener noreferrer"
            class="flex items-center justify-center gap-2 w-full text-center bg-[#25D366] hover:bg-[#1FBE5C] text-white py-3.5 text-sm font-medium tracking-wide transition-colors duration-200">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
            Hubungi Admin via WhatsApp
        </a>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            const orderId = {{ $order->id }};
            const pollUrl = '{{ route('customer.orders.status', $order) }}';
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

            // Sama persis dengan palet di Blade, supaya warna badge yang di-update via polling tetap konsisten.
            const statusPalette = {
                'yellow': {
                    bg: '#FBF3DE',
                    text: '#9C7A1C'
                },
                'blue': {
                    bg: '#E7EEF6',
                    text: '#3C6A96'
                },
                'indigo': {
                    bg: '#EAEDF6',
                    text: '#4C5C9C'
                },
                'purple': {
                    bg: '#F1EAF6',
                    text: '#7A4C9C'
                },
                'green': {
                    bg: '#EAF3E8',
                    text: '#457359'
                },
                'red': {
                    bg: '#FBEAEE',
                    text: '#B33F5C'
                },
                'gray': {
                    bg: '#F1F0EA',
                    text: '#6E8577'
                },
            };

            const statusKeys = ['menunggu_konfirmasi', 'dikonfirmasi', 'diproses', 'dikirim', 'selesai'];

            function formatRupiah(num) {
                return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function updateProgress(status) {
                const idx = statusKeys.indexOf(status);
                if (status === 'dibatalkan') {
                    const section = document.getElementById('progress-section');
                    section.innerHTML =
                        '<div class="border border-[#E9B9C6] bg-[#FBEAEE] p-6 mb-6 text-center"><p class="text-2xl mb-2 text-[#B33F5C]">✕</p><p class="font-medium text-[#B33F5C] text-lg">Pesanan Dibatalkan</p></div>';
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

                    circle.className =
                        'w-8 h-8 mx-auto flex items-center justify-center text-xs font-medium mb-2 step-circle transition-all duration-300 ' +
                        (isCurrent ? 'bg-[#D37897] text-white ring-4 ring-[#F9DEE5]' :
                            isCompleted ? 'bg-[#D37897] text-white' :
                            'bg-[#F1F0EA] text-[#C9A9B4]');
                    circle.innerHTML = (isCompleted && !isCurrent) ? '✓' : (stepIdx + 1);

                    label.className = 'text-[11px] leading-tight step-label ' +
                        (isCurrent ? 'text-[#33413A] font-semibold' :
                            isCompleted ? 'text-[#6E8577]' :
                            'text-[#C9A9B4]');
                });
            }

            function updateStatusBadge(status, label, color) {
                const badge = document.getElementById('status-badge');
                const style = statusPalette[color] || statusPalette['gray'];
                badge.textContent = label;
                badge.className = 'inline-block px-4 py-1.5 text-sm font-medium';
                badge.style.backgroundColor = style.bg;
                badge.style.color = style.text;
            }

            function updatePaymentStatus(verified) {
                const el = document.getElementById('payment-status');
                el.textContent = verified ? 'Terverifikasi' : 'Menunggu Verifikasi';
                el.className = 'font-medium mt-0.5';
                el.style.color = verified ? '#457359' : '#9C7A1C';
            }

            function updateTrackingLogs(logs) {
                const container = document.getElementById('tracking-logs');
                if (logs.length === 0) {
                    container.innerHTML = '<p class="text-sm text-[#6E8577] tracking-empty">Belum ada riwayat.</p>';
                    return;
                }

                let html = '';
                logs.forEach(function(log) {
                    html += '<div class="flex items-start gap-3 p-3.5 border border-[#E7E4DC] tracking-log">';
                    html +=
                        '<div class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full" style="background-color: #D37897;"></div>';
                    html += '<div class="flex-1 min-w-0">';
                    html += '<p class="text-sm text-[#6E8577]">';
                    if (log.previous_status) {
                        html += (statusLabels[log.previous_status] || log.previous_status) + ' → ';
                    }
                    html += '<span class="font-medium text-[#33413A]">' + (statusLabels[log.new_status] || log
                        .new_status) + '</span>';
                    html += '</p>';
                    html += '<p class="text-xs text-[#C9A9B4] mt-0.5">' + log.created_at + '</p>';
                    if (log.note) {
                        html += '<p class="text-xs text-[#C9A9B4] mt-1 italic">"' + log.note + '"</p>';
                    }
                    html += '</div></div>';
                });
                container.innerHTML = html;
            }

            function updateWaLink(statusLabel, orderCode) {
                const waNumber = '{{ config('app.wa_admin_number', env('WA_ADMIN_NUMBER', '6281234567890')) }}';
                const text = 'Halo Admin, saya ingin konfirmasi pesanan dengan kode ' + orderCode +
                    '. Status saat ini: ' + statusLabel;
                const url = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent(text);
                document.getElementById('wa-link').href = url;
            }

            function poll() {
                fetch(pollUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        credentials: 'same-origin'
                    })
                    .then(function(res) {
                        return res.json();
                    })
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
                            indicator.innerHTML =
                                '<span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background-color: #6E8577;"></span>Selesai';
                            indicator.className =
                                'inline-flex items-center text-[11px] tracking-[0.1em] uppercase font-medium px-2.5 py-1';
                            indicator.style.backgroundColor = '#F1F0EA';
                            indicator.style.color = '#6E8577';
                        }
                    })
                    .catch(function() {});
            }

            pollingInterval = setInterval(poll, 5000);
            poll();
        })();
    </script>
@endpush
