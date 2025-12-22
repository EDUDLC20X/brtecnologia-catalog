{{-- SEO Meta Tags Component --}}

{{-- Basic Meta Tags --}}
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="B&R Tecnología">
<meta name="robots" content="index, follow">

{{-- Open Graph (Facebook, LinkedIn) --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:url" content="{{ $url }}">
<meta property="og:site_name" content="B&R Tecnología">
<meta property="og:locale" content="es_ES">

{{-- Twitter Cards --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $url }}">

{{-- Product Schema.org (for product pages) --}}
@if($type === 'product' && $price)
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $title }}",
    "description": "{{ $description }}",
    "image": "{{ $image }}",
    "url": "{{ $url }}",
    "offers": {
        "@type": "Offer",
        "price": "{{ $price }}",
        "priceCurrency": "{{ $currency }}",
        "availability": "https://schema.org/{{ $availability ?? 'InStock' }}",
        "seller": {
            "@type": "Organization",
            "name": "B&R Tecnología"
        }
    }
}
</script>
@endif

{{-- Organization Schema.org (for all pages) --}}
@if($type === 'website')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "B&R Tecnología",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logo.png') }}",
    "description": "{{ $description }}",
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "availableLanguage": "Spanish"
    }
}
</script>
@endif
