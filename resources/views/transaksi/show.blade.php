@extends('layouts.app')
@section('title', 'Detail Transaksi')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Detail Transaksi</h1>
        <p class="text-gray-500 text-sm mt-1">{{ $transaction->invoice_number }}</p>
    </div>
    <a href="{{ route('transaksi.index') }}"
        class="text-sm text-gray-500 hover:text-gray-700">
        ← Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Detail Transaksi --}}
    <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200 p-5">
        <h2 class="font-semibold text-gray-700 mb-4">Item Pembelian</h2>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="pb-3 font-medium">Produk</th>
                    <th class="pb-3 font-medium text-right">Harga</th>
                    <th class="pb-3 font-medium text-right">Qty</th>
                    <th class="pb-3 font-medium text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr class="border-b border-gray-50">
                    <td class="py-3 text-gray-800">{{ $item->product->name }}</td>
                    <td class="py-3 text-right text-gray-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="py-3 text-right text-gray-600">{{ $item->quantity }}</td>
                    <td class="py-3 text-right font-medium text-gray-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Ringkasan --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-700 mb-4">Ringkasan</h2>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Belanja</span>
                    <span class="font-bold text-gray-800">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Dibayar</span>
                    <span class="text-gray-700">Rp {{ number_format($transaction->paid_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-100 pt-2">
                    <span class="text-gray-500">Kembalian</span>
                    <span class="font-bold text-green-600">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <h2 class="font-semibold text-gray-700 mb-4">Informasi</h2>
            <div class="space-y-2 text-sm">
                <div>
                    <span class="text-gray-500">Kasir</span>
                    <p class="text-gray-800 font-medium">{{ $transaction->user->name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Cabang</span>
                    <p class="text-gray-800 font-medium">{{ $transaction->store->name }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Tanggal</span>
                    <p class="text-gray-800 font-medium">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Status</span>
                    <p>
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $transaction->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $transaction->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection