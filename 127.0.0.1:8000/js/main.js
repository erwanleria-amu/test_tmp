/*
    http://a.tile.openstreetmap.org/{z}/{x}/{y}.png

    Copyright
    L.tileLayer('http://a.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    L.tileLayer('https://tile.waymarkedtrails.org/hiking/{z}/{x}/{y}.png').addTo(map);
    L.tileLayer('https://tile.waymarkedtrails.org/cycling/{z}/{x}/{y}.png').addTo(map);
 */

let routeCoordinates = [];
let markers = [];
itineraireMode = 0;

$('#itineraireMode').on('click', function () {
    itineraireMode = !itineraireMode;
    if(itineraireMode) {
        if(clickCircle !== undefined)
            map.removeLayer(clickCircle);
        map.removeLayer(marker);
        $('#radiusForm').hide();
        $('#itinerary').show();
        $(this).text('Terminer');
    } else {
        $('#radiusForm').show();
        $(this).text('Créer un itinéraire');
        newRoute();
        markers.forEach(function (element){
            map.removeLayer(element);
        });
        markers = [];
        routeCoordinates = [];
        $('#itinerary').hide();
        alert('L\'itinéraire a bien été créé!\nVous pouvez le consulter depuis votre profil.');
    }
});


L.tileLayer('http://toolserver.org/tiles/hikebike/{z}/{x}/{y}.png').addTo(map);
L.Control.geocoder().addTo(map);
marker = L.marker(coordinates).addTo(map);

let radiusInput = document.getElementById("radiusInput").value;
let clickCircle;

// Listeners
map.on('click', onMapClick);
map.on('contextmenu', removeCircle);
$('#radiusInput').on('input', lookForRadiusChange);

function removeCircle(){
    if (clickCircle != null) {
        map.removeLayer(clickCircle);
    }
}

function hasNumber(myString) {
    return /\d/.test(myString);
}

// Move or place the circle area on the map then get all nearest locations weather
function onMapClick(e) {
    if(itineraireMode){
        newPoint(e.latlng);
    } else {
        if(marker != null) map.removeLayer(marker);
        removeCircle();
        coordinates = e.latlng;
        //marker = L.marker(coordinates).addTo(map);
        clickCircle = L.circle(coordinates, radiusInput*1000, {
            color: '#f07300',
            fillOpacity: 0.3,
            opacity: 0.5
        }).addTo(map);
        getNearestLocations();
    }
}

// Change the circle arza radius depending on the radiusInput field
function lookForRadiusChange()
{
    var newRadius = document.getElementById("radiusInput").value;
    if (newRadius !== radiusInput && clickCircle !== undefined) {
        if(newRadius <= 50) radiusInput = newRadius;
        else {
            radiusInput = 50;
            document.getElementById("radiusInput").value = radiusInput;
        }
        let data = {
            latlng: coordinates
        }
        onMapClick(data);
    }
}

function getNearestLocations() {
    $('#results').empty();
    overpassRequest = 'https://www.overpass-api.de/api/interpreter?data=' +
        '[out:json][timeout:60];' +
        'node(around:' + radiusInput * 1000 + ',' + coordinates.lat + ',' + coordinates.lng + ')[place=city];' +
        'out;' + 
        'node(around:' + radiusInput * 1000 + ',' + coordinates.lat + ',' + coordinates.lng + ')[place=town];' +
        'out;' + 
        'node(around:' + radiusInput * 1000 + ',' + coordinates.lat + ',' + coordinates.lng + ')[place=village];' +
        'out;';

    dataOverpass = $.ajax({
        url: overpassRequest,
        dataType: 'json',
        type: 'GET',
        async: true,
        crossDomain: true
    }).done(function (returnOverpass) {
        addCitiesToStats(returnOverpass.elements.length);
        nearCities = [];
        for(var i = 0; i < returnOverpass.elements.length && i < 5; ++i){
            element = returnOverpass.elements[i];
            $.get("https://api.openweathermap.org/data/2.5/weather?lat=" + element.lat + "&lon=" + element.lon + "&units=metric" + "&APPID=" + OWeatherMapAPIKey, function (data) {
                $('#results').empty();
                let noRepeat = [];
                console.log(data);
                if(data != null) {
                    if ($.inArray(data.name, noRepeat) === -1 && !hasNumber(data.name)) {
                        noRepeat.push(data.name);
                        let id = 0;
                        let starIcon = "star_border";
                        let startingClass = "addFavorite";
                        $.ajax({
                            url: Routing.generate('get-location'),
                            type: 'POST',
                            context: this,
                            data: {
                                map_id: data.id
                            }
                        }).done(function (data2) {
                            if (data2 > 0) {
                                id = data2;
                                starIcon = "star";
                                startingClass = "removeFavorite";
                            }
                            $('#results').append('<tr>' +
                                '<td>' + data.name + '</td>' +
                                '<td>' + data.weather[0].description + '(' + parseInt(data.main.temp) + '°C)<img src="https://openweathermap.org/img/w/' + data.weather[0].icon + '.png"></td>' +
                                '<td><button class="' + startingClass + ' mdl-button mdl-js-button mdl-button--icon" ' +
                                'data-name="' + data.name + '" data-lat="' + data.coord.lat + '" data-lng="' + data.coord.lon +
                                '" data-id="' + id + '" data-map-id="' + data.id + '"><i class="material-icons favorite">' + starIcon + '</i></button><a class="mdl-button mdl-js-button mdl-button--icon" href="' + Routing.generate('reviews-mapid-finder', {'mapId': data.id, 'name': data.name, 'lat': data.coord.lat, 'lng': data.coord.lon }) + '"><i class="material-icons" style="font-size: 15px; color: #0174DF;">comment</i></a></td>')
                        });
                    }
                }
            });
        }
    }).fail(function (error) {
        console.log(error);
    });
}

function isEmpty(str){
    return !str.replace(/\s+/, '').length;
}

function newPoint(coordinates) {
    routeCoordinates.push(coordinates);
    let marker = L.marker(coordinates).addTo(map);
    markers.push(marker);
}

function newRoute(){
    let buildRequest = "https://api.openrouteservice.org/directions?api_key=" + ORouteServiceAPIKey + "&language=fr&profile=foot-hiking&coordinates=";
    routeCoordinates.forEach(function (element, index, array) {
        buildRequest += element.lng + "," + element.lat;
        if(index !== array.length - 1) buildRequest += "|";
    });
    console.log(routeCoordinates);
    $.get(buildRequest, function (data) {
        console.log(routeCoordinates);
        $.ajax({
            url: Routing.generate('add-route'),
            type: 'POST',
            data: {
                name: $('#itineraryName').val(),
                distance: data.routes[0].summary.distance,
                duration: data.routes[0].summary.duration,
                polyline: data.routes[0].geometry,
                waypoints: data.info.query.coordinates
            }
        }).done(function (response) {});
    });
    console.log(buildRequest);
}
