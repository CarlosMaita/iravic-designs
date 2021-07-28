<div id="modal-new-customer" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form-customers" method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <input type="hidden" name="without_flash" value="0">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('dashboard.customers-management.customers.create') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('dashboard.customers-management.customers._form')
                </div>
                <div class="modal-footer">
                    <button id="btn-cancel-new-customer" class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                    <button class="btn btn-primary">{{ __('dashboard.form.create') }} {{ __('dashboard.boxes-sales.orders.customer') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>