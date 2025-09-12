<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">{{ __('dashboard.form.fields.users.name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $user->name) }}" autofocus>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="email">{{ __('dashboard.form.fields.users.email') }}</label>
            <input class="form-control" id="email" name="email" type="text" value="{{ old("name", $user->email) }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="password">{{ __('dashboard.form.fields.users.password') }}</label>
            <input class="form-control" id="password" name="password" type="password" placeholder="Min 6 caracteres">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="password_confirmation">{{ __('dashboard.form.fields.users.confirm_password') }}</label>
            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
        </div>
    </div>
</div>