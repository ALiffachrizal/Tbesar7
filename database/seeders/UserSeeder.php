<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner — tidak terikat cabang
        $owner = User::firstOrCreate(
            ['email' => 'owner@minimarket.com'],
            ['name' => 'Pak Jayusman', 'password' => Hash::make('password'), 'store_id' => null]
        );
        $owner->assignRole('owner');

        // Buat 1 manajer, supervisor, kasir, gudang per cabang
        $stores = Store::all();

        foreach ($stores as $index => $store) {
            $n = $index + 1;

            $manajer = User::firstOrCreate(
                ['email' => "manajer{$n}@minimarket.com"],
                ['name' => "Manajer Cabang {$store->city}", 'password' => Hash::make('password'), 'store_id' => $store->id]
            );
            $manajer->assignRole('manajer');

            $supervisor = User::firstOrCreate(
                ['email' => "supervisor{$n}@minimarket.com"],
                ['name' => "Supervisor Cabang {$store->city}", 'password' => Hash::make('password'), 'store_id' => $store->id]
            );
            $supervisor->assignRole('supervisor');

            $kasir = User::firstOrCreate(
                ['email' => "kasir{$n}@minimarket.com"],
                ['name' => "Kasir Cabang {$store->city}", 'password' => Hash::make('password'), 'store_id' => $store->id]
            );
            $kasir->assignRole('kasir');

            $gudang = User::firstOrCreate(
                ['email' => "gudang{$n}@minimarket.com"],
                ['name' => "Gudang Cabang {$store->city}", 'password' => Hash::make('password'), 'store_id' => $store->id]
            );
            $gudang->assignRole('gudang');
        }
    }
}