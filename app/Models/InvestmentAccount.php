<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class InvestmentAccount extends Model
{
    protected $fillable = [
        'user_id',
        'product',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Credit the investment account. Amount is in the same "cents" unit as Wallet balances.
     */
    public function credit($amount)
    {
        return DB::transaction(function () use ($amount) {
            $account = self::where('id', $this->id)->lockForUpdate()->first();

            $account->balance = $account->balance + $amount;
            $account->save();

            $this->refresh();
            return $this;
        });
    }
}
