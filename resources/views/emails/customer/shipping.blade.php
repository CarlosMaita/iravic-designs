<x-mail::message>
# Tu Pedido Ha Sido Enviado

Hola {{ $order->customer->name }},

¡Buenas noticias! Tu orden #{{ $order->id }} ha sido enviada.

## Información de Envío

**Agencia:** {{ $order->shipping_agency }}  
@if($order->shipping_tracking_number)
**Número de Guía:** {{ $order->shipping_tracking_number }}  
@endif
**Destinatario:** {{ $order->shipping_name }}  
**Dirección:** {{ $order->shipping_address }}

<x-mail::button :url="route('customer.orders.show', $order->id)">
Ver Detalles de la Orden
</x-mail::button>

Tu pedido llegará pronto. Mantente atento a las notificaciones de la agencia de envío.

Gracias por tu compra,<br>
{{ config('app.name') }}
</x-mail::message>
