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

//$after  = date("Y-m-d H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
//$before = date("Y-m-d H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00'));
	
 ///////////////////// ////////////////////// ////////////////////// ////////////////////// ////////////////////// ////////////////////// ////////////////////// ///////////////////////
//$current_day = date("d");
//$current_month = date("m");
//$current_year = date("Y");
//$yesterday = $current_day - 1;

//$yesterday = date("m - d - Y", strtotime( '-1 days' ) );
//$yesterday_day = date("d", strtotime( '-1 days' ) );
//$yesterday_month = date("m", strtotime( '-1 days' ) );
//$yesterday_year = date("Y", strtotime( '-1 days' ) );

//$after  = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T00:00:00";
//$before = $current_year . "-" . $current_month . "-" . $current_day . "T00:00:00";
//$before = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T23:59:59";

//echo "After is " . $after . " and Before is " . $before . " And stuff " . $month;
//exit;
//$after = strtotime('12/01/2019 00:00:00');
//$before = strtotime('12/03/2019 23:59:59');

// https://otisdev.alclr.co/api/WooCommerce/scott_a_v2.php?StartDate=12/01/0219&EndDate=12/03/2019%22
//$after  = date("Y-m-d H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
//$before = date("Y-m-d H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00'));

$StartDate = new DateTime($_REQUEST["StartDate"] );
$EndDate = new DateTime($_REQUEST["EndDate"] );

$started = $_REQUEST["StartDate"];
$ended = $_REQUEST["EndDate"];
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
$StartDate_day = $StartDate->format('d');
$StartDate_month = $StartDate->format('m');
$StartDate_year = $StartDate->format('y');

$EndDate_day = $EndDate->format('d');
$EndDate_month = $EndDate->format('m');
$EndDate_year = $EndDate->format('y');
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//$start = date_create("2019-12-01");
$start = date_create($StartDate_year . "-" . $StartDate_month . "-" . $StartDate_day);
//$stop = date_create("2019-12-03");
$stop = date_create($EndDate_year . "-" . $EndDate_month . "-" . $EndDate_day);
$diff = date_diff($start, $stop); // DETERMINE NUMBER OF DAYS BETWEEN START AND STOP DATES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

$store = [];
$count = [];
$number = [];
$inc = 1;
$black_cable = 0;
$clear_cable = 0;

