<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 16px; }
    .header h1 { font-size: 18px; margin: 0; }
    table { width: 100%; border-collapse: collapse; }
    th { background: #f3f4f6; padding: 7px 8px; text-align: left; border: 1px solid #e5e7eb; font-size: 11px; }
    td { padding: 6px 8px; border: 1px solid #e5e7eb; font-size: 11px; }
    .low { color: #dc2626; font-weight: bold; }
    .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #999; }
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Stok Barang</h1>
    <p>{{ $store ? $store->name : 'Semua Cabang' }}</p>
    <p>Per tanggal: {{ now()->format('d/m/Y') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Barcode</th>
            <th>Nama Produk</th>
            <th>Kategori</th>
            @if(!$store)<th>Cabang</th>@endif
            <th>Stok</th>
            <th>Min. Stok</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stocks as $i => $s)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $s->product->barcode }}</td>
            <td>{{ $s->product->name }}</td>
            <td>{{ $s->product->category }}</td>
            @if(!$store)<td>{{ $s->store->name }}</td>@endif
            <td class="{{ $s->quantity <= $s->min_quantity ? 'low' : '' }}">{{ $s->quantity }}</td>
            <td>{{ $s->min_quantity }}</td>
            <td class="{{ $s->quantity <= $s->min_quantity ? 'low' : '' }}">
                {{ $s->quantity <= $s->min_quantity ? 'MENIPIS' : 'Aman' }}
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    Dicetak: {{ now()->format('d/m/Y H:i') }} oleh {{ auth()->user()->name }}
</div>
</body>
</html>