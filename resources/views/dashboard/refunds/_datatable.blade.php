<table id="datatable_refunds" class="table" width="100%">
    <thead>
        <tr>
            <th scope="col">{{ __('dashboard.refunds.id') }}</th>
            <th scope="col">{{ __('dashboard.refunds.customer') }}</th>
            <th scope="col">{{ __('dashboard.refunds.total') }}</th>
            <th scope="col">{{ __('dashboard.refunds.date') }}</th>
            <th></th>
        </tr>
    </thead>
    @if(isset($refunds))
    <tbody>
        @foreach ($refunds as $refund)
            <tr>
                <td>{{ $refund->id }}</td>
                <td>{{ optional($refund->customer)->name }}</td>
                <td>{{ $refund->total_str }}</td>
                <td>{{ $refund->date }}</td>
                <td>
                    @if (Auth::user()->can('view', $refund))
                        <a href="{{ route('devoluciones.show', $refund->id) }}" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    @endif
</table>