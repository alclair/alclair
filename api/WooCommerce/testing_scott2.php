<?php

$params = [
    //'after' => '2019-03-16T12:32:37'
        'after' => '2019-03-16T00:00:00'
];

$ch = curl_init('https://alclair.com/wp-json/wc/v3/orders/2524692');

$headers = array(
    'Authorization: Basic Y2tfYWNjODcyZTE5YTE5MDhjZDVhYmFkZmQyOWE4NGU1ZWRmOGQzNDQ2OTpjc184N2ZlMTUwODYzNTdiN2U5MGE4ZDI0NTc1NTJkZGI5NTdiYTkzOWZi'
);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$return = curl_exec($ch);

print_r("<br/><br/>33");
var_dump(json_decode($return, true));
$obj = json_decode($return, true);
$json = json_encode($return);

print_r($obj);
?>