@extends('layouts.app')

@section('content')
<div style="font-family: Arial, Helvetica, sans-serif; max-width:600px; margin:0 auto;">
    <h2 style="color:#0d6efd;">Hemos recibido tu mensaje</h2>
    <p>Hola {{ $data['name'] }},</p>
    <p>Gracias por contactarnos. Hemos recibido tu mensaje con el asunto <strong>{{ $data['subject'] ?? '-' }}</strong> y responderemos a la brevedad.</p>
    <p>Resumen:</p>
    <ul>
        <li><strong>Fecha:</strong> {{ $data['sent_at'] ?? now() }}</li>
        <li><strong>Mensaje:</strong> {{ \Illuminate\Support\Str::limit($data['message'], 200) }}</li>
    </ul>
    <p>Atentamente,<br>B&amp;R Tecnología</p>
</div>
@endsection
