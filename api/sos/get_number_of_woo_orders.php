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
//$the_order_number = $_REQUEST["order_number"];

//$HOURS = array("T00:00:00", "T06:00:00", "T06:00:01", "T12:00:00", "T12:00:01", "T18:00:00", "T18:00:01", "T23:59:59");	
$HOURS = array("T00:00:00", "T23:59:59");	
//$after  = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T00:00:00";
//$before = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day . "T23:59:59";
$date = $yesterday_year . "-" . $yesterday_month . "-" . $yesterday_day;
$date = '2017-12-01';
$date = '2017-12-31';

$month = '08';
$year = '2020';

$date1 = $year . '-' . $month . '-01';
$date2 = $year . '-' . $month . '-02';
$date3 = $year . '-' . $month . '-03';

$date4 = $year . '-' . $month . '-04';
$date5 = $year . '-' . $month . '-05';
$date6 = $year . '-' . $month . '-06';

$date7 = $year . '-' . $month . '-07';
$date8 = $year . '-' . $month . '-08';
$date9 = $year . '-' . $month . '-09';

$date10 = $year . '-' . $month . '-10';
$date11 = $year . '-' . $month . '-11';
$date12 = $year . '-' . $month . '-12';

$date13 =$year . '-' . $month . '-13';
$date14 =$year . '-' . $month . '-14';
$date15 = $year . '-' . $month . '-15';

$date16 = $year . '-' . $month . '-16';
$date17 = $year . '-' . $month . '-17';
$date18 = $year . '-' . $month . '-18';

$date19 = $year . '-' . $month . '-19';
$date20 = $year . '-' . $month . '-20';
$date21 = $year . '-' . $month . '-21';

$date22 = $year . '-' . $month . '-22';
$date23 = $year . '-' . $month . '-23';
$date24 = $year . '-' . $month . '-24';

$date25 = $year . '-' . $month . '-25';
$date26 = $year . '-' . $month . '-26';
$date27 = $year . '-' . $month . '-27';

$date28 = $year . '-' . $month . '-28';
$date29 = $year . '-' . $month . '-29';
$date30 = $year . '-' . $month . '-30';
$date31 = $year . '-' . $month . '-31';

$params = ['before' =>  $date1 . $HOURS[1], 'after' => $date1 . $HOURS[0], 'per_page' => 100];
$result1 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date2 . $HOURS[1], 'after' => $date2 . $HOURS[0], 'per_page' => 100];
$result2 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date3 . $HOURS[1], 'after' => $date3 . $HOURS[0], 'per_page' => 100];
$result3 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date4 . $HOURS[1], 'after' => $date4 . $HOURS[0], 'per_page' => 100];
$result4 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date5 . $HOURS[1], 'after' => $date5 . $HOURS[0], 'per_page' => 100];
$result5 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date6 . $HOURS[1], 'after' => $date6 . $HOURS[0], 'per_page' => 100];
$result6 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date7 . $HOURS[1], 'after' => $date7 . $HOURS[0], 'per_page' => 100];
$result7 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date8 . $HOURS[1], 'after' => $date8 . $HOURS[0], 'per_page' => 100];
$result8 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date9 . $HOURS[1], 'after' => $date9 . $HOURS[0], 'per_page' => 100];
$result9 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date10 . $HOURS[1], 'after' => $date10 . $HOURS[0], 'per_page' => 100];
$result10 = $woocommerce->get('orders', $params);

