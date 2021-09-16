<div id="modal-map" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agenda {{ $schedule->date }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="zones-map">Zonas</label>
                                <select class="form-control" id="zones-map" multiple>
                                    @isset($zones)
                                        @foreach ($zones as $zone)
                                            <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    @php
                        $roles_name = Auth::user()->roles->flatten()->pluck('name');
                    @endphp
                    @if($roles_name->contains('superadmin') || $roles_name->contains('admin'))
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="role-map">Tipo</label>
                                <select class="form-control" id="role-map" multiple>
                                    <option value="Camión">Camión</option>
                                    <option value="Moto">Moto</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-md-3 col-6 mt-2">
                            <img src="http://maps.google.com/mapfiles/ms/icons/blue-dot.png" alt="Bueno">
                            <span class="mr-4">Muy Bueno</span>
                        </div>
                        <div class="col-md-3 col-6 mt-2">
                            <img src="http://maps.google.com/mapfiles/ms/icons/green-dot.png" alt="Bueno">
                            <span class="mr-4">Bueno</span>
                        </div>
                        <div class="col-md-3 col-6 mt-2">
                            <img src="http://maps.google.com/mapfiles/ms/icons/yellow-dot.png" alt="Malo">
                            <span class="mr-4">Malo</span>
                        </div>
                        <div class="col-md-3 col-6 mt-2">
                            <img src="http://maps.google.com/mapfiles/ms/icons/red-dot.png" alt="Muy Malo">
                            <span>Muy Malo</span>
                        </div>
                    </div>
                    <br>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div id="map-schedule" style="height: 300px;"></div>
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