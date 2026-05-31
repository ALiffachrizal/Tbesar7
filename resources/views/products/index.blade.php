@extends('layouts.app')
@section('title', 'Daftar Produk')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Daftar Produk</h1>
        <p class="text-gray-500 text-sm mt-1">Kelola semua produk mini market</p>
    </div>
    <a href="{{ route('products.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
        + Tambah Produk
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">Barcode</th>
                    <th class="px-5 py-3 font-medium">Nama Produk</th>
                    <th class="px-5 py-3 font-medium">Kategori</th>
                    <th class="px-5 py-3 font-medium">Satuan</th>
                    <th class="px-5 py-3 font-medium text-right">Harga Beli</th>
                    <th class="px-5 py-3 font-medium text-right">Harga Jual</th>
                    <th class="px-5 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-500">
                        {{ $product->barcode ?? '-' }}
                    </td>
                    <td class="px-5 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                            {{ $product->category }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-gray-500">{{ $product->unit }}</td>
                    <td class="px-5 py-3 text-right text-gray-600">
                        Rp {{ number_format($product->buy_price, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3 text-right font-medium text-gray-800">
                        Rp {{ number_format($product->sell_price, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            <a href="{{ route('products.edit', $product) }}"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Edit</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}"
                                onsubmit="return confirm('Yakin hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-500 hover:text-red-700 text-xs font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-10 text-center text-gray-400">
                        Belum ada produk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection