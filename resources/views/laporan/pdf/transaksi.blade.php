<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 16px; }
    .header h1 { font-size: 18px; margin: 0; }
    .header p { margin: 2px 0; color: #666; font-size: 11px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th { background: #f3f4f6; padding: 7px 8px; text-align: left; font-size: 11px; border: 1px solid #e5e7eb; }
    td { padding: 6px 8px; border: 1px solid #e5e7eb; font-size: 11px; }
    .total-row td { font-weight: bold; background: #f9fafb; }
    .footer { margin-top: 20px; text-align: right; font-size: 10px; color: #999; }
</style>
</head>
<body>
<div class="header">
    <h1>Mini Market {{ $store ? $store->name : 'Semua Cabang' }}</h1>
    <p>Laporan Transaksi Penjualan</p>
    <p>Periode: {{ \Carbon\Carbon::parse($request->dari)->format('d/m/Y') }} s.d. {{ \Carbon\Carbon::parse($request->sampai)->format('d/m/Y') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>No. Invoice</th>
            <th>Kasir</th>
            @if(!$store)<th>Cabang</th>@endif
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $i => $t)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $t->created_at->format('d/m/Y H:i') }}</td>
            <td>{{ $t->invoice_number }}</td>
            <td>{{ $t->user->name }}</td>
            @if(!$store)<td>{{ $t->store->name }}</td>@endif
            <td>Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="{{ $store ? 4 : 5 }}">TOTAL</td>
            <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
        </tr>
    </tbody>
</table>

<div class="footer">
    Dicetak: {{ now()->format('d/m/Y H:i') }} oleh {{ auth()->user()->name }}
</div>
</body>
</html>