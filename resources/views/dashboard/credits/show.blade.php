@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header"><i class="fa fa-align-justify"></i> {{ __('dashboard.credits.edit') }} - {{ $credit->order->customer->name}}</div>
                        <div class="card-body">
                            {{-- Informacion de credito --}}
                            <credit-show 
                                url-schedule-index="{{ route('agendas.index') }}"
                                :collection="{{ $credit }}" 
                                :visits='@json($collections)' 
                                :readonly="true">
                            </credit-show>
                            <br>
                            <a href="{{ route('creditos.index') }}" class="btn btn-primary">{{ __('dashboard.form.back to list') }}</a>
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
@endpush