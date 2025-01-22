<div id="modal-visits" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="form-visits">
            @csrf
            @if (isset($customer))
                <input type="hidden" name="customer_id" value="{{ $customer->id }}">
            @endif
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('dashboard.visits._form', ['fromCustomer' => true])
                </div>
                <div class="modal-footer">
                    <button id="btn-cancel-visit" class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                    <button class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>