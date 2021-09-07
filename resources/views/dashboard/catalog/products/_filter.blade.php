@push('css')
    <style>
        .select2-selection--multiple .select2-search__field{
            width:100%!important;
        }
    </style>
@endpush

<div class="row mb-3">
    <div class="col-12 mb-2">
        <div>
            <button id="btn-advanced-search" class="btn" type="button" style="border: 1px solid #000;border-radius: unset;font-weight: 600;"><i class="fa fa-search"></i> {{ __('dashboard.advanced_search.advanced_search') }}</button>
        </div>
        <div class="collapse mt-3" id="adv-search">
            <div class="card card-body px-2">
                <form id="form-advanced-search">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand">{{ __('dashboard.form.fields.products.brand') }}</label>
                                <select class="form-control" id="brand" name="brand[]" multiple>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">{{ __('dashboard.form.fields.products.category') }}</label>
                                <select class="form-control" id="category" name="category[]" multiple>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="gender">{{ __('dashboard.form.fields.products.gender') }}</label>
                                <select class="form-control" id="gender" name="gender[]" multiple>
                                    <option value="F">F</option>
                                    <option value="M">M</option>
                                    <option value="Niño">Niño</option>
                                    <option value="Niña">Niña</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="color">{{ __('dashboard.form.fields.products.color') }}</label>
                                <select class="form-control" id="color" name="color[]" multiple>
                                    @foreach ($colors as $color)
                                        <option value="{{ $color->id }}">{{ $color->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="size">{{ __('dashboard.form.fields.products.size') }}</label>
                                <select class="form-control" id="size" name="size[]" multiple>
                                    @foreach ($sizes as $size)
                                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 mb-2">
                            <div class="d-flex">
                                <button type="button" class="btn text-primary clear-form">{{ __('dashboard.advanced_search.clear_filter') }}</button>
                                <div class="d-inline-block ml-auto">
                                    <button id="close-advance-search" type="button" class="btn btn-danger">{{ __('dashboard.general.close') }}</button>
                                    <button type="submit" class="btn btn-primary">{{ __('dashboard.general.search') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>