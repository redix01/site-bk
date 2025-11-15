<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Log;

class LogSuccessfulLogout
{
    /**
     * Handle the event.
     *
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        // Log successful logout if needed
        // For now, this listener exists to prevent errors
        // You can extend this to add logout tracking functionality
        
        if ($event->user) {
            Log::info('User logged out', [
                'user_id' => $event->user->id,
                'email' => $event->user->email,
            ]);
        }
    }
}

