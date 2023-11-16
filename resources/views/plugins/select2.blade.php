@push('css')
    {{-- <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
    {{-- <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script> --}}
    <script>
        $(function () {
            // Select2 does not function properly when I use it inside a Bootstrap modal.
            // $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        });
    </script>
@endpush