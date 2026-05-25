@extends('layouts.app')
@section('title', 'Riwayat Transaksi')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Transaksi</h1>
        <p class="text-gray-500 text-sm mt-1">Daftar semua transaksi di toko ini</p>
    </div>
    @if(auth()->user()->hasRole('kasir'))
    <a href="{{ route('transaksi.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
        + Transaksi Baru
    </a>
    @endif
</div>

<div class="bg-white rounded-xl border border-gray-200">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">No. Invoice</th>
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Kasir</th>
                    <th class="px-5 py-3 font-medium text-right">Total</th>
                    <th class="px-5 py-3 font-medium">Status</th>
                    <th class="px-5 py-3 font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $t->invoice_number }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $t->user->name }}</td>
                    <td class="px-5 py-3 text-right font-medium text-gray-800">
                        Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-3">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $t->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $t->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('transaksi.show', $t) }}"
                            class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                        Belum ada transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($transactions->hasPages())
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection