<!-- MultiStep Form -->
<div class="container-fluid">
    <div class="row justify-content-center mt-0">
        <div class="col-12 text-center p-0 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3" style="z-index: 0;">
                {{-- <h2><strong>Sign Up Your User Account</strong></h2> --}}
                <p>{{ __('dashboard.form.labels.Complete each step until you reach the end') }}</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <div id="msform">
                            <!-- progressbar -->
                            <ul id="progressbar" class="p-0">
                                <li class="active" id="customer-step"><strong>{{ __('dashboard.form.fields.orders.customer') }}</strong></li>
                                <li id="products-step"><strong>{{ __('dashboard.form.fields.orders.products') }}</strong></li>
                                <li id="payment-step"><strong>{{ __('dashboard.form.fields.orders.payment') }}</strong></li>
                                <li id="confirm-step"><strong>{{ __('dashboard.form.fields.orders.finish') }}</strong></li>
                            </ul> <!-- fieldsets -->
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">{{ __('dashboard.form.fields.orders.customer_information') }}</h2>
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="customer">{{ __('dashboard.form.fields.orders.customer') }}</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control" name="customer_id" id="customer">
                                                        <option selected disabled>Seleccionar</option>
                                                        @foreach ($customers as $customer)
                                                            <option value="{{ $customer->id }}" 
                                                                    data-address="{{ $customer->address }}"
                                                                    data-dni="{{ $customer->dni }}"
                                                                    data-max-credit="{{ $customer->max_credit }}"
                                                                    data-max-credit-str="{{ $customer->max_credit_str }}"
                                                                    data-name="{{ $customer->name }}"
                                                                    data-telephone="{{ $customer->telephone }}"
                                                                    data-qualification="{{ $customer->qualification }}"
                                                                    data-debt="{{ $customer->total_debt }}"
                                                                    data-balance="{{ $customer->balance }}"
                                                                    data-balance-numeric="{{ $customer->balance_numeric }}"
                                                                    @if(!empty($customerParam) && $customerParam->id == $customer->id) selected @endif
                                                            >{{ $customer->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="add-customer" type="button"><i class="fa fa-plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="customer-selected-container" class="row d-none">
                                        <div class="col-12">
                                            <div class="row mb-4">
                                                <div class="col-12">
                                                    <small class="form-text text-muted font-weight-bold text-success">{{ __('dashboard.form.labels.customer_selected') }}</small>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.name') }}</label>
                                                        <input id="selected-customer-name" class="form-control" type="text" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.dni') }}</label>
                                                        <input id="selected-customer-dni" class="form-control" type="text" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.telephone') }}</label>
                                                        <input id="selected-customer-telephone" class="form-control" type="text" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.qualification') }}</label>
                                                        <input id="selected-customer-qualification" class="form-control" type="text" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.max_credit') }}</label>
                                                        <input id="selected-customer-maxcredit" class="form-control" type="text" readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.customers.balance') }}</label>
                                                        <input id="selected-customer-balance" class="form-control" type="text" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.address') }}</label>
                                                        <input id="selected-customer-address" class="form-control" type="text" readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-info next action-button" type="button" data-step="1">{{ __('dashboard.general.next') }}</button>
                            </fieldset>
                            <fieldset>
                                <div class="form-card px-md-4 px-2">
                                    <h2 class="fs-title">{{ __('dashboard.form.fields.orders.products_information') }}</h2> 
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="product">{{ __('dashboard.form.fields.orders.product') }}</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control" id="product">
                                                        <option selected disabled>Seleccionar</option>
                                                        @foreach ($products as $product)
                                                            @if ($product->is_regular)
                                                                <option value="{{ $product->id }}" 
                                                                    data-id="{{ $product->id }}"
                                                                    data-brand="{{ $product->brand->name }}"
                                                                    data-category="{{ optional($product->category)->name }}"
                                                                    data-code="{{ $product->code }}"
                                                                >
                                                                    {{ $product->name }}
                                                                </option>
                                                            @elseif($product->product_id)
                                                                <option value="{{ $product->id }}" 
                                                                    data-id="{{ $product->id }}"
                                                                    data-brand="{{ $product->brand->name }}"
                                                                    data-category="{{ optional($product->category)->name }}"
                                                                    data-code="{{ $product->code }}"
                                                                >
                                                                    {{ $product->name }} (T: {{ optional($product->size)->name }} - Color: {{ optional($product->color)->name }})
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="add-product" type="button"><i class="fa fa-plus"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--  --}}
                                    <hr>
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Productos seleccionados</small>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="datatable_products" class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">{{ __('dashboard.form.fields.general.name') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.code') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.gender') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.brand') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.category') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.color') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.size') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.price') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.available') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.qty') }}</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <p class="font-weight-bold text-dark">Total: <span class="total">$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div> 
                                {{--  --}}
                                <button class="btn btn-secondary previous action-button-previous" type="button">{{ __('dashboard.general.previous') }}</button>
                                <button class="btn btn-info next action-button" type="button" data-step="2">{{ __('dashboard.general.next') }}</button>
                            </fieldset>
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">{{ __('dashboard.form.fields.orders.payment_information') }}</h2>
                                    <div class="radio-group">
                                        <div class='radio' data-value="bankwire" for="bankwire">
                                            <label for="bankwire">{{ __('dashboard.form.fields.orders.bankwire') }}</label>
                                            <input id="bankwire" class="d-none" type="radio" name="payment_method" value="bankwire">
                                        </div>
                                        <div class='radio' data-value="cash" for="cash">
                                            <label for="cash">{{ __('dashboard.form.fields.orders.cash') }}</label>
                                            <input id="cash" class="d-none" type="radio" name="payment_method" value="cash">
                                        </div>
                                        <div class='radio' data-value="card" for="card">
                                            <label for="card">{{ __('dashboard.form.fields.orders.card') }}</label>
                                            <input id="card" class="d-none" type="radio" name="payment_method" value="card">
                                        </div>
                                        <div class='radio' data-value="credit" for="credit">
                                            <label for="credit">{{ __('dashboard.form.fields.orders.credit') }}</label>
                                            <input id="credit" class="d-none" type="radio" name="payment_method" value="credit">
                                        </div>
                                        <br>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="font-weight-bold text-dark mb-1">Crédito Máximo: <span class="max-credit">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark mb-1">Saldo: <span class="customer-balance">$ 0.00</span></p>
                                            <hr>
                                            <div>
                                                <span>Quiere aplicar algun descuento?</span>
                                                <button id="open-modal-discount" class="btn btn-light" type="button">Aplicar Descuento</button>
                                            </div>
                                            <hr>
                                            <p class="font-weight-bold text-dark mb-1">Subtotal: <span class="subtotal">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark mb-1">Descuento: <span class="discount">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark mb-1">Total: <span class="total">$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div> 
                                {{--  --}}
                                <button class="btn btn-secondary previous action-button-previous" type="button">{{ __('dashboard.general.previous') }}</button>
                                <button class="btn btn-info next action-button" type="button" data-step="3">{{ __('dashboard.general.next') }}</button>
                            </fieldset>
                            <fieldset>
                                <div class="form-card py-1">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Resumen Productos</small>
                                        </div>
                                    </div>
                                    {{--  --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="datatable_products_resume" class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">{{ __('dashboard.form.fields.general.name') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.code') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.gender') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.brand') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.category') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.color') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.size') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.price') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.qty') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="fs-title text-center mt-4">Confirmar venta</h2>
                                    <div class="row mt-3 mb-4">
                                        <div class="col-12">
                                            <p class="font-weight-bold text-dark text-center mb-0">Total: <span class="total">$ 0.00</span></p>
                                        </div>
                                    </div>
                                    {{--  --}}
                                    <div id="new-visit-container" class="mt-3 d-none">
                                        <hr>
                                        <div class="row mt-3">
                                            <div class="col-12">
                                                <div class="form-check form-check-inline mb-4">
                                                    <input class="form-check-input" type="checkbox" name="enable_new_visit" id="enable_new_visit" value="1">
                                                    <label class="form-check-label" for="enable_new_visit">¿Desea agendar una visita? El cliente tendrá deuda al final la compra</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="visit-fields" class="row d-none">
                                            <div class="col-md-6 mx-auto">
                                                @include('dashboard.visits._form', ['fromPayment' => true])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-secondary previous action-button-previous" type="button">{{ __('dashboard.general.previous') }}</button>
                                <button class="btn btn-success" type="submit">Confirmar</button>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        #msform {
            text-align: center;
            position: relative;
            margin-top: 20px
        }
        #msform fieldset .form-card {
            background: white;
            border: 0 none;
            border-radius: 0px;
            padding: 20px 40px 30px 40px;
            box-sizing: border-box;
            position: relative
        }
        #msform fieldset {
            background: white;
            border: 0 none;
            border-radius: 0.5rem;
            box-sizing: border-box;
            margin: 0;
            padding-bottom: 20px;
            position: relative;
            width: 100%;
        }
        #msform fieldset:not(:first-of-type) {
            display: none;
        }
        #msform fieldset .form-card {
            color: #9E9E9E;
            text-align: left;
        }

        select.list-dt {
            border: none;
            border-bottom: 1px solid #ccc;
            margin: 2px;
            outline: 0;
            padding: 2px 5px 3px 5px;
        }

        select.list-dt:focus {
            border-bottom: 2px solid skyblue
        }
        .fs-title {
            font-size: 25px;
            color: #2C3E50;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
        }
        #progressbar {
            color: lightgrey;
            margin-bottom: 30px;
            overflow: hidden;
        }
        #progressbar .active {
            color: #000000;
        }
        #progressbar li {
            list-style-type: none;
            font-size: 12px;
            width: 25%;
            float: left;
            position: relative
        }
        #progressbar #customer-step:before {
            content: "1"
        }
        #progressbar #products-step:before {
            content: "2"
        }
        #progressbar #payment-step:before {
            content: "3"
        }
        #progressbar #confirm-step:before {
            content: "4"
        }
        #progressbar li:before {
            width: 50px;
            height: 50px;
            line-height: 45px;
            display: block;
            font-size: 18px;
            color: #ffffff;
            background: lightgray;
            border-radius: 50%;
            margin: 0 auto 10px auto;
            padding: 2px
        }
        #progressbar li:after {
            background: lightgray;
            content: '';
            height: 2px;
            position: absolute;
            left: 0;
            top: 25px;
            width: 100%;
            z-index: -1;
        }
        #progressbar li.active:before,
        #progressbar li.active:after {
            background: #3c4b64
        }
        @media(max-width: 500px) {
            #progressbar li:before {
                width: 30px;
                height: 30px;
                line-height: 27px;
            }
            #progressbar li:after {
                top: 15px;
            }
        }
        .radio-group {
            position: relative;
            margin-bottom: 25px
        }
        .radio {
            display: inline-block;
            height: 50px;
            width: 22%;
            border-radius: 0;
            background: #3c4b64;
            box-sizing: border-box;
            cursor: pointer;
            margin: 8px 2px
        }
        @media(max-width: 500px) {
            .radio {
                width: 47%;
            }
        }
        .radio label {
            align-items: center;
            color: #fff;
            display: flex;
            justify-content: center;
            line-height: 50px;
            text-align: center;
            width: 100%;
        }
        .radio:hover {
            box-shadow: 1px 1px 1px 1px rgba(0, 0, 0, 0.3)
        }
        .radio.selected {
            box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.2);
            background: #07d807;
        }
        .fit-image {
            width: 100%;
            object-fit: cover
        }
    </style>
@endpush

{{-- 
* https://bbbootstrap.com/snippets/multi-step-form-wizard-30467045
* https://www.w3schools.com/howto/howto_js_form_steps.asp    
--}}