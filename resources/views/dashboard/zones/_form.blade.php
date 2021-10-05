<div class="row">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="name">{{ __('dashboard.zones.name') }}</label>
            <input class="form-control" id="name" name="name" type="text" value="{{ old("name", $zone->name) }}" required autofocus>
        </div>
    </div>
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="position">{{ __('dashboard.zones.position') }}</label>
            <input class="form-control" id="position" name="position" type="text" value="{{ old("position", $zone->position) }}" required>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <label class="control-label" for="address">{{ __('dashboard.zones.address_destination') }}:</label>
            <textarea id="address" name="address_destination" rows="2" cols="1" class="form-control">{{ old("address_destination", $zone->address_destination)}}</textarea>
        </div>
    </div>
</div>
<div class="row mb-5">
    <div class="col-md-12">
        <div id="map-zone" style="height: 300px;"></div>
        <input id="latitude" name="latitude_destination" type="hidden" value='{{old("latitude_destination", $zone->latitude_destination)}}'>
        <input id="longitude" name="longitude_destination" type="hidden" value='{{old("longitude_destination", $zone->longitude_destination)}}'>
    </div>  
</div>