<div class="mb-3">
    <label for="title" class="form-label">Título</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $banner->title ?? '') }}" required>
    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="subtitle" class="form-label">Subtítulo</label>
    <input type="text" name="subtitle" id="subtitle" class="form-control" value="{{ old('subtitle', $banner->subtitle ?? '') }}">
    @error('subtitle') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="text_button" class="form-label">Texto Botón</label>
    <input type="text" name="text_button" id="text_    php artisan storage:link    php artisan storage:linkbutton" class="form-control" value="{{ old('text_button', $banner->text_button ?? '') }}">
    @error('text_button') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="url_button" class="form-label">URL Botón</label>
    <input type="text" name="url_button" id="url_button" class="form-control" value="{{ old('url_button', $banner->url_button ?? '') }}">
    @error('url_button') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
    <label for="image_banner" class="form-label">Imagen</label>
    <input type="file" name="image_banner" id="image_banner" class="form-control" {{ (isset($banner) ? '' : 'required') }} accept="image/*">
    @if(isset($banner) && $banner->image_banner)
        <div class="mt-2">
            <img src="{{ $banner->image_url }}" alt="Banner" style="max-width: 200px;">
        </div>
    @endif
    @error('image_banner') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="order" class="form-label">Orden</label>
    <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $banner->order ?? 0) }}" min="0">
    @error('order') <div class="text-danger">{{ $message }}</div> @enderror
</div>
