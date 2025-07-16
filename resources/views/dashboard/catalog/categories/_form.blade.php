<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $category->name) }}" required autofocus>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="base_categories">{{ __('BaseCategory') }}</label>
            <select class="form-control" name="base_category_id" id="base_categories">
                @foreach ($base_categories as $base_category)
                    <option value="{{ $base_category->id }}" @if($category->base_category_id == $base_category->id) selected @endif>{{ $base_category->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="slug">Slug</label>
            <input class="form-control" id="slug" name="slug" type="text" value="{{ old('slug', $category->slug) }}" maxlength="100" placeholder="Ejemplo: nombre-categoria">
            <small class="form-text text-muted">El slug se usa en la URL. Si no se especifica, se generará automáticamente.</small>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="image_banner">{{ __('Imagen de la tarjeta en Home') }}</label>
            <input class="form-control" id="image_banner" name="image_banner" type="file" accept="image/*">
            @if($category->image_banner)
                <img src="{{ asset('storage/' . $category->image_banner) }}" alt="{{ $category->name }}" style="max-width: 100px; margin-top: 5px;">
            @endif
        </div>
    </div>
     <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="bg_banner">{{ __('Color de la tarjeta en Home / Background') }}</label>
            <input class="form-control" id="bg_banner" name="bg_banner" type="color" value="{{ old('bg_banner', $category->bg_banner ?? '#ffffff') }}">
        </div>
    </div>
</div>