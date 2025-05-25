@if (count($customer->orders))
<div class="tab-pane fade @if($showRefundsTab) show active @endif" id="refunds" role="tabpanel" aria-labelledby="refunds-tab">
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('ecommerce.refunds._datatable', ['refunds' => $refunds])
            </div>
        </div>
    </div>
</div>
@endif