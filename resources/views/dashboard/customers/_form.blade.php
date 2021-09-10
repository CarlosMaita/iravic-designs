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
                <label for="qualification">{{ __('dashboard.form.fields.customers.qualification') }}</label>
                <select id="qualification" class="form-control" name="qualification">
                    <option selected disabled>Seleccionar</option>
                    <option value="Muy Bueno" @if($customer->qualification == "Muy Bueno") selected @endif>Muy Bueno</option>
                    <option value="Bueno" @if($customer->qualification == "Bueno") selected @endif>Bueno</option>
                    <option value="Malo" @if($customer->qualification == "Malo") selected @endif>Malo</option>
                    <option value="Muy Malo" @if($customer->qualification == "Muy Malo") selected @endif>Muy Malo</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="telephone">{{ __('dashboard.form.fields.customers.telephone') }}</label>
                <input class="form-control" id="telephone" name="telephone" type="text" value="{{ old("telephone", $customer->telephone) }}">
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
                <label for="dni">{{ __('dashboard.form.fields.customers.dni') }}</label>
                <input class="form-control" id="dni" name="dni" type="text" value="{{ old("dni", $customer->dni) }}">
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="dni_picture">{{ __('dashboard.form.fields.customers.dni_picture') }}</label>
                <div class="custom-file">
                    <input accept="image/*" type="file" class="custom-file-input" id="dni_picture" name="dni_picture" lang="es">
                    <label class="custom-file-label" for="dni_picture">Seleccionar Archivo</label>
                </div>
                <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                    <img id="img-dni_picture" class="mt-3 img-fluid @if(!$customer->dni_picture) d-none @endif" src="{{ $customer->url_dni }}" alt="{{ __('dashboard.form.fields.customers.dni_picture') }}" />
                    @if($customer->dni_picture) 
                        <span class="delete-img position-absolute" type="button" data-target="dni_picture"><i class="fa fa-times-circle"></i></span>
                        <a href="#" class="cancel-delete-img d-none badge badge-dark">Recuperar imagen</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</fieldset>
<hr>
<fieldset>
    <div class="row mb-4">
        <div class="col-12">
            <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_finance_info') }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="max_credit">{{ __('dashboard.form.fields.customers.max_credit') }}</label>
                <input class="form-control" id="max_credit" name="max_credit" type="number" step="any" value="{{ old("max_credit", $customer->max_credit) }}">
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="receipt_picture">{{ __('dashboard.form.fields.customers.receipt_picture') }}</label>
                <div class="custom-file">
                    <input accept="image/*" type="file" class="custom-file-input" id="receipt_picture" name="receipt_picture" lang="es">
                    <label class="custom-file-label" for="receipt_picture">Seleccionar Archivo</label>
                </div>
                <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                    <img id="img-receipt_picture" class="mt-3 img-fluid  @if(!$customer->receipt_picture) d-none @endif" src="{{ $customer->url_receipt }}" alt="{{ __('dashboard.form.fields.customers.receipt_picture') }}" />
                    @if($customer->receipt_picture) 
                        <span class="delete-img position-absolute" type="button" data-target="receipt_picture"><i class="fa fa-times-circle"></i></span>
                        <a href="#" class="cancel-delete-img d-none badge badge-dark">Recuperar imagen</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</fieldset>
<hr>
<fieldset>
    <div class="row mb-4">
        <div class="col-12">
            <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_contact_info') }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="contact_name">{{ __('dashboard.form.fields.customers.contact_name') }}</label>
                <input class="form-control" id="contact_name" name="contact_name" type="text" value="{{ old("contact_name", $customer->contact_name) }}">
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="contact_telephone">{{ __('dashboard.form.fields.customers.contact_telephone') }}</label>
                <input class="form-control" id="contact_telephone" name="contact_telephone" type="text" value="{{ old("contact_telephone", $customer->contact_telephone) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="contact_dni">{{ __('dashboard.form.fields.customers.contact_dni') }}</label>
                <input class="form-control" id="contact_dni" name="contact_dni" type="text" value="{{ old("contact_dni", $customer->contact_dni) }}">
            </div>
        </div>
    </div>
</fieldset>
<hr>
<fieldset>
    <div class="row mb-4">
        <div class="col-12">
            <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_address_info') }}</small>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="zone">{{ __('dashboard.form.fields.customers.zone') }}</label>
                <select id="zone" class="form-control" name="zone_id">
                    <option selected disabled>Seleccionar</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone->id }}" @if($customer->zone_id == $zone->id) selected @endif>{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label class="control-label" for="address">{{ __('dashboard.form.fields.customers.address') }}:</label>
                <textarea id="address" name="address" rows="2" cols="1" class="form-control">{{ old("address", $customer->address)}}</textarea>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-md-12">
            <div id="map-customer" style="height: 300px;"></div>
            <input id="latitude" name="latitude" type="hidden" value='{{old("latitude", $customer->latitude)}}'>
            <input id="longitude" name="longitude" type="hidden" value='{{old("longitude", $customer->longitude)}}'>
        </div>  
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="form-group">
                <label for="address_picture">{{ __('dashboard.form.fields.customers.address_picture') }}</label>
                <div class="custom-file">
                    <input accept="image/*" type="file" class="custom-file-input" id="address_picture" name="address_picture" lang="es">
                    <label class="custom-file-label" for="address_picture">Seleccionar Archivo</label>
                </div>
                <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                    <img id="img-address_picture" class="mt-3 img-fluid @if(!$customer->address_picture) d-none @endif" src="{{ $customer->url_address }}" alt="{{ __('dashboard.form.fields.customers.address_picture') }}" />
                    @if($customer->address_picture) 
                        <span class="delete-img position-absolute" type="button" data-target="address_picture"><i class="fa fa-times-circle"></i></span>
                        <a href="#" class="cancel-delete-img d-none badge badge-dark">Recuperar imagen</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</fieldset>