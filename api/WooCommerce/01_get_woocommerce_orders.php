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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////// CALC ESTIMATED SHIP DATE CODE  ////////////////////////////////////////////////////////////////

									//////////////////////////////////////////////////////      HOLIDAYS       //////////////////////////////////////////////////
    // GET THE HOLIDAYS
	$query = "SELECT *, to_char(date, 'MM/dd/yyyy') as holiday_date FROM holiday_log WHERE active = TRUE ORDER BY date ASC";
    //$params2[":repair_form_id"] = $_REQUEST['id'];
    $stmt = pdo_query( $pdo, $query, null); 
	$holidays = pdo_fetch_all( $stmt );  
	$rows_in_result = pdo_rows_affected($stmt);
													////////////////////////////////////////////////////////////////////////////////////////////////////////
					//////////////////////////////////////     GET THE DAILY BUILD RATE INFORMATION     ///////////////////////////////////////////////
	$query2 = "SELECT * FROM daily_build_rate ";
    $stmt2 = pdo_query( $pdo, $query2, null); 
	$daily_build_rate= pdo_fetch_all( $stmt2 );  

	$daily_rate = $daily_build_rate[0]["daily_rate"];
	$fudge = $daily_build_rate[0]["fudge"];
	$shop_days = $daily_build_rate[0]["shop_days"];	 
	
	//$daily_rate = 5;
	//$fudge = 1;
	//$shop_days = 7;
	$daily_rate = $daily_rate - $fudge;
				//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////   
    
									///////////////////////////////////////////////////////  FUNCTIONS START    /////////////////////////////////////////////////		 
	function calc_estimate_ship_date($array, $date, $holidays, $shop_days, $pdo) {	
		$weekend = array('Sun', 'Sat');
		$nextDay = clone $date;
		$finalDay = clone $date;
		$work_days = 0;
		$days_to_final_date = 0;
		while ($work_days < $shop_days)
		{
   	 		$nextDay->modify('+1 day'); // Add 1 day
   	 		if($nextDay->format('D'))
   	 		if (in_array($nextDay->format('D'), $weekend) || in_array($nextDay->format('m-d'), $holidays)) {
	   	 		$response["test"] = "HERE"; 
	   	 		$days_to_final_date++;
	   	 	} else {		   	 
		   		$days_to_final_date++;
		   		$work_days++;
	   	 	}
		}
		$finalDay->modify('+' . $days_to_final_date .  ' day');
		$ship_day = $finalDay->format('Y-m-d');
		$imp_date = $date->format('Y-m-d');
		
		// IN THIS FILE WE HAVE TO MANUALLY SET THE ORDER STATUS ID EQUAL TO 1 WHICH IS THE START CART
		$query = "UPDATE import_orders SET fake_imp_date = :imp_date, estimated_ship_date = :estimated_ship_date, order_status_id = 1 WHERE id = :id";
		//$query = "UPDATE import_orders SET estimated_ship_date=:estimated_ship_date WHERE id = :id";
		$stmt = pdo_query( $pdo, $query, array(":imp_date"=>$imp_date, "estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
		//$stmt = pdo_query( $pdo, $query, array("estimated_ship_date"=>$ship_day, ":id"=>$array["id"])); 	
		return $array["id"];
		//return $finalDay;
	}
						///////////////////////////////////////////////////////  FUNCTIONS END    /////////////////////////////////////////////////
						
//print_r($woocommerce);
// DATE -> 0 
// ORDER ID -> 1
// PRODUCT -> 2
// QUANTITY -> 3
// MODEL -> 4
// ARTWORK-> 5
// COLOR-> 6
// RUSH PROCESS -> 7
// LEFT SHELL -> 8
// RIGHT SHELL -> 9
// LEFT FACEPLATE -> 10
// RIGHT FACEPLATE -> 11
// CABLE COLOR -> 12
// CLEAR CANAL -> 13
// LEFT ALCLAIR LOGO -> 14
// RIGHT ALCLAIR LOGO -> 15
// LEFT CUSTOM ART -> 16
// RIGHT CUSTOM ART -> 17
// LINK TO DESIGN IMAGE -> 18
// OPEN ORDER IN DESIGNER -> 19
// DESIGNED FOR -> 20
// MY IMPRESSIONS -> 21

// BILLING NAME -> 22  NEW -> 44
// SHIPPING NAME -> 23  NEW -> 45
// PRICE -> 24  NEW -> 46
// COUPON ->25   NEW -> 47
// DISCOUNT -> 26  NEW -> 48
// TOTAL -> 27  NEW -> 49
// ENTERED BY = INTEGER
// ACTIVE = TRUE
// ORDER STATUS ID = 99
// NUM EARPHONES PER ORDER -> 28  NEW -> 50

// PELICAN CASE NAME -> 29
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

/*
$params = [
			'before' => '2019-04-17T23:59:59',
			'after' => '2019-04-17T00:00:00',
			'per_page' => 100			
        ];
   */     
    $result = $woocommerce->get('orders', $params);
    //$result = $woocommerce->get('orders/12524');
    $order = [];
    $ind = 0;
  
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
			
			if(!strcmp($data["status"], "processing")  || !strcmp($data["status"], "completed") ) {
				//$order[$ind]["num_earphones_per_order"] = 0;
				//$order[$ind]["num_earphones_per_order"] = $order[$ind]["num_earphones_per_order"] + 1;
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
					}
					//echo "Key is " . $line_item[meta_data][$j]->key . " and Value is " . $line_item[meta_data][$j]->value . " <br/>";			
				} // CLOSES FOR LOOP - METADATA
				$ind++;
			} // CLOSES IF STATEMENT - STATUS
	    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
	  } // CLOSES IF STATEMENT - IS IT A UNIVERSAL EARPHONE - LINE 189
	} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
    } // END FOR LOOP THAT STEPS THROUGH EVERY ORDER


