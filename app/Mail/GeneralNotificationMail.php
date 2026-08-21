<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GeneralNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public string $subjectStr,
        public string $title,
        public string $messageContent,
        public ?string $actionUrl = null,
        public string $actionText = 'View Dashboard'
    ) {
        //
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject($this->subjectStr)
            ->view('emails.general_notification')
            ->with([
                'title' => $this->title,
                'messageContent' => $this->messageContent,
                'actionUrl' => $this->actionUrl,
                'actionText' => $this->actionText,
            ]);
    }
}
