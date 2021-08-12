<div id="modal-visits" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <form id="form-visits" method="POST">
            @csrf
            <input type="hidden" name="_method" value="PUT">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Actualizar responsable</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="visit-responsable">Responsable</label>
                                    <select class="form-control" name="user_responsable_id" id="visit-responsable">
                                        <option selected disabled>Seleccionar</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-dismiss="modal">{{ __('dashboard.form.cancel') }}</button>
                    <button class="btn btn-primary">Aceptar</button>
                </div>
            </div>
        </form>
    </div>
</div>