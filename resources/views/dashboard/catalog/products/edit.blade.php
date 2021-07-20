@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.catalog.products.edit') }} - {{ $product->name }}</div>
                        <div class="card-body">
                          <form id="form-products" method="POST" action="{{ route('productos.update', [$product->id]) }}">
                            @csrf
                            @method('PUT')
                            @include('dashboard.catalog.products._form')
                            <button class="btn btn-success" type="submit">{{ __('dashboard.form.update') }}</button>
                            <a href="{{ route('productos.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
  @include('dashboard.catalog.products.js.form')
@endpush