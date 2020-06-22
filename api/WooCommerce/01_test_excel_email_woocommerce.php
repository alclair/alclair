<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
include_once "../../includes/phpmailer/class.phpmailer.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

//require '/var/www/html/otisdev/vendor/autoload.php';
require $rootScope["RootPath"] . '/vendor/autoload.php';
//require '/var/www/html/otis/vendor/autoload.php';

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
			'before' => '2019-05-01T23:59:59',
			'after' => '2019-05-01T00:00:00',
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
                
		// IF THE WORD "DRIVER" OR "POS" IS INSIDE THE FULL PRODUCT NAME STORE INFO FOR IMPOT
		//if(is_string($model) == 1 && (stristr($full_product_name, "Driver") !== false ) || stristr($full_product_name, "POS") !== false ))) { 
		if( stristr($full_product_name, "Driver") !== false || stristr($full_product_name, "POS") !== false ) { 
			
			if(!strcmp($data["status"], "processing")  || !strcmp($data["status"], "completed") ) {
				$order[$ind]["num_earphones_per_order"] = 0;
				$order[$ind]["num_earphones_per_order"] = $order[$ind]["num_earphones_per_order"] + 1;
				$order[$ind]["status"] = $data["status"];
				$order[$ind]["date"] = date_format(date_create($data["date_created"]), "m/d/y"); // DATE -> 0
				$order[$ind]["order_id"] = $data["id"]; // ORDER ID -> 1
				$order[$ind]["product"] = $full_product_name; // PRODUCT -> 2 
				$order[$ind]["quantity"] = 1; // QUANTITY -> 3
				$order[$ind]["price"] = $price;
				$order[$ind]["total"] = $total;
				$order[$ind]["discount"] = $discount;
				$order[$ind]["coupon"] = $coupon;
										
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
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Hearing Protection") ) {
						$order[$ind]["hearing_protection"] = $line_item[meta_data][$j]->value;	
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Hearing Protection Color") ) {
						$order[$ind]["hearing_protection_color"] = $line_item[meta_data][$j]->value;	
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
	} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
    } // END FOR LOOP THAT STEPS THROUGH EVERY ORDER
	
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

		} // CLOSE FOR LOOP FOR EACH ORDER
			
			$filename = "Testing-Import-Cron -".date("m-d-Y").".xlsx";
			//new code:
			//$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
			//$writer->save("../../data/export/woocommerce/$filename");
			//$writer->save("/var/www/html/otis/data/export/woocommerce/$filename");
			
			$writer_dev = IOFactory::createWriter($spreadsheet, 'Xlsx');
			//$writer_dev->save("/var/www/html/otisdev/data/export/woocommerce/$filename");
			$writer_dev->save($rootScope["RootPath"]."/data/export/woocommerce/$filename");
			
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////  E-MAIL CODE ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//$json=file_get_contents($url);
	//$list=json_decode($json,true);
	//var_dump($list);
	//$file_lng=$rootScope["RootPath"]."data/export/woocommerce/".$list["data"];
	$file_lng=$rootScope["RootPath"]."data/export/woocommerce/".$filename;
	//echo "File is " . "/var/www/html/otisdev/data/export/woocommerce/$filename";
    
//for($i = 0; $i < count($order); $i++) {
			if(file_exists($file_lng)) {
				$mail3= new PHPMailer();
				$mail3->IsSendmail(); // telling the class to use IsSendmail
				$mail3->Host       = "localhost"; // SMTP server
				$mail3->SMTPAuth   = false;                  // disable SMTP authentication  
				//$mail3->SetFrom($rootScope["SupportEmail"], $rootScope["SupportName"]);
				$mail3->SetFrom("tyler@alclair.com", "Import Time!");
				//$mail3->AddReplyTo($rootScope["SupportEmail"], $rootScope["SupportName"]);
				$mail3->AddReplyTo("tyler@alclair.com", "The Admin");
   
				$mail3->AddAddress("tyler@alclair.com");
				$mail3->AddAddress("scott@alclair.com");

				$mail3->Subject    = "Orders Imported";
				$body3="<p>Here are the orders that were imported today from yesterday.</p>";
				$mail3->MsgHTML($body3);

				$mail3->AddAttachment($file_lng, "Import File - ".date("m-d-Y").".xlsx");
				//$mail3->AddAttachment($json, "Testing-".date("m-d-Y").".xlsx");

				//echo json_encode($response);

				if(!$mail3->Send()) {
					$error="Error: Alclair  Excel document";
					file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
				} 
			} // CLOSE IF STATEMENT  -> if(file_exists($file_lng)&&!empty($emails))
////////////////////////////////////////////////////////  END E-MAIL CODE ////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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