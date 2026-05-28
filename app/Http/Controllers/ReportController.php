<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Stock;
use App\Models\Store;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('laporan.index');
    }

    public function transaksi(Request $request)
    {
        $stores = Store::all();

        // Kalau belum ada input tanggal, tampilkan form kosong dulu
        if (!$request->has('dari')) {
            return view('laporan.transaksi', compact('stores'));
        }

        $request->validate([
            'dari'   => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ]);

        $user    = auth()->user();
        $storeId = $user->hasRole('owner') ? $request->store_id : $user->store_id;

        $query = Transaction::with(['user', 'store', 'items.product'])
            ->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ])
            ->where('status', 'completed');

        if ($storeId) $query->where('store_id', $storeId);

        $transactions = $query->latest()->get();
        $total        = $transactions->sum('total_amount');

        return view('laporan.transaksi', compact('transactions', 'total', 'request', 'stores'));
    }

    public function cetakTransaksi(Request $request)
    {
        $request->validate([
            'dari'   => 'required|date',
            'sampai' => 'required|date|after_or_equal:dari',
        ]);

        $user    = auth()->user();
        $storeId = $user->hasRole('owner') ? $request->store_id : $user->store_id;

        $query = Transaction::with(['user', 'store', 'items.product'])
            ->whereBetween('created_at', [
                $request->dari . ' 00:00:00',
                $request->sampai . ' 23:59:59',
            ])
            ->where('status', 'completed');

        if ($storeId) $query->where('store_id', $storeId);

        $transactions = $query->latest()->get();
        $total        = $transactions->sum('total_amount');
        $store        = $storeId ? Store::find($storeId) : null;

        $pdf = Pdf::loadView('laporan.pdf.transaksi', compact('transactions', 'total', 'request', 'store'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-transaksi-' . $request->dari . '-sd-' . $request->sampai . '.pdf');
    }

    public function stok(Request $request)
    {
        $user    = auth()->user();
        $storeId = $user->hasRole('owner') ? $request->store_id : $user->store_id;
        $stores  = Store::all();

        $query = Stock::with(['product', 'store'])->orderBy('quantity');
        if ($storeId) $query->where('store_id', $storeId);

        $stocks = $query->get();

        return view('laporan.stok', compact('stocks', 'stores'));
    }

    public function cetakStok(Request $request)
    {
        $user    = auth()->user();
        $storeId = $user->hasRole('owner') ? $request->store_id : $user->store_id;

        $query = Stock::with(['product', 'store'])->orderBy('quantity');
        if ($storeId) $query->where('store_id', $storeId);

        $stocks = $query->get();
        $store  = $storeId ? Store::find($storeId) : null;

        $pdf = Pdf::loadView('laporan.pdf.stok', compact('stocks', 'store'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('laporan-stok.pdf');
    }
}