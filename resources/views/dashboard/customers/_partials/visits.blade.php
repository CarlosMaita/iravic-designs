<div class="tab-pane fade" id="visits" role="tabpanel" aria-labelledby="visits-tab">
    @can('create', App\Models\Visit::class)
        <div class="row"> 
            <a id="btn-create-visit" href="#" class="btn btn-primary m-2 ml-auto">{{ __('dashboard.general.new_a') }}</a>
        </div>
    @endcan
    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('dashboard.visits._datatable')
            </div>
        </div>
    </div>
</div>