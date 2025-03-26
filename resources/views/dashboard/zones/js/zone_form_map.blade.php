<script type="text/javascript">
    var geocoder = new google.maps.Geocoder;

    /**
     * Crea un objeto/funcion para crear mapa en crear/editar una zona
     */ 
    function ZoneMap(map_id, lat = null, lng = null, address = '', updatable = true) {
        this.address = address;
        this.lat = lat;
        this.lng = lng;
        this.map_id = map_id;
        this.map = null;
        this.marker = null;
        this.has_marker = false;
        this.marker_created_after_search = false;
        this.marker_created_after_click = false;
        this.updatable = updatable;
        this.setHasMarker();
        this.setMapCoords();
    }

    /**
     * Valida si se aplica el geoconding
     */
    ZoneMap.prototype.canGeocoding = function() {
        return this.marker_created_after_click ? false : true;
    }

    /**
     * Valida si se aplica el geocoding inverso
     * Puede aplicarse el geoconding solamente si la direccion no se busco primeramente escribiendo la direccion (y encontro resultados) o si no ha ingresado ninguna direccion
     */
    ZoneMap.prototype.canGeocodingReverse = function() {
        if (!this.marker_created_after_search || $.trim($("#address").val()).length == 0) {
            return true;
        }

        return false;
    }

    /**
     * Agregar error de seleccionar ubicacion en el mapa porque no encontro direccion con el geocoding
     */
    ZoneMap.prototype.addressError = function() {
        var content = '<span id="error-address">Selecciona la ubicación en el mapa</span>';
    	$('#error-address').remove();
    	$('#address').after(content);
    }

    /**
     * Agrega popup al marcador con evento para capturar click y abrirlo
     */
    ZoneMap.prototype.createMarkerPopup = function(marker, content) {
        this.marker.infowindow = new google.maps.InfoWindow({
            content: content
        });

        var that = this;
        this.marker.addListener('click', function() {
            that.marker.infowindow.open(that.map, that.marker);
        });
    }

    /**
     * Elimina error del DOM
     */
    ZoneMap.prototype.removeErrorAddress = function() {
		$('#error-address').remove();
	}

    /**
     * Actualiza values de los inputs DOM de latitud y longitud
     */
	ZoneMap.prototype.updateLatLngInputs = function(lat, lng) {
	    $('#latitude').val(lat);
		$('#longitude').val(lng);
    }

    /**
     * Valida que la direccion tenga algun caracter
     */
    ZoneMap.prototype.validateAddressLength = function(direction) {
		if (!direction && direction.trim().length == 0) {
			return false;
		}

		return true;
	}

    /**
     * Agregar marcador al mapa
     */
    ZoneMap.prototype.addMarker = function(data) {
        var latlong = new google.maps.LatLng(data.lat, data.lng);

        if (this.marker) {
			this.marker.setMap(null);
		}

     	this.marker = new google.maps.Marker({
        	map: this.map,
        	animation: google.maps.Animation.DROP,
        	position: latlong,
        	draggable: this.updatable
      	});

     	this.map.setCenter(latlong);
     	this.map.setZoom(18);
        this.marker.setAnimation(google.maps.Animation.BOUNCE);

        if (this.address) {
            var content = this.address;
        } else if ($('#address').val().length) {
            var content = $('#address').val();
        }

        this.createMarkerPopup(this.marker, content);

        if (this.updatable) {
            this.updateLatLngInputs(data.lat, data.lng);

            var that = this;
            google.maps.event.addListener(that.marker, 'dragend', function(evt) {
                that.updateLatLngInputs(evt.latLng.lat(), evt.latLng.lng());
            });
        }
    }

    /**
     * Setea propiedade marcador en el objeto/function
     */
    ZoneMap.prototype.setHasMarker = function () {
        if (this.lat && this.lng) {
            this.has_marker = true;
        }
    };

    /**
     * Retorna el elemento DOM del mapa por su ID
     */ 
    ZoneMap.prototype.getMapElement = function() {
        return document.getElementById(this.map_id);
    };

    /**
     * Setea las coordenadas del mapa recibida por la funcion getCoords
     */
    ZoneMap.prototype.setMapCoords = function() {
        this.map_coords = this.getCoords();
    };

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
    };  

    /**
     * Crea el mapa con las coordenadas y el elemento DOM
     */ 
    ZoneMap.prototype.setMap = function() {
        var map_element = this.getMapElement();
        
        if (map_element) {
            this.map = new google.maps.Map(map_element,{
                zoom: 10,
                center:new google.maps.LatLng(this.map_coords),
                zoomControl: true
            });

            var that = this;

            if (that.updatable) {
                google.maps.event.addListener(that.map, 'click', function(event) {
                    var data =  {
                            lat: event.latLng.lat(),
                            lng: event.latLng.lng()
                        };
                    
                    if (that.canGeocodingReverse()) {
                        that.geocodingReverse(event.latLng);
                    }

                    that.addMarker(data);
                    that.removeErrorAddress();
                    that.marker_created_after_click = true;
                });
            }
        }
    };  

    /**
     * Agrega marcador si el objeto recibe uno como parametro. Sucede cuando se esta editando la zona
     */
    ZoneMap.prototype.setInitialMarker = function() {
        if (this.has_marker) {
            var data = {
                lat: this.lat,
				lng: this.lng
            };

		    this.addMarker(data);
        }
    }
    
    /**
     * Aplica geocoding de la direccion
     * Geocoding busca coordenadas a traves de una direccion [String]
     */
    ZoneMap.prototype.geocoding = function() {
        var address = this.getFormatedAddress();

		if (this.validateAddressLength(address)) {
            var that = this;

			geocoder.geocode({ 'address': address}, function(results, status) {
                
                if (status == 'OK') {
                    if (results.length == 0) {
                        that.marker_created_after_search = false;
                        that.createAddressError();
                        that.removeMarkerFromMap();
                        new Noty({
                            text: "No se encuentran resultados para la dirección indicada",
                            type: 'error'
                        }).show();
                    } else {
						var result = results[0];
                        var coords =  {
                                lat: result.geometry.location.lat(),
                                lng: result.geometry.location.lng()
                            };
                            
                        that.addMarker(coords); 
                        that.marker_created_after_search = true;
						$('#error-address').remove();
                    }
                } else {
                    that.marker_created_after_search = false;
                    that.createAddressError();
                    that.removeMarkerFromMap();
                    new Noty({
                        text: "No se encuentran resultados para la dirección indicada",
                        type: 'error'
                    }).show();
                }
            });
		}
	}

    /**
     * Aplica geocoding inverso de las coordenas ingresadas
     * Geocoding Inverso busca una direccion a traves de coordenadas
     */
    ZoneMap.prototype.geocodingReverse = function(data) {
        var that = this;
		geocoder.geocode({ 'location': data}, function(results, status) {
			if (status == 'OK') {
				if (results.length == 0) {
					that.createAddressError();
                    new Noty({
                        text: "No se encuentran resultados para la ubicación seleccionada",
                        type: 'error'
                    }).show();
				} else {
                    var coords =  {
		              	lat: data.lat(),
		              	lng: data.lng()
		            };
					var result = results[0];
					var address = result.formatted_address;
                    
                    that.marker_created_after_search = false;
                    $("#address").val(address);
					$('#error-address').remove();
				}
			} else {
				that.createAddressError();
                new Noty({
                    text: "No se encuentran resultados para la ubicación seleccionada",
                    type: 'error'
                }).show();
			}
		});
	}

    /**
     * Formatea el string de la direccion a buscar (inpput), eliminando espacios.
     */
    ZoneMap.prototype.getFormatedAddress = function() {
    	var value = $("#address").val();
		return value.replace(/\s/g, "+");
    }

    /**
     * Agrega error de seleccionar ubicacion en el mapa [AL DOM] porque no encontro direccion con el geocoding
     */
    ZoneMap.prototype.createAddressError = function() {
    	var content = '<span id="error-address" class="text-danger font-weight-bold">Selecciona la ubicación en el mapa</span>';
    	$('#error-address').remove();
    	$('#address').after(content);
    }

    /**
     * Elimina el macador del mapa
     */
    ZoneMap.prototype.removeMarkerFromMap = function() {
        if (this.marker) {
			this.marker.setMap(null);
		}
    }
</script>