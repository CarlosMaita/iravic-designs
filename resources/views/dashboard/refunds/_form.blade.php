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
                                <li id="products-refund-step"><strong>{{ __('dashboard.form.fields.orders.products_refund') }}</strong></li>
                                <li id="products-order-step"><strong>{{ __('dashboard.form.fields.orders.products_change') }}</strong></li>
                                <li id="payment-step"><strong>{{ __('dashboard.form.fields.orders.payment') }}</strong></li>
                                <li id="confirm-step"><strong>{{ __('dashboard.form.fields.orders.finish') }}</strong></li>
                            </ul> <!-- fieldsets -->
                            {{--  --}}
                            <fieldset>
                                <div class="form-card">
                                    <h2 class="fs-title">{{ __('dashboard.form.fields.orders.customer_information') }}</h2>
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="customer">{{ __('dashboard.form.fields.orders.customer') }}</label>
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
                                                                @if(!empty($customerParam) && $customerParam->id == $customer->id) selected @endif
                                                        >{{ $customer->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="customer-selected-container" class="row @if (!$customerParam) d-none @endif">
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
                                                        <input id="selected-customer-name" class="form-control" type="text" @if ($customerParam) value="{{ $customerParam->name }}" @endif readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.dni') }}</label>
                                                        <input id="selected-customer-dni" class="form-control" type="text"  @if ($customerParam) value="{{ $customerParam->dni }}" @endif readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.telephone') }}</label>
                                                        <input id="selected-customer-telephone" class="form-control" type="text" @if ($customerParam) value="{{ $customerParam->telephone }}" @endif readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.qualification') }}</label>
                                                        <input id="selected-customer-qualification" class="form-control" type="text" @if ($customerParam) value="{{ $customerParam->qualification }}" @endif readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.max_credit') }}</label>
                                                        <input id="selected-customer-maxcredit" class="form-control" type="text" @if ($customerParam) value="{{ $customerParam->max_credit }}" @endif readOnly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.customers.balance') }}</label>
                                                        <input id="selected-customer-balance" class="form-control" type="text" @if ($customerParam) value="{{ $customerParam->balance }}" @endif readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>{{ __('dashboard.form.fields.customers.address') }}</label>
                                                        <input id="selected-customer-address" class="form-control" type="text" @if ($customerParam) value="{{ $customerParam->address }}" @endif readOnly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-info next action-button" type="button" data-step="1">{{ __('dashboard.general.next') }}</button>
                            </fieldset>
                            {{--  --}}
                            <fieldset>
                                <div class="form-card px-md-4 px-2">
                                    <h2 class="fs-title">Productos que puede devolver</h2> 
                                    <div class="row">
                                        <div class="col-md-6 mx-auto">
                                            <div class="form-group">
                                                <label for="product_refund">{{ __('dashboard.form.fields.orders.product') }}</label>
                                                <div class="input-group mb-3">
                                                    <select class="form-control" id="product_refund">
                                                        <option selected disabled>Seleccionar</option>
                                                        @foreach ($productsForRefund as $product)
                                                            <option value="{{ $product->id }}" 
                                                                data-id="{{ $product->id }}"
                                                                data-brand="{{ $product->brand_name }}"
                                                                data-category="{{ $product->category_name }}"
                                                                data-code="{{ $product->code }}"
                                                            >
                                                                {{ $product->name }} - Cod:{{ $product->code }} 
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="add-product-refund" type="button"><i class="fa fa-plus"></i></span>
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
                                                <table id="datatable_products_refund" class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Compra</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.general.name') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.code') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.gender') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.brand') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.category') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.color') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.size') }}</th>
                                                            <th scope="col">{{ __('dashboard.form.fields.products.price') }}</th>
                                                            <th scope="col">Pagado a Credito</th>
                                                            <th scope="col">Disponible</th>
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
                                            <p class="font-weight-bold text-dark">Total pagado en Crédito: <span class="total-refund-credit">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark">Total pagado otros: <span class="total-refund-others">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark">Total en devolución: <span class="total-refund">$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div> 
                                {{--  --}}
                                <button class="btn btn-secondary previous action-button-previous" type="button">{{ __('dashboard.general.previous') }}</button>
                                <button class="btn btn-info next action-button" type="button" data-step="2">{{ __('dashboard.general.next') }}</button>
                            </fieldset>
                            {{--  --}}
                            <fieldset>
                                <div class="form-card px-md-4 px-2">
                                    <h2 class="fs-title">{{ __('dashboard.form.fields.orders.products_information') }} que se puede llevar</h2> 
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
                                                                    data-category="{{ $product->category->name }}"
                                                                    data-code="{{ $product->code }}"

                                                                    @if($product->stock_user < 1) disabled @endif
                                                                >
                                                                    {{ $product->name }} - Cod:{{ $product->code }} 
                                                                    @if($product->stock_user < 1) (SIN STOCK) @endif
                                                                </option>
                                                            @elseif($product->product_id)
                                                                <option value="{{ $product->id }}" 
                                                                    data-id="{{ $product->id }}"
                                                                    data-brand="{{ $product->brand->name }}"
                                                                    data-category="{{ $product->category->name }}"
                                                                    data-code="{{ $product->code }}"
                                                                    @if($product->stock_user < 1) disabled @endif
                                                                >
                                                                    {{ $product->name }} (T: {{ optional($product->size)->name }} - Color: {{ optional($product->color)->name }}) - Cod: {{ $product->real_code }} 
                                                                    @if($product->stock_user < 1) (SIN STOCK) @endif
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
                                            <p class="font-weight-bold text-dark">Total: <span class="total-order">$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div> 
                                {{--  --}}
                                <button class="btn btn-secondary previous action-button-previous" type="button">{{ __('dashboard.general.previous') }}</button>
                                <button class="btn btn-info next action-button" type="button" data-step="3">{{ __('dashboard.general.next') }}</button>
                            </fieldset>
                            {{--  --}}
                            <fieldset>
                                <div id="card-payment" class="form-card">
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
                                            <p class="font-weight-bold text-dark mb-1">Deuda Total: <span class="total-debt">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark mb-1">Saldo: <span class="customer-balance">$ 0.00</span></p>
                                            <hr>
                                            <div>
                                                <span>Quiere aplicar algun descuento?</span>
                                                <button id="open-modal-discount" class="btn btn-light" type="button">Aplicar Descuento</button>
                                            </div>
                                            <hr>
                                            <p class="font-weight-bold text-dark mb-1">Subtotal: <span class="subtotal-order">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark mb-1">Descuento: <span class="discount">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark mb-1">Total: <span class="total-order">$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div> 
                                {{--  --}}
                                <button class="btn btn-secondary previous action-button-previous" type="button">{{ __('dashboard.general.previous') }}</button>
                                <button class="btn btn-info next action-button" type="button" data-step="4">{{ __('dashboard.general.next') }}</button>
                            </fieldset>
                            {{--  --}}
                            <fieldset>
                                <div id="resume-products-refund" class="form-card">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Resumen Productos a devolver</small>
                                        </div>
                                    </div>
                                    {{--  --}}
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table id="datatable_products_resume_refund" class="table" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">venta</th>
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
                                </div>
                                <hr>
                                <div id="resume-products-order" class="form-card">
                                    <div class="row mb-4">
                                        <div class="col-12">
                                            <small class="form-text text-muted font-weight-bold text-success">Resumen Productos a llevarse</small>
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
                                </div>
                                <div class="form-card">
                                    <h2 class="fs-title text-center mt-4">Confirmar Devolución</h2>
                                    <div class="row mt-3 mb-4">
                                        <div class="col-12">
                                            <p class="font-weight-bold text-dark text-center mb-0">Total Devolución: <span class="total-refund">$ 0.00</span></p>
                                            <p class="font-weight-bold text-dark text-center mb-0">Total Nuevo venta: <span class="total-order">$ 0.00</span></p>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-secondary previous action-button-previous" type="button">{{ __('dashboard.general.previous') }}</button>
                                <button class="btn btn-success" type="submit">{{ __('dashboard.form.create') }}</button>
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
            width: 20%;
            float: left;
            position: relative
        }
        #progressbar #customer-step:before {
            content: "1"
        }
        #progressbar #products-refund-step:before {
            content: "2"
        }
        #progressbar #products-order-step:before {
            content: "3"
        }
        #progressbar #payment-step:before {
            content: "4"
        }
        #progressbar #confirm-step:before {
            content: "5"
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