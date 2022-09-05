<script>
    $(function() {
        let customer_map = new CustomerMap('map-customer', $customer.latitude, $customer.longitude, $customer.address, false);
        customer_map.setMap();
        customer_map.setInitialMarker();

        let datatable_orders = $('#datatable_orders').DataTable({
            ordering: false,
            pageLength: 25
        });

        let datatable_refunds = $('#datatable_refunds').DataTable({
            ordering: false,
            pageLength: 25
        });

        /**
         * Captura evento de click en la pestana ventas
         * Espera 1 segundo para ajustar el tamano del datatable
         * Cuando el datatable no esta visible y es creado, no configura bien el width
         */
        $('#orders-tab').on('click', function(e) {
            setTimeout(function(e) {
                datatable_orders
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });

        /**
         * Captura evento de click en la pestana devoluciones
         * Espera 1 segundo para ajustar el tamano del datatable
         * Cuando el datatable no esta visible y es creado, no configura bien el width
         */
        $('#refunds-tab').on('click', function(e) {
            setTimeout(function(e) {
                datatable_refunds
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });
    });
</script>