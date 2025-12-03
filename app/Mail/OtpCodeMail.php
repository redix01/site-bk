<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        protected string $code,
        protected string $userName
    ) {
        //
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Your Login Code')
            ->view('emails.otp_code')
            ->with([
                'code' => $this->code,
                'userName' => $this->userName,
                'expiresInMinutes' => 10,
            ]);
    }
}

