<div class="row">
    {{-- card total --}}
    <div class="col-sm-6 col-xl-4 p-2">
        <div class="bg-white border-1 round p-2 text-black">
            <h2 class="text-md">Cajas Abiertas / Cerradas</h2>
            <h3 class="text-3xl font-bold" id="open-closed-boxes">{{ $open_closed_boxes ?? '0/0' }}</h3>
            <small class="">Todas las cajas abiertas y cerradas para la fecha seleccionada</small>
        </div>
    </div>
    {{-- Sales cards removed - Sales module disabled --}}
    {{-- Returns cards removed - Returns module disabled --}}
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
        {{-- Payment method section removed - Sales module disabled --}}
    </div>
    {{-- Payment method cards removed - Sales module disabled --}}

</div>