<?php
//echo "Adsfasdf";
//require __DIR__ . '/vendor/autoload.php';
require '/var/www/html/otisdev/vendor/autoload.php';
use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

$woocommerce = new Client(
    'https://alclair.com',
    'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469',
    'cs_87fe15086357b7e90a8d2457552ddb957ba939fb',
    [
        'version' => 'wc/v3', 
    ]
);

print_r($woocommerce);

try {
    //$result = $woocommerce->get('orders');
    $params = [
			//'after' => '2019-03-16T00:00:00',
			'per_page' => 1
			//'before' => '2019-03-16T23:59:59'
        ];
    $result = $woocommerce->get('orders', $params);
    print_r($result);
} catch (HttpClientException $e) {
    echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
    echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
    echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
}
?>