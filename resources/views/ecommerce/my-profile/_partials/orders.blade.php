<div class="tab-pane fade @if($showOrdersTab) show active @endif" id="orders" role="tabpanel" aria-labelledby="orders-tab">
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('ecommerce.orders._datatable', ['orders' => $orders])
            </div>
        </div>
    </div>
</div>