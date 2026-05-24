<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'account_number',
        'balance',
        'ledger_balance',
        'currency',
        'status',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
        'ledger_balance' => 'decimal:2',
    ];

    /**
     * Get the user that owns the wallet.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Generate a unique account number.
     * Account numbers are 10 digits starting with bank code (default: 100).
     */
    public static function generateAccountNumber(): string
    {
        $bankCode = config('banking.bank_code', '100'); // Get from config or default to 100
        
        do {
            // Generate random 7-digit number to make total 10 digits
            $randomDigits = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
            $accountNumber = $bankCode . $randomDigits;
        } while (self::where('account_number', $accountNumber)->exists());

        return $accountNumber;
    }

    /**
     * Credit the wallet.
     */
    public function credit($amount)
    {
        return DB::transaction(function () use ($amount) {
            // Lock the row for update
            $wallet = self::where('id', $this->id)->lockForUpdate()->first();
            
            // Update balance directly
            $wallet->balance = $wallet->balance + $amount;
            $wallet->ledger_balance = $wallet->ledger_balance + $amount;
            $wallet->save();
            
            // Refresh the current instance
            $this->refresh();
            return $this;
        });
    }

    /**
     * Debit the wallet.
     */
    public function debit($amount)
    {
        return DB::transaction(function () use ($amount) {
            // Lock the row for update
            $wallet = self::where('id', $this->id)->lockForUpdate()->first();
            
            // Check sufficient balance
            if ($wallet->balance < $amount || $wallet->ledger_balance < $amount) {
                throw new \Exception('Insufficient balance');
            }
            
            // Update balance directly
            $wallet->balance = $wallet->balance - $amount;
            $wallet->ledger_balance = $wallet->ledger_balance - $amount;
            $wallet->save();
            
            // Refresh the current instance
            $this->refresh();
            return $this;
        });
    }

    /**
     * Check if wallet is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }
}
