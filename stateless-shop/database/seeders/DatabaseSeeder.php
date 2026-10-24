<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'username' => 'admin',
            'nama_lengkap' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->role = 'admin';
        $admin->save();

        $staff = User::create([
            'username' => 'staff',
            'nama_lengkap' => 'Staff Toko',
            'email' => 'staff@example.com',
            'password' => Hash::make('password123'),
        ]);
        $staff->role = 'staff';
        $staff->save();

        User::create([
            'username' => 'budi',
            'nama_lengkap' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'username' => 'siti',
            'nama_lengkap' => 'Siti Aminah',
            'email' => 'siti@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
