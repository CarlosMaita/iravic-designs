<script>
    $(function() {
        var customer_map = new CustomerMap('map-customer', $customer.latitude, $customer.longitude, $customer.address, false);
        customer_map.setMap();
        customer_map.setInitialMarker();

        $('#datatable_orders').DataTable({
            pageLength: 25,
        });
    });
</script>