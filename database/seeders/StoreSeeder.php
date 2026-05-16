<?php

namespace Database\Seeders;

use App\Models\Store;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    public function run(): void
    {
        $stores = [
            ['name' => 'Mini Market Jayusman Pusat',  'address' => 'Jl. Merdeka No. 1',   'city' => 'Bandung',  'phone' => '022-1234567'],
            ['name' => 'Mini Market Jayusman Selatan', 'address' => 'Jl. Sudirman No. 10', 'city' => 'Garut',    'phone' => '0262-1234567'],
            ['name' => 'Mini Market Jayusman Timur',  'address' => 'Jl. Diponegoro No. 5', 'city' => 'Cianjur',  'phone' => '0263-1234567'],
            ['name' => 'Mini Market Jayusman Barat',  'address' => 'Jl. Ahmad Yani No. 20','city' => 'Sukabumi', 'phone' => '0266-1234567'],
            ['name' => 'Mini Market Jayusman Utara',  'address' => 'Jl. Pahlawan No. 8',   'city' => 'Cimahi',   'phone' => '022-7654321'],
        ];

        foreach ($stores as $store) {
            Store::firstOrCreate(['name' => $store['name']], $store);
        }
    }
}