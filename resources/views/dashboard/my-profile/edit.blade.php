@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.my-profile.edit') }}</div>
                        <div class="card-body">
                          <form id="form-profile" method="POST" action="{{ route('my-profile.update') }}">
                            @csrf
                            @method('PUT')
                            @include('dashboard.my-profile._form', ['user' => Auth::user()])
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
    @include('dashboard.my-profile.js.form')
@endpush