@extends('layouts.app')

@section('content')
<div style="font-family: Arial, Helvetica, sans-serif; max-width:600px; margin:0 auto;">
    <h2 style="color:#0d6efd;">Solicitud recibida</h2>
    <p>Hola {{ $data['name'] }},</p>
    <p>Hemos recibido tu solicitud para el producto <strong>{{ $data['product_name'] }}</strong>. Nos pondremos en contacto contigo pronto.</p>
    <p>Contacto de la empresa: {{ config('mail.from.address') }}</p>
    <p>Gracias por confiar en B&amp;R Tecnología.</p>
</div>
@endsection
