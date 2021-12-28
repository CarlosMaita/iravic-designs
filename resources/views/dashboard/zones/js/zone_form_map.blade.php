<script type="text/javascript">
    var geocoder = new google.maps.Geocoder;

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

    ZoneMap.prototype.canGeocoding = function() {
        return this.marker_created_after_click ? false : true;
    }

    ZoneMap.prototype.canGeocodingReverse = function() {
        if (!this.marker_created_after_search || $.trim($("#address").val()).length == 0) {
            return true;
        }

        return false;
    }

    ZoneMap.prototype.addressError = function() {
        var content = '<span id="error-address">Selecciona la ubicación en el mapa</span>';
    	$('#error-address').remove();
    	$('#address').after(content);
    }

    ZoneMap.prototype.createMarkerPopup = function(marker, content) {
        this.marker.infowindow = new google.maps.InfoWindow({
            content: content
        });

        var that = this;
        this.marker.addListener('click', function() {
            that.marker.infowindow.open(that.map, that.marker);
        });
    }

    ZoneMap.prototype.removeErrorAddress = function() {
		$('#error-address').remove();
	}

	ZoneMap.prototype.updateLatLngInputs = function(lat, lng) {
	    $('#latitude').val(lat);
		$('#longitude').val(lng);
    }

    ZoneMap.prototype.validateAddressLength = function(direction) {
		if (!direction && direction.trim().length == 0) {
			return false;
		}

		return true;
	}

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

    ZoneMap.prototype.setHasMarker = function () {
        if (this.lat && this.lng) {
            this.has_marker = true;
        }
    };

    ZoneMap.prototype.getMapElement = function() {
        return document.getElementById(this.map_id);
    };

    ZoneMap.prototype.setMapCoords = function() {
        this.map_coords = this.getCoords();
    };

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

    ZoneMap.prototype.setInitialMarker = function() {
        if (this.has_marker) {
            var data = {
                lat: this.lat,
				lng: this.lng
            };

		    this.addMarker(data);
        }
    }
	
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

    ZoneMap.prototype.getFormatedAddress = function() {
    	var value = $("#address").val();
		return value.replace(/\s/g, "+");
    }

    ZoneMap.prototype.createAddressError = function() {
    	var content = '<span id="error-address" class="text-danger font-weight-bold">Selecciona la ubicación en el mapa</span>';
    	$('#error-address').remove();
    	$('#address').after(content);
    }

    ZoneMap.prototype.removeMarkerFromMap = function() {
        if (this.marker) {
			this.marker.setMap(null);
		}
    }
</script>