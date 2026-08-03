@extends('layouts.app')

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
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-10">
            <span class="text-xs tracking-[0.3em] uppercase text-[#6E8577]">Akun Saya</span>
            <h2 class="font-display text-2xl sm:text-3xl font-medium text-[#33413A] mt-2">Riwayat Pesanan</h2>
            <p class="text-sm text-[#6E8577] mt-1">Semua pesanan Anda ada di sini. Status ter-update otomatis.</p>
        </div>

        <div id="orders-container">
            @include('customer.orders-list', ['orders' => $orders])
        </div>
    </div>
@endsection

@if (session('clear_cart'))
    <script>
        if (typeof CartStorage !== 'undefined') {
            CartStorage.clear();
        }
    </script>
@endif

@push('scripts')
    <script>
        (function() {
            const container = document.getElementById('orders-container');
            if (!container) return;

            let inflight = false;

            async function poll() {
                if (inflight) return;
                inflight = true;

                try {
                    const res = await fetch(window.location.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html',
                        },
                        credentials: 'same-origin',
                    });
                    if (res.ok) {
                        const html = await res.text();
                        container.innerHTML = html;
                    }
                } catch (e) {
                    // abaikan error sementara (offline, dll.)
                } finally {
                    inflight = false;
                }
            }

            setInterval(poll, 5000);
        })();
    </script>
@endpush
