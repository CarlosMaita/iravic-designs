<div class="tab-pane fade" id="payments" role="tabpanel" aria-labelledby="payments-tab">
            <div class="row"> 
            <a id="btn-create-payment" href="#" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_o') }}</a>
        </div>
        <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('dashboard.payments._datatable')
            </div>
        </div>
    </div>
</div>