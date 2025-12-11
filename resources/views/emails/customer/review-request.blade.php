<x-mail::message>
# ¿Cómo Fue Tu Experiencia?

Hola {{ $order->customer->name }},

Esperamos que hayas disfrutado de tu pedido (Orden #{{ $order->id }}).

Nos encantaría conocer tu opinión sobre los productos que recibiste. Tu retroalimentación nos ayuda a mejorar nuestro servicio.

<x-mail::button :url="route('customer.orders.show', $order->id)">
Ver Mi Pedido
</x-mail::button>

Si tienes algún problema o pregunta, no dudes en contactarnos.

¡Gracias por elegir Iravic Designs!<br>
{{ config('app.name') }}
</x-mail::message>
