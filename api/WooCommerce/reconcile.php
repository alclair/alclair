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
//$black_cable_accessory = 0;
//$clear_cable_accessory = 0;

//$date = strtotime('12/01/2019 00:00:00');
$date = strtotime($_REQUEST["StartDate"]);
$starting_date = $date;
// $diff-format("%a") RETURNS THE NUMBERS OF DAYS BETWEEN START AND STOP DATES

$order = [];
$ind = 0;
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


$result = $woocommerce->get('orders', $params);

  
for($i = 0; $i < count($result); $i++) {
    		//$holder = json_decode(json_encode($result[$ind]), true);    
	$data = get_object_vars($result[$i]);  // STORE THE DATA	
    for($k = 0; $k < count($data[line_items]); $k++) {
	    //echo "Count is " . count($order) . "<br>";
	    	
	    //if( get_object_vars($data[line_items][$k])  ) {
			//$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
	       //$line_item = get_object_vars($data[line_items][0]); // PRODUCT -> 2
			//$is_earphone = get_object_vars($line_item[meta_data][0]); // MODEL -> 4
			//$coupon_lines = get_object_vars($data[coupon_lines][0]); 
			$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
			$is_earphone = get_object_vars($line_item[meta_data][$k]); // MODEL -> 4
			$coupon_lines = get_object_vars($data[coupon_lines][$k]); 
	
			$model_name = $is_earphone["value"];
			$full_product_name = $line_item["name"];
			$price = $line_item["subtotal"];
			$total = $data["total"];
			$discount = $data["discount_total"];
			$coupon = $coupon_lines["code"];
		
		//echo '<p>TEST 2 IS  ' .  $model_name . " and " . $ind . "<br/>";
                
		// IF THE WORD "DRIVER" OR "POS" IS INSIDE THE FULL PRODUCT NAME STORE INFO FOR IMPORT
		
	if( !stristr($full_product_name, "UV") ) { // IF UV EXISTS DO NOT STORE THE EARPHONE
		//if( stristr($full_product_name, "Driver") !== false || stristr($full_product_name, "POS") !== false || stristr($full_product_name, "Custom Hearing Protection") !== false) { 
		if( stristr($full_product_name, "Driver") || stristr($full_product_name, "POS") || stristr($full_product_name, "Custom Hearing Protection") ) { 
			
			if(!strcmp($data["status"], "completed") ) {
			//if(!strcmp($data["status"], "processing")  || !strcmp($data["status"], "completed") ) {
				//$order[$ind]["num_earphones_per_order"] = 0;
				//$order[$ind]["num_earphones_per_order"] = $order[$ind]["num_earphones_per_order"] + 1;
				$order[$ind]["index"] = $ind;
				$order[$ind]["num_earphones_per_order"] = 1;
				$order[$ind]["status"] = $data["status"];
				$order[$ind]["date"] = date_format(date_create($data["date_created"]), "m/d/y"); // DATE -> 0
				$order[$ind]["order_id"] = $data["id"]; // ORDER ID -> 1
				$order[$ind]["product"] = $full_product_name; // PRODUCT -> 2 
				$order[$ind]["quantity"] = 1; // QUANTITY -> 3
				$order[$ind]["price"] = $price;
				$order[$ind]["total"] = $total;
				$order[$ind]["discount"] = $discount;
				$order[$ind]["coupon"] = $coupon;
				$order[$ind]["hearing_protection"] = NULL;
				$order[$ind]["hearing_protection_color"] = NULL;
				
				$order[$ind]["nashville_order"] = NULL;
				if(!strcmp( substr($data["number"], 0, 4), "AATN") ) {
					$order[$ind]["nashville_order"] = TRUE; // THIS WILL MARK THE ORDER AS FROM JONNY IN NASHVILLE
				}
				
				// CHECK TO SEE IF ORDER IS ONLY FOR CUSTO HEARING PROTECTION
				if(!strcmp( substr($full_product_name, 0, 25), "Custom Hearing Protection") ) {
					$order[$ind]["hearing_protection"] = TRUE;
					//$order[$ind]['hearing_protection_color'] =  substr($full_product_name, 28, 88);
					$order[$ind]['hearing_protection_color'] = $line_item[meta_data][0]->value;
				}
										
				/*if(!strcmp($full_product_name, "ELECTRO 6 DRIVER ELECTROSTATIC HYBRID") ) {
					$order[$ind]["model"] = "Electro";  // MODEL -> 4 	
 				} else {
	 				$order[$ind]["model"] = $model_name; // MODEL -> 4 	
 				}*/
 				
				$order[$ind]["billing_name"] = $data[billing]->first_name . " " . $data[billing]->last_name;
				$order[$ind]["shipping_name"] = $data[shipping]->first_name . " " . $data[shipping]->last_name;
				
				for($j = 0; $j < count($line_item[meta_data]); $j++) {
					if(!strcmp($line_item[meta_data][$j]->key, "Model") ) {
						$order[$ind]["model"] = $line_item[meta_data][$j]->value;
/*
						if(!strcmp($full_product_name, "ELECTRO 6 DRIVER ELECTROSTATIC HYBRID") ) {
							$order[$ind]["model"] = "Electro";  // MODEL -> 4 	
						} elseif(!strcmp($full_product_name, "ELECTRO SIX DRIVER ELECTROSTATIC HYBRID") ) {
							$order[$ind]["model"] = "Electro";  // MODEL -> 4 		
						} elseif(!strcmp($full_product_name, "STUDIO3 TRIPLE DRIVER") ) {
							$order[$ind]["model"] = "Studio3";  // MODEL -> 4 		
 						} elseif(!strcmp($full_product_name, "REVX TEN DRIVER") ) {
	 						$order[$ind]["model"] = "Rev X";  // MODEL -> 4 	
	 					} elseif(!strcmp($full_product_name, "Alclair UV2 Dual Driver Universal POS") ) {
	 						$order[$ind]["model"] = "UV2";  // MODEL -> 4 	
	 					} elseif(!strcmp($full_product_name, "Alclair UV3 Triple Driver Universal POS") ) {
	 						$order[$ind]["model"] = "UV3";  // MODEL -> 4 	
	 					} else {
 							//$order[$ind]["model"] = $model_name; // MODEL -> 4 	
 						}
					} elseif(!strcmp( substr($line_item[meta_data][$j]->key, 0, 7), "Artwork") ) {
						$order[$ind]["artwork"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp(  substr($line_item[meta_data][$j]->key, 0, 5), "Color") ) {
						$order[$ind]["color"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp( substr($line_item[meta_data][$j]->key, 0, 15), "Rush Processing") ) {
						$order[$ind]["rush_process"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Left Shell") ) {
						$order[$ind]["left_shell"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Right Shell") ) {
						$order[$ind]["right_shell"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Left Faceplate") ) {
						$order[$ind]["left_faceplate"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Right Faceplate") ) {
						$order[$ind]["right_faceplate"] = $line_item[meta_data][$j]->value;		
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Cable Color") ) {
						$order[$ind]["cable_color"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Clear Canal Tips") ) {
						$order[$ind]["clear_canal"] = $line_item[meta_data][$j]->value;
						if(strcmp($line_item[meta_data][$j]->value, "Yes")) {
						 	$left_tip = null;
						 	$right_tip = null;
						} else {
							$left_tip = "Clear";
							$right_tip = "Clear";
						};
						$order[$ind]["left_tip"] = $left_tip;
						$order[$ind]["right_tip"] = $right_tip;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Left Alclair Logo") ) {
						$order[$ind]["left_alclair_logo"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Right Alclair Logo") ) {
						$order[$ind]["right_alclair_logo"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Left Custom Art") ) {
						$order[$ind]["left_custom_art"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Right Custom Art") ) {
						$order[$ind]["right_custom_art"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Link to Design Image") ) {
						$order[$ind]["link_to_design_image"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Open Order in Designer") ) {
						$order[$ind]["open_order_in_designer"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Designed For") ) {
						$order[$ind]["designed_for"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "My Impressions") ) {
						$order[$ind]["my_impressions"] = $line_item[meta_data][$j]->value;
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
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Pelican Case Name") ) {
						$order[$ind]["pelican_case_name"] = $line_item[meta_data][$j]->value;
						$value = $line_item[meta_data][$j]->value;
						// IF STRING LENGTH IS GREATER THAN 1 AND DOES NOT EQUAL BLANK SPACES
						if(strlen($value) > 1 && (strcmp($value, "  ") || strcmp($value, "   ") || strcmp($value, "    ") || strcmp($value, "     ") )  ) {
	    						$notes = 'Pelican case name "' . $value . '"';	   
    						} else {
							$notes = "";
    						}
						$order[$ind]["notes"] = $notes;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Soft Case") ) {
						$order[$ind]["soft_case"] = $line_item[meta_data][$j]->value;	
					//} //elseif(!strcmp( substr($full_product_name, 0, 25), "Custom Hearing Protection") ) {
						//elseif(!strcmp( substr($line_item[meta_data][$j]->key, 0, 18), "Hearing Protection") ) {
						
						//$order[$ind]["hearing_protection"] = TRUE;	
						//$order[$ind]["notes"] = "J is " . $j;	
						
						//$order[$ind]['hearing_protection_color'] =  substr($full_product_name, 28, 88);
						//$order[$ind]['hearing_protection_color'] =  substr($line_item[meta_data][$j]->key, 28, 88);
					} elseif(!strcmp( substr($line_item[meta_data][$j]->key, 0, 24), "Hearing Protection Color") ) {
					//elseif(!strcmp($line_item[meta_data][$j]->key, "Hearing Protection Color") ) {
						$order[$ind]["hearing_protection"] = TRUE;	

						$order[$ind]["hearing_protection_color"] = $line_item[meta_data][$j]->value;	
						$order[$ind]["notes"] = "Made it";	
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
*/
					}

					//echo "Key is " . $line_item[meta_data][$j]->key . " and Value is " . $line_item[meta_data][$j]->value . " <br/>";			
				} // CLOSES FOR LOOP - METADATA
				$ind++;
			} // CLOSES IF STATEMENT - STATUS
	    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
	  } // CLOSES IF STATEMENT - IS IT A UNIVERSAL EARPHONE - LINE 189
	} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
} // END FOR LOOP THAT STEPS THROUGH EVERY ORDER
} // END THE FOR LOOP
	$response["num_shipped"] = $ind;
//exit;
    
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