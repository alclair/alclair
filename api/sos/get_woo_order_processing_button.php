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
$the_order_number = $_REQUEST["order_number"];
$result = $woocommerce->get('orders/' . $the_order_number);
$order = [];
$ind = 0;
  
//$holder = json_decode(json_encode($result[$ind]), true);    
$data = get_object_vars($result);  // STORE THE DATA

//$first_name = $data[billing]->first_name;
//last_name = $data[billing]->last_name;
//$customer_name = $first_name . " " . $last_name;
$order_date =  date_format(date_create($data["date_created"]), DATE_ATOM);
//$email = $data[billing]->email;
$order_number = $data["id"];

$ind = 0;	
for($k = 0; $k < count($data[line_items]); $k++) {
		
		$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
		$is_earphone = get_object_vars($line_item[meta_data][$k]); // MODEL -> 4
		$coupon_lines = get_object_vars($data[coupon_lines][$k]); 
		
		$model_name[$ind] = $is_earphone["value"];
		$full_product_name[$ind] = $line_item["name"];
		$SKU[$ind] = $line_item["sku"];
		$shipping_total = $data["shipping_total"];
		$total[$ind] = $data["total"]; // BELIEVE THIS IS SUBTOTAL PLUS SHIPPING
		$subtotal[$ind] = $line_item["subtotal"];
		$price_original_sku = $subtotal[$ind];
		$discount[$ind] = $data["discount_total"];
		$coupon[$ind] = $coupon_lines["code"];

if( stristr($full_product_name[$ind], "Driver") || stristr($full_product_name[$ind], "POS") ) {
	$yes_no_earphone[$ind] = "YES";	
	$earphone_price = $line_item["subtotal"];
	$remember_index = $ind;
} else {
	$yes_no_earphone[$ind] = "NO";	
}

// IF THE WORD "DRIVER" OR "POS" IS INSIDE THE FULL PRODUCT NAME STORE INFO FOR IMPORT
if( !stristr($full_product_name[$ind], "UV") ) { // IF UV EXISTS DO NOT STORE THE EARPHONE
		
	if( stristr($full_product_name[$ind], "Driver") || stristr($full_product_name[$ind], "POS") || stristr($full_product_name[$ind], "Custom Hearing Protection") ) { 
			
		if(!strcmp($data["status"], "processing")  || !strcmp($data["status"], "completed") ) {
			$order[$ind]["status"] = $data["status"];
			$order[$ind]["date"] = date_format(date_create($data["date_created"]), "m/d/y"); // DATE -> 0
			$order[$ind]["order_id"] = $data["id"]; // ORDER ID -> 1
			$order[$ind]["product"] = $full_product_name; // PRODUCT -> 2 
			$order[$ind]["price"] = $price[$ind];
			$order[$ind]["total"] = $total[$ind];
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
				} elseif( stristr($line_item[meta_data][$j]->key, "Artwork") ) { // IF THE WORD ARTWORK EXISTS IN THE KEY
					if(stristr($line_item[meta_data][$j]->value, "Custom") ) { // IF THE WORD CUSTOM IS THE VALUE FIELD
						$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
						$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
						
						$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
						//$dollar_value = substr($line_item[meta_data][$j]->key, -$str_pos, -1); // GRAB DOLLAR AMOUNT
						
						$ind = $ind+1;
						$SKU[$ind] = 'ALCLR-ARTWORK';
						$subtotal[$ind] = $dollar_value;
						$price_original_sku = $price_original_sku - $dollar_value;
						$earphone_price = $earphone_price - $dollar_value;
						$yes_no_earphone[$ind] = "NO";	
					}
				} elseif( stristr($line_item[meta_data][$j]->key, "Color") ) { // IF THE WORD COLOR EXISTS IN THE KEY
					$ind = $ind+1;
					if(stristr($line_item[meta_data][$j]->value, "Glitter") ) { // IF THE WORD GLITTER IS THE VALUE FIELD
						$SKU[$ind] = 'ALCLR-GLITTER';
					} elseif(stristr($line_item[meta_data][$j]->value, "Pearlescence") ) {
						$SKU[$ind] = 'ALCLR-PEARL';
					}
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$yes_no_earphone[$ind] = "NO";	
				//} elseif(!strcmp($line_item[meta_data][$j]->key, "Cleaning Kit") ) {
				} elseif(stristr($line_item[meta_data][$j]->key, "Cleaning Kit") ) {
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$SKU[$ind] = 'ALCLR-CLEAN-KIT';
					$yes_no_earphone[$ind] = "NO";	

				//} elseif(!strcmp($line_item[meta_data][$j]->key, "Cleaning Tools") ) {
				} elseif(stristr($line_item[meta_data][$j]->key, "Cleaning Tools") ) {	
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$SKU[$ind] = 'ALCLR-CLNTOOL';
					$yes_no_earphone[$ind] = "NO";	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Dotz Clip") ) {
					$order[$ind]["dotz_clip"] = $line_item[meta_data][$j]->value;	
				} elseif(stristr($line_item[meta_data][$j]->key, "Pelican Case (") ) {
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					//$SKU[$ind] = 'ALCLR-CASE-CLAM';
					$SKU[$ind] = 'ALCLR-CASE-PELICAN';
					$yes_no_earphone[$ind] = "NO";	
				} elseif(stristr($line_item[meta_data][$j]->key, "Soft Case") ) {
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$SKU[$ind] = 'ALCLR-CASE-CLAM';
					$yes_no_earphone[$ind] = "NO";	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Upgrade ($5.00)") || !strcmp($line_item[meta_data][$j]->key, "Cable Upgrade ($20.00)") ) {
					$ind = $ind+1;
					if(stristr($line_item[meta_data][$j]->value, "64_clear") ) { 
						$SKU[$ind] = 'ALCLR-CABLE-64CUG';
					} elseif(stristr($line_item[meta_data][$j]->value, "64_black") ) {
						$SKU[$ind] = 'ALCLR-CABLE-64BUG';
					} elseif(stristr($line_item[meta_data][$j]->value, "mic_cable") ) {
						$SKU[$ind] = 'ALCLR-CABLE-MIC';
					} 
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$price_original_sku = $price_original_sku + $dollar_value;
					$earphone_price = $earphone_price + $dollar_value;
					$yes_no_earphone[$ind] = "NO";	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Upgrade Type") ) {
					$order[$ind]["cable_upgrade_type"] = $line_item[meta_data][$j]->value;
				} elseif(stristr($line_item[meta_data][$j]->key, "Cable Addon") ) {
					$ind = $ind+1;
					if(stristr($line_item[meta_data][$j]->value, "50_clear") ) {
						$SKU[$ind] = 'ALCLR-CABLE-50CINC';
					} elseif(stristr($line_item[meta_data][$j]->value, "50_black") ) {
						$SKU[$ind] = 'ALCLR-CABLE-50CINC';
					} elseif(stristr($line_item[meta_data][$j]->value, "64_clear") ) {
						$SKU[$ind] = 'ALCLR-CABLE-64C';
					} elseif(stristr($line_item[meta_data][$j]->value, "64_black") ) {
						$SKU[$ind] = 'ALCLR-CABLE-64B';
					} elseif(stristr($line_item[meta_data][$j]->value, "mic_cable") ) {
						$SKU[$ind] = 'ALCLR-CABLE-MIC';
					}
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$yes_no_earphone[$ind] = "NO";	
					$response["test"] = "HERE";
					//echo json_encode($response);
					//exit;
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
  $subtotal[$remember_index] = $earphone_price;
//echo $data["number"] . " is and I is " . $i ." and date is " . $data["date_created"] . " PRODUCT IS " . $SKU[7]. " and first name is " . $first_name. "<br/>";
} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
//$SKUs[$i] = implode(" / ", $SKU);
//$emails[$i] = implode(" / ", $email);
	
	//$response['SKUs'] = $SKUs;
	$response['SKUs'] = $SKU;
	$response['SUBTOTALs'] = $subtotal;
	$response['SHIPPING_AMOUNT'] = $shipping_total;
	$response['price_original_sku'] = $price_original_sku;
	$response['order_number'] = $order_number;
	$response['email'] = $email;
	$response['customer_name'] = $customer_name;
	$response['order_date'] = $order_date;
	$response["Is_Earphone"] = $yes_no_earphone;
	//$response['SKU'] = $SKU;
	$response['ind'] = $ind;
	$response["num_items"] = count($data[line_items]);			
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