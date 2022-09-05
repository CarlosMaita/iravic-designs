<div id="modal-stock-modify" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="stock-modify-stock-form" action="{{ route('productos.stock.update') }}" method="POST">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modificar Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="product_id">
                    <input type="hidden" name="stock_column">
                    <input type="hidden" name="stock_name">
                    <div class="style-dialog-body css-g4fmj2">
                        <div class="css-oxzfpw">
                            <div class="css-1pxm4lx">
                                <label class="css-51ezhr">
                                    <div class="css-cr60ng">
                                        <div data-bn-type="text" class="css-vurnku"><label for="Stock">Stock </label></div>
                                        <div class="css-19gx5t6">
                                            <span data-bn-type="text" class="css-6hm6tl stock-origin"></span>
                                        </div>
                                    </div>
                                </label>
                                <div class=" css-17mzxiv">
                                    <input name="stock" 
                                            data-bn-type="input" 
                                            autocomplete="off" 
                                            class="css-16fg16t" 
                                            type="number" 
                                            min="0" 
                                            step="1"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                    <button class="btn btn-success" type="submit">{{ __('dashboard.form.accept') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>