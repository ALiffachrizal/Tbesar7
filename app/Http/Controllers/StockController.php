<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function index()
    {
        $storeId = auth()->user()->store_id;

        $stocks = Stock::with('product')
            ->where('store_id', $storeId)
            ->orderBy('quantity')
            ->paginate(20);

        return view('stok.index', compact('stocks'));
    }

    public function masuk()
    {
        $products = Product::orderBy('name')->get();
        return view('stok.masuk', compact('products'));
    }

    public function storeMasuk(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
            'note'       => 'nullable|string|max:255',
        ]);

        $storeId = auth()->user()->store_id;
        $userId  = auth()->id();

        DB::beginTransaction();
        try {
            $stock = Stock::firstOrCreate(
                ['store_id' => $storeId, 'product_id' => $request->product_id],
                ['quantity' => 0, 'min_quantity' => 10]
            );

            $before = $stock->quantity;
            $after  = $before + $request->quantity;

            $stock->increment('quantity', $request->quantity);

            StockMovement::create([
                'store_id'        => $storeId,
                'product_id'      => $request->product_id,
                'user_id'         => $userId,
                'type'            => 'in',
                'quantity'        => $request->quantity,
                'quantity_before' => $before,
                'quantity_after'  => $after,
                'note'            => $request->note ?? 'Barang masuk dari supplier',
            ]);

            DB::commit();
            return redirect()->route('stok.index')->with('success', 'Stok berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function riwayat()
    {
        $storeId = auth()->user()->store_id;

        $movements = StockMovement::with(['product', 'user'])
            ->where('store_id', $storeId)
            ->latest()
            ->paginate(30);

        return view('stok.riwayat', compact('movements'));
    }
}