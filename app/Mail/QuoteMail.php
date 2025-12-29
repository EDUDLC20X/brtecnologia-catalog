<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public Quote $quote;
    public string $recipientType;

    public function __construct(Quote $quote, string $recipientType = 'customer')
    {
        $this->quote = $quote;
        $this->recipientType = $recipientType;
    }

    public function build()
    {
        $quote = $this->quote->load(['items.product']);
        
        $subject = $this->recipientType === 'admin' 
            ? 'Nueva Cotización Recibida: ' . $this->quote->quote_number
            : 'Tu Cotización ' . $this->quote->quote_number . ' - B&R Tecnología';

        return $this->subject($subject)
                    ->view('emails.quote')
                    ->with([
                        'quote' => $quote,
                        'isAdmin' => $this->recipientType === 'admin',
                    ]);
    }
}
