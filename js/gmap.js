var map;
var markers = [];
var latInitial;// = '14.7362987';
var lngInitial; // = '-17.45662444';
var initialLocation;
var agences;

function onSuccess(position) {
    if (position) {
        latInitial = position.coords.latitude;
        lngInitial = position.coords.longitude;
    } else {
        latInitial = 14.7362987;
        lngInitial = -17.45662444;
    }
    alert('position detectée\n' + latInitial + '<==/==>' + lngInitial);
//                initialLocation = new google.maps.LatLng(latInitial, lngInitial);
    initialLocation = {lat: latInitial, lng: lngInitial};
    //var mapOptions = {zoom: 12, center: initialLocation, mapTypeId: google.maps.MapTypeId.ROADMAP};
    map = new google.maps.Map(
            document.getElementById('map'),
            {zoom: 12, center: initialLocation, mapTypeId: google.maps.MapTypeId.ROADMAP}
    );
    var marker = new google.maps.Marker({
        position: initialLocation,
        map: map,
        title: 'Ma position'
    });
}

function onError(error) {
    alert('Une erreur est survenue ===>' + 'message: ' + error.message + '\n');
}

function initialize() {
    var options = {timeout: 30000, enableHighAccuracy: true, maximumAge: 900000};
    navigator.geolocation.getCurrentPosition(onSuccess, onError, options);
}

function rad(x) {
    return x * Math.PI / 180;
}

function deleteMarkers() {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap();
    }
    markers = [];
}

function distance(lat1, lng1, lat2, lng2) {
    var radlat1 = Math.PI * lat1 / 180;
    var radlat2 = Math.PI * lat2 / 180;
    var radlon1 = Math.PI * lng1 / 180;
    var radlon2 = Math.PI * lng2 / 180;
    var theta = lng1 - lng2;
    var radtheta = Math.PI * theta / 180;
    var dist = Math.sin(radlat1) * Math.sin(radlat2) + Math.cos(radlat1) * Math.cos(radlat2) * Math.cos(radtheta);
    dist = Math.acos(dist);
    dist = dist * 180 / Math.PI;
    dist = dist * 60 * 1.1515;
    //Get in in kilometers
    dist = dist * 1.609344;

    return dist;
}

function find_closest_marker(distanceKM) {
    var lat = latInitial;
    var lng = lngInitial;
    deleteMarkers();
    for (i = 0; i < agences.length; i++) {
        var mlat = agences[i].latitude;
        var mlng = agences[i].longitude;
        var libelle = agences[i].libelle;
        var adresse = agences[i].adresse;
        var origine = {lat: latInitial, lng: lngInitial};
        var agence = {lat: mlat, lng: mlng};
        var responsable = agences[i].responsable;
        var telephone = '<a href="tel:' + agences[i].telephone + '">' + agences[i].telephone + '</a>';
        var d = distance(lat, lng, mlat, mlng);
        if (d < distanceKM) {
            var marker = new google.maps.Marker({
//                            position: dest,
                position: new google.maps.LatLng(mlat, mlng),
                map: map,
                title: libelle,
                icon: 'images/bm-map2.png'
            });

            var infowindow = new google.maps.InfoWindow({
                content: '<strong>' + libelle + '</strong><br/><strong>Adresse:</strong>   ' + adresse + '<br/><strong>Telephone:</strong>  ' + telephone + '<br/><strong>Responsable:</strong>  ' + responsable + '<br/>'
            });
            marker.addListener('click', function() {
                infowindow.open(map, marker);
            });

            markers.push(marker);
        }
    }
}

jQuery(document).ready(function() {
    var options = {timeout: 30000, enableHighAccuracy: true, maximumAge: 900000};
    navigator.geolocation.getCurrentPosition(onSuccess, onError, options);
    
    agences = function() {
        var tmp = null;
        jQuery.ajax({
            'async': false,
            'global': false,
            'dataType': 'json',
            'url': "http://agences.noflay.com/agences.php",
            'data': {'request': ""},
            'success': function(data) {
                tmp = data;
            }
        });
        return tmp;
    }();
    find_closest_marker(1);
    $("#distance").val(1);
});

/*function onDeviceReady() {

} */
