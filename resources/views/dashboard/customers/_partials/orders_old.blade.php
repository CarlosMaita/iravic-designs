<div class="tab-pane fade @if($showOrdersTab) show active @endif" id="orders" role="tabpanel" aria-labelledby="orders-tab">
    @can('create', App\Models\Order::class)
        <div class="row"> 
            <a href="{{ route('ventas.create') }}?cliente={{ $customer->id }}" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_o') }}</a>
        </div>
    @endcan
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('dashboard.orders._datatable', ['orders' => $orders])
            </div>
        </div>
    </div>
</div>