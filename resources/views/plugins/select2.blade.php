@push('css')
    <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}">
@endpush

@push('js')
    <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $(function () {
            // Select2 does not function properly when I use it inside a Bootstrap modal.
            // $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        });
    </script>
@endpush