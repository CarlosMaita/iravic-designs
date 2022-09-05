<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="amount">{{ __('dashboard.form.fields.spendings.amount') }}</label>
            <input class="form-control" id="amount" name="amount" type="number" min="0" step="any">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="comment">{{ __('dashboard.form.fields.spendings.comment') }}</label>
            <textarea class="form-control" name="comment" id="comment" cols="30" rows="5"></textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="picture">{{ __('dashboard.form.fields.spendings.picture') }}</label>
            <div class="custom-file">
                <input accept="image/*" type="file" class="custom-file-input" id="picture" name="picture" lang="es">
                <label class="custom-file-label" for="picture">Seleccionar Archivo</label>
            </div>
            <div id="img-picture-wrapper" class="img-wrapper mt-3 mx-auto text-center position-relative" style="max-width: 320px;">
                <img id="img-picture" class="mt-3 img-fluid d-none" src="" alt="{{ __('dashboard.form.fields.spendings.picture') }}" />
                <span class="delete-img position-absolute d-none" type="button" data-target="picture"><i class="fa fa-times-circle"></i></span>
                <a href="#" class="cancel-delete-img d-none badge badge-dark">Recuperar imagen</a>
            </div>
        </div>
    </div>
</div>