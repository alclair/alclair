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
$the_order_number = 10570381;

$result = $woocommerce->get('orders/' . $the_order_number);
$order = [];
$ind = 0;
  
$data = get_object_vars($result);  // STORE THE DATA
$email = $data[billing]->email;
$order_date =  date_format(date_create($data["date_created"]), DATE_ATOM);
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
		$cart_tax = $data["cart_tax"];
		
		if($ind == 0 ) {
			$response["test"] = $SKU[$ind];
			//echo json_encode($response);
			//exit;	
		}
		$total[$ind] = $data["total"]; // BELIEVE THIS IS SUBTOTAL PLUS SHIPPING
		$subtotal[$ind] = $line_item["subtotal"];
		$price_original_sku = $subtotal[$ind];
		$discount[$ind] = -(double) $data["discount_total"]; // DISCOUNT
		
		$coupon[$ind] = $coupon_lines["code"];

		if( stristr($full_product_name[$ind], "Driver") || stristr($full_product_name[$ind], "POS") ) {
			$yes_no_earphone[$ind] = "YES";	
			$earphone_price = $line_item["subtotal"];
			$remember_index = $ind;
		} else {
			$yes_no_earphone[$ind] = "NO";	
			$earphone_price = $line_item["subtotal"];
			$remember_index = $ind;
		}
		
		if( stristr($full_product_name[$ind], "Headphone Vacuum - Headphone Vac Pro") ) {
			//$ind = $ind + 1;
		}
		if( stristr($full_product_name[$ind], "Headphone Vacuum - Headphone Vac Jr.") ) {
			//$ind = $ind + 1;
		}
	
		
