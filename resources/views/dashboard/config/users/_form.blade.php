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
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="font-weight-bold">Notificaciones por Email</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="notify_new_order" name="notify_new_order" value="1" {{ old('notify_new_order', $user->notify_new_order) ? 'checked' : '' }}>
                <label class="form-check-label" for="notify_new_order">
                    Recibir notificación de Nueva Venta
                </label>
                <small class="form-text text-muted">
                    Al marcar esta opción, recibirás un correo electrónico cada vez que se registre una nueva orden en el sistema.
                </small>
            </div>
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" id="notify_new_payment" name="notify_new_payment" value="1" {{ old('notify_new_payment', $user->notify_new_payment) ? 'checked' : '' }}>
                <label class="form-check-label" for="notify_new_payment">
                    Recibir notificación de Nuevo Pago
                </label>
                <small class="form-text text-muted">
                    Al marcar esta opción, recibirás un correo electrónico cada vez que se registre un nuevo pago en el sistema.
                </small>
            </div>
        </div>
    </div>
</div>