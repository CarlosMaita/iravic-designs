@php
    $visits = $schedule->getVisitsByCustomersZone($zone->id);
    $visits_qty = count($visits);
@endphp

<div id="zone-{{ $zone->id }}" class="card zona-item" data-id="{{ $zone->id }}">
    <div class="card-header">
        <span>{{ $zone->name }}</span> @if ($visits_qty) <span class="qty-clientes">({{ $visits_qty }})</span> @endif
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse-zone-{{ $zone->id }}"  aria-controls="collapse-zona-{{ $zone->id }}" @if(isset($openZone) && $openZone == $zone->id) aria-expanded="true" @else aria-expanded="false" @endif>
            Ver listado de clientes <i class="fas fa-expand-alt"></i> 
        </button>
    </div>
    <div class="card-body collapse zone-customers @if(isset($openZone) && $openZone == $zone->id) show @endif" id="collapse-zone-{{ $zone->id }}">
        <div class="table-responsive">
            <table id="datatable-zone-{{ $zone->id }}" class="table datatable-zone table-schedule responsive table_btn table-vcenter dataTable no-footer no-wrap" width="100%">    
                <thead>
                    <tr>
                        <th scope="col">{{ __('dashboard.visits.customer') }}</th>
                        <th>{{ __('dashboard.visits.address') }}</th>
                        <th>{{ __('dashboard.visits.comment') }}</th>
                        <th>{{ __('dashboard.visits.responsable') }}</th>
                        <th>{{ __('dashboard.visits.completed') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($visits as $visit)
                        <tr @if($visit->is_completed) style="background: lightgray; color: #000;" @endif>
                            <td>
                                @if (Auth::user()->can('viewany', App\Models\Customer::class))
                                    <a href="{{ route('clientes.show', [$visit->customer_id]) }}" class="link"><span>{{ $visit->customer->name }}</span></a>
                                @else
                                    <span>{{ $visit->customer->name }}</span>
                                @endif

                                @if ($visit->customer->cellphone)
                                    <a class="whatsapp-link" href="https://wa.me/{{ $visit->customer->whatsapp_number }}?text=Hola buenos días, te escribimos desde {{ config('app.name') }}. Tenemos agendado para pasar a cobrar hoy. Te queda bien?" target='_blank'><i class="fab fa-whatsapp"></i></a>
                                @endif
                            </td>
                            <td>
                                <span>{{ $visit->customer->address }}</span>
                            </td>
                            <td>
                                {{ $visit->comment }}
                            </td>
                            <td>
                                <span id="visit-{{ $visit->id }}-responsable">{{ $visit->responsable ? $visit->responsable->name : 'Por asignar' }}</span>

                                @if (Auth::user()->can('updateResponsable', App\Models\Visit::class))
                                    <button data-id="{{ $visit->id }}"  class="btn btn-sm btn-success btn-action-icon btn-edit-responsable" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></button>
                                @endif
                            </td>
                            <td>
                                @if ($visit->is_completed)
                                    <span>Si</span>
                                @else
                                    <span>No</span>
                                @endif

                                @if (Auth::user()->can('complete', App\Models\Visit::class))
                                    @if ($visit->is_completed)
                                        <button data-id="{{ $visit->id }}" data-to-complete="0" class="btn btn-sm btn-danger btn-action-icon btn-complete-visit" title="Cancelar" data-toggle="tooltip"><i class="fas fa-times"></i></button>
                                    @else
                                    @can ('create', App\Models\Payment::class)
                                    @if( $visit->is_collection && !$visit->is_paid)
                                            <button
                                                data-id="{{ $visit->id }}" 
                                                data-customer_id="{{ $visit->customer_id }}" 
                                                data-customer="{{ $visit->customer->name }}" 
                                                data-motive="{{ $visit->comment}}" 
                                                data-visit_date_now = "{{ $visit->date }}"
                                                data-suggested_collection_amount="{{ $visit->suggested_collection_formatted }}" 
                                                class="btn btn-sm btn-success btn-action-icon btn-payment-installments" 
                                                title="Pagar Couta" 
                                                data-toggle="tooltip">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                            @else
                                                <button data-id="{{ $visit->id }}" data-to-complete="1" class="btn btn-sm btn-primary btn-action-icon btn-complete-visit" title="Completar" data-toggle="tooltip"><i class="fas fa-check"></i></button>
                                            @endif
                                        @endcan
                                        <button data-id="{{ $visit->id }}" class="btn btn-sm btn-info btn-action-icon edit-visit" title="Cambiar fecha" data-toggle="tooltip"><i class="fas fa-calendar-alt"></i></button>
                                        <button data-id="{{ $visit->id }}" class="btn btn-sm btn-danger btn-action-icon btn-pending-to-visit" title="Postergar" data-toggle="tooltip"><i class="fas fa-arrow-circle-right"></i></button>
                                    @endif
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br>
        <div class="d-flex">
            <button class="btn btn-link ml-auto btn-collapse-zone" type="button">Cerrar listado de clientes</button>
        </div>
    </div>
</div>