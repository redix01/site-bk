<?php

namespace App\Listeners;

use App\Mail\AdminAlertMail;
use App\Support\SettingsManager;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class NotifyAdminOfNewRegistration implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $user = $event->user;
        
        // Notify Admin
        $adminEmail = SettingsManager::get('site_email', config('mail.from.address'));
        if ($adminEmail) {
            try {
                Mail::to($adminEmail)->send(new AdminAlertMail(
                    'New User Registration',
                    "A new user has registered on the platform: {$user->name} ({$user->email}).",
                    'success',
                    $user
                ));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send admin registration alert: ' . $e->getMessage());
            }
        }

        // Notify User
        try {
            Mail::to($user->email)->queue(new \App\Mail\UserWelcomeMail($user));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send user welcome email: ' . $e->getMessage());
        }
    }
}
