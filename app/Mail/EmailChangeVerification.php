<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailChangeVerification extends Mailable
{
    use Queueable, SerializesModels;

    public string $userName;
    public string $verificationUrl;
    public string $newEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(string $userName, string $newEmail, string $verificationUrl)
    {
        $this->userName = $userName;
        $this->newEmail = $newEmail;
        $this->verificationUrl = $verificationUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Confirma tu nuevo correo electrónico - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.email-change-verification',
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
