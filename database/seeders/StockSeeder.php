<?php

namespace Database\Seeders;

use App\Models\Stock;
use App\Models\Store;
use App\Models\Product;
use Illuminate\Database\Seeder;

class StockSeeder extends Seeder
{
    public function run(): void
    {
        $stores = Store::all();
        $products = Product::all();

        foreach ($stores as $store) {
            foreach ($products as $product) {
                Stock::firstOrCreate(
                    ['store_id' => $store->id, 'product_id' => $product->id],
                    ['quantity' => rand(20, 100), 'min_quantity' => 10]
                );
            }
        }
    }
}