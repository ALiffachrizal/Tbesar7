@extends('layouts.app')
@section('title', 'Dashboard Owner')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Owner</h1>
    <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}. Pantau semua cabang di sini.</p>
    <p class="text-xs text-gray-400 mt-0.5">{{ now()->format('l, d F Y') }}</p>
</div>

{{-- Kartu Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
        <div class="bg-blue-100 p-3 rounded-xl">
            <span class="text-2xl">💰</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Total Penjualan Hari Ini</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400">Semua cabang</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
        <div class="bg-green-100 p-3 rounded-xl">
            <span class="text-2xl">🏪</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Jumlah Cabang Aktif</p>
            <p class="text-xl font-bold text-gray-800">{{ $stores->count() }}</p>
            <p class="text-xs text-gray-400">Cabang terdaftar</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
        <div class="bg-red-100 p-3 rounded-xl">
            <span class="text-2xl">⚠️</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Stok Menipis</p>
            <p class="text-xl font-bold text-red-600">{{ $lowStocks->count() }}</p>
            <p class="text-xs text-gray-400">Item di semua cabang</p>
        </div>
    </div>
</div>

{{-- Penjualan Per Cabang --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <h2 class="text-base font-semibold text-gray-700 mb-4">📈 Penjualan Bulan Ini per Cabang</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="pb-3 font-medium">Nama Cabang</th>
                    <th class="pb-3 font-medium">Kota</th>
                    <th class="pb-3 font-medium text-right">Jumlah Transaksi</th>
                    <th class="pb-3 font-medium text-right">Total Penjualan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($salesPerStore as $s)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="py-3 text-gray-800 font-medium">{{ $s['name'] }}</td>
                    <td class="py-3 text-gray-500">{{ $s['city'] }}</td>
                    <td class="py-3 text-right text-gray-700">{{ $s['count'] }}</td>
                    <td class="py-3 text-right font-semibold text-gray-800">
                        Rp {{ number_format($s['total'], 0, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-blue-50">
                    <td colspan="2" class="py-3 px-2 font-bold text-blue-700">TOTAL SEMUA CABANG</td>
                    <td class="py-3 text-right font-bold text-blue-700">
                        {{ $salesPerStore->sum('count') }}
                    </td>
                    <td class="py-3 text-right font-bold text-blue-700">
                        Rp {{ number_format($salesPerStore->sum('total'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- Alert Stok Menipis --}}
@if($lowStocks->count() > 0)
<div class="bg-white rounded-xl border border-red-200 p-5">
    <h2 class="text-base font-semibold text-red-600 mb-4">⚠️ Peringatan — Stok Menipis di Semua Cabang</h2>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="pb-3 font-medium">Produk</th>
                    <th class="pb-3 font-medium">Cabang</th>
                    <th class="pb-3 font-medium text-right">Stok</th>
                    <th class="pb-3 font-medium text-right">Minimum</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lowStocks as $s)
                <tr class="border-b border-gray-50 hover:bg-red-50">
                    <td class="py-3 text-gray-800">{{ $s->product->name }}</td>
                    <td class="py-3 text-gray-500">{{ $s->store->name }}</td>
                    <td class="py-3 text-right font-bold text-red-600">{{ $s->quantity }}</td>
                    <td class="py-3 text-right text-gray-500">{{ $s->min_quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@else
<div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
    <p class="text-green-600 font-medium">✅ Semua stok barang dalam kondisi aman</p>
</div>
@endif
@endsection