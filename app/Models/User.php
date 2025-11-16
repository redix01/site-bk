<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'pass_preview',
        'transaction_pin',
        'account_type',
        'phone',
        'profile_photo_path',
        'date_of_birth',
        'gender',
        'nationality',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'country',
        'passport_number',
        'passport_country',
        'passport_expiry',
        'tax_identification_number',
        'occupation',
        'employment_status',
        'source_of_funds',
        'branch_code',
        'preferred_currency',
        'status',
        'is_admin',
        'balance',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'transaction_pin',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'account_number',
        'has_transaction_pin',
        'profile_photo_url',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'balance' => 'integer',
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
    ];

    /**
     * Check if the user is an admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->is_admin === true;
    }

    /**
     * Get the user's transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the user's received transfers.
     */
    public function receivedTransfers()
    {
        return $this->hasMany(Transaction::class, 'recipient_id');
    }

    /**
     * Get the user's wallet.
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Get the user's audit logs.
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'actor_id');
    }

    /**
     * Get the login history records for the user.
     */
    public function loginHistory()
    {
        return $this->hasMany(LoginHistory::class)->latest();
    }

    /**
     * Get the transaction codes created by this admin.
     */
    public function createdCodes()
    {
        return $this->hasMany(TransactionCode::class, 'created_by');
    }

    /**
     * Check if user account is active.
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if user account is suspended.
     */
    public function isSuspended()
    {
        return $this->status === 'suspended';
    }

    /**
     * Check if user account is locked.
     */
    public function isLocked()
    {
        return $this->status === 'locked';
    }

    /**
     * Suspend user account.
     */
    public function suspend($reason = null)
    {
        $this->update(['status' => 'suspended']);
        AuditLog::logEvent('user.suspended', ['reason' => $reason], $this);
    }

    /**
     * Activate user account.
     */
    public function activate()
    {
        $this->update(['status' => 'active']);
        AuditLog::logEvent('user.activated', [], $this);
    }

    /**
     * Lock user account.
     */
    public function lock($reason = null)
    {
        $this->update(['status' => 'locked']);
        AuditLog::logEvent('user.locked', ['reason' => $reason], $this);
    }

    /**
     * Get profile photo URL.
     */
    public function getProfilePhotoUrlAttribute()
    {
        if ($this->profile_photo_path) {
            // Add cache-busting parameter using updated_at timestamp to ensure fresh image after upload
            $url = Storage::disk('public')->url($this->profile_photo_path);
            $separator = strpos($url, '?') !== false ? '&' : '?';
            // Use updated_at timestamp for cache-busting (updated_at is cast as datetime/Carbon)
            $timestamp = $this->updated_at ? $this->updated_at->getTimestamp() : time();
            return $url . $separator . 'v=' . $timestamp;
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the account number from the user's wallet.
     */
    public function getAccountNumberAttribute()
    {
        return $this->wallet?->account_number;
    }

    public function getHasTransactionPinAttribute()
    {
        return !empty($this->transaction_pin);
    }

    /**
     * Determine if the user has a preferred currency configured.
     *
     * When a currency code is provided, the comparison is made against that code.
     * Otherwise, the method simply checks if any preferred currency exists.
     *
     * @param  string|null  $currency
     * @return bool
     */
    public function hasPreferredCurrency(?string $currency = null): bool
    {
        if (empty($this->preferred_currency)) {
            return false;
        }

        if ($currency === null) {
            return true;
        }

        return strtoupper($this->preferred_currency) === strtoupper($currency);
    }
}
