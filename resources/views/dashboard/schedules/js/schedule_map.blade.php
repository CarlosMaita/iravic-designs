<script type="text/javascript">
    var geocoder = new google.maps.Geocoder;

    function ScheduleMap(map_id, schedule, url_schedule, zone_select) {
        this.infowindow = new google.maps.InfoWindow();
        this.lat = -34.889052;
        this.lng = -56.164012;
        this.map_id = map_id;
        this.map = null;
        this.map_coords = null;
        this.markers = [];
        this.schedule = schedule;
        this.zone_select = zone_select;
        this.showed = false;
        this.url_schedule = url_schedule;
        this.setMapCoords();
    };

    ScheduleMap.prototype.getMapElement = function() {
        return document.getElementById(this.map_id);
    }

    ScheduleMap.prototype.setMapCoords = function() {
        this.map_coords = this.getCoords();
    }

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

    ScheduleMap.prototype.getIconByCustomerReputation = function(customer) {
        var icon = '';

        switch (customer.qualification) {
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

    ScheduleMap.prototype.showAllCustomers = function() {
        this.showMarkers(this.schedule.visits);
    }

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
                        <h4 class="firstHeading" style="font-weight: bold;">${data.customer.name}</h4>
                        ${ data.customer.address ? '<p>' + data.customer.address + '</p>' : ''}
                        ${ data.comentario ? '<p style="margin-top:10px;"><b>Comentario:</b> ' + data.comentario + '</p>' : ''}
                    </div>`
                );

                that.infowindow.open(that.map, marker);
            }
        })(marker, (data.index)));



        // let marker = new google.maps.Marker({
        //     map: map,
        //     position: {lat: -34.397, lng: 150.644},
        //     icon: {
        //         url: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
        //     }
        // });
    }

    ScheduleMap.prototype.clearMarkers = function() {
        this.markers.forEach(element => {
            element.setMap(null);
        });
    }

    ScheduleMap.prototype.removeMarkers = function() {
        this.clearMarkers();
        this.markersRecorrido = [];
    }

    ScheduleMap.prototype.httpGetVisits = function() {
        var params = this.getHttpZonesParams(),
            url = `${this.url_schedule}?axios=1&zones=${params}`,
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

    ScheduleMap.prototype.getHttpZonesParams = function() {
        var zones = this.zone_select.val(),
            params = zones.join();

        return params;
    }
</script>