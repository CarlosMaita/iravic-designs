{{-- Graphs --}}
<div class="row justify-content-end">
    {{-- Sales graphs removed - Sales module disabled --}}
    {{-- Refunds graphs removed - Returns module disabled --}}
    
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



