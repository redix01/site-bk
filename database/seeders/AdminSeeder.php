<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin User',
            'email' => 'admin@bluecrestcommercial.com',
            'password' => \Illuminate\Support\Facades\Hash::make('ADMINPASS12'),
            'phone' => '+1234567890',
            'balance' => 10000.00,
            'is_admin' => true,
        ]);
    }
}
