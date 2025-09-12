@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.config.users.index') }}</div>
                        <div class="card-body">
                                                            <div class="row"> 
                                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_o') }}</a>
                                </div>
                                <br>
                                                        {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_users" class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('dashboard.config.users.name') }}</th>
                                                <th scope="col">{{ __('dashboard.config.users.email') }}</th>
                                                {{-- <th scope="col">Role</th> --}}
                                                <th></th>
                                            </tr>
                                        </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    @include('plugins.sweetalert')
    @include('dashboard.config.users.js.index')
@endpush