<table id="datatable_orders" class="table" width="100%">
    <thead>
        <tr>
            <th scope="col">{{ __('dashboard.orders.id') }}</th>
            <th scope="col">{{ __('dashboard.orders.customer') }}</th>
            <th scope="col">{{ __('dashboard.orders.payment_method') }}</th>
            <th scope="col">{{ __('dashboard.orders.total') }}</th>
            <th scope="col">{{ __('dashboard.orders.date') }}</th>
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
            </tr>
        @endforeach
    </tbody>
    @endif
</table>