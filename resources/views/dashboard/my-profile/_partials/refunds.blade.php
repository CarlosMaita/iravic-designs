@if (count($customer->orders))
<div class="tab-pane fade @if($showRefundsTab) show active @endif" id="refunds" role="tabpanel" aria-labelledby="refunds-tab">
            <div class="row"> 
            <a href="{{ route('devoluciones.create') }}?cliente={{ $customer->id }}" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_a') }}</a>
        </div>
        <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('dashboard.refunds._datatable', ['refunds' => $refunds])
            </div>
        </div>
    </div>
</div>
@endif