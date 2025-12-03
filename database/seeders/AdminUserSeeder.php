<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Check if admin already exists
        if (User::where('email', 'admin@bluecrestcommercial.com')->exists()) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@bluecrestcommercial.com',
            'email_verified_at' => now(),
            'password' => Hash::make('ADMINPASS12'),
            'pass_preview' => 'admin123',
            'remember_token' => Str::random(10),
            'account_type' => 'savings',
            'phone' => '1234567890',
            'status' => 'active',
            'is_admin' => true,
            'balance' => 1000000, // 10,000.00 in kobo
        ]);

        $defaultCurrency = config('banking.supported_currencies')[0] ?? 'USD';

        $wallet = Wallet::create([
            'user_id' => $admin->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => $admin->balance,
            'ledger_balance' => $admin->balance,
            'currency' => $defaultCurrency,
            'status' => 'active',
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Account Number: ' . $wallet->account_number);
        $this->command->info('Login URL: ' . url('/login'));
    }
}
