@extends('layouts.app')
@section('title', 'Tambah Produk')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Tambah Produk</h1>
        <p class="text-gray-500 text-sm mt-1">Produk baru akan otomatis tersedia di semua cabang</p>
    </div>
    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
</div>

<div class="max-w-lg bg-white rounded-xl border border-gray-200 p-6">
    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <div class="space-y-4">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Barcode <span class="text-gray-400">(opsional)</span></label>
                <input type="text" name="barcode" value="{{ old('barcode') }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="Contoh: 8990000001">
                @error('barcode') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="Contoh: Indomie Goreng">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                <input type="text" name="category" value="{{ old('category') }}" required
                    list="kategori-list"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                    placeholder="Contoh: Makanan">
                <datalist id="kategori-list">
                    <option value="Makanan">
                    <option value="Minuman">
                    <option value="Snack">
                    <option value="Kebersihan">
                    <option value="Dapur">
                </datalist>
                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                <input type="text" name="unit" value="{{ old('unit', 'pcs') }}" required
                    list="satuan-list"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                <datalist id="satuan-list">
                    <option value="pcs">
                    <option value="botol">
                    <option value="kg">
                    <option value="liter">
                    <option value="karung">
                    <option value="dus">
                    <option value="pack">
                </datalist>
                @error('unit') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli</label>
                    <input type="number" name="buy_price" value="{{ old('buy_price', 0) }}" required min="0"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @error('buy_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual</label>
                    <input type="number" name="sell_price" value="{{ old('sell_price', 0) }}" required min="0"
                        class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    @error('sell_price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

        </div>

        <button type="submit"
            class="mt-6 w-full bg-blue-600 text-white py-2.5 rounded-lg text-sm font-medium hover:bg-blue-700">
            Simpan Produk
        </button>
    </form>
</div>
@endsection