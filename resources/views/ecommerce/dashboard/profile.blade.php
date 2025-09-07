@extends('ecommerce.dashboard.base')

@section('title', 'Mi Perfil')

@push('css')
<style>
    .profile-card {
        border-radius: 1rem;
        border: none;
        box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 1rem 1rem 0 0;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
    }
    .info-item {
        border-bottom: 1px solid #f8f9fa;
        padding: 1rem 0;
    }
    .info-item:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    .info-value {
        color: #212529;
    }
    .edit-btn {
        border-radius: 0.5rem;
    }
</style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="row">
        <div class="col-12">
            <div class="card profile-card">
                <!-- Profile Header -->
                <div class="profile-header p-4">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="profile-avatar">
                                <i class="ci-user"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h3 class="mb-1">{{ $customer->name }}</h3>
                            <p class="mb-0 opacity-75">Cliente desde {{ $customer->created_at->format('F Y') }}</p>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-light edit-btn" type="button" disabled>
                                <i class="ci-edit me-2"></i>Editar Perfil
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Profile Content -->
                <div class="card-body p-4">
                    <div class="row">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Información Personal</h5>
                            
                            <div class="info-item">
                                <div class="info-label">Nombre Completo</div>
                                <div class="info-value">{{ $customer->name }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Correo Electrónico</div>
                                <div class="info-value">{{ $customer->email ?? 'No registrado' }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Número de Documento</div>
                                <div class="info-value">{{ $customer->dni ?? 'No registrado' }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Teléfono</div>
                                <div class="info-value">{{ $customer->telephone ?? 'No registrado' }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Celular</div>
                                <div class="info-value">{{ $customer->cellphone ?? 'No registrado' }}</div>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Información de Cuenta</h5>
                            
                            <div class="info-item">
                                <div class="info-label">Estado de Cuenta</div>
                                <div class="info-value">
                                    <span class="badge 
                                        @if($customer->qualification == 'Muy Bueno') bg-success
                                        @elseif($customer->qualification == 'Bueno') bg-primary
                                        @elseif($customer->qualification == 'Malo') bg-warning
                                        @else bg-danger
                                        @endif">
                                        {{ $customer->qualification ?? 'Bueno' }}
                                    </span>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Límite de Crédito</div>
                                <div class="info-value">{{ $customer->max_credit ? number_format($customer->max_credit, 2) . ' Bs' : 'No establecido' }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Fecha de Registro</div>
                                <div class="info-value">{{ $customer->created_at->format('d/m/Y H:i') }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Última Actualización</div>
                                <div class="info-value">{{ $customer->updated_at->format('d/m/Y H:i') }}</div>
                            </div>

                            @if($customer->collection_frequency)
                            <div class="info-item">
                                <div class="info-label">Frecuencia de Cobro</div>
                                <div class="info-value">{{ $customer->collection_frequency }}</div>
                            </div>
                            @endif

                            @if($customer->collection_day)
                            <div class="info-item">
                                <div class="info-label">Día de Cobro</div>
                                <div class="info-value">{{ $customer->collection_day }}</div>
                            </div>
                            @endif
                        </div>
                    </div>

                    @if($customer->address)
                    <!-- Address Information -->
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Información de Dirección</h5>
                            
                            <div class="info-item">
                                <div class="info-label">Dirección</div>
                                <div class="info-value">{{ $customer->address }}</div>
                            </div>

                            @if($customer->latitude && $customer->longitude)
                            <div class="info-item">
                                <div class="info-label">Coordenadas</div>
                                <div class="info-value">{{ $customer->latitude }}, {{ $customer->longitude }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($customer->contact_name)
                    <!-- Contact Information -->
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Contacto de Emergencia</h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Nombre del Contacto</div>
                                        <div class="info-value">{{ $customer->contact_name }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Teléfono del Contacto</div>
                                        <div class="info-value">{{ $customer->contact_telephone ?? 'No registrado' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Security Section -->
                    <hr class="my-4">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">Seguridad</h5>
                            <div class="alert alert-info">
                                <i class="ci-info-circle me-2"></i>
                                Para cambiar tu contraseña o actualizar información personal, contacta con nuestro equipo de soporte.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection