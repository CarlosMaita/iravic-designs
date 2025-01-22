<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="motive">Motivo</label>
            <input type="text" class="form-control" id="motive" name="motive" disabled>
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="customer">{{ __('dashboard.form.fields.payments.customer') }}</label>
            <input type="text" class="form-control" id="customer" name="customer" disabled>
        </div> 
    </div>
    <div class="col-6">
        <div class="form-group">
            <label for="suggested_collection_amount">{{ __('dashboard.form.fields.payments.suggested_collection_amount') }}</label>
            <input type="text" class="form-control" id="suggested_collection_amount" name="suggested_collection_amount" disabled>
        </div> 
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="amount">{{ __('dashboard.form.fields.payments.amount_collection') }}</label>
            <input class="form-control" id="amount" name="amount" type="number" min="0" step="any">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
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
            <textarea class="form-control" name="comment" id="comment" cols="30" rows="3"></textarea>
        </div>
    </div>
</div>