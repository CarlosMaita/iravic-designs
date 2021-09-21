@if (isset($customers) && !isset($customer))
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="customer">{{ __('dashboard.form.fields.payments.customer') }}</label>
            <select class="form-control" id="customer" name="customer_id">
                <option disabled selected>Seleccionar</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
@endif
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="amount">{{ __('dashboard.form.fields.payments.amount') }}</label>
            <input class="form-control" id="amount" name="amount" type="number" min="0" step="any">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="payment_method">{{ __('dashboard.form.fields.payments.payment_method') }}</label>
            <select class="form-control" id="payment_method" name="payment_method">
                <option disabled selected>Seleccionar</option>
                <option value="cash">Efectivo</option>
                <option value="card">Tarjeta</option>
                <option value="bankwire">Transferencia</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="comment">{{ __('dashboard.form.fields.payments.comment') }}</label>
            <textarea class="form-control" name="comment" id="comment" cols="30" rows="5"></textarea>
        </div>
    </div>
</div>