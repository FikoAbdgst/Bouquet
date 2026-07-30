@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Riwayat Pesanan</h1>
        <p class="text-slate-400 mt-1">Semua pesanan Anda ada di sini.</p>
    </div>

    @if($orders->isEmpty())
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-16 text-center shadow-sm">
            <p class="text-5xl mb-4">📦</p>
            <p class="text-slate-500 text-lg">Belum ada pesanan.</p>
            <a href="{{ route('customer.catalog') }}" class="inline-block mt-4 bg-rose-400 text-white px-6 py-2.5 rounded-xl hover:bg-rose-500 font-medium transition-all duration-200 shadow-sm hover:shadow-md text-sm">
                Mulai Belanja
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl border border-rose-100 p-6 shadow-sm hover:shadow-md transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Kode Pesanan</p>
                            <p class="font-bold text-slate-800 text-lg mt-0.5">{{ $order->order_code }}</p>
                        </div>
                        <span class="mt-2 sm:mt-0 inline-block px-3.5 py-1 rounded-full text-xs font-semibold bg-{{ $order->status_color }}-100 text-{{ $order->status_color }}-700 self-start">
                            {{ $order->status_label }}
                        </span>
                    </div>

                    <div class="space-y-1.5 text-sm text-slate-500 mb-4">
                        @foreach($order->items as $item)
                            <div class="flex justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex items-start gap-1{{ $item->custom_options ? ' cursor-pointer' : '' }}"{{ $item->custom_options ? ' onclick="this.nextElementSibling.classList.toggle(\'hidden\');this.querySelector(\'.cv\').classList.toggle(\'-rotate-180\')"' : '' }}>
                                        <span class="truncate">{{ $item->product_name_snapshot }} × {{ $item->quantity }}</span>
                                        @if($item->custom_options)
                                            <svg class="cv w-2.5 h-2.5 mt-0.5 flex-shrink-0 text-[#C9A9B4] transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        @endif
                                    </div>
                                    @if($item->custom_options)
                                        <div class="hidden mt-1.5 space-y-1 pl-2.5 border-l border-[#EFD3DE]">
                                            @foreach($item->custom_options as $label => $value)
                                                <div>
                                                    <p class="text-[10px] tracking-[0.15em] uppercase text-[#6E8577]">{{ $label }}</p>
                                                    <p class="text-xs text-[#33413A]">{{ is_array($value) ? implode(', ', $value) : $value }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                <span class="font-medium text-slate-700 whitespace-nowrap ml-3 flex-shrink-0">{{ $item->formatted_subtotal }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pt-4 border-t border-rose-100 gap-3">
                        <span class="text-sm text-slate-400">{{ $order->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</span>
                        <div class="flex items-center space-x-3">
                            <span class="font-bold text-slate-800">{{ $order->formatted_total }}</span>
                            <a href="{{ route('customer.orders.show', $order) }}" class="px-4 py-1.5 bg-rose-50 text-rose-600 rounded-xl text-sm font-semibold hover:bg-rose-100 transition">
                                Lacak →
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
if (typeof CartStorage !== 'undefined' && CartStorage.get().length > 0) {
    CartStorage.clear();
}
</script>
@endpush
