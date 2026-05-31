<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Stock;
use App\Models\Store;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('category')->orderBy('name')->paginate(20);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode'    => 'nullable|string|unique:products,barcode',
            'name'       => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'unit'       => 'required|string|max:50',
            'buy_price'  => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($request->only([
            'barcode', 'name', 'category', 'unit', 'buy_price', 'sell_price'
        ]));

        // Otomatis buat stok 0 di semua cabang
        $stores = Store::all();
        foreach ($stores as $store) {
            Stock::firstOrCreate(
                ['store_id' => $store->id, 'product_id' => $product->id],
                ['quantity' => 0, 'min_quantity' => 10]
            );
        }

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan ke semua cabang.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'barcode'    => 'nullable|string|unique:products,barcode,' . $product->id,
            'name'       => 'required|string|max:255',
            'category'   => 'required|string|max:100',
            'unit'       => 'required|string|max:50',
            'buy_price'  => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ]);

        $product->update($request->only([
            'barcode', 'name', 'category', 'unit', 'buy_price', 'sell_price'
        ]));

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}