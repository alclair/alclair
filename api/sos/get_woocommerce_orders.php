<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

require $rootScope["RootPath"] . '/vendor/autoload.php';

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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$order_number = $_REQUEST["order_number"];

$current_day = date("d");
$current_month = date("m");
$current_year = date("Y");

$yesterday = date("m - d - Y", strtotime( '-1 days' ) );
$yesterday_day = date("d", strtotime( '-1 days' ) );
$yesterday_month = date("m", strtotime( '-1 days' ) );
$yesterday_year = date("Y", strtotime( '-1 days' ) );

$HOURS = array("T00:00:00", "T06:00:00", "T06:00:01", "T12:00:00", "T12:00:01", "T18:00:00", "T18:00:01", "T23:59:59");	
//$after  = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T00:00:00";
//$before = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T23:59:59";
$date = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day;
$date = '2019-12-01';
$result = [];
$params = ['before' =>  $date . $HOURS[1], 'after' => $date . $HOURS[0], 'per_page' => 100];
$result1 = $woocommerce->get('orders', $params);

$params = ['before' => $date . $HOURS[3], 'after' => $date . $HOURS[2], 'per_page' => 100];
$result2 = $woocommerce->get('orders', $params);

$params = ['before' => $date . $HOURS[5], 'after' => $date . $HOURS[4], 'per_page' => 100];
$result3 = $woocommerce->get('orders', $params);

$params = ['before' => $date . $HOURS[7], 'after' => $date . $HOURS[6], 'per_page' => 100	];
$result4 = $woocommerce->get('orders', $params);

$result = array_merge($result1, $result2, $result3, $result4);

$order = [];
$ind = 0;
  
for($i = 0; $i < count($result); $i++) {
    		//$holder = json_decode(json_encode($result[$ind]), true);    
		$data = get_object_vars($result[$i]);  // STORE THE DATA
		$first_name[$i] = $data[billing]->first_name;
		$last_name[$i] = $data[billing]->last_name;
		//$billing = get_object_vars($data[billing]);
		//$first_name[$i] = $billing["first_name"];
		//$last_name[$i] = $billing["last_name"];
		$email[$i] = $billing["email"];
	
	$ind = 0;	
    for($k = 0; $k < count($data[line_items]); $k++) {
			
			$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
			$is_earphone = get_object_vars($line_item[meta_data][$k]); // MODEL -> 4
			$coupon_lines = get_object_vars($data[coupon_lines][$k]); 
			
			$model_name[$ind] = $is_earphone["value"];
			$full_product_name[$ind] = $line_item["name"];
			$SKU[$ind] = $line_item["sku"];
			$price[$ind] = $line_item["subtotal"];
			$total[$ind] = $data["total"];
			$discount[$ind] = $data["discount_total"];
			$coupon[$ind] = $coupon_lines["code"];
			$ind++;


	// IF THE WORD "DRIVER" OR "POS" IS INSIDE THE FULL PRODUCT NAME STORE INFO FOR IMPORT
	if( !stristr($full_product_name, "UV") ) { // IF UV EXISTS DO NOT STORE THE EARPHONE

		if( stristr($full_product_name, "Driver") || stristr($full_product_name, "POS") || stristr($full_product_name, "Custom Hearing Protection") ) { 
			
			if(!strcmp($data["status"], "processing")  || !strcmp($data["status"], "completed") ) {
				$order[$ind]["status"] = $data["status"];
				$order[$ind]["date"] = date_format(date_create($data["date_created"]), "m/d/y"); // DATE -> 0
				$order[$ind]["order_id"] = $data["id"]; // ORDER ID -> 1
				$order[$ind]["product"] = $full_product_name; // PRODUCT -> 2 
				$order[$ind]["price"] = $price;
				$order[$ind]["total"] = $total;
				$order[$ind]["discount"] = $discount;
				$order[$ind]["coupon"] = $coupon;
				
				// CHECK TO SEE IF ORDER IS ONLY FOR CUSTO HEARING PROTECTION
				if(!strcmp( substr($full_product_name, 0, 25), "Custom Hearing Protection") ) {
					$order[$ind]["hearing_protection"] = TRUE;
					//$order[$ind]['hearing_protection_color'] = $line_item[meta_data][0]->value;
				}
 				
				for($j = 0; $j < count($line_item[meta_data]); $j++) {
					if(!strcmp($line_item[meta_data][$j]->key, "Model") ) {
						// DELETED WHAT WAS HERE BECAUSE IT WAS NOT NEEDED
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Color") ) {
						$order[$ind]["cable_color"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "64in. Cable") ) {
						$order[$ind]["64in_cable"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Mic Cable") ) {
						$order[$ind]["mic_cable"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Forza Audio") ) {
						$order[$ind]["forza_audio"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cleaning Kit") ) {
						$order[$ind]["cleaning_kit"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cleaning Tools") ) {
						$order[$ind]["cleaning_tools"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Dotz Clip") ) {
						$order[$ind]["dotz_clip"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Pelican Case") ) {
						$order[$ind]["pelican_case"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Soft Case") ) {
						$order[$ind]["soft_case"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Upgrade") ) {
						$order[$ind]["cable_upgrade"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Upgrade Type") ) {
						$order[$ind]["cable_upgrade_type"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Addon") ) {
						$order[$ind]["cable_addon"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Addon Type") ) {
						$order[$ind]["cable_addon_type"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Musician's Plugs 9dB") ) {
						$order[$ind]["musicians_plugs_9db"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Musician's Plugs 15dB") ) {
						$order[$ind]["musicians_plugs_15db"] = $line_item[meta_data][$j]->value;						
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Musician's Plugs 25dB") ) {
						$order[$ind]["musicians_plugs_25db"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Musician's Plugs") ) {
						$order[$ind]["musicians_plugs"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Wood Box") ) {
						$order[$ind]["wood_box"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Standard Cable") ) {
						$order[$ind]["standard_cable"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Long Cable") ) {
						$order[$ind]["long_cable"] = $line_item[meta_data][$j]->value;	
					}	
				} // CLOSES FOR LOOP - METADATA
				$ind++;
			} // CLOSES IF STATEMENT - STATUS
	    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
	  } // CLOSES IF STATEMENT - IS IT A UNIVERSAL EARPHONE - LINE 189

	//echo $data["number"] . " is and I is " . $i ." and date is " . $data["date_created"] . " PRODUCT IS " . $SKU[7]. " and first name is " . $first_name. "<br/>";
	} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
	$SKUs[$i] = implode(" / ", $SKU);
	$emails[$i] = implode(" / ", $email);
} // END FOR LOOP THAT STEPS THROUGH EVERY ORDER
	
	$response['SKUs'] = $SKUs;
	$response['emails'] = $emails;
	//$response['SKU'] = $SKU;
	$response['ind'] = $ind;
	$response["num_orders"] = $i;			
	$response['code'] = 'success';
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>