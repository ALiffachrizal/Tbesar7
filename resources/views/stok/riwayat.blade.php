@extends('layouts.app')
@section('title', 'Riwayat Mutasi Stok')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Mutasi Stok</h1>
        <p class="text-gray-500 text-sm mt-1">Log semua pergerakan stok barang</p>
    </div>
    <a href="{{ route('stok.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
</div>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Produk</th>
                    <th class="px-5 py-3 font-medium">Tipe</th>
                    <th class="px-5 py-3 font-medium text-right">Qty</th>
                    <th class="px-5 py-3 font-medium text-right">Sebelum</th>
                    <th class="px-5 py-3 font-medium text-right">Sesudah</th>
                    <th class="px-5 py-3 font-medium">Oleh</th>
                    <th class="px-5 py-3 font-medium">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movements as $m)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-500">{{ $m->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3 text-gray-800 font-medium">{{ $m->product->name }}</td>
                    <td class="px-5 py-3">
                        @if($m->type === 'in')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Masuk</span>
                        @elseif($m->type === 'out')
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Keluar</span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">Opname</span>
                        @endif
                    </td>
                    <td class="px-5 py-3 text-right font-medium text-gray-800">{{ $m->quantity }}</td>
                    <td class="px-5 py-3 text-right text-gray-500">{{ $m->quantity_before }}</td>
                    <td class="px-5 py-3 text-right text-gray-500">{{ $m->quantity_after }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $m->user->name }}</td>
                    <td class="px-5 py-3 text-gray-500 text-xs">{{ $m->note }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-gray-400">Belum ada riwayat mutasi stok</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($movements->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $movements->links() }}
    </div>
    @endif
</div>
@endsection