<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    public const TYPES = [
        'deposit',
        'withdrawal',
        'transfer',
        'fee',
        'refund',
        'stamp_duty',
        'monthly_fee',
        'general',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'recipient_id',
        'type',
        'amount',
        'fee',
        'reference',
        'status',
        'description',
        'metadata',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'integer',
        'fee' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'metadata',
    ];

    /**
     * Get the user that owns the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the recipient of the transaction.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /**
     * Get the reversal transaction.
     */
    public function reversal()
    {
        return $this->hasOne(Transaction::class, 'reversed_transaction_id');
    }

    /**
     * Get the reversed transaction.
     */
    public function reversedTransaction()
    {
        return $this->belongsTo(Transaction::class, 'reversed_transaction_id');
    }

    /**
     * Get the amount in dollars (converted from kobo).
     */
    public function getAmountInDollarsAttribute()
    {
        return $this->amount / 100;
    }

    /**
     * Get the fee in dollars (converted from kobo).
     */
    public function getFeeInDollarsAttribute()
    {
        return $this->fee / 100;
    }

    /**
     * Set the amount from dollars (convert to kobo).
     */
    public function setAmountFromDollars($dollars)
    {
        $this->amount = (int) ($dollars * 100);
    }

    /**
     * Set the fee from dollars (convert to kobo).
     */
    public function setFeeFromDollars($dollars)
    {
        $this->fee = (int) ($dollars * 100);
    }
}
