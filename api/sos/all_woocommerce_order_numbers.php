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

		$emails[$i] = $data[billing]->email;
		$order_numbers[$i] = $data["id"];
	
} // END FOR LOOP THAT STEPS THROUGH EVERY ORDER
	
	$response['order_numbers'] = $order_numbers;
	$response['emails'] = $emails;

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