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
                <label for="telephone">{{ __('dashboard.form.fields.customers.telephone') }}</label>
                <input class="form-control" id="telephone" name="telephone" type="text" value="{{ old("telephone", $customer->telephone) }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="cellphone">{{ __('dashboard.form.fields.customers.cellphone') }}</label>
                <input class="form-control" id="cellphone" name="cellphone" type="text" value="{{ old("cellphone", $customer->cellphone) }}">
            </div>
        </div>
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
    <div class="row">
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
                <label for="days_to_notify_debt">{{ __('dashboard.form.fields.customers.days_to_notify_debt') }}</label>
                <input class="form-control" id="days_to_notify_debt" name="days_to_notify_debt" type="number" step="1" value="{{ old("days_to_notify_debt", $customer->days_to_notify_debt) }}">
            </div>
        </div>
    </div>
    <div class="row">
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

        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="card_front">{{ __('dashboard.form.fields.customers.card_front') }}</label>
                <div class="custom-file">
                    <input accept="image/*" type="file" class="custom-file-input" id="card_front" name="card_front" lang="es">
                    <label class="custom-file-label" for="card_front">Seleccionar Archivo</label>
                </div>
                <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                    <img id="img-card_front" class="mt-3 img-fluid  @if(!$customer->card_front) d-none @endif" src="{{ $customer->url_card_front }}" alt="{{ __('dashboard.form.fields.customers.card_front') }}" />
                    @if($customer->card_front) 
                        <span class="delete-img position-absolute" type="button" data-target="card_front"><i class="fa fa-times-circle"></i></span>
                        <a href="#" class="cancel-delete-img d-none badge badge-dark">Recuperar imagen</a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="card_back">{{ __('dashboard.form.fields.customers.card_back') }}</label>
                <div class="custom-file">
                    <input accept="image/*" type="file" class="custom-file-input" id="card_back" name="card_back" lang="es">
                    <label class="custom-file-label" for="card_back">Seleccionar Archivo</label>
                </div>
                <div class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                    <img id="img-card_back" class="mt-3 img-fluid  @if(!$customer->card_back) d-none @endif" src="{{ $customer->url_card_back }}" alt="{{ __('dashboard.form.fields.customers.card_back') }}" />
                    @if($customer->card_back) 
                        <span class="delete-img position-absolute" type="button" data-target="card_back"><i class="fa fa-times-circle"></i></span>
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
        <div class="col-md-12 mt-5 col-md-offset-3 text-center">
            <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" id="location-switch">
                <label class="custom-control-label" for="location-switch">Buscar Ubicación por Latitud y Longitud</label>
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
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="latitude_search">Latitud</label>
                <input class="form-control" id="latitude_search" type="text" value="{{ $customer->latitude }}" disabled>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="longitude_search">Longitud</label>
                <input class="form-control" id="longitude_search" type="text" value="{{ $customer->longitude }}" disabled>
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