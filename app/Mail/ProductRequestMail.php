<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->subject('Nueva solicitud de producto: ' . ($this->data['product_name'] ?? ''))
                    ->view('emails.product_request')
                    ->with(['data' => $this->data]);
    }
}
