<div id="modal-discount" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aplicar descuento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label>Total actual</label>
                        <p class="total font-weight-bold"></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="discount-input">Descuento</label>
                            <input id="discount-input" class="form-control" name="discount" type="number" min="0" step="any">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="discount_password">Contrasena para descuentos</label>
                            <input id="discount_password" class="form-control" name="discount_password" type="password">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-apply-discount" class="btn btn-primary" type="button">Aplicar</button>
                <button class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
            </div>
        </div>
    </div>
</div>