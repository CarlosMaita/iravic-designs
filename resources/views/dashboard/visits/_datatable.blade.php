<table id="datatable_visits" class="table" width="100%">
    <thead>
        <tr>
            <th scope="col">{{ __('dashboard.visits.date') }}</th>
            @if (isset($customer)) 
                <th scope="col">{{ __('dashboard.visits.schedule') }}</th>
            @else
                <th scope="col">{{ __('dashboard.visits.customer') }}</th>
            @endif
            <th scope="col">{{ __('dashboard.visits.responsable') }}</th>
            <th scope="col">{{ __('dashboard.visits.completed') }}</th>
            <th></th>
        </tr>
    </thead>
</table>