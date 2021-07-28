<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="form-group">
            <label for="customer">{{ __('dashboard.form.fields.orders.customer') }}</label>
            <div class="input-group mb-3">
                <select class="form-control" name="customer_id" id="customer">
                    <option selected disabled>Seleccionar</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
                <div class="input-group-prepend">
                    <span class="input-group-text" id="add-customer" type="button"><i class="fa fa-plus"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>