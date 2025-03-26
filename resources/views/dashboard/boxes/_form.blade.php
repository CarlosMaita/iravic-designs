<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="cash_initial">{{ __('dashboard.form.fields.boxes.cash_initial') }}</label>
            <input class="form-control" id="cash_initial" name="cash_initial" type="number" step="any" value="{{ old("name", $box->cash_initial) }}" required autofocus>
        </div>
    </div>
</div>