<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="title" class="form-label">Título *</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $specialOffer->title ?? '') }}" required>
            @error('title') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="product_id" class="form-label">Producto *</label>
            <select name="product_id" id="product_id" class="form-control" required>
                <option value="">Seleccionar producto</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                            {{ old('product_id', $specialOffer->product_id ?? '') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
            @error('product_id') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="description" class="form-label">Descripción</label>
    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $specialOffer->description ?? '') }}</textarea>
    @error('description') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="start_date" class="form-label">Fecha de Inicio *</label>
            <input type="datetime-local" name="start_date" id="start_date" class="form-control" 
                   value="{{ old('start_date', isset($specialOffer) ? $specialOffer->start_date->format('Y-m-d\TH:i') : '') }}" required>
            @error('start_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="end_date" class="form-label">Fecha de Fin *</label>
            <input type="datetime-local" name="end_date" id="end_date" class="form-control" 
                   value="{{ old('end_date', isset($specialOffer) ? $specialOffer->end_date->format('Y-m-d\TH:i') : '') }}" required>
            @error('end_date') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="mb-3">
            <label for="discount_percentage" class="form-label">Porcentaje de Descuento (%)</label>
            <input type="number" name="discount_percentage" id="discount_percentage" class="form-control" 
                   value="{{ old('discount_percentage', $specialOffer->discount_percentage ?? '') }}" 
                   min="0" max="100" step="0.01">
            @error('discount_percentage') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-3">
            <label for="order" class="form-label">Orden</label>
            <input type="number" name="order" id="order" class="form-control" 
                   value="{{ old('order', $specialOffer->order ?? 0) }}" min="0">
            @error('order') <div class="text-danger">{{ $message }}</div> @enderror
        </div>
    </div>
</div>

<div class="mb-3">
    <label for="image" class="form-label">Imagen de la Oferta</label>
    <input type="file" name="image" id="image" class="form-control" accept="image/*">
    @if(isset($specialOffer) && $specialOffer->image)
        <div class="mt-2">
            <img src="{{ $specialOffer->image_url }}" alt="Oferta" style="max-width: 200px; height: auto;">
            <small class="text-muted d-block">Imagen actual</small>
        </div>
    @endif
    @error('image') <div class="text-danger">{{ $message }}</div> @enderror
</div>

<div class="mb-3">
    <div class="form-check">
        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
               {{ old('is_active', $specialOffer->is_active ?? true) ? 'checked' : '' }}>
        <label for="is_active" class="form-check-label">Oferta Activa</label>
    </div>
    @error('is_active') <div class="text-danger">{{ $message }}</div> @enderror
</div>