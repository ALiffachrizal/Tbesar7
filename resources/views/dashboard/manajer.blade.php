@extends('layouts.app')
@section('title', 'Dashboard Toko')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Toko</h1>
    <p class="text-gray-500 text-sm mt-1">{{ $store->name }} — {{ $store->city }}</p>
    <p class="text-xs text-gray-400 mt-0.5">{{ now()->format('l, d F Y') }}</p>
</div>

{{-- Kartu Ringkasan --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
        <div class="bg-blue-100 p-3 rounded-xl">
            <span class="text-2xl">💰</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Penjualan Hari Ini</p>
            <p class="text-xl font-bold text-gray-800">Rp {{ number_format($todaySales, 0, ',', '.') }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
        <div class="bg-green-100 p-3 rounded-xl">
            <span class="text-2xl">🧾</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
            <p class="text-xl font-bold text-gray-800">{{ $todayCount }}</p>
        </div>
    </div>
    <div class="bg-white rounded-xl border border-gray-200 p-5 flex items-center gap-4">
        <div class="bg-red-100 p-3 rounded-xl">
            <span class="text-2xl">⚠️</span>
        </div>
        <div>
            <p class="text-sm text-gray-500">Stok Menipis</p>
            <p class="text-xl font-bold text-red-600">{{ $lowStocks->count() }}</p>
        </div>
    </div>
</div>

{{-- Transaksi Terbaru --}}
<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <h2 class="text-base font-semibold text-gray-700 mb-4">🧾 Transaksi Terbaru</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500 border-b border-gray-100">
                <th class="pb-3 font-medium">Invoice</th>
                <th class="pb-3 font-medium">Kasir</th>
                <th class="pb-3 font-medium">Waktu</th>
                <th class="pb-3 font-medium text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTransactions as $t)
            <tr class="border-b border-gray-50 hover:bg-gray-50">
                <td class="py-3 font-mono text-xs text-gray-700">{{ $t->invoice_number }}</td>
                <td class="py-3 text-gray-600">{{ $t->user->name }}</td>
                <td class="py-3 text-gray-500">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                <td class="py-3 text-right font-medium text-gray-800">
                    Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="py-8 text-center text-gray-400">Belum ada transaksi hari ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Stok Menipis --}}
@if($lowStocks->count() > 0)
<div class="bg-white rounded-xl border border-red-200 p-5">
    <h2 class="text-base font-semibold text-red-600 mb-4">⚠️ Stok Menipis</h2>
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left text-gray-500 border-b border-gray-100">
                <th class="pb-3 font-medium">Produk</th>
                <th class="pb-3 font-medium text-right">Stok</th>
                <th class="pb-3 font-medium text-right">Minimum</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStocks as $s)
            <tr class="border-b border-gray-50 hover:bg-red-50">
                <td class="py-3 text-gray-800">{{ $s->product->name }}</td>
                <td class="py-3 text-right font-bold text-red-600">{{ $s->quantity }}</td>
                <td class="py-3 text-right text-gray-500">{{ $s->min_quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@else
<div class="bg-green-50 border border-green-200 rounded-xl p-4 text-center">
    <p class="text-green-600 font-medium">✅ Semua stok barang dalam kondisi aman</p>
</div>
@endif
@endsection