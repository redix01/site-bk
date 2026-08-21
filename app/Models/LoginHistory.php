<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;

class LoginHistory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'device',
        'platform',
        'browser',
        'country',
        'city',
        'latitude',
        'longitude',
        'login_successful',
        'login_failure_reason',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'login_successful' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'device_icon',
        'location',
        'formatted_created_at',
    ];

    /**
     * Get the user that owns the login history.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Record a successful login attempt.
     *
     * @param  \App\Models\User  $user
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\LoginHistory
     */
    public static function recordSuccess(User $user, $request = null)
    {
        $request = $request ?? Request::instance();
        $userAgent = $request->userAgent();
        $agent = new Agent();
        $agent->setUserAgent($userAgent);
        
        return static::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'browser' => $agent->browser(),
            'login_successful' => true,
        ]);
    }

    /**
     * Record a failed login attempt.
     *
     * @param  mixed  $user
     * @param  string  $reason
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Models\LoginHistory
     */
    public static function recordFailure($user, $reason, $request = null)
    {
        $request = $request ?? Request::instance();
        $userAgent = $request->userAgent();
        $agent = new Agent();
        $agent->setUserAgent($userAgent);
        
        return static::create([
            'user_id' => $user ? $user->id : null,
            'ip_address' => $request->ip(),
            'user_agent' => $userAgent,
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'browser' => $agent->browser(),
            'login_successful' => false,
            'login_failure_reason' => $reason,
        ]);
    }

    /**
     * Get the device icon based on the device type.
     *
     * @return string
     */
    public function getDeviceIconAttribute()
    {
        $device = strtolower($this->device);
        
        if (str_contains($device, 'mobile')) {
            return 'mobile-alt';
        } elseif (str_contains($device, 'tablet')) {
            return 'tablet-alt';
        } else {
            return 'desktop';
        }
    }

    /**
     * Get the location as a string.
     *
     * @return string
     */
    public function getLocationAttribute()
    {
        $parts = array_filter([$this->city, $this->country]);
        return $parts ? implode(', ', $parts) : 'Unknown';
    }

    /**
     * Get the formatted created at timestamp.
     *
     * @return string
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Scope a query to only include successful logins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSuccessful($query)
    {
        return $query->where('login_successful', true);
    }

    /**
     * Scope a query to only include failed logins.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFailed($query)
    {
        return $query->where('login_successful', false);
    }

    /**
     * Scope a query to only include logins from a specific IP address.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $ipAddress
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Scope a query to only include logins from a specific user.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\User|int  $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $user)
    {
        $userId = $user instanceof User ? $user->id : $user;
        return $query->where('user_id', $userId);
    }

    /**
     * Get the login status as a badge.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        return $this->login_successful 
            ? '<span class="px-2 py-1 text-xs font-semibold leading-tight text-green-700 bg-green-100 rounded-full">Success</span>'
            : '<span class="px-2 py-1 text-xs font-semibold leading-tight text-red-700 bg-red-100 rounded-full">Failed</span>';
    }
}
