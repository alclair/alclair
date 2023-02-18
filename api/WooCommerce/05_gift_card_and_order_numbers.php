<?php
include_once "../../config.inc.php";

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
			
//echo "The code is working."  . " <br/>";
$year = ['2020', '2021', '2022', '2023'];
$year = ['2022'];
$month = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
$month = ['09'];
$day_31 = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'];
//$day_31 = [ '31'];
$day_30 = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30'];
//$day_30 = ['29'];
$day_28 = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28'];
//$day_28 = ['27', '28'];

$result = [];
for($y = 0; $y < count($year); $y++) {
	for($m = 0; $m < count($month); $m++) {
		if($month[$m] == '01' || $month[$m] == '03' || $month[$m] == '05' || $month[$m] == '07' || $month[$m] == '08' || $month[$m] == '10' || $month[$m] == '12' || $month[$m] == '02') {
			if($month[$m] == '02') {
				$day = $day_28;
			} else {
				$day = $day_31;		
			}
		} else {
			$day = $day_30;
		}
	
		for($d = 0; $d < count($day); $d++) {
			$print_to_screen = $year[$y] . '-' . $month[$m] . '-' . $day[$d] . 'T00:00:00';
			$print_to_screen = $year[$y] . '-' . $month[$m] . '-' . $day[$d] . 'T23:59:59';

			$params = [
					'before' => $year[$y] . '-' . $month[$m] . '-' . $day[$d] . 'T23:59:59',
					'after' => $year[$y] . '-' . $month[$m] . '-' . $day[$d] . 'T00:00:00',
					'per_page' => 100
		        ];

		    $result = $woocommerce->get('orders', $params);
		    for($i = 0; $i < count($result); $i++) {
				$data = get_object_vars($result[$i]);  // STORE THE DATA
				
			    for($k = 0; $k < count($data[line_items]); $k++) {
			
					$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
					$meta_data = get_object_vars($data[meta_data][$k]); // PRODUCT -> 2
					$is_earphone = get_object_vars($line_item[meta_data][$k]); // MODEL -> 4
					$coupon_lines = get_object_vars($data[coupon_lines][$k]); 
				
					$full_product_name = $line_item["name"];
					$discount = $data["discount_total"];
					$coupon = $coupon_lines["code"];
					
					//if( stristr($full_product_name, "Driver") || stristr($full_product_name, "POS") || stristr($full_product_name, "Gift Certificate") ) { 
					if( stristr($full_product_name, "Gift Certificate") ) { 
						
						if(!strcmp($data["status"], "processing")  || !strcmp($data["status"], "completed")   ) {  //|| stristr($data["status"], "hold")
												
							$order_id = $data["id"]; // ORDER ID -> 1
							$billing_name = $data[billing]->first_name . " " . $data[billing]->last_name;
							$shipping_name = $data[shipping]->first_name . " " . $data[shipping]->last_name;
							$email = $data[billing]->email;
							//echo "Number of Meta Data is " . count($data[meta_data])  . " <br/>";
							for($j = 0; $j < count($data[meta_data]); $j++) {
								$meta_data= get_object_vars($data[meta_data][$j]); 
								if(!strcmp($meta_data["key"], "sc_coupon_receiver_details") ) {
									//echo "Length of values is " . count($meta_data[value])  . " <br/>";
									for($c = 0; $c < count($meta_data[value]); $c++) {
										$value = get_object_vars($meta_data[value][$c]);
										$CODE = $value["code"];
										//echo "Date is " . $month[$m] . "/" . $day[$d] . "/" . $year[$y] . " & Order # is " .  $order_id  . " & billing name is  " . $billing_name . " & Code is " . $CODE . " <br/>";
										echo $month[$m] . "/" . $day[$d] . "/" . $year[$y] . ", " .  $order_id  . ", " . $billing_name . ", " . $CODE . " <br/>";
									}
								}
							}
						} // CLOSES IF STATEMENT - STATUS
				    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
				} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER 	
		    } // END FOR LOOP THAT STEPS THROUGH EVERY ORDER - I

		    

		} // END FOR LOOP FOR DAY
	} // END FOR LOOP FOR MONTH
} // END FOR LOOP FOR YEAR

/*		
			$params = [
					'before' => $year[$y] . '-' . $month[$m] . '-' . $day[$d] . 'T23:59:59',
					'after' => $year[$y] . '-' . $month[$m] . '-' . $day[$d] . 'T00:00:00',
					'per_page' => 100
		        ];
		    $params = [
				'before' => '2022-06-29T23:59:59',
				'after' => '2022-06-29T00:00:00',
				'per_page' => 100
			];
		    $result = $woocommerce->get('orders', $params);
		    for($i = 0; $i < count($result); $i++) {
				$data = get_object_vars($result[$i]);  // STORE THE DATA
				
			    for($k = 0; $k < count($data[line_items]); $k++) {
			
					$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
					$meta_data = get_object_vars($data[meta_data][$k]); // PRODUCT -> 2
					$is_earphone = get_object_vars($line_item[meta_data][$k]); // MODEL -> 4
					$coupon_lines = get_object_vars($data[coupon_lines][$k]); 
				
					$full_product_name = $line_item["name"];
					$discount = $data["discount_total"];
					$coupon = $coupon_lines["code"];
					
					//if( stristr($full_product_name, "Driver") || stristr($full_product_name, "POS") || stristr($full_product_name, "Gift Certificate") ) { 
					if( stristr($full_product_name, "Gift Certificate") ) { 
						
						if(!strcmp($data["status"], "processing")  || !strcmp($data["status"], "completed")   ) {  //|| stristr($data["status"], "hold")
												
							$order_id = $data["id"]; // ORDER ID -> 1
							$billing_name = $data[billing]->first_name . " " . $data[billing]->last_name;
							$shipping_name = $data[shipping]->first_name . " " . $data[shipping]->last_name;
							$email = $data[billing]->email;
							//echo "Number of Meta Data is " . count($data[meta_data])  . " <br/>";
							for($j = 0; $j < count($data[meta_data]); $j++) {
								$meta_data= get_object_vars($data[meta_data][$j]); 
								if(!strcmp($meta_data["key"], "sc_coupon_receiver_details") ) {
									//echo "Length of values is " . count($meta_data[value])  . " <br/>";
									for($c = 0; $c < count($meta_data[value]); $c++) {
										$value = get_object_vars($meta_data[value][$c]);
										$CODE = $value["code"];
										echo "Date is " . $month[$m] . "/" . $day[$d] . "/" . $year[$y] . " & Order # is " .  $order_id  . " & billing name is  " . $billing_name . " & Code is " . $CODE . " <br/>";
									}
								}
							}
						} // CLOSES IF STATEMENT - STATUS
				    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
				} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER 	
		    } // END FOR LOOP THAT STEPS THROUGH EVERY ORDER - I
*/	
	$response['code'] = 'success';
	echo json_encode($response);
} // CLOSE TRY
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>
