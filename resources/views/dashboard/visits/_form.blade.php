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