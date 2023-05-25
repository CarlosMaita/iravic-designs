@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.catalog.products.create') }}</div>
                        <div class="card-body">
                            <form id="form-products" method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                                @csrf
                                {{--  --}}
                                <product-form 
                                    v-bind:product="{{ $product->exists ? json_encode($product) : json_encode(new stdClass()) }}"
                                    v-bind:brands="{{ json_encode($brands) }}"
                                    v-bind:categories="{{ json_encode($categories) }}"
                                    v-bind:colors="{{ json_encode($colors) }}"
                                    v-bind:genders="{{ json_encode(array('F', 'M', 'Niño', 'Niña', 'Unisex Niños', 'Unisex Adultos')) }}"
                                    v-bind:sizes="{{ json_encode($sizes) }}"
                                    url-products="{{ route('productos.index') }}"
                                    url-products-combinations="{{ route('productos.delete_combinations') }}"
                                ></product-form>
                                {{--  --}}
                                <a href="{{ route('productos.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                                <button class="btn btn-success" type="submit">{{ __('dashboard.form.create') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('plugins.dropzone')
    @include('plugins.sweetalert')

    <script>
        Dropzone.autoDiscover = false;

        let URL_RESOURCE = "{{ route('productos.store') }}";
    </script>
    
    @include('dashboard.catalog.products.js.form')
@endpush