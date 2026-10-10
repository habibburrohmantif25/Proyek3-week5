<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['id_user' => 'USR001'],
            [
                'nama_lengkap' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'username' => 'budi',
                'password' => Hash::make('password123'),
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Mawar No. 12, Jakarta Selatan',
            ]
        );

        User::updateOrCreate(
            ['id_user' => 'USR002'],
            [
                'nama_lengkap' => 'Siti Rahma',
                'email' => 'siti@example.com',
                'username' => 'siti',
                'password' => Hash::make('password123'),
                'no_hp' => '089876543210',
                'alamat' => 'Jl. Melati No. 5, Bandung',
            ]
        );
    }
}
