/*
 * L.Handler.UrlView is used by L.Map to update the current position in the url as parameters
 */

L.Util.parseParamString = function(searchUrl) {
    if (searchUrl[0] !== '?') {
        return {};
    }

    var parsedParams = {};

    searchUrl.substring(1).split('&').forEach(function(paramPair) {
        var splitPair = paramPair.split('='),
            key = decodeURIComponent(splitPair[0]),
            value = splitPair.length === 2 ? decodeURIComponent(splitPair[1]) : true;

        parsedParams[key] = value;
    });

    return parsedParams;
};

L.Map.UrlView = L.Handler.extend({
    addHooks: function() {
        // Only set a view if it has not been set yet
        if (!this.viewLoaded()) {
            this._trySetView();
        }
        this._map.on('moveend', this._onMapMoved, this);
    },

    removeHooks: function() {
        this._map.off('moveend', this._onMapMoved, this);
    },

    _trySetView: function() {
        var urlViewParams = L.Util.parseParamString(window.location.search);

        try {
            var center = new L.LatLng(urlViewParams.lat, urlViewParams.lng),
                zoom = urlViewParams.zoom;
        } catch (e) {
            return false;
        }

        if (center && zoom >= 0) {
            this._map.setView(center, zoom, { reset: true });
            return true;
        }
        return false;
    },

    viewLoaded: function() {
        try {
            this._map.getCenter();
            return true;
        } catch (e) {
            return false;
        }
    },

    _onMapMoved: function() {
        var urlParams = L.Util.parseParamString(window.location.search),
            mapView = this._getMapView();

        this._updateSearchLocation(L.Util.getParamString(L.extend(urlParams, mapView)));
    },

    _updateSearchLocation: function(searchLocation) {
        window.history.replaceState(null, null, searchLocation);
    },

    _getMapView: function() {
        var mapCenter = this._map.getCenter(),
            mapZoom = this._map.getZoom(),
            precision = this.getLatLngPrecision();

        return {
            zoom: mapZoom,
            lat: mapCenter.lat.toFixed(precision.lat),
            lng: mapCenter.lng.toFixed(precision.lng)
        };
    },

    // Calculate the precision required to set our view accurately again
    getLatLngPrecision: function() {
        // Get distance of a single pixel in the current view in LatLng coordinates
        var latLng1 = this._map.getCenter(),
            latLng2 = this._map.unproject(this._map.project(latLng1).add(L.point(1, 1))),
            pixelLatLng = L.point([
                Math.abs(latLng1.lng - latLng2.lng),
                Math.abs(latLng1.lat - latLng2.lat)
            ]);

        // Calculate number of decimals of this difference
        var latPrecision = Math.floor(Math.log(pixelLatLng.x) / Math.log(10)),
            lngPrecision = Math.floor(Math.log(pixelLatLng.y) / Math.log(10));

        latPrecision = Math.max(1, -latPrecision);
        lngPrecision = Math.max(1, -lngPrecision);

        return {
            lat: latPrecision,
            lng: lngPrecision
        };
    }
});

L.Map.mergeOptions({
    urlView: false
});

if (window.history && window.history.replaceState && L.Util.parseParamString) {
    L.Map.addInitHook('addHandler', 'urlView', L.Map.UrlView);
}