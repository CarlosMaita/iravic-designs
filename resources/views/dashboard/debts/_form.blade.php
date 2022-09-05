@if (isset($customers) && !isset($customer))
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="customer">{{ __('dashboard.form.fields.debts.customer') }}</label>
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
            <label for="amount">{{ __('dashboard.form.fields.debts.amount') }}</label>
            <input class="form-control" id="amount" name="amount" type="number" min="0" step="any">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="comment">{{ __('dashboard.form.fields.debts.comment') }}</label>
            <textarea class="form-control" name="comment" id="comment" cols="30" rows="5"></textarea>
        </div>
    </div>
</div>