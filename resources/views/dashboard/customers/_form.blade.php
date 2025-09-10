<fieldset>
    <div class="row mb-4">
        <div class="col-12">
            <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_personal_info') }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="name">{{ __('dashboard.form.fields.customers.name') }}</label>
                <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $customer->name) }}" autofocus>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="email">{{ __('dashboard.form.fields.customers.email') }}</label>
                <input class="form-control" id="email" name="email" type="text" value="{{ old("email", $customer->email) }}" autofocus>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="dni">{{ __('dashboard.form.fields.customers.dni') }}</label>
                <input class="form-control" id="dni" name="dni" type="text" value="{{ old("dni", $customer->dni) }}">
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="cellphone">{{ __('dashboard.form.fields.customers.cellphone') }}</label>
                <input class="form-control" id="cellphone" name="cellphone" type="text" value="{{ old("cellphone", $customer->cellphone) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="qualification">{{ __('dashboard.form.fields.customers.qualification') }}</label>
                <select id="qualification" class="form-control" name="qualification">
                    <option selected disabled>Seleccionar</option>
                    @foreach($qualifications as $q)
                    <option value="{{$q}}" @if($customer->qualification == $q) selected @endif>{{$q}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</fieldset>
<hr>
<fieldset>
    <div class="row mb-4">
        <div class="col-12">
            <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_shipping_info') }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="shipping_agency">{{ __('dashboard.form.fields.customers.shipping_agency') }}</label>
                <input class="form-control" id="shipping_agency" name="shipping_agency" type="text" value="{{ old("shipping_agency", $customer->shipping_agency) }}">
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="shipping_agency_address">{{ __('dashboard.form.fields.customers.shipping_agency_address') }}</label>
                <textarea class="form-control" id="shipping_agency_address" name="shipping_agency_address" rows="3">{{ old("shipping_agency_address", $customer->shipping_agency_address) }}</textarea>
            </div>
        </div>
    </div>
</fieldset>