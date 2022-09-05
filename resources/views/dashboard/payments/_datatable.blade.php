<table id="datatable_payments" class="table" width="100%">
    <thead>
        <tr>
            <th scope="col">{{ __('dashboard.payments.id') }}</th>
            @if (isset($customer)) 
                <th scope="col">{{ __('dashboard.payments.box') }}</th>
            @else
                <th scope="col">{{ __('dashboard.payments.customer') }}</th>
            @endif
            <th scope="col">{{ __('dashboard.payments.date') }}</th>
            <th scope="col">{{ __('dashboard.payments.payment_method') }}</th>
            <th scope="col">{{ __('dashboard.payments.amount') }}</th>
            <th scope="col">{{ __('dashboard.payments.comment') }}</th>
            <th></th>
        </tr>
    </thead>
</table>