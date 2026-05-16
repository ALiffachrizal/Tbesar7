<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Transaction;
use App\Models\Stock;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('owner')) {
            return redirect()->route('owner.dashboard');
        }

        return redirect()->route('toko.dashboard');
    }

    public function owner()
    {
        $stores = Store::withCount('transactions')
            ->with(['transactions' => function ($q) {
                $q->whereMonth('created_at', now()->month);
            }])
            ->get();

        // Total penjualan per cabang bulan ini
        $salesPerStore = Store::all()->map(function ($store) {
            return [
                'name'  => $store->name,
                'city'  => $store->city,
                'total' => Transaction::where('store_id', $store->id)
                    ->whereMonth('created_at', now()->month)
                    ->where('status', 'completed')
                    ->sum('total_amount'),
                'count' => Transaction::where('store_id', $store->id)
                    ->whereMonth('created_at', now()->month)
                    ->count(),
            ];
        });

        // Stok menipis (semua cabang)
        $lowStocks = Stock::with(['product', 'store'])
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->get();

        // Total penjualan hari ini (semua cabang)
        $todaySales = Transaction::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        return view('dashboard.owner', compact('salesPerStore', 'lowStocks', 'todaySales', 'stores'));
    }

    public function manajer()
    {
        $user  = auth()->user();
        $store = $user->store;

        $todaySales = Transaction::where('store_id', $store->id)
            ->whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        $todayCount = Transaction::where('store_id', $store->id)
            ->whereDate('created_at', today())
            ->count();

        $lowStocks = Stock::with('product')
            ->where('store_id', $store->id)
            ->whereColumn('quantity', '<=', 'min_quantity')
            ->get();

        $recentTransactions = Transaction::with('user')
            ->where('store_id', $store->id)
            ->latest()
            ->take(10)
            ->get();

        return view('dashboard.manajer', compact('store', 'todaySales', 'todayCount', 'lowStocks', 'recentTransactions'));
    }
}