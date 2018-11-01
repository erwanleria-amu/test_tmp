
//http://a.tile.openstreetmap.org/{z}/{x}/{y}.png

// Copyright
/*L.tileLayer('http://a.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);*/

//L.tileLayer('https://tile.waymarkedtrails.org/hiking/{z}/{x}/{y}.png').addTo(map);
//L.tileLayer('https://tile.waymarkedtrails.org/cycling/{z}/{x}/{y}.png').addTo(map);
L.tileLayer('http://toolserver.org/tiles/hikebike/{z}/{x}/{y}.png').addTo(map);

// Searchbar
L.Control.geocoder().addTo(map);

marker = L.marker(coordinates).addTo(map);
// Variables
let radiusInput = document.getElementById("radiusInput").value;
let clickCircle;


// Listeners
map.on('click', onMapClick);
map.on('contextmenu', removeCircle);
$('#radiusInput').on('input', lookForRadiusChange);

/*
displayFavorites();

//Functions

*/
// Remove circle area from the map

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

// Get
function getNearestLocations(){
    $('#results').empty();
    $.get("https://api.openweathermap.org/data/2.5/find?lat=" + coordinates.lat + "&lon=" + coordinates.lng + "&cnt=" +
        radiusInput + "&units=metric&appid=" + OWeatherMapAPIKey,
        function(data) {
            let noRepeat = [];
            console.log(data);
            if(data != null){
                $.each(data.list, function (key, value) {
                    if($.inArray(value.name, noRepeat) === -1 && !hasNumber(value.name)){
                        noRepeat.push(value.name);
                        let id = 0;
                        let starIcon = "star_border";
                        let startingClass = "addFavorite";
                        console.log(value.id);
                        $.ajax({
                            url: Routing.generate('get-location'),
                            type: 'POST',
                            context: this,
                            data: {
                                map_id: value.id
                            }
                        }).done(function(data){
                            if(data > 0) {
                                id = data;
                                starIcon = "star";
                                startingClass = "removeFavorite";
                            }

                            $('#results').append('<tr>' +
                                '<td>' + value.name + '</td>' +
                                '<td>' + value.weather[0].description + '(' + parseInt(value.main.temp) + '°C)<img src="https://openweathermap.org/img/w/' +  value.weather[0].icon  + '.png"></td>' +
                                '<td><button class="' + startingClass + ' mdl-button mdl-js-button mdl-button--icon" ' +
                                'data-name="' + value.name + '" data-lat="' + value.coord.lat + '" data-lng="' + value.coord.lon +
                                '" data-id="' + id + '" data-map-id="' + value.id + '"><i class="material-icons favorite">' + starIcon + '</i></button></td>')
                        });
                    }
                })
            }
        }
    );
}

getNearestLocations();


