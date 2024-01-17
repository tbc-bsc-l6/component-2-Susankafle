<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $userName;
    public $pin;

    /**
     * Create a new message instance.
     *
     * @param string $userName
     */
    public function __construct($userName)
    {
        $this->userName = $userName;
        $this->pin = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT); // Generate a random 4-digit PIN
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Welcome Email')
            ->view('emails.welcome', [
                'userName' => $this->userName,
                'pin' => $this->pin,
            ]);
    }
}
