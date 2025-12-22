<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductsExportReady extends Mailable
{
    use Queueable, SerializesModels;

    public $url;
    public $path;

    public function __construct(string $url, ?string $path = null)
    {
        $this->url = $url;
        $this->path = $path;
    }

    public function build()
    {
        $mail = $this->subject('Your products export is ready')
                    ->markdown('emails.products_export_ready')
                    ->with(['url' => $this->url]);

        if ($this->path && file_exists($this->path)) {
            $mail->attach($this->path);
        }

        return $mail;
    }
}
