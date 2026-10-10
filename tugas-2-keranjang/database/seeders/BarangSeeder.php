<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        Barang::truncate();

        Barang::insert([
            ['nama' => 'Buku Tulis', 'harga' => 5000, 'stok' => 20, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pulpen', 'harga' => 3000, 'stok' => 15, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Penggaris', 'harga' => 4000, 'stok' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Pensil 2B', 'harga' => 2500, 'stok' => 30, 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Penghapus', 'harga' => 1500, 'stok' => 25, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
