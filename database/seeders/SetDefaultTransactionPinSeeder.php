<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SetDefaultTransactionPinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Sets a default transaction PIN (123456) for all users that don't have one.
     * In production, users should set their own PIN.
     */
    public function run(): void
    {
        $defaultPin = '123456'; // Default PIN for testing
        
        $usersWithoutPin = User::whereNull('transaction_pin')->get();
        
        foreach ($usersWithoutPin as $user) {
            $user->update(['transaction_pin' => $defaultPin]);
        }
        
        $this->command->info("Set default transaction PIN for {$usersWithoutPin->count()} users.");
        $this->command->warn("Default PIN: {$defaultPin} (for testing only!)");
    }
}


