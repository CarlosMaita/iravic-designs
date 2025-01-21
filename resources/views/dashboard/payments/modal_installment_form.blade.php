<div id="modal-payments-installments" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <form id="form-payment-installments">
            @csrf
            <input type="hidden" id="customer_id" name="customer_id" value="">
            <input type="hidden" id="visit_date_now" name="visit_date_now" value="">

            @if (isset($box))
                <input type="hidden" name="box_id" value="{{ $box->id }}">
            @endif
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('dashboard.payments._installment_form')
                </div>
                <div class="modal-footer">
                    <button id="btn-cancel-payment" class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                    <button class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>