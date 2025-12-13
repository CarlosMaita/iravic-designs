<x-mail::message>
# Orden Creada Exitosamente

Hola {{ $order->customer->name }},

Tu orden #{{ $order->id }} ha sido creada exitosamente.

## Detalles de la Orden

**Total:** ${{ number_format($order->total, 2) }}  
**Estado:** {{ $order->status_label }}  
**Fecha:** {{ $order->date->format('d/m/Y H:i') }}

### Productos

@foreach($order->orderProducts as $item)
- {{ $item->product_name }} - Cantidad: {{ $item->qty }} - ${{ number_format($item->total, 2) }}
@endforeach

<x-mail::button :url="route('customer.orders.show', $order->id)">
Ver Orden
</x-mail::button>

Recuerda que puedes realizar el pago de tu orden desde tu panel de cliente.

Gracias por tu compra,<br>
{{ config('app.name') }}
</x-mail::message>
