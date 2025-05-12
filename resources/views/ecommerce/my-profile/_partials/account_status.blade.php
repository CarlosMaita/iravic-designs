<div class="tab-pane fade" id="account-status" role="tabpanel" aria-labelledby="account-status-tab">
    <div class="row mt-3">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('dashboard.customers.balance') }}</label>
                <input id="balance-text" class="form-control" type="text" value="{{ $customer->balance }}" readOnly>
            </div>
        </div>
    </div>
    <hr>
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('ecommerce.operations._datatable')
            </div>
        </div>
    </div>
</div>