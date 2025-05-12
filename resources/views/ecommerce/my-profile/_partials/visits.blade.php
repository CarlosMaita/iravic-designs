<div class="tab-pane fade" id="visits" role="tabpanel" aria-labelledby="visits-tab">
    {{-- alert to show summary --}}
    <div id="planning-collection-alert" class="row {{ !$planningCollection['check'] ?  '' : 'd-none' }}">
        <div class="col-12">
            <div class="alert alert-warning" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">
                        <i class="fas fa-times"></i>
                    </span>
                </button>
                <h4 class="alert-heading">{{ __('dashboard.visits.planning_collection') }}</h4>
                @if( $planningCollection['rest'] > 0)
                <p id="message-alert">{{ __('dashboard.visits.planning_collection_positive_alert', array( 'customer' => $customer->name , 'suggested_collection_total'=> $planningCollection['rest_formatted'])) }}</p>
                @else
                <p  id="message-alert">{{ __('dashboard.visits.planning_collection_negative_alert', array( 'customer' => $customer->name , 'suggested_collection_total'=> $planningCollection['rest_formatted'])) }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="table-responsive">
                @include('ecommerce.visits._datatable')
            </div>
        </div>
    </div>
</div>