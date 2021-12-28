<script type="text/javascript">
    const geocoder = new google.maps.Geocoder;

    function CustomerMap(map_id, lat = null, lng = null, address = '', updatable = false) {
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

    CustomerMap.prototype.canGeocoding = function() {
        return this.marker_created_after_click ? false : true;
    }

    CustomerMap.prototype.canGeocodingReverse = function() {
        if (!this.marker_created_after_search || $.trim($("#address").val()).length == 0) {
            return true;
        }

        return false;
    }

    CustomerMap.prototype.addressError = function() {
        var content = '<span id="error-address">Selecciona la ubicación en el mapa</span>';
    	$('#error-address').remove();
    	$('#address').after(content);
    }

    CustomerMap.prototype.createMarkerPopup = function(marker, content) {
        this.marker.infowindow = new google.maps.InfoWindow({
            content: content
        });

        var that = this;
        this.marker.addListener('click', function() {
            that.marker.infowindow.open(that.map, that.marker);
        });
    }

    CustomerMap.prototype.removeErrorAddress = function() {
		$('#error-address').remove();
	}

	CustomerMap.prototype.updateLatLngInputs = function(lat, lng) {
	    $('#latitude').val(lat);
		$('#longitude').val(lng);
        $('#latitude_search').val(lat);
		$('#longitude_search').val(lng);
    }

    CustomerMap.prototype.validateAddressLength = function(direction) {
		if (!direction && direction.trim().length == 0) {
			return false;
		}

		return true;
	}

    CustomerMap.prototype.addMarker = function(data) {
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

    CustomerMap.prototype.setHasMarker = function () {
        if (this.lat && this.lng) {
            this.has_marker = true;
        }
    };

    CustomerMap.prototype.getMapElement = function() {
        return document.getElementById(this.map_id);
    };

    CustomerMap.prototype.setMapCoords = function() {
        this.map_coords = this.getCoords();
    };

    CustomerMap.prototype.getCoords = function() {
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

    CustomerMap.prototype.setMap = function() {
        var self = this;
        var map_element = this.getMapElement();
        
        if (map_element) {
            this.map = new google.maps.Map(map_element,{
                zoom: 10,
                center:new google.maps.LatLng(this.map_coords),
                zoomControl: true
            });

            if (self.updatable) {
                google.maps.event.addListener(self.map, 'click', function(event) {
                    var data =  {
                            lat: event.latLng.lat(),
                            lng: event.latLng.lng()
                        };
                    
                    if (self.canGeocodingReverse()) {
                        self.geocodingReverse(data);
                    }

                    self.addMarker(data);
                    self.removeErrorAddress();
                    self.marker_created_after_click = true;
                });
            }
        }
    };  

    CustomerMap.prototype.setInitialMarker = function() {
        if (this.has_marker) {
            var data = {
                lat: this.lat,
				lng: this.lng
            };

		    this.addMarker(data);
        }
    }
	
    CustomerMap.prototype.geocoding = function() {
        var address = this.getFormatedAddress();

		if(this.validateAddressLength(address)){
            var that = this;

			geocoder.geocode( { 'address': address}, function(results, status) {
                
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

    CustomerMap.prototype.geocodingReverse = function(data = null) {
        var that = this;

        if (!data) {
            data = this.getSearchCoords();
        }

		geocoder.geocode( { 'location': data}, function(results, status) {
			if (status == 'OK') {
				if (results.length == 0) {
					that.createAddressError();
                    new Noty({
                        text: "No se encuentran resultados para la ubicación seleccionada",
                        type: 'error'
                    }).show();
				} else {
                    var coords =  {
		              	lat: data.lat,
		              	lng: data.lng
		            };
					var result = results[0];
					var address = result.formatted_address;
                    
                    that.addMarker(coords);
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

    CustomerMap.prototype.getSearchCoords = function() {
        return {
            lat: parseFloat($('#latitude_search').val()),
            lng: parseFloat($('#longitude_search').val())
        }
    }

    CustomerMap.prototype.getFormatedAddress = function() {
    	var value = $("#address").val();
		return value.replace(/\s/g, "+");
    }

    CustomerMap.prototype.createAddressError = function() {
    	var content = '<span id="error-address" class="text-danger font-weight-bold">Selecciona la ubicación en el mapa</span>';
    	$('#error-address').remove();
    	$('#address').after(content);
    }

    CustomerMap.prototype.removeMarkerFromMap = function() {
        if (this.marker) {
			this.marker.setMap(null);
		}
    }
</script>