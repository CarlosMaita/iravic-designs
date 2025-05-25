<table id="datatable_refunds" class="table" width="100%">
    <thead>
        <tr>
            <th scope="col">{{ __('dashboard.refunds.id') }}</th>
            <th scope="col">{{ __('dashboard.refunds.customer') }}</th>
            <th scope="col">{{ __('dashboard.refunds.total') }}</th>
            <th scope="col">{{ __('dashboard.refunds.date') }}</th>
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
            </tr>
        @endforeach
    </tbody>
    @endif
</table>