@extends('layouts.app')
@section('title', 'Dashboard Owner')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Owner</h1>
    <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}. Pantau semua cabang di sini.</p>
</div>

{{-- Kartu Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Total Penjualan Hari Ini</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
        <p class="text-xs text-gray-400 mt-1">Semua cabang</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Jumlah Cabang Aktif</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $stores->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Cabang terdaftar</p>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Stok Menipis</p>
        <p class="text-2xl font-bold text-red-600 mt-1">{{ $lowStocks->count() }}</p>
        <p class="text-xs text-gray-400 mt-1">Item di semua cabang</p>
    </div>
</div>

{{-- Penjualan Per Cabang --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <h2 class="text-base font-semibold text-gray-700 mb-4">Penjualan Bulan Ini per Cabang</h2>
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
                    <td class="py-3 text-gray-800">{{ $s['name'] }}</td>
                    <td class="py-3 text-gray-500">{{ $s['city'] }}</td>
                    <td class="py-3 text-right text-gray-700">{{ $s['count'] }}</td>
                    <td class="py-3 text-right font-medium text-gray-800">Rp {{ number_format($s['total'], 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Alert Stok Menipis --}}
@if($lowStocks->count() > 0)
<div class="bg-white rounded-xl border border-red-200 p-5">
    <h2 class="text-base font-semibold text-red-600 mb-4">Peringatan — Stok Menipis</h2>
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
@endif
@endsection