// REQUIRED 2 INCREMENTS BUT OF THE SAME ARRAY
$inc2 =  count($order);
// DETERMINE NUMBER OF EARPHONES PER ORDER
for ($x=0; $x <  count($order); $x++) { 
	$num_of_earphones_in_order = 0;  // START NUMBER OF EARPHONES PER ORDER AT ZERO
	for ($y=0; $y < $inc2; $y++) {	
		if($order[$x]["order_id"] == $order[$y]["order_id"] && strcmp( substr($order[$y]["product"], 0, 14), "Custom Hearing") ) {  // SEEEING IF THE ORDER NUMBER EXISTS
			$num_of_earphones_in_order = $num_of_earphones_in_order + 1;  // EACH ORDER NUMBER WILL EXIST AT LEAST ONCE
		}
		if($y == $inc2-1) {  // AT THE END OF THE ARRAY SET THE 28TH COLUMN TO THE NUMBER OF EARPHONES PER ORDER
			$order[$x]["num_earphones_per_order"] = $num_of_earphones_in_order;
		}
	}
}


	// Create new Spreadsheet object
	$spreadsheet = new Spreadsheet();
	//$objPHPExcel = new PHPExcel();
	
	// Set workbook properties
	$spreadsheet->getProperties()->setCreator('Tyler Folsom')
        ->setLastModifiedBy('Tyler Folsom')
        ->setTitle('Testing')
        ->setSubject('PhpSpreadsheet')
        ->setDescription('A Simple Excel Spreadsheet generated using PhpSpreadsheet.')
        ->setKeywords('Microsoft office 2013 php PhpSpreadsheet')
        ->setCategory('Test file');
 
	// Set worksheet title
	$spreadsheet->getActiveSheet()->setTitle('OTIS');
	//$objPHPExcel->setActiveSheetIndex(0)->setTitle("TESTING");
	
	//Set active sheet index to the first sheet, 
	//and add some data
	$spreadsheet->setActiveSheetIndex(0)
        	->setCellValue("A1", "Date") 
			->setCellValue("B1", "Order ID") 
			->setCellValue("C1",  "Product") 
			->setCellValue("D1", "QTY") 
			->setCellValue("E1", "Model") 
			->setCellValue("F1", "Artwork")
			->setCellValue("G1", "Color")
			->setCellValue("H1", "Rush Process")
			->setCellValue("I1", "Left Shell")
			->setCellValue("J1", "Right Shell")
			->setCellValue("K1", "Left Faceplate")
			->setCellValue("L1", "Right Faceplate")
			->setCellValue("M1", "Cable Color")
			->setCellValue("N1", "Clear Canal")
			->setCellValue("O1", "Left Alclair Logo")
			->setCellValue("P1", "Right Alclair Logo")
			->setCellValue("Q1", "Left Custom Art")
			->setCellValue("R1", "Right Custom Art")
			->setCellValue("S1", "Link to Design Image")
			->setCellValue("T1", "Open Order in Designer")
			->setCellValue("U1", "Designed For")
			->setCellValue("V1", "My Impressions")
			->setCellValue("W1", "64in. Cable")
			->setCellValue("X1", "Mic Cable")
			->setCellValue("Y1", "Forza Audio")
			->setCellValue("Z1", "Cleaning Kit")
			->setCellValue("AA1", "Cleaning Tools")
			->setCellValue("AB1", "Dotz Clip")
			->setCellValue("AC1", "Pelican Case")
			->setCellValue("AD1", "Pelican Case Name")
			->setCellValue("AE1", "Soft Case")
			->setCellValue("AF1", "Hearing Protection")
			->setCellValue("AG1", "Hearing Protection Color")
			->setCellValue("AH1", "Cable Upgrade")
			->setCellValue("AI1", "Cable Upgrade Type")
			->setCellValue("AJ1", "Cable Addon")
			->setCellValue("AK1", "Cable Addon Type")
			->setCellValue("AL1", "Musician's Plugs 9dB")
			->setCellValue("AM1", "Musician's Plugs 15dB")
			->setCellValue("AN1", "Musician's Plugs 25dB")
			->setCellValue("AO1", "Musician's Plugs")
			->setCellValue("AP1", "Wood Box")
			->setCellValue("AQ1", "Standard Cable")
			->setCellValue("AR1", "Long Cable")
			->setCellValue("AS1", "Billing Name")
			->setCellValue("AT1", "Shipping Name")
			->setCellValue("AU1", "Price")
			->setCellValue("AV1", "Coupon")
			->setCellValue("AW1", "Discount")
			->setCellValue("AX1", "Total");
			
		$row = 2;
		for($k = 0; $k < count($order); $k++) {
			$spreadsheet->setActiveSheetIndex(0)
        	->setCellValue("A".$row, $order[$k]["date"]) 
			->setCellValue("B".$row, $order[$k]["order_id"]) 
			->setCellValue("C".$row,  $order[$k]["product"]) 
			->setCellValue("D".$row, $order[$k]["quantity"]) 
			->setCellValue("E".$row, $order[$k]["model"]) 
			->setCellValue("F".$row, $order[$k]["artwork"])
			->setCellValue("G".$row, $order[$k]["color"])
			->setCellValue("H".$row, $order[$k]["rush_process"])
			->setCellValue("I".$row, $order[$k]["left_shell"])
			->setCellValue("J".$row, $order[$k]["right_shell"])
			->setCellValue("K".$row, $order[$k]["left_faceplate"])
			->setCellValue("L".$row, $order[$k]["right_faceplate"])
			->setCellValue("M".$row, $order[$k]["cable_color"])
			->setCellValue("N".$row, $order[$k]["clear_canal"])
			->setCellValue("O".$row, $order[$k]["left_alclair_logo"])
			->setCellValue("P".$row, $order[$k]["right_alclair_logo"])
			->setCellValue("Q".$row, $order[$k]["left_custom_art"])
			->setCellValue("R".$row, $order[$k]["right_custom_art"])
			->setCellValue("S".$row, $order[$k]["link_to_design_image"])
			->setCellValue("T".$row, $order[$k]["open_order_in_designer"])
			->setCellValue("U".$row, $order[$k]["designed_for"])
			->setCellValue("V".$row, $order[$k]["my_impressions"])
			->setCellValue("W".$row, $order[$k]["64in_cable"])
			->setCellValue("X".$row, $order[$k]["mic_cable"])
			->setCellValue("Y".$row, $order[$k]["forza_audio"])
			->setCellValue("Z".$row, $order[$k]["cleaning_kit"])
			->setCellValue("AA".$row, $order[$k]["cleaning_tools"])
			->setCellValue("AB".$row, $order[$k]["dotz_clip"])
			->setCellValue("AC".$row, $order[$k]["pelican_case"])
			->setCellValue("AD".$row, $order[$k]["pelican_case_name"])
			->setCellValue("AE".$row, $order[$k]["soft_case"])
			->setCellValue("AF".$row, $order[$k]["hearing_protection"])
			->setCellValue("AG".$row, $order[$k]["hearing_protection_color"])
			->setCellValue("AH".$row, $order[$k]["cable_upgrade"])
			->setCellValue("AI".$row, $order[$k]["cable_upgrade_type"])
			->setCellValue("AJ".$row, $order[$k]["cable_addon"])
			->setCellValue("AK".$row, $order[$k]["cable_addon_type"])
			->setCellValue("AL".$row, $order[$k]["musicians_plugs_9db"])
			->setCellValue("AM".$row, $order[$k]["musicians_plugs_5db"])
			->setCellValue("AN".$row, $order[$k]["musicians_plugs_25db"])
			->setCellValue("AO".$row, $order[$k]["musicians_plugs"])
			->setCellValue("AP".$row, $order[$k]["wood_box"])
			->setCellValue("AQ".$row, $order[$k]["standard_cable"])
			->setCellValue("AR".$row, $order[$k]["long_cable"])
			->setCellValue("AS".$row, $order[$k]["billing_name"])
			->setCellValue("AT".$row, $order[$k]["shipping_name"])
			->setCellValue("AU".$row, $order[$k]["price"])
			->setCellValue("AV".$row, $order[$k]["coupon"])
			->setCellValue("AW".$row, $order[$k]["discount"])
			->setCellValue("AX".$row, $order[$k]["total"]);
			
			$row++;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////     TAKEN FROM THE ORIGINAL IMPORT ROUTINE    ///////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////    STARTS HERE - CREATES ORDER THEN QC FORM      ////////////////////////////////////////////////////////////////////////////////////
			// POPULATE IMPORT ORDERS TABLE IN THE DATABASE
			$entered_by = 1;
			$stmt = pdo_query( $pdo, 
					   "INSERT INTO import_orders (
date, order_id, product, quantity, model, artwork, color, rush_process, left_shell, right_shell, left_faceplate, right_faceplate, cable_color, clear_canal, left_alclair_logo, right_alclair_logo, left_custom_art, right_custom_art, link_to_design_image, open_order_in_designer, designed_for, my_impressions, billing_name, shipping_name, price, coupon, discount, total, entered_by, active, order_status_id, num_earphones_per_order, hearing_protection, hearing_protection_color, left_tip, right_tip, pelican_case_name, notes)
VALUES (
:date, :order_id, :product, :quantity, :model, :artwork, :color, :rush_process, :left_shell, :right_shell, :left_faceplate, :right_faceplate, :cable_color, :clear_canal, :left_alclair_logo, :right_alclair_logo, :left_custom_art, :right_custom_art, :link_to_design_image, :open_order_in_designer, :designed_for, :my_impressions, :billing_name, :shipping_name, :price, :coupon, :discount, :total, :entered_by, :active, :order_status_id, :num_earphones_per_order, :hearing_protection, :hearing_protection_color, :left_tip, :right_tip, :pelican_case_name, :notes) RETURNING id",
array(':date'=>$order[$k]['date'], ':order_id'=>$order[$k]['order_id'],':product'=>$order[$k]['product'], ':quantity'=>$order[$k]['quantity'], ':model'=>$order[$k]['model'], ':artwork'=>$order[$k]['artwork'], ':color'=>$order[$k]['color'], ':rush_process'=>$order[$k]['rush_process'], ':left_shell'=>$order[$k]['left_shell'], ':right_shell'=>$order[$k]['right_shell'], ':left_faceplate'=>$order[$k]['left_faceplate'], ':right_faceplate'=>$order[$k]['right_faceplate'], ':cable_color'=>$order[$k]['cable_color'], ':clear_canal'=>$order[$k]['clear_canal'], ':left_alclair_logo'=>$order[$k]['left_alclair_logo'], ':right_alclair_logo'=>$order[$k]['right_alclair_logo'], ':left_custom_art'=>$order[$k]['left_custom_art'], ':right_custom_art'=>$order[$k]['right_custom_art'], ':link_to_design_image'=>$order[$k]['link_to_design_image'], ':open_order_in_designer'=>$order[$k]['open_order_in_designer'], 
':designed_for' =>$order[$k]['designed_for'], 
':my_impressions'=>$order[$k]['my_impressions'], 
':billing_name'=>$order[$k]['billing_name'], 
':shipping_name'=>$order[$k]['shipping_name'], 
':price'=>$order[$k]['price'], 
':coupon'=>$order[$k]['coupon'], 
':discount'=>$order[$k]['discount'], 
':total'=>$order[$k]['total'], 
':entered_by'=>1, //$_SESSION['UserId',
':active'=>TRUE,
':order_status_id'=>99, 
':num_earphones_per_order'=>$order[$k]['num_earphones_per_order'],
':hearing_protection'=>$order[$k]['hearing_protection'],
':hearing_protection_color'=>$order[$k]['hearing_protection_color'],
':left_tip'=>$order[$k]['left_tip'],
':right_tip'=>$order[$k]['right_tip'],
':pelican_case_name'=>$order[$k]['pelican_case_name'],
':notes'=>$order[$k]['notes']) //=>$order[28])
);

	$id_after_import = pdo_fetch_all( $stmt );
	$id_of_order = $id_after_import[0]["id"];

	$stmt = pdo_query($pdo, "SELECT * FROM monitors WHERE name = :monitor_name", array('monitor_name'=>$order[$k]['model']));
	$result = pdo_fetch_all( $stmt );

	$qc_form = array();
	$qc_form['customer_name'] = $order[$k]['designed_for'];  // DESIGNED FOR
	$qc_form['order_id'] = $order[$k]['order_id'];  // ORDER ID
	$qc_form['monitor_id'] = $result[0]["id"];
	$qc_form['build_type_id'] = 1; // New Build
					
	$qc_form['notes'] = "Entry from import " . $k;
	$qc_form['notes'] = "";

	$stmt = pdo_query( $pdo, 
					   "INSERT INTO qc_form (customer_name, order_id, monitor_id, build_type_id, notes, active, qc_date, pass_or_fail, id_of_order)
					   	 VALUES (:customer_name, :order_id, :monitor_id, :build_type_id, :notes, :active, now(), :pass_or_fail, :id_of_order) RETURNING id",
array(':customer_name'=>$qc_form['customer_name'], ':order_id'=>$qc_form['order_id'],':monitor_id'=>$qc_form['monitor_id'], ':build_type_id'=>$qc_form['build_type_id'], ':notes'=>$qc_form['notes'], ":active"=>TRUE, ":pass_or_fail"=>"IMPORTED", ":id_of_order"=>$id_of_order)
);		

	$id_of_qc_form = pdo_fetch_all( $stmt );

	$stmt = pdo_query( $pdo, "UPDATE import_orders SET id_of_qc_form = :id_of_qc_form WHERE id = :id_of_order", array(":id_of_qc_form"=>$id_of_qc_form[0]["id"], ":id_of_order"=>$id_of_order));

	// LOOK INSIDE BATCHES FOR PAID ORDERS WITH IMPRESSIONS
	// GREATER THAN BATCH TYPE ID 1 IS ALL BATCHES THAT ARE NOT FROM TRADE SHOWS
	$query_batches = "SELECT * FROM batches AS t1 
					  LEFT JOIN batch_item_log AS t2 ON t1.id = t2.batch_id
					  WHERE t1.received_date IS NULL AND t1.batch_type_id > 1 AND t1.active = TRUE AND t2.active = TRUE AND t2.paid = TRUE";
	$stmt_batches = pdo_query( $pdo, $query_batches, null);
	$result_batches = pdo_fetch_all( $stmt_batches );
	$row_count_batches = pdo_rows_affected( $stmt_batches );
	
	for ($j=0; $j < $row_count_batches; $j++) {
		//$batch_order_num = preg_replace("/[^a-zA-Z]/", "", $result_batches[$j]["order_number"]);
		//$woo_order_num = preg_replace("/[^a-zA-Z]/", "", $order[1]);
		$batch_order_num = preg_replace("/[^0-9]/", "",  $result_batches[$j]["order_number"] );
		$woo_order_num = preg_replace("/[^0-9]/", "",  $order[$k]['order_id'] );
				
		//if(!strcmp($order[1], $clear) {
		// MOVE TO START CART	
	if(!strcmp($woo_order_num, $batch_order_num)) {	

						//////////////////////////////////////////////   GET ORDERS IN START CART    ////////////////////////////////////////////////////////////   
	$weekend = array('Sun', 'Sat');
	
	$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NOT NULL ORDER BY fake_imp_date DESC LIMIT :daily_rate";
	$stmt3 = pdo_query( $pdo, $query3, array(":daily_rate"=>$daily_rate)); 
	$find_last_fake_imp_date= pdo_fetch_all( $stmt3 ); 
	$count = pdo_rows_affected($stmt3);
		
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// IF ABOVE QUERY RETURNS ZERO MEANS EVERY ORDER IN START CART IS NULL - THE CODE HAS BEEN NEVER RUN BEFORE   
	// IF THIS ALGORITHM HAS NEVER BEEN RUN BEFORE - THE SYSTEM AUTO-POPULATES ALL OF THE ORDERS IN THE START CART	 
	// START WITH THE IF STATEMENT
	// PULLS ALL OF START CART WHICH IS NULL FOR FAKE IMPRESSION DATE
	if($count == 0) {
		$num = 1;
		$query3 = "SELECT * FROM import_orders WHERE active = TRUE AND order_status_id = 1 AND fake_imp_date IS NULL ORDER BY received_date ASC";
		$stmt3 = pdo_query( $pdo, $query3, null); 
		$populate_new= pdo_fetch_all( $stmt3 ); 
		$count = pdo_rows_affected($stmt3);
		$date = new DateTime(); // TODAY'S DATE
		$date->modify('+1 day'); // NEEDS TO START WITH TOMORROW
		while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
			$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
		}
		
		for ($i = 0; $i < $count; $i++) {
			if($num > $daily_rate) {
				$date->modify('+1 day'); 
				while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
					$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
				}
				$num = 1;
			}

			$response["test"] = calc_estimate_ship_date($populate_new[$i], $date, $holidays, $shop_days, $pdo);
			$num++;
		}
	} else{ // START HAS NO NULL ORDERS IN START CART - THIS CODE HAS RUN BEFORE
		$query4 = "SELECT * FROM import_orders WHERE active = TRUE AND id = :id";
		$stmt4 = pdo_query( $pdo, $query4, array("id"=>$id_of_order)); 
		$order_batch= pdo_fetch_all( $stmt4 ); 
		
		if($count == $daily_rate && ($find_last_fake_imp_date[0]["fake_imp_date"] == $find_last_fake_imp_date[$daily_rate-1]["fake_imp_date"]) ) {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
			$date->modify('+1 day');
			while (in_array($date->format('D'), $weekend) || in_array($date->format('m-d'), $holidays)) {
				$date->modify('+1 day'); // ADDING A DAY UNTIL NOT A WEEKEND OR A HOLIDAY	
			}
		} else {
			$date = new DateTime($find_last_fake_imp_date[0]["fake_imp_date"]); 
		}
		$response["test"] = $order[0]["id"];
		//$response["test"] = "fasdfasdfadsf";
		//echo json_encode($response);
		//exit;
		$response["test"] = calc_estimate_ship_date($order_batch[0], $date, $holidays, $shop_days, $pdo);
	}
/////////////////////////////////////////////////////// END CALC ESTIMATED SHIP DATE ////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	} // CLOSE THE IF STATEMENT THAT RUNS THE START CART CODE
	
} // CLOSE FOR LOOP THAT CYCLES THROUGH BATCHES

////////////////////////////////////////////////    ENDS HERE - CREATES ORDER THEN QC FORM      ////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////     TAKEN FROM THE ORIGINAL IMPORT ROUTINE    ///////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


		} // CLOSE FOR LOOP FOR EACH ORDER
			
			$filename = "Testing-Import2 -".date("m-d-Y").".xlsx";
			//new code:
			//$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			//$writer->save("../../data/export/woocommerce/$filename");
			//$writer->save("/var/www/html/otis/data/export/woocommerce/$filename");
			
			$writer_dev = IOFactory::createWriter($spreadsheet, 'Xlsx');
			//$writer_dev->save("/var/www/html/otisdev/data/export/woocommerce/$filename");
			$writer_dev->save($rootScope["RootPath"]."/data/export/woocommerce/$filename");

			$response['code'] = 'success';
			$response['data'] = $filename;
			echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>