<?php


//echo "Adsfasdf";
//require __DIR__ . '/vendor/autoload.php';
require '/var/www/html/otisdev/vendor/autoload.php';
use Automattic\WooCommerce\Client;
//use automattic\wooCommerce\Client;
//use /var/www/html/otisdev/vendor/Automattic/WooCommerce/Client;

/*	
	$woocommerce = new Client(
    'https://example.com',
    'consumer_key',
    'consumer_secret',
    [
        'wp_api' => true,
        'version' => 'wc/v3'
    ]
);

$woocommerce = new Client(
    'http://example.com', 
    'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469', 
    'cs_87fe15086357b7e90a8d2457552ddb957ba939fb',
    [
        'version' => 'wc/v3',
    ]
);

*/
//Client class
$url = 'https://alclair.com/';
$url = 'https://alclair.com/wp-json/wc/v2/orders';
$consumer_key = 'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469';
$consumer_secret = 'cs_87fe15086357b7e90a8d2457552ddb957ba939fb';
$options = [];
//$woocommerce = new Client($url, $consumer_key, $consumer_secret, $options);
$woocommerce = new Client($url, $consumer_key, $consumer_secret);

//$endpoint = 'customers';
//$parameters = 4027755
 //wp-json/wc/v3/customers/<id>
//$woocommerce->get($endpoint, $parameters = [])

echo "It is working</br>"; 
print_r($woocommerce->get('customers/9188'));

$response["code"] = "success";
$response["testing1"] = $inc;

echo json_encode($response);

/*
	
	require '/var/www/html/otisdev/vendor/autoload.php';
use Automattic\WooCommerce\Client;

$url = 'https://alclair.com/';
$consumer_key = 'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469';
$consumer_secret = 'cs_87fe15086357b7e90a8d2457552ddb957ba939fb';
$options = [];

$woocommerce = new Client($url, $consumer_key, $consumer_secret);
echo "It is working</br>"; 
print_r($woocommerce->get('customers/9188'));

*/


?>