$params = ['before' =>  $date11 . $HOURS[1], 'after' => $date11 . $HOURS[0], 'per_page' => 100];
$result11 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date12 . $HOURS[1], 'after' => $date12 . $HOURS[0], 'per_page' => 100];
$result12 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date13 . $HOURS[1], 'after' => $date13 . $HOURS[0], 'per_page' => 100];
$result13 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date14 . $HOURS[1], 'after' => $date14 . $HOURS[0], 'per_page' => 100];
$result14 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date15 . $HOURS[1], 'after' => $date15 . $HOURS[0], 'per_page' => 100];
$result15 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date16 . $HOURS[1], 'after' => $date16 . $HOURS[0], 'per_page' => 100];
$result16 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date17 . $HOURS[1], 'after' => $date17 . $HOURS[0], 'per_page' => 100];
$result17 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date18 . $HOURS[1], 'after' => $date18 . $HOURS[0], 'per_page' => 100];
$result18 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date19 . $HOURS[1], 'after' => $date19 . $HOURS[0], 'per_page' => 100];
$result19 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date20 . $HOURS[1], 'after' => $date20 . $HOURS[0], 'per_page' => 100];

$params = ['before' =>  $date20 . 'T15:25:00', 'after' => $date20 . 'T15:20:00', 'per_page' => 100];
$result20 = $woocommerce->get('orders', $params);

$params = ['before' =>  $date21 . $HOURS[1], 'after' => $date21 . $HOURS[0], 'per_page' => 100];
$result21 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date22 . $HOURS[1], 'after' => $date22 . $HOURS[0], 'per_page' => 100];
$result22 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date23 . $HOURS[1], 'after' => $date23 . $HOURS[0], 'per_page' => 100];
$result23 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date24 . $HOURS[1], 'after' => $date24 . $HOURS[0], 'per_page' => 100];
$result24 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date25 . $HOURS[1], 'after' => $date25 . $HOURS[0], 'per_page' => 100];
$result25 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date26 . $HOURS[1], 'after' => $date26 . $HOURS[0], 'per_page' => 100];
$result26 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date27 . $HOURS[1], 'after' => $date27 . $HOURS[0], 'per_page' => 100];
$result27 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date28 . $HOURS[1], 'after' => $date28 . $HOURS[0], 'per_page' => 100];
$result28 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date29 . $HOURS[1], 'after' => $date29 . $HOURS[0], 'per_page' => 100];
$result29 = $woocommerce->get('orders', $params);
$params = ['before' =>  $date30 . $HOURS[1], 'after' => $date30 . $HOURS[0], 'per_page' => 100];
$result30 = $woocommerce->get('orders', $params);

$params = ['before' =>  $date31 . $HOURS[1], 'after' => $date31 . $HOURS[0], 'per_page' => 100];
$result31 = $woocommerce->get('orders', $params);

$result = [];

//$result = array_merge($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30);

//$result = array_merge($result1, $result2, $result3, $result4, $result5, $result6, $result7,$result8,$result9,$result11,$result12,$result13,$result14,$result15,$result16, $result17, $result18, $result19, $result20);
//$result = array_merge($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22,$result23,$result24,$result25,$result26, $result27, $result28, $result29, $result30, $result31);

$result = array_merge($result24);
/*
$params = ['before' => '2017-12-04T23:59:59', 'after' => '2017-12-04T00:00:00', 'per_page' => 100];
$before = '2017-12-04T23:59:59';
$after = '2017-12-04T00:00:00';

$params = [
			'before' => $before,
			'after' => $after,
			'per_page' => 100,			
        ];

$result = $woocommerce->get('orders', $params);
*/
$inc = 0;
for($k = 0; $k < count($result); $k++) { 
	$data = get_object_vars($result[$k]);  // STORE THE DATA
	if(!strcmp($data["status"], "processing") || !strcmp($data["status"], "completed") ) { // || !strcmp($data["status"], "completed") ) {
		$order_numbers[$inc] = $data["id"];
		$customer_names[$inc] = $data[billing]->first_name . " " . $data[billing]->last_name;
		$emails[$inc] = strtolower($data[billing]->email);
		$inc = $inc + 1;
	}
}
$response["num_of_orders"] = count($result);
$response["num_of_orders"] = count($order_numbers);
$response["order_numbers"] =  $order_numbers;
$response["customer_names"] =  $customer_names;
$response["emails"] =  $emails;
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