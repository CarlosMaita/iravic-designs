@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.collections.edit') }} - {{ $collection->order->customer->name}}</div>
                        <div class="card-body">
                          {{-- form of edit collection --}}
                          <form id="form-collections" method="POST" action="{{ route('cobros.update', [$collection->id]) }}">
                            @csrf
                            @method('PUT')
                            {{-- Informacion de credito --}}
                            <collection-form :collection="{{ $collection }}"></collection-form>
                            <a href="{{ route('cobros.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            <button class="btn btn-success" type="submit">{{ __('dashboard.form.update') }}</button>
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
        let URL_RESOURCE = "{{ route('cobros.update', [$collection->id]) }}";
    </script>
    
    {{-- @include('dashboard.catalog.products.js.form') --}}
@endpush