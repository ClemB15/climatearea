<?php

ini_set('xdebug.var_display_max_depth', '10');
ini_set('xdebug.var_display_max_children', '256');
ini_set('xdebug.var_display_max_data', '1024');

include('netatmo_API/src/netatmo/autoload.php');

$config = array();
$config['client_id'] = "60058c1f4e174a1118068de8";
$config['client_secret'] = "2gKWFAY7N06vHwOV6Wk2YXI71sgM5";
$config['scope'] = 'read_station read_thermostat write_thermostat';
$client = new Netatmo\Clients\NAApiClient($config);

$username = 'shnake.video@gmail.com';
$pwd = 'LesCuisuiniers05!';
$client->setVariable('username', $username);
$client->setVariable('password', $pwd);
$toke = file_get_contents('http://miwgap.ddns.net/auth.php');
$toke = json_decode($toke);
try {
    $tokens = $client->getAccessToken();
    $refresh_token = $toke->refresh_token;
    $access_token = $toke->access_token;
} catch (Netatmo\Exceptions\NAClientException $ex) {
    echo "An error occcured while trying to retrive your tokens 1 \n";
}

try {
    $params = [
        'lat_ne' => '50.8838492',
        'lon_ne' => '8.0209591',
        'lat_sw' => '42.5999',
        'lon_sw' => '-5.57175'

    ];
    $response = $client->api('getpublicdata', 'GET', $params);
    $response = json_encode($response);
    //var_dump($response);
} catch (Netatmo\Exceptions\NAClientException $ex) {
    echo "An error occcured while trying to retrive your tokens 2 \n";
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Climate Area</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
          integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
            integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
            crossorigin=""></script>
    <style>
        html, body { height: 100%}
        h2 { text-align: center}
    </style>

</head>
<body onload="initialize()">
<div id="map" style="width:100%; height:100%">

</div>
</body>

<script>
    var map = L.map('map').setView([46.227638, 2.213749], 6);
    function initialize() {

        var osmLayer = L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        });

        map.addLayer(osmLayer);
        weather();
    }

    function weather(){
        let donnees = <?php echo $response ?>;
        console.log(donnees);

        donnees.forEach((el) => {
            if (el.place['country']==='FR'){
                let lat = el.place.location['1'];
                let lon = el.place.location['0'];

                let marker;
                marker = L.marker([lat, lon]);
                marker.addTo(map);

                let city = Object.entries(el.place)[4];
                let street = Object.entries(el.place)[5];

                let mesure = Object.entries(el.measures)[0][1].res;

                let temp = Object.entries(mesure)[0][1][0];
                let humididate = Object.entries(mesure)[0][1][1];

                let pressure = Object.entries(mesure)[1];
                console.log(pressure)

                marker.on('mouseover', function (event){
                    marker.bindPopup(`<h2>${city[1]}</h2><h3>${street[1]}</h3><p>Température : ${temp}°C</p><p>Taux d'humidité : ${humididate}%</p><p>Pression : ${pressure}</p>`).openPopup();
                });
            }
        });
    }
</script>
</html>