//$date = strtotime('12/01/2019 00:00:00');
$date = strtotime($_REQUEST["StartDate"]);
$starting_date = $date;
// $diff-format("%a") RETURNS THE NUMBERS OF DAYS BETWEEN START AND STOP DATES
for($g = 0; $g < $diff->format("%a")+1; $g++) { // ADDED 1 BECAUSE NEED TO GO TO MIDNIGHT OF THE DAY AFTER THE STOP DATE
	if($g != 0 ) {
		$starting_date = $ending_date;	
	}
	$ending_date = strtotime("+1 day", $starting_date);
	//echo "The date is " . date("m/d/Y", $starting_date). " and ending is " . date("m/d/Y", $ending_date) . "<br/>";
	
	$after  = date("Y-m-d", $starting_date);
	$before = date("Y-m-d", $ending_date);
	$after = $after . "T00:00:00";
	$before = $before . "T00:00:00";
	//echo "After is " . $before . "<br/>";
//}

//exit;

$params = [
	'before' => $before,
	'after' => $after,
	'per_page' => 100,			
];

/*
$params = [
	'before' => '2019-11-24T00:00:00',
	'after' => '2019-11-23T00:00:00',
	'per_page' => 100			
];
*/

//$result = $woocommerce->get('orders/12524');
/*
$order = [];
$ind = 0;

$order_index = 0;
$arr = get_object_vars($result[$order_index]); //28
$data = get_object_vars($result[$order_index]);  // STORE THE DATA
$line_item = get_object_vars($data[line_items][0]); // PRODUCT -> 2
$full_product_name = $line_item["name"];
//echo "The order number is " . $arr[number] .  "<br/>";
echo '<pre><code>' . print_r($arr, true) . '</code><pre>';
//exit;
*/
$result = $woocommerce->get('orders', $params);
for($i = 0; $i < count($result); $i++) {
//for($i = 0; $i < 5; $i++) {
	$data = get_object_vars($result[$i]);  // STORE THE DATA
    
    for($k = 0; $k < count($data[line_items]); $k++) { // CYCLES THROUGH EACH LINE ITEM WITHIN THE ORDER
	    $line_item = get_object_vars($data[line_items][$k]); 
		
	    if( empty($store) && $k == 0) { // IF STATEMENT FOR THE FIRST TIME IN THE LOOP / NEED TO SET THE $STORE ARRAY
			$store[0] = $line_item["name"];
			//$product[0]["product_name"] = $line_item["name"];
			$count[0] = 1;
			//$product[0]["product_count"] = 1;
			$number[0] = $data[number];
			//echo "1 empty state Store is " . $store[0] . " Count is " . $count[0] . "<br/>";
	    } else {
		    if(in_array($line_item["name"], $store) ) { // FIND THE PRODUCT NAME IN THE ARRAY
			    $key = array_search($line_item["name"], $store); // FIND THE INDEX/KEY OF THE PRODUCT NAME ALREADY STORED
			    $count[$key] = $count[$key] + 1;
			    //$product[$key]["count"] = 1;
				$number[$key] = $number[$key] . " " . $data[number];
				
			 	if($line_item["subtotal"] > 200) {
				 	for($j = 0; $j < count($line_item[meta_data]); $j++) {
				 		if(!strcmp($line_item[meta_data][$j]->key, "Cable Color") ) {
					 		if(!strcmp($line_item[meta_data][$j]->value, "Black") ) {
							 	$black_cable = $black_cable + 1;
							 } else {
							 	$clear_cable = $clear_cable + 1;
						 	}
						 }
						 if(!strcmp($line_item[meta_data][$j]->key, "Cable Addon Type") ) {
					 		if(in_array($line_item[meta_data][$j]->value, $store) ) { 
					 			$key = array_search($line_item[meta_data][$j]->value, $store);
					 			$count[$key] = $count[$key] + 1;
					 			$number[$key] = $number[$key] . " " . $data[number];
					 		} else {
						 		$store[$inc] = $line_item[meta_data][$j]->value;
						 		$count[$inc] = 1;
						 		$number[$inc] = $data[number];
						 		$inc = $inc + 1;
					 		}
						 }
					} 
				}
				//break;
			} else { // ADD A NEW ROW TO THE ARRAY MEANING A DIFFERENT PRODUCT
				$store[$inc] = $line_item["name"];
				$count[$inc] = 1;
				$number[$inc] = $data[number];
				//echo "INSIDE ELSE - Name is " . $store[$inc] . " Count is " . $count[$inc] . "<br/>";
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
						 if(!strcmp($line_item[meta_data][$j]->key, "Cable Addon Type") ) {
					 		if(in_array($line_item[meta_data][$j]->value, $store) ) { 
					 			$key = array_search($line_item[meta_data][$j]->value, $store);
					 			$count[$key] = $count[$key] + 1;
					 			$number[$key] = $number[$key] . " " . $data[number];
					 		} else {
						 		$store[$inc] = $line_item[meta_data][$j]->value;
						 		$count[$inc] = 1;
						 		$number[$inc] = $data[number];
						 		$inc = $inc + 1;
					 		}
						 }
					} 
				}
				//break;
			 }
	    }	 // CLOSES IF/ELSE STATEMENT	
	} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
} // END FOR LOOP THAT STEPS THROUGH EVERY ORDER

} // END THE FOR LOOP
$ending = [];
$ending[0]["product_name"] = "Black Cables";
$ending[0]["product_count"] = $black_cable;
$ending[1]["product_name"] = "Clear Cables";
$ending[1]["product_count"] = $clear_cable;
for($p = 0; $p < count($store); $p++) {
	$ending[$p+2]["product_name"] = $store[$p];
	$ending[$p+2]["product_count"] = $count[$p];
}
for($p = 0; $p < count($ending); $p++) {
	//echo "The item is " . $ending[$p]["product_name"] . " and count is " . $ending[$p]["product_count"]  . "<br/>";
}
//exit;
//echo "Count is " . count($store)  . "<br/>";
for($p = 0; $p < count($store); $p++) {
	if($p == 0) {
		//echo "Black Cable QTY: " . $black_cable . " and Clear Cable QTY: " . $clear_cable  . "<br/>";	
	}
	//echo "The item is " . $store[$p] . " and count is " . $count[$p]  . " and # is " . $number[$p] . "<br/>";
}
//exit;
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $ending;
    $response['data2'] = $result2;
    $response["started"] = $started;
    $response["ended"] = $ended;        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>