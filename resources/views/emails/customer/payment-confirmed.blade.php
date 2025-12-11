<x-mail::message>
# Pago Confirmado

Hola {{ $payment->customer->name }},

Tu pago ha sido verificado y confirmado exitosamente.

## Detalles del Pago

**Orden:** #{{ $payment->order_id }}  
**Monto:** ${{ number_format($payment->amount, 2) }}  
**Método:** {{ $payment->payment_method_label }}  
**Estado:** {{ $payment->status_label }}

<x-mail::button :url="route('customer.orders.show', $payment->order_id)">
Ver Orden
</x-mail::button>

Tu orden será procesada y enviada lo antes posible.

Gracias por tu confianza,<br>
{{ config('app.name') }}
</x-mail::message>
