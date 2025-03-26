@push('css')
    {{-- <link rel="stylesheet" href="{{ asset('plugins/select2/select2.min.css') }}"> --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>  
    {{-- <script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script> --}}
    <script>

        function matchCustomer(params, data) {
           
            // If there are no search terms, return all of the data
            if ($.trim(params.term) === '') {
                return data;
            }
            // Do not display the item if there is no 'text' property
            if (typeof data.text === 'undefined') {
                return null;
            }

            // Convertir tanto params.term como data.text a minúsculas o mayúsculas
            var searchTerm = params.term.toLowerCase();
            var itemText = data.text.toLowerCase();

            // Comparar los términos sin considerar mayúsculas o minúsculas
            if (itemText.indexOf(searchTerm) > -1) {
                var modifiedData = $.extend({}, data, true);
                // Puedes devolver objetos modificados desde aquí
                // Esto incluye cómo coinciden los 'children' en conjuntos de datos anidados
                return modifiedData;
            }

            return null;
          
        }

        function matchProduct (params, data) {
                if ($.trim(params.term) === '') {
                    return data;
                }

                // Do not display the item if there is no 'text' property
                if (typeof data.text === 'undefined') {
                    return null;
                }

                 // Convertir tanto params.term como data.text a minúsculas o mayúsculas
                var searchTerm = params.term.toLowerCase();
                var itemText = data.text.toLowerCase();

                // Comparar los términos sin considerar mayúsculas o minúsculas
                if (itemText.indexOf(searchTerm) > -1) {
                    var modifiedData = $.extend({}, data, true);
                    // Puedes devolver objetos modificados desde aquí
                    // Esto incluye cómo coinciden los 'children' en conjuntos de datos anidados
                    return modifiedData;
                }

                // if (
                //     ($(data.element).data('brand') && $(data.element).data('brand').toString().indexOf(params.term) > -1) ||
                //     ($(data.element).data('category') && $(data.element).data('category').toString().indexOf(params.term) > -1) ||
                //     ($(data.element).data('code') && $(data.element).data('code').toString().indexOf(params.term) > -1)
                // ) {
                //     return data;
                // }

                return null;
            }

        $(function () {
            // Select2 does not function properly when I use it inside a Bootstrap modal.
            // $.fn.modal.Constructor.prototype.enforceFocus = function() {};
        });
    </script>
@endpush