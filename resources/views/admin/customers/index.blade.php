@extends('layouts.admin')

@section('content')
<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <h1 class="text-2xl font-bold text-pink-800">Manajemen Pelanggan</h1>
    </div>

    {{-- Search --}}
    <form action="{{ route('admin.customers.index') }}" method="GET" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, atau no HP..."
                   class="flex-1 border border-pink-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-pink-500 focus:border-pink-500 sm:text-sm">
            <button type="submit" class="px-4 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 font-medium transition shadow-sm">
                Cari
            </button>
        </div>
    </form>

    @if($customers->isEmpty())
        <div class="bg-white rounded-lg shadow-sm border border-pink-200 p-12 text-center">
            <p class="text-4xl mb-4">👤</p>
            <p class="text-pink-600">Belum ada pelanggan terdaftar.</p>
        </div>
    @else
        <div class="bg-white rounded-lg shadow-sm border border-pink-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-pink-200">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Nama</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Telepon</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-pink-600 uppercase">Terdaftar</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-pink-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-pink-100">
                        @foreach($customers as $customer)
                            <tr class="hover:bg-pink-50 transition">
                                <td class="px-4 py-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-pink-200 text-pink-700 text-sm font-bold">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                                        </span>
                                        <span class="text-sm font-medium text-pink-800">{{ $customer->name }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-pink-700">{{ $customer->email }}</td>
                                <td class="px-4 py-3 text-sm text-pink-700">{{ $customer->phone }}</td>
                                <td class="px-4 py-3">
                                    @if($customer->is_active)
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-pink-600">
                                    {{ $customer->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <form action="{{ route('admin.customers.toggle-active', $customer) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        @if($customer->is_active)
                                            <button type="submit" onclick="return confirm('Nonaktifkan akun {{ $customer->name }}?')"
                                                    class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-sm font-medium hover:bg-red-200 transition">
                                                Nonaktifkan
                                            </button>
                                        @else
                                            <button type="submit" onclick="return confirm('Aktifkan kembali akun {{ $customer->name }}?')"
                                                    class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-sm font-medium hover:bg-green-200 transition">
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
