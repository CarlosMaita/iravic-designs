<script type="text/javascript">
    var geocoder = new google.maps.Geocoder;

    /**
     * Crea un objeto/funcion para crear mapas de zonas
     */ 
    function ZoneMap(map_id, zone) {
        this.infowindow = new google.maps.InfoWindow(); // Permite agregar ventanitas a cada marcador cuando se les da click
        this.lat = -34.889052;
        this.lng = -56.164012;
        this.map_id = map_id;
        this.map = null;
        this.map_coords = null;
        this.markers = [];
        this.zone = zone;
        this.setMapCoords();
    };

    /**
     * Retorna el elemento DOM del mapa por su ID
     */ 
    ZoneMap.prototype.getMapElement = function() {
        return document.getElementById(this.map_id);
    }

    /**
     * Setea las coordenadas
     */ 
    ZoneMap.prototype.setMapCoords = function() {
        this.map_coords = this.getCoords();
    }

    /**
     * Retorna las coordenadas que tendra el mapa. Si la funcion/Objeto no recibe las coordenas, se colocan unas predeterminadas
     */ 
    ZoneMap.prototype.getCoords = function() {
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
     * Crea el mapa con las coordenadas y el elemento DOM
     */ 
    ZoneMap.prototype.setMap = function() {
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
     * Retorna icon del pin que se le coloca al cliente basado en su reputacion
     */ 
    ZoneMap.prototype.getIconByCustomerReputation = function(customer) {
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
     * Manda a imprimir todos los clientes de la zona en el mapa (Marcadores)
     */ 
    ZoneMap.prototype.showAllCustomers = function() {
        this.showMarkers(this.zone.customers);
    }

    /**
     * A cada cliente que recibe como parametro lo agrega en el mapa como marcador
     */ 
    ZoneMap.prototype.showMarkers = function(customers) {
        var that = this;
        customers.forEach((customer, index) => {
            var data = {
                index: (index + 1),
                customer: customer,
                address: customer.address,
                icon: that.getIconByCustomerReputation(customer),
                lat: customer.latitude,
                lng: customer.longitude
            }

            that.addMarker(data);
        });
    }

    /**
     * Agregar el marcador al mapa
     */ 
    ZoneMap.prototype.addMarker = function(data) {
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
                        ${ data.customer.date_next_visit ? '<p class="mb-1"><b>Agendado:</b> ' + data.customer.date_next_visit+ '</p>' : ''}
                        ${ data.customer.address ? '<p><b>Dirección:</b> ' + data.customer.address + '</p>' : ''}
                    </div>`
                );

                that.infowindow.open(that.map, marker);
            }
        })(marker, (data.index)));
    }

    /**
     * Elimina todos los marcadores (clientes) del mapa
     */ 
    ZoneMap.prototype.clearMarkers = function() {
        this.markers.forEach(element => {
            element.setMap(null);
        });
    }

    /**
     * Vacia el listado de marcadores y manda a eliminarlos todos del mapa
     */ 
    ZoneMap.prototype.removeMarkers = function() {
        this.clearMarkers();
        this.markers = [];
    }
</script>