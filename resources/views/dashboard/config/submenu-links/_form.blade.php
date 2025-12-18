<div class="mb-3">
    <label for="title" class="form-label">Título</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $submenuLink->title ?? '') }}" required>
    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="url" class="form-label">URL</label>
    <input type="text" name="url" id="url" class="form-control" value="{{ old('url', $submenuLink->url ?? '') }}" required placeholder="/categoria/ejemplo o https://ejemplo.com">
    @error('url') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="order" class="form-label">Orden</label>
    <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $submenuLink->order ?? 0) }}" min="0" required>
    @error('order') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <div class="form-check">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" id="active" class="form-check-input" value="1" {{ old('active', $submenuLink->active ?? true) ? 'checked' : '' }}>
        <label class="form-check-label" for="active">
            Activo
        </label>
    </div>
    @error('active') <div class="text-danger">{{ $message }}</div> @enderror
</div>
