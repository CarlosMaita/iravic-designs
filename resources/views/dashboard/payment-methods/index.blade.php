@extends('dashboard.base')

@section('content')
    <div class="container-fluid">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-credit-card"></i> Métodos de Pago
                            <div class="card-header-actions">
                                <a href="{{ route('admin.payment-methods.create') }}" class="btn btn-sm btn-success">
                                    <i class="fa fa-plus"></i> Nuevo Método de Pago
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Orden</th>
                                            <th>Nombre</th>
                                            <th>Código</th>
                                            <th>Instrucciones</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($paymentMethods as $method)
                                            <tr>
                                                <td>{{ $method->sort_order }}</td>
                                                <td><strong>{{ $method->name }}</strong></td>
                                                <td><code>{{ $method->code }}</code></td>
                                                <td>
                                                    <small class="text-muted">
                                                        {{ \Illuminate\Support\Str::limit($method->instructions, 50) }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <button 
                                                        class="btn btn-sm btn-{{ $method->is_active ? 'success' : 'secondary' }}" 
                                                        onclick="toggleActive({{ $method->id }}, this)">
                                                        <i class="fa fa-{{ $method->is_active ? 'check' : 'times' }}"></i>
                                                        {{ $method->is_active ? 'Activo' : 'Inactivo' }}
                                                    </button>
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.payment-methods.edit', $method) }}" 
                                                       class="btn btn-sm btn-primary" 
                                                       title="Editar">
                                                        <i class="fa fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.payment-methods.destroy', $method) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('¿Está seguro de eliminar este método de pago?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Eliminar">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">
                                                    No hay métodos de pago registrados.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function toggleActive(id, button) {
    const url = `/admin/metodos-pago/${id}/toggle-active`;
    
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button appearance
            if (data.is_active) {
                button.className = 'btn btn-sm btn-success';
                button.innerHTML = '<i class="fa fa-check"></i> Activo';
            } else {
                button.className = 'btn btn-sm btn-secondary';
                button.innerHTML = '<i class="fa fa-times"></i> Inactivo';
            }
            
            // Show success message
            const alertDiv = document.createElement('div');
            alertDiv.className = 'alert alert-success alert-dismissible fade show';
            alertDiv.innerHTML = `
                ${data.message}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            `;
            document.querySelector('.card-body').insertBefore(alertDiv, document.querySelector('.table-responsive'));
            
            setTimeout(() => alertDiv.remove(), 3000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el estado del método de pago');
    });
}
</script>
@endpush
