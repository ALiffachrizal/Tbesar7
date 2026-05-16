<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['barcode' => '8990000001', 'name' => 'Indomie Goreng',     'category' => 'Makanan',  'unit' => 'pcs',  'buy_price' => 2500,  'sell_price' => 3500],
            ['barcode' => '8990000002', 'name' => 'Aqua 600ml',         'category' => 'Minuman',  'unit' => 'botol','buy_price' => 2000,  'sell_price' => 3000],
            ['barcode' => '8990000003', 'name' => 'Teh Botol Sosro',    'category' => 'Minuman',  'unit' => 'botol','buy_price' => 4000,  'sell_price' => 5500],
            ['barcode' => '8990000004', 'name' => 'Sabun Lifebuoy',     'category' => 'Kebersihan','unit' => 'pcs', 'buy_price' => 3500,  'sell_price' => 5000],
            ['barcode' => '8990000005', 'name' => 'Minyak Goreng 1L',   'category' => 'Dapur',    'unit' => 'liter','buy_price' => 14000, 'sell_price' => 17000],
            ['barcode' => '8990000006', 'name' => 'Beras 5kg',          'category' => 'Dapur',    'unit' => 'karung','buy_price' => 60000,'sell_price' => 72000],
            ['barcode' => '8990000007', 'name' => 'Gula Pasir 1kg',     'category' => 'Dapur',    'unit' => 'kg',   'buy_price' => 13000, 'sell_price' => 16000],
            ['barcode' => '8990000008', 'name' => 'Susu Ultra 250ml',   'category' => 'Minuman',  'unit' => 'pcs',  'buy_price' => 4500,  'sell_price' => 6000],
            ['barcode' => '8990000009', 'name' => 'Chitato Sapi Panggang','category' => 'Snack',  'unit' => 'pcs',  'buy_price' => 9000,  'sell_price' => 12000],
            ['barcode' => '8990000010', 'name' => 'Deterjen Rinso 800g','category' => 'Kebersihan','unit' => 'pcs', 'buy_price' => 18000, 'sell_price' => 23000],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['barcode' => $product['barcode']], $product);
        }
    }
}