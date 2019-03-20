<?php
//echo "Adsfasdf";
//require __DIR__ . '/vendor/autoload.php';
require '/var/www/html/otisdev/vendor/autoload.php';
use Automattic\WooCommerce\Client;
//use automattic\wooCommerce\Client;
//use /var/www/html/otisdev/vendor/Automattic/WooCommerce/Client;

//Client class
$store_url = 'http://alclair.com';
//$endpoint = '/wc-auth/v1/authorize';
$endpoint = 'wc/v2';
$params = [
    'app_name' => 'OTIS Import',
    'scope' => 'read',
    'user_id' => 17,
    'return_url' => 'https://otisdev.alclr.co',
    'callback_url' => 'https://otisdev.alclr.co'
];
$query_string = http_build_query( $params );
echo $store_url . $endpoint . '?' . $query_string;


$url = 'https://alclair.com';
$consumer_key = 'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469';
$consumer_secret = 'cs_87fe15086357b7e90a8d2457552ddb957ba939fb';
$options = [];
$woocommerce = new Client($url, $consumer_key, $consumer_secret, $options);
/*
$woocommerce = new Client(
    'https://alclair.com', 
    //'https://alclair.com/wp-json', 
    'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469', 
    'cs_87fe15086357b7e90a8d2457552ddb957ba939fb',
    [
        //'version' => 'wc/v2',
        'version' => 'wc/v3',
    ]
);
*/

echo "It is working</br>"; 

$order = wc_get_order( 2524692 );

//$results = $woocommerce->get('customers');

// Tracy Evans' Order
//print_r($woocommerce->get('order/2524692'));

// John Dowler - Customer
//print_r($woocommerce->get('/customers/9123', $parameters = [] ));
echo "END</br>";

?>