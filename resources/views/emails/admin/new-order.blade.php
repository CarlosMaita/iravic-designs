<x-mail::message>
# Nueva Orden Creada

Se ha creado una nueva orden en la tienda.

## Detalles de la Orden

**Orden ID:** #{{ $order->id }}  
**Cliente:** {{ $order->customer->name }}  
**Email:** {{ $order->customer->email }}  
**Total:** ${{ number_format($order->total, 2) }}  
**Fecha:** {{ $order->date->format('d/m/Y H:i') }}

### Productos

@foreach($order->orderProducts as $item)
- {{ $item->product_name }} - Cantidad: {{ $item->qty }} - ${{ number_format($item->total, 2) }}
@endforeach

### Información de Envío

**Destinatario:** {{ $order->shipping_name }}  
**DNI:** {{ $order->shipping_dni }}  
**Teléfono:** {{ $order->shipping_phone }}  
**Agencia:** {{ $order->shipping_agency }}  
**Dirección:** {{ $order->shipping_address }}

<x-mail::button :url="route('admin.orders.show', $order->id)">
Ver Orden en el Admin
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
