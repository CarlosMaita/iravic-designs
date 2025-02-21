@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.config.index') }}</div>
                        <div class="card-body">
                          <form id="form-users" method="POST" action="{{ route('general.store') }}" autocomplete="off" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name_project">{{ __('dashboard.form.fields.config.name_project') }}</label>
                                        <input class="form-control" id="name_project" name="name_project" type="text" value="{{ $nameProject }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="logo_img">{{ __('dashboard.form.fields.config.logo_img') }}</label>
                                        <input class="form-control" id="logo_img" name="logo_img" type="file" accept="image/*" value="">
                                    </div>
                                </div>
                                <div class=" col-md-3">
                                    @isset($logoImg->value)
                                    <div class="form-group" >
                                        <img src="{{ asset( 'storage/img/configs/' . $logoImg->value) }}" alt="Logo" style="width: 100px; ">
                                    </div>
                                    @endisset
                                </div>
                            </div>
                            {{--  --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="discount_password">{{ __('dashboard.form.fields.config.discount_password') }}</label>
                                        <input class="form-control" id="discount_password" name="discount_password" type="text" value="{{ $discountPassword->value }}">
                                    </div>
                                </div>
                            </div>
                            {{--  --}}
                            <a href="{{ route('usuarios.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
                            @can('create', App\Models\Config::class)
                                <button class="btn btn-success" type="submit">{{ __('dashboard.form.save') }}</button>
                            @endcan
                          </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection