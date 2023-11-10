<div class="tab-pane fade @if(!$showOrdersTab) show active @endif" id="info" role="tabpanel" aria-labelledby="info-tab">
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
                <label>{{ __('dashboard.form.fields.customers.qualification') }}</label>
                <input class="form-control" type="text" value="{{ $customer->qualification }}" readOnly>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.telephone') }}</label>
                <input class="form-control" type="text" value="{{ $customer->telephone }}" readOnly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.cellphone') }}</label>
                <input class="form-control" type="text" value="{{ $customer->cellphone }}" readOnly>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label>{{ __('dashboard.form.fields.customers.dni') }}</label>
                <input class="form-control" type="text" value="{{ $customer->dni }}" readOnly>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="dni_picture">{{ __('dashboard.form.fields.customers.dni_picture') }}</label>
                {{-- @if($customer->dni_picture)  --}}
                <div class="img-wrapper mt-3 mx-auto text-center" style="max-width: 320px;">
                    <img id="img-dni_picture" class="mt-3 img-fluid" src="{{ $customer->url_dni }}" alt="{{ __('dashboard.form.fields.customers.dni_picture') }}" />
                </div>
                {{-- @endif --}}
            </div>
        </div>
    </div>
    <hr>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_finance_info') }}</small>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.max_credit') }}</label>
                    <input class="form-control" type="text" value="{{ $customer->max_credit }}" readOnly>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for="receipt_picture">{{ __('dashboard.form.fields.customers.receipt_picture') }}</label>
                    
                    {{-- @if($customer->receipt_picture)  --}}
                    <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                        <img id="img-receipt_picture" class="mt-3 img-fluid" src="{{ $customer->url_receipt }}" alt="{{ __('dashboard.form.fields.customers.receipt_picture') }}" />
                    </div>
                    {{-- @endif --}}
                </div>
            </div>
        </div>
        <div class="row">
            @if ($customer->card_front)
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="receipt_picture">{{ __('dashboard.form.fields.customers.card_front') }}</label>
                        
                        <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                            <img id="img-receipt_picture" class="mt-3 img-fluid" src="{{ $customer->url_card_front }}" alt="{{ __('dashboard.form.fields.customers.card_front') }}" />
                        </div>
                    </div>
                </div>
            @endif

            @if ($customer->card_back)
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="receipt_picture">{{ __('dashboard.form.fields.customers.card_back') }}</label>
                        
                        <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                            <img id="img-receipt_picture" class="mt-3 img-fluid" src="{{ $customer->url_card_back }}" alt="{{ __('dashboard.form.fields.customers.card_front') }}" />
                        </div>
                    </div>
                </div>
            @endif
            
            
        </div>
    </div>
    <hr>
    <div  class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_contact_info') }}</small>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.contact_name') }}</label>
                    <input class="form-control" type="text" value="{{ $customer->contact_name }}" readOnly>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.contact_telephone') }}</label>
                    <input class="form-control" type="text" value="{{ $customer->contact_telephone }}" readOnly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.contact_dni') }}</label>
                    <input class="form-control" type="text" value="{{ $customer->contact_dni }}" readOnly>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div  class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_address_info') }}</small>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.zone') }}</label>
                    <input class="form-control" type="text" value="{{ optional($customer->zone)->name }}" readOnly>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.address') }}:</label>
                    <input class="form-control" type="text" value="{{ $customer->address }}" readOnly>
                </div>
            </div>
        </div>
        <div class="row mb-5 mt-3">
            <div class="col-md-12">
                <div id="map-customer" style="height: 300px;"></div>
            </div>  
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>{{ __('dashboard.form.fields.customers.address_picture') }}</label>
                    <div class="img-wrapper mt-3 mx-auto text-center" style="max-width: 320px;">
                        <img class="mt-3 img-fluid" src="{{ $customer->url_address }}" alt="{{ __('dashboard.form.fields.customers.address_picture') }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>