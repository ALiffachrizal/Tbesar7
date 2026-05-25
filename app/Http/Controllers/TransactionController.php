<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index()
    {
        $storeId = auth()->user()->store_id;

        $transactions = Transaction::with('user')
            ->where('store_id', $storeId)
            ->latest()
            ->paginate(20);

        return view('transaksi.index', compact('transactions'));
    }

    public function create()
    {
        $storeId = auth()->user()->store_id;

        $products = Stock::with('product')
            ->where('store_id', $storeId)
            ->where('quantity', '>', 0)
            ->get()
            ->map(fn($s) => [
                'id'         => $s->product->id,
                'name'       => $s->product->name,
                'barcode'    => $s->product->barcode,
                'sell_price' => $s->product->sell_price,
                'stock'      => $s->quantity,
            ]);

        return view('transaksi.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items'        => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'paid_amount'  => 'required|numeric|min:0',
        ]);

        $storeId = auth()->user()->store_id;
        $userId  = auth()->id();

        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $itemsData   = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $stock   = Stock::where('store_id', $storeId)
                    ->where('product_id', $product->id)
                    ->lockForUpdate()
                    ->first();

                if (!$stock || $stock->quantity < $item['quantity']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi.");
                }

                $subtotal     = $product->sell_price * $item['quantity'];
                $totalAmount += $subtotal;
                $itemsData[]  = [
                    'product'   => $product,
                    'stock'     => $stock,
                    'quantity'  => $item['quantity'],
                    'subtotal'  => $subtotal,
                ];
            }

            if ($request->paid_amount < $totalAmount) {
                throw new \Exception('Jumlah bayar kurang dari total belanja.');
            }

            // Buat nomor invoice
            $invoice = 'INV-' . date('Ymd') . '-' . strtoupper(uniqid());

            $transaction = Transaction::create([
                'invoice_number' => $invoice,
                'store_id'       => $storeId,
                'user_id'        => $userId,
                'total_amount'   => $totalAmount,
                'paid_amount'    => $request->paid_amount,
                'change_amount'  => $request->paid_amount - $totalAmount,
                'status'         => 'completed',
            ]);

            foreach ($itemsData as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['product']->id,
                    'quantity'       => $item['quantity'],
                    'price'          => $item['product']->sell_price,
                    'subtotal'       => $item['subtotal'],
                ]);

                $before = $item['stock']->quantity;
                $after  = $before - $item['quantity'];

                $item['stock']->decrement('quantity', $item['quantity']);

                StockMovement::create([
                    'store_id'        => $storeId,
                    'product_id'      => $item['product']->id,
                    'user_id'         => $userId,
                    'type'            => 'out',
                    'quantity'        => $item['quantity'],
                    'quantity_before' => $before,
                    'quantity_after'  => $after,
                    'note'            => 'Penjualan ' . $invoice,
                ]);
            }

            DB::commit();
            return redirect()->route('transaksi.show', $transaction)
                ->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['items.product', 'user', 'store']);
        return view('transaksi.show', compact('transaction'));
    }
}