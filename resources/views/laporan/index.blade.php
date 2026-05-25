@extends('layouts.app')
@section('title', 'Laporan')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan</h1>
    <p class="text-gray-500 text-sm mt-1">Pilih jenis laporan yang ingin dilihat</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <a href="{{ route('laporan.transaksi') }}"
        class="bg-white rounded-xl border border-gray-200 p-6 hover:border-blue-300 hover:shadow-sm transition">
        <div class="text-blue-600 text-3xl mb-3">🧾</div>
        <h2 class="text-base font-semibold text-gray-800">Laporan Transaksi</h2>
        <p class="text-sm text-gray-500 mt-1">Lihat dan cetak laporan penjualan berdasarkan rentang tanggal</p>
    </a>

    <a href="{{ route('laporan.stok') }}"
        class="bg-white rounded-xl border border-gray-200 p-6 hover:border-blue-300 hover:shadow-sm transition">
        <div class="text-green-600 text-3xl mb-3">📦</div>
        <h2 class="text-base font-semibold text-gray-800">Laporan Stok Barang</h2>
        <p class="text-sm text-gray-500 mt-1">Lihat dan cetak kondisi stok barang saat ini</p>
    </a>
</div>
@endsection