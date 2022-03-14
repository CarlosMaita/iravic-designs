<div class="tab-pane fade" id="debts" role="tabpanel" aria-labelledby="debts-tab">
    @can('create', App\Models\Debt::class)
        <div class="row"> 
            <a id="btn-create-debt" href="#" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_a') }}</a>
        </div>
    @endcan
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('dashboard.debts._datatable')
            </div>
        </div>
    </div>
</div>