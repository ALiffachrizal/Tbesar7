@extends('layouts.app')
@section('title', 'Laporan Stok')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Stok Barang</h1>
        <p class="text-gray-500 text-sm mt-1">Kondisi stok barang saat ini</p>
    </div>
    <a href="{{ route('laporan.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
</div>

@if(auth()->user()->hasRole('owner'))
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <form method="GET" action="{{ route('laporan.stok') }}">
        <div class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
                <select name="store_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Semua Cabang</option>
                    @foreach($stores as $store)
                    <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                        {{ $store->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                Tampilkan
            </button>
        </div>
    </form>
</div>
@endif

<div class="bg-white rounded-xl border border-gray-200">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-sm font-medium text-gray-700">Per tanggal: {{ now()->format('d/m/Y') }}</p>
        <a href="{{ route('laporan.cetakStok', request()->all()) }}"
            target="_blank"
            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700">
            🖨️ Cetak PDF
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">No.</th>
                    <th class="px-5 py-3 font-medium">Produk</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    @if(auth()->user()->hasRole('owner'))
                    <th class="px-5 py-3 font-medium">Cabang</th>
                    @endif
                    <th class="px-5 py-3 font-medium text-right">Stok</th>
                    <th class="px-5 py-3 font-medium text-right">Min. Stok</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $i => $s)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 text-gray-800 font-medium">{{ $s->product->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $s->product->category }}</td>
                    @if(auth()->user()->hasRole('owner'))
                    <td class="px-5 py-3 text-gray-500">{{ $s->store->name }}</td>
                    @endif
                    <td class="px-5 py-3 text-right font-bold
                        {{ $s->quantity <= $s->min_quantity ? 'text-red-600' : 'text-gray-800' }}">
                        {{ $s->quantity }}
                    </td>
                    <td class="px-5 py-3 text-right text-gray-500">{{ $s->min_quantity }}</td>
                    <td class="px-5 py-3">
                        @if($s->quantity <= $s->min_quantity)
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Menipis</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Aman</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">Tidak ada data stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection