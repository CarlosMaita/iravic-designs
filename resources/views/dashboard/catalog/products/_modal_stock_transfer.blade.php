<div id="modal-stock-transfer" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="stock-transfer-form" action="{{ route('stock-transferencias.store') }}" method='POST'>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Transferencia de Stock</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @csrf
                    <input type="hidden" name="product_id">
                    <input type="hidden" name="stock_origin">
                    <input type="hidden" name="stock_destination">
                    <div class="style-dialog-body css-g4fmj2">
                        <div class="css-pmqufv"></div>
                        <div data-bn-type="text" class="css-oxzfpw">Las transferencias de stocks tienen que ser aceptadas.</div>
                        <div class="css-7tkdw6">
                            <div class="css-1kzpntp">
                                <div class="css-vwdmr0">
                                    <div class="css-13zymhf"></div>
                                    <div class="css-11mpmlu"></div>
                                    <div class="css-1a1w98z"></div>
                                    <div class="css-38fup1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="css-124czaz"><path d="M2 10V8l5-5 1.414 1.414L4.83 8h17.17v2H2zM22 14v2l-5 5-1.414-1.414L19.172 16H2v-2h20z" fill="#76808F"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="css-1pysja1">
                                <div class="css-bz1qgm">
                                    <div class="css-1efkrz1">
                                        <div class="css-yc6oq3">
                                            <label class="css-51ezhr">De</label>
                                            <div class="css-1xcjeua">
                                                <div id="stock-origin" class="css-1pysja1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="css-bz1qgm">
                                    <div class="css-1efkrz1">
                                        <div class="css-1pxm4lx">
                                            <label class="css-51ezhr">A</label>
                                            <div class="css-1xcjeua">
                                                <div id="stock-destination" class="css-1pysja1"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="css-oxzfpw">
                            <div class="css-1pxm4lx">
                                <label class="css-51ezhr">
                                    <div class="css-cr60ng">
                                        <div data-bn-type="text" class="css-vurnku"><label for="Stock">Stock</label></div>
                                        <div class="css-19gx5t6">
                                            <span id="stock-available" data-bn-type="text" class="css-6hm6tl">0</span>
                                            disponibles
                                        </div>
                                    </div>
                                </label>
                                <div class=" css-17mzxiv">
                                    <input id="stock-transfer" 
                                            name="qty" 
                                            data-bn-type="input" 
                                            autocomplete="off" 
                                            class="css-16fg16t" 
                                            type="number" 
                                            min="0" 
                                            step="1" 
                                            value=""
                                    >
                                    <div class="bn-input-suffix css-vurnku">
                                        <div class="css-1ii0qmr">
                                            <div id="btn-max" data-bn-type="text" class="css-3bhv7e">MÁX.</div>
                                        </div>
                                    </div>
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