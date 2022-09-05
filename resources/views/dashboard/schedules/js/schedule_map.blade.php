<script type="text/javascript">
    var geocoder = new google.maps.Geocoder;

    function ScheduleMap(map_id, schedule, url_schedule, zone_select, role_select) {
        this.infowindow = new google.maps.InfoWindow();
        this.lat = -34.889052;
        this.lng = -56.164012;
        this.map_id = map_id;
        this.map = null;
        this.map_coords = null;
        this.markers = [];
        this.schedule = schedule;
        this.role_select = role_select;
        this.zone_select = zone_select;
        this.showed = false;
        this.url_schedule = url_schedule;
        this.setMapCoords();
    };

    /**
     * Retorna el elemento del DOM del mapa basadi en el id que recibe por parametro
     */
    ScheduleMap.prototype.getMapElement = function() {
        return document.getElementById(this.map_id);
    }

    /**
     * Setea las coordenas del mapa
     */
    ScheduleMap.prototype.setMapCoords = function() {
        this.map_coords = this.getCoords();
    }

    /**
     * Retorna las coordenas del mapa
     * Si el mapa no recibe latitude y longitud como parametro, se le setea una coordenada predeterminada
     */
    ScheduleMap.prototype.getCoords = function() {
        if (this.lat && this.lng) {
            return  {
	              	lat: Number(this.lat),
	              	lng: Number(this.lng)
	            };
        } else {
            return {
                    lat: -34.87073,
                    lng:  -56.2042
	            };
        }
    }

    /**
     * Setea el mapa con las coordenadas y el elemento DOM 
     */
    ScheduleMap.prototype.setMap = function() {
        var map_element = this.getMapElement();
        
        if (map_element = this.getMapElement()) {
            this.map = new google.maps.Map(map_element,{
                zoom: 10,
                center:new google.maps.LatLng(this.map_coords),
                zoomControl: true
            });
        }
    }

    /**
     * Retorna icon para el marker de un cliente basado en su reputacion
     */
    ScheduleMap.prototype.getIconByCustomerReputation = function(customer) {
        var icon = '';

        switch (customer.qualification) {
            case 'Muy Bueno':
                icon = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
                break;
            case 'Bueno':
                icon = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
                break;
            case 'Malo':
                icon = 'http://maps.google.com/mapfiles/ms/icons/yellow-dot.png';
                break;
            case 'Muy Malo':
                icon = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
                break;
            default:
                break;
        }

        return icon;
    }

    /**
     * Manda a mostrar todos los clientes/visitas de la agenda
     */
    ScheduleMap.prototype.showAllCustomers = function() {
        this.showMarkers(this.schedule.visits);
    }

    /**
     * Agrega como marcadores todas las visitas que no han sido completadas
     */
    ScheduleMap.prototype.showMarkers = function(visits) {
        var that = this;
        visits.forEach((visit, index) => {
            if (!visit.is_completed) {
                var data = {
                    index: (index + 1),
                    customer: visit.customer,
                    comment: visit.comment,
                    icon: that.getIconByCustomerReputation(visit.customer),
                    lat: visit.customer.latitude,
                    lng: visit.customer.longitude
                }

                that.addMarker(data);
            }
        });
    }

    /**
     * Agregar marcador de una visita al mapa
     * Con evento para click en el marcador para mostrar popup con sus detalles
     */
    ScheduleMap.prototype.addMarker = function(data) {
        var that = this;
        var marker = new google.maps.Marker({
            map: this.map,
            animation: google.maps.Animation.DROP,
            position: new google.maps.LatLng(data.lat, data.lng),
            label: data.customer.name,
            icon: {
                url: data.icon
            }
        });

        this.markers.push(marker);

        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                that.infowindow.setContent(
                    `<div id="content">
                        <h4 class="firstHeading" style="font-weight: bold;">${data.customer.name}</h4><hr>
                        ${ data.customer.telephone ? '<p class="mb-1"><b>Teléfono:</b> ' + data.customer.telephone + '</p>' : ''}
                        <p class="mb-1"><b>Calificación:</b> ${data.customer.qualification}</p>
                        ${ data.customer.address ? '<p><b>Dirección:</b> ' + data.customer.address + '</p>' : ''}
                    </div>`
                );

                that.infowindow.open(that.map, marker);
            }
        })(marker, (data.index)));
    }

    /**
     * Elimina todos los marcadores del mapa
     */
    ScheduleMap.prototype.clearMarkers = function() {
        this.markers.forEach(element => {
            element.setMap(null);
        });
    }

    /**
     * Vacia los marcadores asociados y manda a eliminarlos del mapa
     */
    ScheduleMap.prototype.removeMarkers = function() {
        this.clearMarkers();
        this.markers = [];
    }

    /**
     * Peticion HTTP para obtener visitas. Se pueden filtrar por roles o zonas
     */
    ScheduleMap.prototype.httpGetVisits = function() {
        var params_roles = this.getHttpRolesParams(),
            params_zones = this.getHttpZonesParams(),
            url = `${this.url_schedule}?axios=1&${params_zones}${params_roles}`,
            that = this;

        axios.get(url)
        .then(function (response) {
            if (response.data.visits) {
                that.showMarkers(response.data.visits);
            }
        })
        .catch(function (error) {
            new Noty({
                text: 'Ha ocurrido un error al tratar de obtener las visitas',
                type: 'error'
            }).show();
        })
        .then(function () {
            // always executed
        });
    }

    /**
     * Retorna string para ser usado como parametro query con las zonas seleccionadas para filtrar
     */
    ScheduleMap.prototype.getHttpZonesParams = function() {
        var zones = this.zone_select.val(),
            params = '';

        if (zones.length) {
            params = zones.join(),
            params = `zones=${zones}`;
        }
    
        return params;
    }

    /**
     * Retorna string para ser usado como parametro query con los roles seleccionados para filtrar
     */
    ScheduleMap.prototype.getHttpRolesParams = function() {
        var params = null,
            roles = '';

        if (this.role_select.length) {
            roles = this.role_select.val(),
            params = roles.join();
            params = `&roles=${params}`;
        }

        return params;
    }
</script>