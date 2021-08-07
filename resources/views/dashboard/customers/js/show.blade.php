<script>
    $(function() {
        let customer_map = new CustomerMap('map-customer', $customer.latitude, $customer.longitude, $customer.address, false);
        customer_map.setMap();
        customer_map.setInitialMarker();

        let datatable_orders = $('#datatable_orders').DataTable({
            pageLength: 25,
        });

        $('#orders-tab').on('click', function(e) {
            setTimeout(function(e) {
                datatable_orders
                .columns.adjust()
                .responsive.recalc();
            }, 1000);
        });
    });
</script>