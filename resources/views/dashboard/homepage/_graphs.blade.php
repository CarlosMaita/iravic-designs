{{-- Graphs --}}
<div class="row justify-content-end">
    {{-- Graph of orders --}}
    <div class="col-xl-6 col-md-6 mb-2">
        @include('dashboard.homepage._graph', [ 
            'id' => 'orders-graph',
            'IdExpanded' => 'orders-graph-expanded',
            'title' => __('dashboard.breadcrumb.graphs.orders')
            ])
    </div>
    {{-- end graph of orders --}}

    {{-- graph of refunds --}}
    <div class="col-xl-6 col-md-6 mb-2">
        @include('dashboard.homepage._graph', [ 
            'id' => 'refunds-graph',
            'IdExpanded' => 'refunds-graph-expanded',
            'title' => __('dashboard.breadcrumb.graphs.refunds')
            ])
    </div>
    {{-- end graph of refunds --}}

    
    {{-- graph of Ventas Efectivo, tarjeta y transferencias --}}
    <div class="col-xl-6 col-md-6 mb-2">
        @include('dashboard.homepage._graph', [ 
            'id' => 'payments-multiples-graph',
            'IdExpanded' => 'payments-multiples-graph-expanded',
            'title' => __('dashboard.breadcrumb.graphs.cash_card_and_bankwire_orders')
            ])
    </div>
    {{-- end graph of Ventas Efectivo, tarjeta y transferencias --}}
    
    {{-- graph of Payments --}}
    <div class="col-xl-6 col-md-6 mb-2">
        @include('dashboard.homepage._graph', [ 
            'id' => 'payments-graph',
            'IdExpanded' => 'payments-graph-expanded',
            'title' => __('dashboard.breadcrumb.graphs.payments')
            ])
    </div>
    {{-- end graph of payments --}}

 
    {{-- graph of collections --}}
    <div class="col-xl-6 col-md-6 mb-2">
        @include('dashboard.homepage._graph', [ 
            'id' => 'collections-graph',
            'IdExpanded' => 'collections-graph-expanded',
            'title' => __('dashboard.breadcrumb.graphs.collections')
            ])
    </div>
    {{-- end graph of collections --}}

</div>



