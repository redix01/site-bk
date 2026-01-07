<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'code',
        'expires_at',
        'used_at',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
    ];

    /**
     * Generate a new OTP code for the given email.
     */
    public static function generateForEmail(string $email): self
    {
        // Delete any existing OTP codes for this email
        static::where('email', $email)->delete();

        // Generate a 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        return static::create([
            'email' => $email,
            'code' => $code,
            'expires_at' => now()->addMinutes(30), // OTP expires in 30 minutes
            'attempts' => 0,
        ]);
    }

    /**
     * Verify the OTP code.
     */
    public function verify(string $code): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        if ($this->used_at !== null) {
            return false;
        }

        if ($this->attempts >= 3) {
            return false;
        }

        $this->increment('attempts');

        if ($this->code === $code) {
            $this->update(['used_at' => now()]);
            return true;
        }

        return false;
    }

    /**
     * Check if the OTP code is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if the OTP code has been used.
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Check if the OTP code has exceeded max attempts.
     */
    public function hasExceededMaxAttempts(): bool
    {
        return $this->attempts >= 3;
    }

    /**
     * Get the user associated with this OTP code.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
