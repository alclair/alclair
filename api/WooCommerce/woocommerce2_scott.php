<?php
include_once "../../config.inc.php";
include_once "../../config.dev.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
include_once "../../includes/phpmailer/class.phpmailer.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
 
//require_once 'vendor/autoload.php';
//require __DIR__ . '/vendor/autoload.php';
//require '/var/www/html/otisdev/vendor/autoload.php';
require $rootScope["RootPath"]."vendor/autoload.php";


$woocommerce = new Client(
    'https://alclair.com',
    'ck_acc872e19a1908cd5abadfd29a84e5edf8d34469',
    'cs_87fe15086357b7e90a8d2457552ddb957ba939fb',
    [
        'version' => 'wc/v3', 
    ]
);

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
try 
{
	$params = [
			//'after' => '2019-03-16T00:00:00',
			'per_page' => 100
			//'before' => '2019-03-16T23:59:59'
        ];
    $result = $woocommerce->get('orders', $params);
    //$result = $woocommerce->get('orders/12524');
    $order = [];
    $ind = 0;
  
    for($i = 0; $i < count($result); $i++) {
    		//$holder = json_decode(json_encode($result[$ind]), true);    
		$data = get_object_vars($result[$ind]);  // STORE THE DATA
                
        $line_item = get_object_vars($data[line_items][0]); // PRODUCT -> 2
		$is_earphone = get_object_vars($line_item[meta_data][0]); // MODEL -> 4
		$model_name = $is_earphone["value"];
		$full_product_name = $line_item["name"];
		//echo '<p>TEST 2 IS  ' .  $model_name . " and " . $ind . "<br/>";
                
		// IF THE WORD "DRIVER" OR "POS" IS INSIDE THE FULL PRODUCT NAME STORE INFO FOR IMPOT
//if(is_string($model) == 1 && (stristr($full_product_name, "Driver") !== false ) || stristr($full_product_name, "POS") !== false ))) { 
		if( stristr($full_product_name, "Driver") !== false || stristr($full_product_name, "POS") !== false ) { 
			$order[$ind]["status"] = $data["status"];
			if(!strcmp($order[$ind]["status"], "processing") ) {
				$order[$ind]["date"] = date_format(date_create($data["date_created"]), "m/d/y"); // DATE -> 0
				$order[$ind]["order_id"] = $data["id"]; // ORDER ID -> 1
				$order[$ind]["product"] = $full_product_name; // PRODUCT -> 2 
				$order[$ind]["quantity"] = 1; // QUANTITY -> 3
				
				if(!strcmp($full_product_name, "ELECTRO 6 DRIVER ELECTROSTATIC HYBRID") ) {
					$order[$ind]["model"] = "Electro";  // MODEL -> 4 	
 				} else {
	 				$order[$ind]["model"] = $model_name; // MODEL -> 4 	
 				}
				
				$order[$ind]["billing_name"] = $data[billing]->first_name . " " . $data[billing]->last_name;
				$order[$ind]["shipping_name"] = $data[shipping]->first_name . " " . $data[shipping]->last_name;
				//echo '<p>Last Name is ' . $arr[billing]->last_name;
						
				for($j = 0; $j < count($line_item[meta_data]); $j++) {
					if(!strcmp( substr($line_item[meta_data][$j]->key, 0, 7), "Artwork") ) {
						$order[$ind]["artwork"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Color") ) {
						$order[$ind]["color"] = $line_item[meta_data][$j]->value;
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Rush Process") ) {
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
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Clear Canal") ) {
						$order[$ind]["clear_canal"] = $line_item[meta_data][$j]->value;
						if(strcmp($line_item[meta_data][$j]->value, "Yes")) {
						 	$left_tip = null;
						 	$right_tip = null;
						} else {
							$left_tip = "Clear";
							$right_tip = "Clear";
						}
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
					} elseif(!strcmp($line_item[meta_data][$j]->key, "Designed for") ) {
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
			} // CLOSES IF STATEMENT - STATUS
	    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
		$ind++;
    }
   
	//$url=$rootScope["RootUrl"]."/api/WooCommerce/excel_woocommerce.php";
	$url=$rootScope["RootUrl"]."/api/WooCommerce/excel_woocommerce.php";
	//echo $rootScope["RootUrl"]."/api/WooCommerce/excel_woocommerce.php";
	//echo $filename . "<br/>";
			
	$json=file_get_contents($url);
	$list=json_decode($json,true);
	var_dump($list);
	//$file_lng="/var/www/html/otisdev/data/export/woocommerce/".$list["data"];
	$file_lng=$rootScope["RootPath"]."data/export/woocommerce/".$list["data"];
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

				$mail3->Subject    = "Testing WooCommerce 01";
				$body3="<p>The attached file is for testing WooCommerce API.</p>";
				$mail3->MsgHTML($body3);

				$mail3->AddAttachment($file_lng, "Testing-".date("m-d-Y").".xlsx");
				//$mail3->AddAttachment($json, "Testing-".date("m-d-Y").".xlsx");

				//echo json_encode($response);

				if(!$mail3->Send()) {
					$error="Error: Alclair  Excel document";
					file_put_contents($rootScope["RootPath"]."data/daily-log.txt",$error,FILE_APPEND);		
				} 
			} // CLOSE IF STATEMENT  -> if(file_exists($file_lng)&&!empty($emails))
			
//}
    
	$arr = get_object_vars($result[25]); //28
	//echo $order[25]["billing_name"];
	//echo '<pre><code>' . print_r($arr, true) . '</code><pre>';
	//echo $arr;
    //echo '<p>Last Name is' . $arr[billing]->last_name;

} catch (HttpClientException $e) {
    echo '<pre><code>' . print_r( $e->getMessage(), true ) . '</code><pre>'; // Error message.
    echo '<pre><code>' . print_r( $e->getRequest(), true ) . '</code><pre>'; // Last request data.
    echo '<pre><code>' . print_r( $e->getResponse(), true ) . '</code><pre>'; // Last response data.
}
?>
?>