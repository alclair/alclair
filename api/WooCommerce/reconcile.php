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
	
	
$START = strtotime($_REQUEST["StartDate"] . "00:00:00");
$END = strtotime($_REQUEST["EndDate"] . "23:59:59");
$START  = date("Y-m-d H:i:s", $START);
$END  = date("Y-m-d H:i:s", $END);

$minus_3_months = new DateTime($START);
$minus_3_months->modify('-4 month');
$minus_3_months_string = $minus_3_months->format('Y-m-d H:i:s' );

//$StartDate = new DateTime($_REQUEST["StartDate"] );
$EndDate = new DateTime($_REQUEST["EndDate"] );

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////
$StartDate_day = $minus_3_months->format('d');
$StartDate_month = $minus_3_months->format('m');
$StartDate_year = $minus_3_months->format('y');

$EndDate_day = $EndDate->format('d');
$EndDate_month = $EndDate->format('m');
$EndDate_year = $EndDate->format('y');

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

$starting_date = strtotime($minus_3_months_string);

$order = [];
$ind = 0;
for($g = 0; $g < $diff->format("%a")+1; $g++) { // ADDED 1 BECAUSE NEED TO GO TO MIDNIGHT OF THE DAY AFTER THE STOP DATE
	if($g != 0 ) {
		$starting_date = $ending_date;	
	}
	$ending_date = strtotime("+1 day", $starting_date);
	
	$after  = date("Y-m-d", $starting_date);
	//$before = date("Y-m-d", $ending_date);
	$before = date("Y-m-d", $starting_date);
	$after = $after . "T00:00:00";
	$before = $before . "T23:59:59";
	//$before = $after . "T23:59:59";

	$params = [
		'before' => $before,
		'after' => $after,
		'per_page' => 100,			
	];

	$result = $woocommerce->get('orders', $params);
  
	for($i = 0; $i < count($result); $i++) {
		$data = get_object_vars($result[$i]);  // STORE THE DATA	
		for($k = 0; $k < count($data[line_items]); $k++) {
	    	//echo "Count is " . count($order) . "<br>";
	    	
			$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
			$is_earphone = get_object_vars($line_item[meta_data][$k]); // MODEL -> 4
			$coupon_lines = get_object_vars($data[coupon_lines][$k]); 
	
			$model_name = $is_earphone["value"];
			$full_product_name = $line_item["name"];
				
		if( !stristr($full_product_name, "UV") ) { // IF UV EXISTS DO NOT STORE THE EARPHONE
			//if( stristr($full_product_name, "Driver") !== false || stristr($full_product_name, "POS") !== false || stristr($full_product_name, "Custom Hearing Protection") !== false) { 
			//if( stristr($full_product_name, "Driver") || stristr($full_product_name, "POS") || stristr($full_product_name, "Custom Hearing Protection") ) { 
			if( stristr($full_product_name, "Driver") || stristr($full_product_name, "POS") ) { 
				
				if(!strcmp($data["status"], "completed") ) {
	
					$date_completed = $data["date_completed"];
					$date_completed = strtotime($data["date_completed"]);
					$date_completed  = date("Y-m-d H:i:s", $date_completed);
						
					if($date_completed <= $END && $date_completed >= $START) {
						$order[$ind]["index"] = $ind;
						$order[$ind]["g"] = $g;
						$order[$ind]["i"] = $i;
						$order[$ind]["k"] = $k;
						$order[$ind]["after"] = $after;
						$order[$ind]["before"] = $before;
						$order[$ind]["num_earphones_per_order"] = 1;
						$order[$ind]["status"] = $data["status"];
						$order[$ind]["date_created"] = date_format(date_create($data["date_created"]), "m/d/y"); // DATE -> 0
						$order[$ind]["date_completed"] = $date_completed;
						$order[$ind]["order_id"] = $data["id"]; // ORDER ID -> 1
						$order[$ind]["product"] = $full_product_name; // PRODUCT -> 2 
		 				
						$order[$ind]["billing_name"] = $data[billing]->first_name . " " . $data[billing]->last_name;
						$order[$ind]["shipping_name"] = $data[shipping]->first_name . " " . $data[shipping]->last_name;
						$ind++;
					}
					
				} // CLOSES IF STATEMENT - STATUS
		    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
		  } // CLOSES IF STATEMENT - IS IT A UNIVERSAL EARPHONE - LINE 189
		  
		} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
	} // END FOR LOOP THAT STEPS THROUGH EVERY ORDER
} // END THE FOR LOOP
	$response["num_shipped"] = $ind;
//exit;
///////////////////////////////////////////////////   ENDS OBTAINING COMPLETED ORDERS FROM WOOCOMMERCE    ///////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// GET ORDERS DONE BY DATE
$started = $_REQUEST["StartDate"] . ' 00:00:00';
$ended = $_REQUEST["EndDate"] . ' 23:59:59';

$query = "SELECT DISTINCT t1.import_orders_id, t2.designed_for, t3.status_of_order, t2.order_id AS order_id, to_char(t1.date, 'MM/dd/yyyy    HH24:MI') as date_done, t4.name AS model, t1.order_status_id, t1.notes,  t2.id
FROM order_status_log AS t1
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
LEFT JOIN monitors AS t4 ON t2.model = t4.name
WHERE t1.order_status_id = 12 AND t2.active = TRUE AND t4.name IS NOT NULL AND t1.date >= :started AND t1.date <= :ended  
ORDER BY date_done ASC,  t1.import_orders_id";
$stmt = pdo_query( $pdo, $query, array(":started"=>$started, ":ended"=>$ended)); 
$result = pdo_fetch_all( $stmt );
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// GET ALL OF THE ORDER IDS FROM WOOCOMMERCE & OTIS
$woo = [];
for($i = 0; $i < count($order); $i++) {
	$woo[$i] = $order[$i]["order_id"];
}
$otis = [];
for($i = 0; $i < count($result); $i++) {
	$otis[$i] = $result[$i]["order_id"];
}

$ind = 0;
$in_woo_not_otis = array();
for($i = 0; $i < count($woo); $i++) {
	if(!in_array($woo[$i], $otis)) {
		$in_woo_not_otis[$ind] = $order[$i];
		$ind = $ind+1;
	}
}
$ind2 = 0;
$in_otis_not_woo = array();
for($i = 0; $i < count($otis); $i++) {
	if(!in_array($otis[$i], $woo)) {
		$in_otis_not_woo[$ind2] = $result[$i];
		$ind2 = $ind2+1;
	}
}

//$response['test'] = "Started is " . $started . " and Ended is " . $ended;
$response['test'] = "Num of Woo is " . count($woo) . " and Num of OTIS is " . count($otis) . " ind and ind2 is " . $ind . " and " . $ind2 . " and random is " . $in_otis_not_woo[5]["order_id"];

$response["InWooNotOits"] = $in_woo_not_otis;
$response["InOtisNotWoo"] = $in_otis_not_woo;

$indexes = array_keys($uid, '9049908'); //array(0, 1)
//$response['test'] = count($indexes);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $order;
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