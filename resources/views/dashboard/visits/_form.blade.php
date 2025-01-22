{{-- fecha --}}
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="visit-date">{{ __('dashboard.form.fields.visits.date') }}</label>
            @if(isset($fromPayment))
                <input class="form-control datepicker-form" id="visit-date" name="visit_date" autocomplete="off">
            @else
                <input class="form-control datepicker-form" id="visit-date" name="date" autocomplete="off">
            @endif
        </div>
    </div>
</div>

{{-- comentarios --}}
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label for="visit-comment">{{ __('dashboard.form.fields.visits.comment') }}</label>
            @if(isset($fromPayment))
                <textarea class="form-control" name="visit_comment" id="visit-comment" cols="30" rows="3"></textarea>
            @else
                <textarea class="form-control" name="comment" id="visit-comment" cols="30" rows="5"></textarea>
            @endif
        </div>
    </div>
</div>
{{-- visita de cobro --}}
@if(isset($fromCustomer))
<hr>
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <input type="hidden" id="is-collection-hidden" name="is_collection" value="0">
            <input class="checkbox" id="is-collection-checkbox"  type="checkbox" value="0"> 
            <label class="form-check-label" for="is-collection">{{ __('Visita de Cobro') }}</label>
        </div>
    </div>
    <div class="col-sm-12 d-none" id="div-suggested-collection">
        <div class="form-group">
            <label for="suggested-collection">{{ __('dashboard.form.fields.visits.suggested_collection') }}</label>
            <input class="form-control" id="suggested-collection" name="suggested_collection" autocomplete="off" type="number" step="0.01">
        </div>
    </div>
</div>
@endif