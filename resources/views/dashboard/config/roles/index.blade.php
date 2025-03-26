@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.config.roles.index') }}</div>
                        <div class="card-body">
                            @can('create', App\Models\Role::class)
                                <div class="row"> 
                                    <a href="{{ route('roles.create') }}" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_o') }}</a>
                                </div>
                                <br>
                            @endcan
                            {{-- Datatable --}}
                            <div class="row">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="datatable_roles" class="table" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('dashboard.config.roles.name') }}</th>
                                                <th>{{ __('dashboard.config.roles.is_employee') }}</th>
                                                <th>{{ __('dashboard.config.roles.description') }}</th>
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
    @include('dashboard.config.roles.js.index')
@endpush