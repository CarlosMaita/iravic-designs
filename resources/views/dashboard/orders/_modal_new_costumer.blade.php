<div id="modal-new-customer" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form-customers" method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <input type="hidden" name="without_flash" value="0">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('dashboard.customers.create') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('dashboard.customers._form')
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">{{ __('dashboard.form.create') }} {{ __('dashboard.orders.customer') }}</button>
                    <button id="btn-cancel-new-customer" class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>