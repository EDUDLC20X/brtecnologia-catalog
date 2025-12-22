<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoMeta extends Component
{
    public string $title;
    public string $description;
    public string $keywords;
    public string $image;
    public string $url;
    public string $type;
    public ?float $price;
    public ?string $currency;
    public ?string $availability;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $title = '',
        string $description = '',
        string $keywords = '',
        string $image = '',
        string $url = '',
        string $type = 'website',
        ?float $price = null,
        ?string $currency = 'USD',
        ?string $availability = null
    ) {
        $this->title = $title ?: config('app.name', 'B&R Tecnología');
        $this->description = $description ?: 'B&R Tecnología - Tu tienda de herramientas eléctricas, equipos industriales y tecnología. Calidad garantizada.';
        $this->keywords = $keywords ?: 'herramientas eléctricas, equipos industriales, tecnología, B&R, taladros, multímetros';
        $this->image = $image ?: asset('images/logo.png');
        $this->url = $url ?: url()->current();
        $this->type = $type;
        $this->price = $price;
        $this->currency = $currency;
        $this->availability = $availability;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.seo-meta');
    }
}
