<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

//require '/var/www/html/otisdev/vendor/autoload.php';
require $rootScope["RootPath"] . '/vendor/autoload.php';
//require '/var/www/html/otis/vendor/autoload.php';
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
	
$after  = date("Y-m-d H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
$before = date("Y-m-d H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00'));
	
 ///////////////////// ////////////////////// ////////////////////// ////////////////////// ////////////////////// ////////////////////// ////////////////////// ///////////////////////
$current_day = date("d");
$current_month = date("m");
$current_year = date("Y");
//$yesterday = $current_day - 1;

$yesterday = date("m - d - Y", strtotime( '-1 days' ) );
$yesterday_day = date("d", strtotime( '-1 days' ) );
$yesterday_month = date("m", strtotime( '-1 days' ) );
$yesterday_year = date("Y", strtotime( '-1 days' ) );

$after  = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T00:00:00";
//$before = $current_year . "-" . $current_month . "-" . $current_day . "T00:00:00";
$before = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T23:59:59";

//echo "After is " . $after . " and Before is " . $before . " And stuff " . $month;
//exit;
$params = [
	'before' => $before,
	'after' => $after,
	'per_page' => 100,			
];


$params = [
	'before' => '2019-12-02T00:00:00',
	'after' => '2019-12-01T00:00:00',
	'per_page' => 100			
];
$result = $woocommerce->get('orders', $params);
//$result = $woocommerce->get('orders/12524');
$order = [];
$ind = 0;

$order_index = 3;
$arr = get_object_vars($result[$order_index]); //28
$data = get_object_vars($result[$order_index]);  // STORE THE DATA
$line_item = get_object_vars($data[line_items][0]); // PRODUCT -> 2
$full_product_name = $line_item["name"];
echo '<pre><code>' . print_r($arr, true) . '</code><pre>';
exit;

$store = [];
$inc = 1;
$black_cable = 0;
$clear_cable = 0;
//for($i = 0; $i < count($result); $i++) {
for($i = 0; $i < 5; $i++) {
	$data = get_object_vars($result[$i]);  // STORE THE DATA
    
    for($k = 0; $k < count($data[line_items]); $k++) { // CYCLES THROUGH EACH LINE ITEM WITHIN THE ORDER
	    $line_item = get_object_vars($data[line_items][$k]); 
		
	    if( empty($store) && $k == 0) { // IF STATEMENT FOR THE FIRST TIME IN THE LOOP / NEED TO SET THE $STORE ARRAY
			$store[0]["name"] = $line_item["name"];
			$store[0]["count"] = 1;
			echo "1 empty state Store is " . $store[0]["name"] . " Count is " . $store[0]["count"]  . "<br/>";
	    } else {
	    	for($m = 0; $m < count($store); $m++) { // LOOP THROUGH THE ENTIRE ARRAY $STORE
				echo "String Cmp " .  $store[$m]["name"] . " And " . $line_item["name"]  . "<br/>";
		    	if( !strcmp($store[$m]["name"], $line_item["name"]) ) { // IF STRINGS ARE EQUAL THEN ADD 1 TO THE COUNT
			 		$store[$m]["count"] = $store[$m]["count"] + 1;
			 		echo "INSIDE IF - NAME is " . $store[$m]["name"] . " and count is " . $store[$m]["count"]  . "<br/>";
			 		if($line_item["subtotal"] > 200) {
				 		for($j = 0; $j < count($line_item[meta_data]); $j++) {
				 			if(!strcmp($line_item[meta_data][$j]->key, "Cable Color") ) {
					 			if(!strcmp($line_item[meta_data][$j]->value, "Black") ) {
							 		$black_cable = $black_cable + 1;
							 	} else {
								 	$clear_cable = $clear_cable + 1;
							 	}
							 }
						} 
					}
					break;
			 	} elseif($m == count($store) - 1 ) { // ADD A NEW ROW TO THE ARRAY MEANING A DIFFERENT PRODUCT
					 $store[$m+1]["name"] = $line_item["name"];
					 $store[$m+1]["count"] = 1;
					 echo "INSIDE ELSE - Name is " . $store[$inc]["name"] . " Count is " . $store[$inc]["count"]  . "<br/>";
					 $inc = $inc + 1;
					 if($line_item["subtotal"] > 200) {
				 		for($j = 0; $j < count($line_item[meta_data]); $j++) {
				 			if(!strcmp($line_item[meta_data][$j]->key, "Cable Color") ) {
					 			if(!strcmp($line_item[meta_data][$j]->value, "Black") ) {
							 		$black_cable = $black_cable + 1;
							 	} else {
								 	$clear_cable = $clear_cable + 1;
							 	}
							 }
						} 
					}
					break;
			 	}
	    	}	
	    	//echo "The item is " . $store[$m]["name"] . " and count is " . $store[$m]["count"]  . " and COUNT IS ". $inc . "<br/>";
	    	//if($i == 0) {
				//echo "The line item is " . count($store). "<br/>";
				//exit;
			//}
	    } // CLOSES IF/ELSE STATEMENT	
	} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
} // END FOR LOOP THAT STEPS THROUGH EVERY ORDER

//echo "Count is " . count($store)  . "<br/>";
for($p = 0; $p < count($store); $p++) {
	if($p == 0) {
		//echo "Black Cable QTY: " . $black_cable . " and Clear Cable QTY: " . $clear_cable  . "<br/>";	
	}
	//echo "The item is " . $store[$p]["name"] . " and count is " . $store[$p]["count"]  . "<br/>";
}
exit;
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data2'] = $result2;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>