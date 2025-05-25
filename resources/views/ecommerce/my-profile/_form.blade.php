<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $user->name) }}" required autofocus>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="email">{{ __('E-Mail Address') }}</label>
            <input class="form-control" id="email" name="email" type="text" value="{{ old("name", $user->email) }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <input class="form-control" id="password" name="password" type="password" placeholder="Min 6 caracteres">
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="password_confirmation">{{ __('Confirm Password') }}</label>
            <input class="form-control" id="password_confirmation" name="password_confirmation" type="password">
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="current_password">{{ __('dashboard.my-profile.enter-current-password') }} 
                <button class="btn p-0" type="button" data-container="body" data-toggle="popover" data-placement="top" data-content="{{ __('dashboard.my-profile.info-password') }}"><i class="fas fa-info-circle"></i></button>
            </label>
            <input class="form-control" id="current_password" name="current_password" type="password">
        </div>
    </div>
</div>