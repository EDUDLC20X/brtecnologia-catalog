@component('mail::message')

# Export listo

Tu exportación de productos está lista. Puedes descargarla desde el siguiente enlace:

@component('mail::button', ['url' => $url])
Descargar export
@endcomponent

Gracias,

El equipo de B&R Tecnology

@endcomponent
