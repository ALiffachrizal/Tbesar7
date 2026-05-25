@extends('layouts.app')
@section('title', 'Stok Barang')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Stok Barang</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar stok barang di toko ini</p>
    </div>
    @if(auth()->user()->hasRole('gudang'))
    <a href="{{ route('stok.masuk') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
        + Barang Masuk
    </a>
    @endif
</div>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">Produk</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Satuan</th>
                    <th class="px-5 py-3 font-medium text-right">Stok</th>
                    <th class="px-5 py-3 font-medium text-right">Min. Stok</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stocks as $s)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-800 font-medium">{{ $s->product->name }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $s->product->category }}</td>
                    <td class="px-5 py-3 text-gray-500">{{ $s->product->unit }}</td>
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
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">Belum ada data stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stocks->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $stocks->links() }}
    </div>
    @endif
</div>

<div class="mt-4">
    <a href="{{ route('stok.riwayat') }}" class="text-sm text-blue-600 hover:text-blue-800">
        Lihat Riwayat Mutasi Stok →
    </a>
</div>
@endsection