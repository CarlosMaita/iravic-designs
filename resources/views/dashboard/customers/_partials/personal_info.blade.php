<div class="tab-pane fade @if(!$showOrdersTab && !$showRefundsTab) show active @endif" id="info" role="tabpanel" aria-labelledby="info-tab">
    <div class="row mb-4 mt-3">
        <div class="col-12">
            <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_personal_info') }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.name') }}</label>
                <input class="form-control" type="text" value="{{ $customer->name }}" readOnly>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.email') }}</label>
                <input class="form-control" type="text" value="{{ $customer->email }}" readOnly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.dni') }}</label>
                <input class="form-control" type="text" value="{{ $customer->dni }}" readOnly>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.cellphone') }}</label>
                <input class="form-control" type="text" value="{{ $customer->cellphone }}" readOnly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.qualification') }}</label>
                <input class="form-control" type="text" value="{{ $customer->qualification }}" readOnly>
            </div>
        </div>
    </div>
    
    <hr>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_shipping_info') }}</small>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.shipping_agency') }}</label>
                    <input class="form-control" type="text" value="{{ $customer->shipping_agency }}" readOnly>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.shipping_agency_address') }}</label>
                    <textarea class="form-control" rows="3" readOnly>{{ $customer->shipping_agency_address }}</textarea>
                </div>
            </div>
        </div>
        
        @if($customer->hasCompleteShippingInfo())
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> Cliente tiene información de envío completa
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i> 
                        Cliente no tiene información de envío completa. Faltan: {{ implode(', ', $customer->getMissingShippingFields()) }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>