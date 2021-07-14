@push('css')
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">

<link rel="stylesheet" href="{{asset('css/bootstrap-datetimepicker.min.css')}}">

@endpush

@push('js')
<script src="{{asset('js/moment.js')}}"></script>
<script src="{{asset('js/bootstrap-datetimepicker.min.js')}}"></script>
<script>
    $(function () {
        $('#datetimepicker3').datetimepicker({
            format: 'LT'
        });

        $('#datetimepicker4').datetimepicker({
            format: 'LT'
        });

    });
</script>
@endpush