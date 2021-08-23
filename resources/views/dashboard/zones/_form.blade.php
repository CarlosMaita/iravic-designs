<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $zone->name) }}" required autofocus>
        </div>
    </div>
</div>