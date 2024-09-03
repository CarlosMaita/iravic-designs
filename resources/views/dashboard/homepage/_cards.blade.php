<div class="row">
    {{-- card total --}}
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-white border-1 round p-2 text-black">
            <h2 class="text-md">Cajas Abiertas / Cerradas</h2>
            <h3 class="text-3xl font-bold" id="open-closed-boxes">{{ $open_closed_boxes ?? '0/0' }}</h3>
            <small class="">Todas las cajas abiertas y cerradas para la fecha seleccionada</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Ventas totales</h2>
            <h3 class="text-3xl font-bold" id="total-sales">{{ $total_sales ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total en ventas de todas las cajas</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Total pagado</h2>
            <h3 class="text-3xl font-bold" id="total-paid">{{ $total_paid ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total de pagos en ventas de todas las cajas</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Credito total</h2>
            <h3 class="text-3xl font-bold" id="total-credit">{{ $total_credit ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total en ventas a credito de todas las cajas</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Total en devoluciones</h2>
            <h3 class="text-3xl font-bold" id="total-returns">{{ $total_returns ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total en devoluciones de todas las cajas</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Total en cobros</h2>
            <h3 class="text-3xl font-bold" id="total-collected">{{ $total_collected ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total en pagos y cobros  de todas las cajas</small>
        </div>
    </div>
    
</div>
<br>
<div class="row">
    <div class="col-12">
        <span>Total Pagado segun el metodo de pago</span>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Total Efectivo</h2>
            <h3 class="text-3xl font-bold" id="total-cash">{{ $total_cash ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total en ventas de todas las cajas</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Total tarjeta</h2>
            <h3 class="text-3xl font-bold" id="total-card">{{ $total_card ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total en ventas de todas las cajas</small>
        </div>
    </div>
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-verde-oscuro-600 round p-2">
            <h2 class="text-md">Total Transferencia</h2>
            <h3 class="text-3xl font-bold" id="total-transfer">{{ $total_transfer ?? '$0.00' }}</h3>
            <small class="text-azul-claro-200">Total en ventas de todas las cajas</small>
        </div>
    </div>

</div>