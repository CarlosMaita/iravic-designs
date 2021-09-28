<table id="datatable_orders" class="table" width="100%">
    <thead>
        <tr>
            <th scope="col">{{ __('dashboard.orders.id') }}</th>
            <th scope="col">{{ __('dashboard.orders.customer') }}</th>
            <th scope="col">{{ __('dashboard.orders.date') }}</th>
            <th scope="col">{{ __('dashboard.orders.payment_method') }}</th>
            <th scope="col">{{ __('dashboard.orders.total') }}</th>
            <th></th>
        </tr>
    </thead>
    @if(isset($orders))
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ optional($order->customer)->name }}</td>
                <td>{{ $order->date }}</td>
                <td>{{ $order->payment_method }}</td>
                <td>{{ $order->total }}</td>
                <td>
                    @if (Auth::user()->can('view', $order))
                        <a href="{{ route('ventas.show', $order->id) }}" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
    @endif
</table>