
<div class="row">
    <div class="col-12">
        <div class="form-check form-check-inline mb-4">
            <input class="form-check-input" type="checkbox" name="is_regular" id="is_regular" value="1" @if(($product->exists &&$product->is_regular) || !$product->exists) checked @endif>
            <label class="form-check-label" for="is_regular">{{ __('dashboard.form.fields.products.is_regular') }}</label>
        </div>
        <div class="form-check form-check-inline mb-4">
            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1" @if($product->exists && $product->is_featured) checked @endif>
            <label class="form-check-label" for="is_featured">Producto Destacado</label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="name">{{ __('dashboard.form.fields.general.name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $product->name) }}" required autofocus>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <div class="form-group">
            <label for="code">{{ __('dashboard.form.fields.products.code') }}</label>
            <input class="form-control" id="code" name="code" type="text" value="{{ old("code", $product->code) }}" required autofocus>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="description">{{ __('dashboard.config.roles.description') }}</label>
            <textarea class="form-control" id="description" name="description" cols="30" rows="5">{{ $role->description }}</textarea>
        </div>
    </div>
</div>
<div class="container-fluid">
    <label>{{ __('dashboard.config.roles.permissions_label') }}</label>
    @foreach($permissions->chunk(2) as $key => $permissions_chunk)
    <div class="row">
        @foreach($permissions_chunk as $key => $permission)
            <div class="col-md-6">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="permissions[]" id="permission_{{ $permission->id }}" value="{{ $permission->id }}" @if($role->permissions()->pluck('permissions.id')->contains($permission->id)) checked @endif>
                    <label class="form-check-label" for="permission_{{ $permission->id }}">{{$permission->display_name}}</label>
                </div>
            </div>
        @endforeach
    </div>
    @endforeach
</div>
<br> --}}


<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="stocks-tab" data-toggle="tab" href="#stocks" role="tab" aria-controls="stocks" aria-selected="true">Stocks</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="combinations-tab" data-toggle="tab" href="#combinations" role="tab" aria-controls="combinations" aria-selected="false">Profile</a>
    </li>
</ul>
<div class="tab-content" id="myTabContent">
    {{--  --}}
    <div class="tab-pane fade show active" id="stocks" role="tabpanel" aria-labelledby="stocks-tab">
        Stocks
    </div>
    {{--  --}}
    <div class="tab-pane fade" id="combinations" role="tabpanel" aria-labelledby="combinations-tab">
        <p>aqui va el tema de las combinaciones</p>
    </div>
</div>