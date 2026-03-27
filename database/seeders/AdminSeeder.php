<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Super Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'super_admin',
        ]);

        Admin::create([
            'name' => 'Nabil Iksan',
            'username' => 'nabiliksan',
            'email' => 'nabiliksan2001@gmail.com',
            'password' => Hash::make('nabil123'),
            'role' => 'admin',
        ]);
    }
}