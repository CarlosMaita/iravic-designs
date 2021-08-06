<div id="modal-new-customer" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="form-payments" method="POST" action="#">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('dashboard.payments._form')
                </div>
                <div class="modal-footer">
                    <button id="btn-cancel-payment" class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                    <button class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>