// IF THE WORD "DRIVER" OR "POS" IS INSIDE THE FULL PRODUCT NAME STORE INFO FOR IMPORT
//if( !stristr($full_product_name[$ind], "UV") ) { // IF UV EXISTS DO NOT STORE THE EARPHONE
		
	if( stristr($full_product_name[$ind], "Driver") || stristr($full_product_name[$ind], "POS") || stristr($full_product_name[$ind], "Custom Hearing Protection") ) { 
	//if( stristr($full_product_name[$ind], "Driver") || stristr($full_product_name[$ind], "POS")  ) { 		
		if(!strcmp($data["status"], "processing") ) { // || !strcmp($data["status"], "completed") ) {
		
			for($j = 0; $j < count($line_item[meta_data]); $j++) {
				if(!strcmp($line_item[meta_data][$j]->key, "Model") ) {
					// DELETED WHAT WAS HERE BECAUSE IT WAS NOT NEEDED
				
				} elseif(stristr($line_item[meta_data][$j]->key, "Hearing Protection ($") ) {	
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					
					$ind = $ind+1;
					$subtotal[$ind] = $dollar_value;
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$yes_no_earphone[$ind] = "NO";
					
					if(!strcmp($line_item[meta_data][$j+1]->value, "Black")) {
						$SKU[$ind] = 'ALCLR-HP-BLK';
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Blue")) {
						$SKU[$ind] = 'ALCLR-HP-BLU';
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Brown")) {
						$SKU[$ind] = 'ALCLR-HP-BRN';
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Clear")) {
						$SKU[$ind] = 'ALCLR-HP-CLR';
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Green")) {
						$SKU[$ind] = 'ALCLR-HP-GRN';
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Orange")) {
						$SKU[$ind] = 'ALCLR-HP-ORNG';	
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Pink")) {
						$SKU[$ind] = 'ALCLR-HP-PNK';
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Red")) {
						$SKU[$ind] = 'ALCLR-HP-RED';
					} elseif(!strcmp($line_item[meta_data][$j+1]->value, "Yellow")) {	
						$SKU[$ind] = 'ALCLR-HP-YLW';	
					}
					
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Color") ) {
					
					$ind = $ind+1;
					if(!strcmp($line_item[meta_data][$j]->value, "Black") ) {
						$SKU[$ind] = 'ALCLR-CABLE-50BINC';	
					} elseif(!strcmp($line_item[meta_data][$j]->value, "Clear") ) {
						$SKU[$ind] = 'ALCLR-CABLE-50CINC';
					} elseif(!strcmp($line_item[meta_data][$j]->value, "PREMIUM STUDIO CABLE") ) {
						$SKU[$ind] = 'ALCLR-CABLE-PREM';
					}
					
					$subtotal[$ind] = 0;
					$price_original_sku = $price_original_sku - $dollar_value;
					//$earphone_price = $earphone_price - $dollar_value;
					$yes_no_earphone[$ind] = "NO";	

					
				} elseif(!strcmp($line_item[meta_data][$j]->key, "64in. Cable") ) {
					//$order[$ind]["64in_cable"] = $line_item[meta_data][$j]->value;	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Mic Cable") ) {
					//$order[$ind]["mic_cable"] = $line_item[meta_data][$j]->value;	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Forza Audio") ) {
					//$order[$ind]["forza_audio"] = $line_item[meta_data][$j]->value;
				} elseif( stristr($line_item[meta_data][$j]->key, "Artwork") ) { // IF THE WORD ARTWORK EXISTS IN THE KEY
					if(stristr($line_item[meta_data][$j]->value, "Custom") ) { // IF THE WORD CUSTOM IS THE VALUE FIELD
						$str_pos = strrpos($line_item[meta_data][$j]->value, "("); // FIND OPEN PARENTHESIS
						$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
						
						//$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
						$dollar_value = 50; // HARD CODED BECAUSE THE WORD FEE EXISTS IN THE TEXT - WHAT?!?!
						
						
						$ind = $ind+1;
						$SKU[$ind] = 'ALCLR-ARTWORK';
						$subtotal[$ind] = $dollar_value;
						$price_original_sku = $price_original_sku - $dollar_value;
						$earphone_price = $earphone_price - $dollar_value;
						$yes_no_earphone[$ind] = "NO";	
					}

				} elseif( stristr($line_item[meta_data][$j]->key, "Color ($") ) { // IF THE WORD COLOR EXISTS IN THE KEY
					$ind = $ind+1;
					if(stristr($line_item[meta_data][$j]->value, "Glitter") ) { // IF THE WORD GLITTER IS THE VALUE FIELD
						$SKU[$ind] = 'ALCLR-GLITTER';
					} elseif(stristr($line_item[meta_data][$j]->value, "Pearlescent") ) {
						$SKU[$ind] = 'ALCLR-PEARL';
					} elseif(stristr($line_item[meta_data][$j]->value, "Wood or Fashion") ) {
						$SKU[$ind] = 'ALCLR-WOOD';
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
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$SKU[$ind] = 'ALCLR-CLEAN-KIT';
					$yes_no_earphone[$ind] = "NO";	

				//} elseif(!strcmp($line_item[meta_data][$j]->key, "Cleaning Tools") ) {
				} elseif(stristr($line_item[meta_data][$j]->key, "Cleaning Tools") ) {	
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$SKU[$ind] = 'ALCLR-CLNTOOL';
					$yes_no_earphone[$ind] = "NO";	
				} elseif(stristr($line_item[meta_data][$j]->key, "Dotz Clip ($") ) {
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$SKU[$ind] = 'ALCLR-DOTZ-1';
					$yes_no_earphone[$ind] = "NO";
				} elseif(stristr($line_item[meta_data][$j]->key, "Pelican Case (") ) {
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$response["test"] = $line_item[meta_data][$j]->key;
					//echo json_encode($response);
					//exit;	
					$SKU[$ind] = 'ALCLR-CASE-PELICAN';
					$yes_no_earphone[$ind] = "NO";	
				} elseif(stristr($line_item[meta_data][$j]->key, "Soft Case") ) {
					$ind = $ind+1;
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$subtotal[$ind] = $dollar_value;
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
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
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$yes_no_earphone[$ind] = "NO";	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Upgrade Type") ) {
					//$order[$ind]["cable_upgrade_type"] = $line_item[meta_data][$j]->value;
				} elseif(stristr($line_item[meta_data][$j]->key, "Cable Addon ($") ) {
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
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					$yes_no_earphone[$ind] = "NO";	
					
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Addon Type") ) {
					//$order[$ind]["cable_addon_type"] = $line_item[meta_data][$j]->value;	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Musicians Plugs 9dB") ) {
					//$order[$ind]["musicians_plugs_9db"] = $line_item[meta_data][$j]->value;	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Musicians Plugs 15dB") ) {
					//$order[$ind]["musicians_plugs_15db"] = $line_item[meta_data][$j]->value;						
				} elseif(stristr($line_item[meta_data][$j]->key, "Musicians Plugs 25dB") ) {
					/*
					$ind = $ind+1;
					$subtotal[$ind] = 125;
					$SKU[$ind] = 'ALCLR-PLUG-25';
					$yes_no_earphone[$ind] = "NO";		
					*/
				} elseif(stristr($line_item[meta_data][$j]->key, "Musicians Plugs ($") ) {
					$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
					$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
					$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
					$price_original_sku = $price_original_sku - $dollar_value;
					$earphone_price = $earphone_price - $dollar_value;
					
					if(!strcmp($line_item[meta_data][$j]->value, "9dB") ) {
						$ind = $ind+1;
						$subtotal[$ind] = 125;
						$SKU[$ind] = 'ALCLR-PLUG-9';
						$yes_no_earphone[$ind] = "NO";	
					} elseif(!strcmp($line_item[meta_data][$j]->value, "15dB") ) {
						$ind = $ind+1;
						$subtotal[$ind] = 125;
						$SKU[$ind] = 'ALCLR-PLUG-15';
						$yes_no_earphone[$ind] = "NO";	
					} elseif(!strcmp($line_item[meta_data][$j]->value, "25dB") ) {
						$ind = $ind+1;
						$subtotal[$ind] = 125;
						$SKU[$ind] = 'ALCLR-PLUG-9';
						$yes_no_earphone[$ind] = "NO";	
					} elseif(!strcmp($line_item[meta_data][$j]->value, "9dB_15dB") ) {
						$ind = $ind+1;
						$subtotal[$ind] = 100;
						$SKU[$ind] = 'ALCLR-PLUG-9';
						$yes_no_earphone[$ind] = "NO";
						$ind = $ind+1;
						$subtotal[$ind] = 100;
						$SKU[$ind] = 'ALCLR-PLUG-15';
						$yes_no_earphone[$ind] = "NO";	
					} elseif(!strcmp($line_item[meta_data][$j]->value, "9dB_25dB") ) {
						$ind = $ind+1;
						$subtotal[$ind] = 100;
						$SKU[$ind] = 'ALCLR-PLUG-9';
						$yes_no_earphone[$ind] = "NO";
						$ind = $ind+1;
						$subtotal[$ind] = 100;
						$SKU[$ind] = 'ALCLR-PLUG-25';
						$yes_no_earphone[$ind] = "NO";		
					} elseif(!strcmp($line_item[meta_data][$j]->value, "15dB_25dB") ) {
						$ind = $ind+1;
						$subtotal[$ind] = 100;
						$SKU[$ind] = 'ALCLR-PLUG-15';
						$yes_no_earphone[$ind] = "NO";
						$ind = $ind+1;
						$subtotal[$ind] = 100;
						$SKU[$ind] = 'ALCLR-PLUG-25';
						$yes_no_earphone[$ind] = "NO";			
					} elseif(!strcmp($line_item[meta_data][$j]->value, "9dB_15dB_25dB") ) {
						$ind = $ind+1;
						$subtotal[$ind] = 75;
						$SKU[$ind] = 'ALCLR-PLUG-9';
						$yes_no_earphone[$ind] = "NO";
						$ind = $ind+1;
						$subtotal[$ind] = 75;
						$SKU[$ind] = 'ALCLR-PLUG-15';
						$yes_no_earphone[$ind] = "NO";	
						$ind = $ind+1;
						$subtotal[$ind] = 75;
						$SKU[$ind] = 'ALCLR-PLUG-25';
						$yes_no_earphone[$ind] = "NO";	
					}
					
					
					//$yes_no_earphone[$ind] = "NO";	
					//$response["test"] = $earphone_price;
					//echo json_encode($response);
					//exit;	

				} elseif(!strcmp($line_item[meta_data][$j]->key, "Wood Box") ) {
					//$order[$ind]["wood_box"] = $line_item[meta_data][$j]->value;	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Standard Cable") ) {
					//$order[$ind]["standard_cable"] = $line_item[meta_data][$j]->value;	
				} elseif(!strcmp($line_item[meta_data][$j]->key, "Long Cable") ) {
					//$order[$ind]["long_cable"] = $line_item[meta_data][$j]->value;	
				}	
			} // CLOSES FOR LOOP - METADATA
			$ind++;
		} // CLOSES IF STATEMENT - STATUS
    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
  //} // CLOSES IF STATEMENT - IS IT A UNIVERSAL EARPHONE
  else {  
	  // CHECK TO SEE IF ORDER IS ONLY FOR CUSTOM HEARING PROTECTION
		if(stristr( substr($full_product_name, 0, 25), "Custom Hearing Protection -") ) {
			
			//$str_pos = strrpos($line_item[meta_data][$j]->key, "("); // FIND OPEN PARENTHESIS
			//$str_pos = $str_pos + 2; // JUMP PAST THE PARENTHESIS AND THE $ SIGN AND KEEP ONLY THE DOLLAR AMOUNT
			//$dollar_value = substr($line_item[meta_data][$j]->key, $str_pos, -1);
			//$price_original_sku = $price_original_sku - $dollar_value;
			//$earphone_price = $earphone_price - $dollar_value;

			//$ind = $ind+1;
			//$subtotal[$ind] = $dollar_value;
				/*			
			$SKU[$ind] = 'ALCLR-HP-BLK';
			$SKU[$ind] = 'ALCLR-HP-BLU';
			$SKU[$ind] = 'ALCLR-HP-BRN';
			$SKU[$ind] = 'ALCLR-HP-CLR';
			$SKU[$ind] = 'ALCLR-HP-GRN';
			$SKU[$ind] = 'ALCLR-HP-ORNG';
			$SKU[$ind] = 'ALCLR-HP-PNK';
			$SKU[$ind] = 'ALCLR-HP-RED';
			$SKU[$ind] = 'ALCLR-HP-YLW';
			$yes_no_earphone[$ind] = "NO";
			*/
		}
		if( stristr($full_product_name[$ind], "Musician Earplug Filters - -9db") ) {
			$SKU[$ind] = 'ALCLR-FILTER-9';
		}
		if( stristr($full_product_name[$ind], "Musician Earplug Filters - -15db") ) {
			$SKU[$ind] = 'ALCLR-FILTER-15';
		}
		if( stristr($full_product_name[$ind], "Musician Earplug Filters - -25db") ) {
			$SKU[$ind] = 'ALCLR-FILTER-25';
		}
	  
	$ind = $ind + 1;
  }
  $subtotal[$remember_index] = $earphone_price;
//echo $data["number"] . " is and I is " . $i ." and date is " . $data["date_created"] . " PRODUCT IS " . $SKU[7]. " and first name is " . $first_name. "<br/>";
} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
//$SKUs[$i] = implode(" / ", $SKU);
//$emails[$i] = implode(" / ", $email);

if($cart_tax > 0) {
	$SKU[$ind] = 'ALCLAIR-SALESTAXPAYABLE';
	$subtotal[$ind] = $cart_tax;
}
	
	//$earphone[0] = 'ALCLR-DUALXB';
	//$MSRP[0] = 349;
	//$earphone[1] = 'ALCLR-ELECTRO';
	//$MSRP[1] = 1499;
	// LOOK UP TABLE/ARRAY
	$earphone = array('ALCLR-VERSA-1', 'ALCLR-VERSA-POS', 'ALCLR-DUAL', 'ALCLR-DUAL-POS', 'ALCLR-DUALXB', 'ALCLR-DUALXB-POS', 'ALCLR-REFERENCE-POS', 'ALCLR-REFERENCE', 'ALCLR-TOUR-POS', 'ALCLR-TOUR', 'ALCLR-RSM-POS', 'ALCLR-RSM', 'ALCLR-CMVK', 'ALCLR-CMVK-POS', 'ALCLR-SPIRE-POS', 'ALCLR-SPIRE', 'ALCLR-STUDIO3-POS', 'ALCLR-STUDIO3', 'ALCLR-STUDIO4-POS', 'ALCLR-STUDIO4', 'ALCLR-REVX-POS', 'ALCLR-REVX', 'ALCLR-ELECTRO', 'ALCLR-ELECTRO-POS', 'VERSA-DUAL', 'ALCLR-ESM', 'ALCLR-ESM-POS');
	$MSRP = array(349, 349, 349, 349, 349, 349, 499, 499, 499, 499, 649, 649, 649, 649, 849, 849, 749, 749, 949, 949, 1499, 1499, 1499, 1499, 249);
	/*
	$discount = 0;
	for($k = 0; $k < count($SKU); $k++) {  // COUNT SKUs
		if(!strcmp($yes_no_earphone[$k], "YES")	 ) {
			for($p = 0; $p < count($earphone); $p++) {	
				if(!strcmp($SKU[$k], $earphone[$p])) {
					
					if($total[$k] != $MSRP[$p]) {
						$discount = $discount + ($MSRP[$p] - $total[$k]);
						$subtotal[$k] = $MSRP[$p];
						echo "The price is " . $total[$k] . " and the discount is " . $MSRP[$p];
					exit;
					}
					if(!strcmp($SKU[$k], "VERSA-DUAL") ) {
						//$response["test"] = "SKU is " . $SKU[$k] . " and order # is " . $order_number;
						//echo json_encode($response);
						//exit;	
						$SKU[$k] = 'ALCLR-VERSA-1';
					}
				}	
			}	
		}
	}
	*/
	//$response['SKUs'] = $SKUs;
	
	$response['SKUs'] = $SKU;
	$response['SUBTOTALs'] = $subtotal;
	$response['SHIPPING_AMOUNT'] = $shipping_total;
	
	//$discount = array_map('intval', $discount);
	$response['DISCOUNT'] = $discount;
	
	$response['TAXES'] = $cart_tax;
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
	echo "The price is " . $subtotal[0] . " and the discount is " . $discount . " and type is " . gettype($discount[0]);
	exit;
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>