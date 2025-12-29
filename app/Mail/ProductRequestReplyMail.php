<?php

namespace App\Mail;

use App\Models\ProductRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductRequestReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public ProductRequest $productRequest;

    /**
     * Create a new message instance.
     */
    public function __construct(ProductRequest $productRequest)
    {
        $this->productRequest = $productRequest;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $productName = $this->productRequest->product?->name ?? 'producto';
        
        return new Envelope(
            subject: "Respuesta a su consulta sobre: {$productName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.product-request-reply',
        );
    }
}
