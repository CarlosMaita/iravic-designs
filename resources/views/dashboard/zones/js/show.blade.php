<script>
    /**
     * Crea un objeto mapa de zona y agrega todos sus clientes
     */
    $(function () {
        let map = new ZoneMap('map-zone', $zone);
        map.setMap();
        map.showAllCustomers();
    });
</script>