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
 
//otisdev.alclr.co/api/WooCommerce/03_print_order_to_screen.php 
$woocommerce = new Client(
    	'https://alclair.com',
		'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469',
		'cs_87fe15086357b7e90a8d2457552ddb957ba939fb',
		[
        	'version' => 'wc/v3', 
			]
	);
$params = [
			//'before' => '2020-01-07T23:59:59',
			'before' => '2019-12-30T23:59:59',
			'after' => '2019-12-30T00:00:00',
			//'before' => '2019-05-22T23:59:59',
			//'after' => '2019-05-22T00:00:00',
			'per_page' => 100			
			//'created_at_min' => '2014-01-01',
			//'created_at_max' => '2014-01-31'
        ];
    //$result = $woocommerce->get('orders', $params);
	$result = $woocommerce->get('orders', $params);		
			
//}
    
    for($i = 0; $i <count($result); $i++) {
	    $data = get_object_vars($result[$i]);  // STORE THE DATA
    		//$holder = json_decode(json_encode($result[$ind]), true);    
		$line_item = get_object_vars($data[line_items][$i]); // PRODUCT -> 2
		//echo "Name is " . $line_item["name"] . "<br/>";
		echo $data["number"] . " is and I is " . $i ." and date is " . $data["date_created"] . "<br/>";
	}
	
	$order_index = 1;
	$arr = get_object_vars($result[$order_index]); //28
	$data = get_object_vars($result[$order_index]);  // STORE THE DATA
	
	$line_item = get_object_vars($data[line_items][0]); // PRODUCT -> 2
	$full_product_name = $line_item["name"];
    $hearing_protection_color =  stristr($full_product_name, "- ");
    echo "Line 1 " . substr($line_item[meta_data][0]->key, 27, 88) . "<br/>";
    echo "Line 2 " . ($line_item[meta_data][14]->key) . "<br/>";
    echo "Line 3 " . substr($full_product_name, 28, 88) . "<br/>";
    echo "Line 4 " . $full_product_name . "<br/>";
    
	if(!strcmp( substr($line_item[meta_data][14]->key, 0, 24), "Hearing Protection Color") ) {
	    echo "Line 5 in IF <br/>";
	} else {
		echo "Line 5 in ELSE <br/>";
	}
	echo "Line 6 " . substr($full_product_name, 0, 25) . "<br/>";
	
	echo "This many line items " . count($data[line_items]) . "<br/>";
	if( get_object_vars($data[line_items][$order_index])  ) {
		$line_item = get_object_vars($data[line_items][$order_index]); // PRODUCT -> 2
		echo "Name is " . $line_item["name"] . "<br/>";
		echo "NOT EMPTY";
	} else {
		echo "Name is " . $line_item["name"] . "<br/>";
		echo " EMPTY";
	}
	//echo $order[25]["billing_name"];
	
	// THIS PRINTS THINGS SO THEY ARE READABLE
	echo '<pre><code>' . print_r($arr, true) . '</code><pre>';
	//echo $arr;
    //echo '<p>Last Name is' . $arr[billing]->last_name;
    
	$order = [];
    $ind = 0;
    $index_to_print = 0;
    //for($i = 0; $i < count($result); $i++) {
		$data = get_object_vars($result[4]);  // STORE THE DATA
                
       $line_item = get_object_vars($data[line_items][0]); // PRODUCT -> 2
       
		$is_earphone = get_object_vars($line_item[meta_data][0]); // MODEL -> 4
		$model_name = $is_earphone["value"];
		$full_product_name = $line_item["name"];
		$coupon_lines = get_object_vars($data[coupon_lines][0]); 
		
		if(!strcmp( substr($data["number"], 0, 4), "AATN") ) {
			$jonny = "JONNY";
		} else {
			$jonny = "NOT JONNY";
		}
		
		echo "Is it ? " . $jonny . " and the order id is " . $data["id"]. "<br/>" ;
		
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
