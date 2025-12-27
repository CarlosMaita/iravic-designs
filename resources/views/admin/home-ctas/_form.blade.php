<div class="mb-3">
    <label for="title" class="form-label">Título</label>
    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $homeCta->title ?? '') }}" required>
    @error('title') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="icon" class="form-label">Icono (FontAwesome)</label>
    <div class="input-group">
        <span class="input-group-text">
            <i id="icon-preview" class="{{ old('icon', $homeCta->icon ?? 'fas fa-star') }}"></i>
        </span>
        <input type="text" name="icon" id="icon" class="form-control" value="{{ old('icon', $homeCta->icon ?? '') }}" 
               placeholder="ej: fas fa-star, fas fa-shopping-bag">
    </div>
    <small class="form-text text-muted">
        Usa clases de FontAwesome. Ejemplos: 
        <a href="#" onclick="setIcon('fas fa-star'); return false;">fas fa-star</a>,
        <a href="#" onclick="setIcon('fas fa-shopping-bag'); return false;">fas fa-shopping-bag</a>,
        <a href="#" onclick="setIcon('fas fa-tshirt'); return false;">fas fa-tshirt</a>,
        <a href="#" onclick="setIcon('fas fa-vest'); return false;">fas fa-vest</a>,
        <a href="#" onclick="setIcon('fas fa-dress'); return false;">fas fa-dress</a>,
        <a href="#" onclick="setIcon('fas fa-question-circle'); return false;">fas fa-question-circle</a>
    </small>
    @error('icon') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descripción</label>
    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $homeCta->description ?? '') }}</textarea>
    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="cta_text" class="form-label">Texto del CTA</label>
    <input type="text" name="cta_text" id="cta_text" class="form-control" value="{{ old('cta_text', $homeCta->cta_text ?? '') }}" required>
    @error('cta_text') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="cta_url" class="form-label">URL del CTA</label>
    <input type="text" name="cta_url" id="cta_url" class="form-control" value="{{ old('cta_url', $homeCta->cta_url ?? '') }}" required>
    @error('cta_url') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <label for="order" class="form-label">Orden</label>
    <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $homeCta->order ?? 0) }}" min="0">
    @error('order') <div class="text-danger">{{ $message }}</div> @enderror
</div>

@push('scripts')
<script>
function setIcon(iconClass) {
    document.getElementById('icon').value = iconClass;
    document.getElementById('icon-preview').className = iconClass;
}

document.getElementById('icon').addEventListener('input', function(e) {
    document.getElementById('icon-preview').className = e.target.value || 'fas fa-star';
});
</script>
@endpush
