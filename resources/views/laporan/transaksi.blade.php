@extends('layouts.app')
@section('title', 'Laporan Transaksi')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Laporan Transaksi</h1>
        <p class="text-gray-500 text-sm mt-1">Filter laporan berdasarkan tanggal</p>
    </div>
    <a href="{{ route('laporan.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
</div>

<div class="bg-white rounded-xl border border-gray-200 p-5 mb-6">
    <form method="GET" action="{{ route('laporan.transaksi') }}">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @if(auth()->user()->hasRole('owner'))
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cabang</label>
                <select name="store_id"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                    <option value="">Semua Cabang</option>
                    @foreach($stores as $store)
                        <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                            {{ $store->name }} — {{ $store->city }}
                        </option>
                        @endforeach
                </select>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari', date('Y-m-01')) }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai', date('Y-m-d')) }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <div>
                <button type="submit"
                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-blue-700">
                    Tampilkan
                </button>
            </div>
        </div>
    </form>
</div>

@isset($transactions)
<div class="bg-white rounded-xl border border-gray-200 mb-4">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-700">
                Periode: {{ \Carbon\Carbon::parse(request('dari'))->format('d/m/Y') }}
                s.d. {{ \Carbon\Carbon::parse(request('sampai'))->format('d/m/Y') }}
            </p>
            <p class="text-xs text-gray-400 mt-0.5">{{ $transactions->count() }} transaksi ditemukan</p>
        </div>
        <a href="{{ route('laporan.cetakTransaksi', request()->all()) }}"
            target="_blank"
            class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-700">
            🖨️ Cetak PDF
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 border-b border-gray-100">
                    <th class="px-5 py-3 font-medium">No.</th>
                    <th class="px-5 py-3 font-medium">Tanggal</th>
                    <th class="px-5 py-3 font-medium">Invoice</th>
                    <th class="px-5 py-3 font-medium">Kasir</th>
                    @if(auth()->user()->hasRole('owner'))
                    <th class="px-5 py-3 font-medium">Cabang</th>
                    @endif
                    <th class="px-5 py-3 font-medium text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $i => $t)
                <tr class="border-b border-gray-50 hover:bg-gray-50">
                    <td class="px-5 py-3 text-gray-500">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-5 py-3 font-mono text-xs text-gray-700">{{ $t->invoice_number }}</td>
                    <td class="px-5 py-3 text-gray-600">{{ $t->user->name }}</td>
                    @if(auth()->user()->hasRole('owner'))
                    <td class="px-5 py-3 text-gray-600">{{ $t->store->name }}</td>
                    @endif
                    <td class="px-5 py-3 text-right font-medium text-gray-800">
                        Rp {{ number_format($t->total_amount, 0, ',', '.') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-gray-400">
                        Tidak ada transaksi pada periode ini
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($transactions->count() > 0)
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="{{ auth()->user()->hasRole('owner') ? 5 : 4 }}"
                        class="px-5 py-3 font-bold text-gray-700 text-right">TOTAL</td>
                    <td class="px-5 py-3 font-bold text-gray-800 text-right">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endisset
@endsection