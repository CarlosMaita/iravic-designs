<table id="datatable_collections" class="table" width="100%">
    <thead>
        <tr>
            <th scope="col">{{ __('dashboard.collections.id') }}</th>
            <th scope="col">{{ __('dashboard.collections.start_date') }}</th>
            <th scope="col">{{ __('dashboard.collections.amount_quotas') }}</th>
            <th scope="col">{{ __('dashboard.collections.frequency') }}</th>
            <th scope="col">{{ __('dashboard.collections.quotas') }}</th>
            <th scope="col">{{ __('dashboard.collections.balance') }}</th>
            <th></th>
        </tr>
    </thead>
    @if(isset($collections))
    <tbody>
        @foreach ($collections as $collection )
            <tr>
                <td>{{ $collection->id }}</td>
                <td>{{ $collection->date }}</td>
                <td>{{ $collection->frequency }}</td>
                <td>{{ $collection->quotas }}</td>
                <td>{{ $collection->balance }}</td>
                <td>
                    <a href="{{ route('collections.edit', $collection->id) }}" class="btn btn-sm btn-warning btn-action-icon" title="Editar" data-toggle="tooltip"><i class="fas fa-edit"></i></a>
                    <a href="{{ route('collections.show', $collection->id) }}" class="btn btn-sm btn-primary btn-action-icon" title="Ver" data-toggle="tooltip"><i class="fas fa-eye"></i></a>
                </td>
            </tr>
        @endforeach
    </tbody>
    @endif
</table>