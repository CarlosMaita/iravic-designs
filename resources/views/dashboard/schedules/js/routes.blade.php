<script>
    const route = {
        GENERAL_COORDS:  {
            lat: -34.87073,
            lng:  -56.2042
        },
        URL_SORT_SCHEDULE: "{{ route('visitas.sort') }}",
        URL_GOOGLE_MAPS: 'https://www.google.com/maps/dir/?api=1',
        directionsRenderer: new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            // preserveViewport: false
        }),
        directionsService: new google.maps.DirectionsService(),
        origin: null,
        destination: null,
        waypoints: [],


        httpUpdateVisitsOrder(waypoints) {
            var self = this;

            $.ajax({
                url: self.URL_SORT_SCHEDULE,
                headers: {'X-CSRF-TOKEN': $("input[name=_token]").val()},
                type: 'POST',
                data: {
                    visits: waypoints
                },
                success: function (response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        self.stopLoading();
                        if (response.message) {
                            new Noty({
                                text: response.message,
                                type: 'error'
                            }).show();
                        } else if (response.error) {
                            new Noty({
                                text: response.error,
                                type: 'error'
                            }).show();
                        } else {
                            new Noty({
                                text: 'No se ha podido ordenar la agenda en este momento.',
                                type: 'error'
                            }).show();
                        }
                    }
                },
                error: function (e) {
                    self.stopLoading();
                    if (e.responseJSON.message) {
                        new Noty({
                            text: e.responseJSON.message,
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: 'No se ha podido ordenar la agenda en este momento.',
                            type: 'error'
                        }).show();
                    }
                }
            });
        },
        async calcZonesRoute(zones) {
            var self = this,
                length = zones.length,
                waypoints = [];

            if (length) {
                this.addLoading();
                try {
                    for (var i=0; i<length; i++) {
                        var zone = zones[i];
                        var data = this.getZoneData(zones, zone, i);
                        await this.calcRoute(data, i, length)
                        .then(result => {
                            waypoints.push(...result);

                            if (i == (length - 1)) {
                                waypoints = waypoints.map(element => {
                                                return element.id_visit
                                            });
                                self.httpUpdateVisitsOrder(waypoints);
                            }
                        })
                        .catch(error => {
                            if (i == (length - 1)) {
                                new Noty({
                                    text: 'Ha ocurrido un error al tratar de ordenar las zonas',
                                    type: 'error'
                                }).show();
                                this.stopLoading();
                            }
                            console.log('Iteracion ' + i + ' dio error', error);
                        });
                    }
                } catch (error) {
                    console.log(error);
                }
            } else {
                new Noty({
                    text: 'La agenda no tiene zonas asociadas',
                    type: 'error'
                }).show();
            }
        },

        async calcRoute(data, index, length) {
            var self = this;
            var request = {
                origin: data.origin.location,
                destination: data.destination.location,
                // waypoints: data.waypoints,
                waypoints: data.waypoints.map(({id_visit, ...item }) => item),
                optimizeWaypoints: true,
                provideRouteAlternatives: true,
                travelMode: google.maps.DirectionsTravelMode.DRIVING,
                unitSystem: google.maps.UnitSystem.METRIC
            };

            return new Promise(async (resolve, reject) => {
                await self.directionsService.route(request, async function(response, status) {
                    if (status == 'OK' && response) {
                        var waypoints_sorted = self.orderedWaypoints(response, data.waypoints);

                        setTimeout(() => {
                            resolve(waypoints_sorted);
                        }, 1000);
                    } else {
                        setTimeout(() => {
                            reject(index);
                        }, 1000);
                    }
                });
            });
        },

        addLoading() {
            $('body').append('<div class="loading">Loading&#8230;</div>');
        },

        stopLoading() {
            $('.loading').remove();
        },

        /**
        *
        */
        getZoneData($zones, zone, index) {
            return {
                    destination: this.getZoneDestination(zone),
                    origin: this.getZoneOrigin($zones, index),
                    waypoints: this.getZoneWaypoints(zone.id)
                };
        },
        getZoneWaypoints(zone_id) {
            return $schedule.visits.filter(function(element) {
                        return element.customer.zone_id == zone_id;
                    })
                    .map(function(element) {
                        return {
                            location: {
                                lat: Number(element.customer.latitude),
                                lng: Number(element.customer.longitude)
                            },
                            stopover: true,
                            id_visit: element.id
                        }
                    });   
        },
        getZoneOrigin(zones, index) {
            if (typeof zones[index - 1] === 'undefined') return this.getDefaultLocation();

            return {
                    is_cliente: false,
                    location: {
                        lat: Number(zones[index - 1].latitude_destination),
                        lng: Number(zones[index - 1].longitude_destination)
                    }
                };
        },
        getZoneDestination(zone) {
            return {
                    is_cliente: false,
                    location: {
                        lat: Number(zone.latitude_destination),
                        lng: Number(zone.longitude_destination)
                    }
                };
        },
        getDefaultLocation() {
            return {
                    is_cliente: false,
                    location: {
                        lat: -34.857234,
                        lng: -56.047327
                    }
                };
        },

        orderedWaypoints(response, waypoints) {
            var waypoints_ordered = [];
            
            if (waypoints.length > 0 && response.routes[0] && response.routes[0].waypoint_order) {
                waypoints_ordered = this.getWaypointsOrdered(response.routes[0].waypoint_order, waypoints);
            }

            return waypoints_ordered;
        },

        getWaypointsOrdered(waypoint_order, waypoints) {
            var new_array = [];

            for(var i=0; i<waypoint_order.length; i++) {
                new_array.push(waypoints[waypoint_order[i]]);
            }

            return new_array;
        }
    };
</script>