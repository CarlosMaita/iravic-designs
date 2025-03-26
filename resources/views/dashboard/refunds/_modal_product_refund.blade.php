<div id="modal-product-refund" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Formulario Producto</span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label><b>Nombre</b></label>
                                <p class="product-name font-weight-normal"></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label><b>Código</b></label>
                                <p class="product-code font-weight-normal"></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label><b>Categoría</b></label>
                                <p class="product-category font-weight-normal"></p>
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <div class="form-group">
                                <label><b>Marca</b></label>
                                <p class="product-brand font-weight-normal"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="product-add-refund-qty">
                </div>
            </div>
            <div class="modal-footer">
                <button id="btn-cancel-product" class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                <button id="add-product-modal-refund" class="btn btn-primary">Agregar</button>
            </div>
        </div>
    </div>
</div>