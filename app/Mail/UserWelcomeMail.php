<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcomeMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user
    ) {
        //
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Welcome to ' . config('app.name'))
            ->view('emails.user_welcome')
            ->with([
                'userName' => $this->user->name,
                'email' => $this->user->email,
                'actionUrl' => url('/login'),
            ]);
    }
}
