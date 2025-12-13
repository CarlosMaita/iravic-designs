<x-mail::message>
# ¡Bienvenido a Iravic Designs, {{ $customer->name }}!

Estamos muy contentos de tenerte con nosotros. Gracias por registrarte en nuestra tienda.

En Iravic Designs encontrarás productos de la más alta calidad con diseños únicos y exclusivos.

<x-mail::button :url="route('ecommerce.home')">
Ver Catálogo
</x-mail::button>

Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos.

Saludos,<br>
{{ config('app.name') }}
</x-mail::message>
