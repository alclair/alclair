<?php
include_once "../../config.inc.php";
//include_once "../../config.dev.inc.php";

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;	
 
//require_once 'vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
require '/var/www/html/otisdev/vendor/autoload.php';
//require $rootScope["RootPath"]."vendor/autoload.php";
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;
try 
{
   
$woocommerce = new Client(
    	'https://alclair.com',
		'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469',
		'cs_87fe15086357b7e90a8d2457552ddb957ba939fb',
		[
        	'version' => 'wc/v3', 
			]
	);
$params = [
			'after' => '2019-03-16T00:00:00',
			'per_page' => 10
			//'before' => '2019-03-16T23:59:59'
        ];
    $result = $woocommerce->get('orders', $params);
			
			
//}
    
	$arr = get_object_vars($result[4]); //28
	//echo $order[25]["billing_name"];
	
	// THIS PRINTS THINGS SO THEY ARE READABLE
	echo '<pre><code>' . print_r($arr, true) . '</code><pre>';
	//echo $arr;
    //echo '<p>Last Name is' . $arr[billing]->last_name;
    
	$order = [];
    $ind = 0;
    //for($i = 0; $i < count($result); $i++) {
		$data = get_object_vars($result[4]);  // STORE THE DATA
                
       $line_item = get_object_vars($data[line_items][0]); // PRODUCT -> 2
		$is_earphone = get_object_vars($line_item[meta_data][0]); // MODEL -> 4
		$model_name = $is_earphone["value"];
		$full_product_name = $line_item["name"];
		$coupon_lines = get_object_vars($data[coupon_lines][0]); 
		
		echo "LINE ITEM IS " . json_encode($line_item) . "<br/>";
		echo "PRICE IS  ". $line_item["price"] .  "<br/>";
		echo "DISCOUNT IS  ". $data["discount_total"] .  "<br/>";
		echo "TOTAL IS  ". $data["total"] .  "<br/>";
		echo "COUPON IS  ". $coupon_lines["code"] . "<br/>";
		echo json_encode($response);
    //}

} catch (HttpClientException $e) {
    echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
    echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
    echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
}
?>
