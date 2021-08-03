<div id="modal-product" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                

                
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Genero</label>
                            <input type="text" value="" readOnly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Categoría</label>
                            <input type="text" value="" readOnly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Marca</label>
                            <input type="text" value="" readOnly>
                        </div>
                    </div>

                    Color
                    Talla
                    Cantidad Disponible
                    Agregrar
                </div>


            </div>
            <div class="modal-footer">
                <button id="btn-cancel-product" class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                <button class="btn btn-primary">Agregar</button>
            </div>
        </div>
    </div>
</div>