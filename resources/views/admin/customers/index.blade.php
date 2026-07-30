@extends('layouts.admin')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-medium text-[#33413A]">Manajemen Pelanggan</h1>
    </div>

    <form action="{{ route('admin.customers.index') }}" method="GET" class="mb-6 max-w-md">
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no HP..."
                   class="flex-1 border-0 border-b border-[#EFD3DE] focus:border-[#D37897] focus:ring-0 px-0 py-2 text-sm bg-transparent outline-none transition-colors placeholder-[#C9A9B4]">
            <button type="submit" class="border border-[#D37897] bg-[#D37897] text-white hover:bg-[#D37897]/90 text-sm tracking-wide transition-colors duration-200 px-4 py-2 flex-shrink-0">
                Cari
            </button>
        </div>
    </form>

    @if($customers->isEmpty())
        <div class="border border-[#EFD3DE] text-center py-16 px-6">
            <p class="text-[#33413A]">Belum ada pelanggan terdaftar.</p>
        </div>
    @else
        <div class="border border-[#EFD3DE]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-[#EFD3DE]">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Nama</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Email</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Telepon</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Terdaftar</th>
                            <th class="px-4 py-3 text-right text-[11px] font-medium text-[#6E8577] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#EFD3DE]">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-[#F9DEE5]/50 transition-colors duration-150">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 border border-[#EFD3DE] text-[#6E8577] text-xs font-medium tracking-wide">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>
                                        <span class="text-sm text-[#33413A]">{{ $customer->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-[#33413A]">{{ $customer->email }}</td>
                                <td class="px-4 py-3 text-sm text-[#33413A]">{{ $customer->phone }}</td>
                                <td class="px-4 py-3">
                                    @if($customer->is_active)
                                        <span class="inline-block px-2.5 py-0.5 text-xs tracking-wide border border-green-600 text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 text-xs tracking-wide border border-red-400 text-red-500">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-[#33413A]">
                                    {{ $customer->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <form action="{{ route('admin.customers.toggle-active', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($customer->is_active)
                                            <button type="submit" onclick="return confirm('Nonaktifkan akun {{ $customer->name }}?')"
                                                    class="text-xs tracking-wide text-red-600 border-b border-red-400 pb-0.5 hover:pb-1 transition-all">
                                                Nonaktifkan
                                            </button>
                                        @else
                                            <button type="submit" onclick="return confirm('Aktifkan kembali akun {{ $customer->name }}?')"
                                                    class="text-xs tracking-wide text-green-600 border-b border-green-400 pb-0.5 hover:pb-1 transition-all">
                                                Aktifkan
                                            </button>
                                        @endif
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-6">
            {{ $customers->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection
