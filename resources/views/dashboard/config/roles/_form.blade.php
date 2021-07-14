<div class="row">
    <div class="col-12">
        <div class="form-check form-check-inline mb-4">
            <input class="form-check-input" type="checkbox" name="is_employee" id="is_employee" value="1" @if($role->is_employee) checked @endif>
            <label class="form-check-label" for="is_employee">{{ __('dashboard.config.roles.is_employee') }}</label>
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label for="name">{{ __('dashboard.config.roles.name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $role->name) }}" required autofocus>
        </div>
    </div>
</div>
<div class="row">
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
<br>
