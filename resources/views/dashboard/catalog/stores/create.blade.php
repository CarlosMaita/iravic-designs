@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.catalog.stores.create') }}</div>
                        <div class="card-body">
                            
                          <store-form 
                            :store-types="{{json_encode($storeTypeList)}}"
                            :store-prop="{{json_encode(new stdClass()) }}"
                            method="POST"
                            action="{{ route('depositos.store') }}" 
                            back-to-list-url="{{route('depositos.index')}}">
                            @csrf
                            @method('POST')
                          </store-form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection