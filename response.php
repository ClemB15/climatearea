<?php

session_start();

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
        $_SESSION['response'] = $response;
        //var_dump($response);
    } catch (Netatmo\Exceptions\NAClientException $ex) {
        echo "An error occcured while trying to retrive your tokens 2 \n";
    }
