<x-mail::message>
# Nuevo Pago Recibido

Se ha recibido un nuevo pago que requiere verificación.

## Detalles del Pago

**Pago ID:** #{{ $payment->id }}  
**Orden:** #{{ $payment->order_id }}  
**Cliente:** {{ $payment->customer->name }}  
**Monto:** ${{ number_format($payment->amount, 2) }}  
**Método de Pago:** {{ $payment->payment_method_label }}  
**Referencia:** {{ $payment->reference_number ?? 'N/A' }}  
**Fecha:** {{ $payment->date->format('d/m/Y H:i') }}

<x-mail::button :url="route('admin.payments.show', $payment->id)">
Ver y Verificar Pago
</x-mail::button>

Por favor, verifica este pago lo antes posible.

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
