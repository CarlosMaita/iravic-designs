<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">{{ __('Name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $category->name) }}" required autofocus>
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
</div>