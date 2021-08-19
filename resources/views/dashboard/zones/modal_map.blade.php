<div id="modal-map" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Zona {{ $zone->date }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-2">
                            <img src="http://maps.google.com/mapfiles/ms/icons/green-dot.png" alt="Bueno">
                            <span class="mr-4">Bueno</span>
                        </div>
                        <div class="col-md-4 mt-2">
                            <img src="http://maps.google.com/mapfiles/ms/icons/yellow-dot.png" alt="Malo">
                            <span class="mr-4">Malo</span>
                        </div>
                        <div class="col-md-4 mt-2">
                            <img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png" alt="Muy Malo">
                            <span>Muy Malo</span>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div id="map-zone" style="height: 300px;"></div>
                        </div>  
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
            </div>
        </div>
    </div>
</div>