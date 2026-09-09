<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Super Admin
        User::create([
            'name'     => 'Super Admin',
            'email'    => 'admin@pos.com',
            'password' => Hash::make('password123'),
            'role'     => 'super_admin',
        ]);

        // 2. Akun Manager
        User::create([
            'name'     => 'Manager Toko',
            'email'    => 'manager@pos.com',
            'password' => Hash::make('password123'),
            'role'     => 'manager',
        ]);

        // 3. Akun Kasir
        User::create([
            'name'     => 'Kasir SyaharuddinFS',
            'email'    => 'kasir@pos.com',
            'password' => Hash::make('password123'),
            'role'     => 'kasir',
        ]);
    }
}