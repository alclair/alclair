<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;

require '/var/www/html/otisdev/vendor/autoload.php';
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
					
$start = strtotime('2019-04-26');
$startmoyr = date('Y', $start) . date('m', $start);
$startdayyr = date('Y', $start) . date('d', $start);
$startdayyr = date('Y', $start) . date('m', $start) . date('d', $start);
$end = strtotime('2019-04-29');
$endmoyr = date('Y', $end) . date('m', $end);
$enddayyr = date('Y', $end) . date('d', $end);
$enddayyr = date('Y', $end) . date('m', $end) . date('d', $end);

/*
while ($startdayyr <= $enddayyr) {
    //echo date("d F Y", $start) . "<br>";
    //echo date("Y", $start) . "-" . date("m", $start) . "-" . date("d", $start) . "T00:00:00"  . "<br>";
    echo date("Y", $start) . "-" . date("m", $start) . "-" . date("d", $start) . "T23:59:59"  . "<br>";
    $start = strtotime("+1day", $start);
    $startmoyr = date('Y', $start) . date('m', $start);
    $startdayyr = date('Y', $start) . date('d', $start);
}
exit;
*/
    $order = [];
    $ind = 0;
while ($startdayyr <= $enddayyr) {
	$before = date("Y", $start) . "-" . date("m", $start) . "-" . date("d", $start) . "T23:59:59";
	$after = date("Y", $start) . "-" . date("m", $start) . "-" . date("d", $start) . "T00:00:00";

	//$before = '2019-04-25T23:59:59';
	//$after = '2019-04-22T00:00:00';
	/*
	$params = [
		'before' => '2019-04-25T23:59:59',
		'after' => '2019-04-22T00:00:00',
		'per_page' => 100			
	];
    */
    $params = [
		'before' => $before,
		'after' => $after,
		'per_page' => 100			
    ];
    
    $result = $woocommerce->get('orders', $params);
       
for($i = 0; $i < count($result); $i++) {
    	//echo $data["number"] . " is and I is " . $i ." and date is " . $data["date_created"] . "<br/>";
		$data = get_object_vars($result[$i]);  // STORE THE DATA
    for($k = 0; $k < count($data[line_items]); $k++) {
		$line_item = get_object_vars($data[line_items][$k]); // PRODUCT -> 2
		$full_product_name = $line_item["name"];

		// IF THE WORD "DRIVER" OR "POS" IS INSIDE THE FULL PRODUCT NAME STORE INFO FOR IMPOT
		//if(is_string($model) == 1 && (stristr($full_product_name, "Driver") !== false ) || stristr($full_product_name, "POS") !== false ))) { 
		if( stristr($full_product_name, "Driver") !== false || stristr($full_product_name, "POS") !== false ) { 
			
			if(!strcmp($data["status"], "processing") || !strcmp($data["status"], "completed") ) {
				for($j = 0; $j < count($line_item[meta_data]); $j++) {
					if(!strcmp(  substr($line_item[meta_data][$j]->key, 0, 5), "Color") ) {
						//$order[$ind]["filename"] = $line_item[meta_data][$j]->value;
					} 
					elseif(!strcmp( substr($line_item[meta_data][$j]->key, 0, 15), "Left Custom Art") ) {
						$order[$ind]["fullpath"] = $line_item[meta_data][$j]->value;	
						$order[$ind]["filename"] = basename($line_item[meta_data][$j]->value);
						$order[$ind]["side"] = "Left";	
						$order[$ind]["order_id"] = $data["id"];
						$ind++;
					} elseif(!strcmp( substr($line_item[meta_data][$j]->key, 0, 16), "Right Custom Art") ) {
						$order[$ind]["fullpath"] = $line_item[meta_data][$j]->value;	
						$order[$ind]["filename"] = basename($line_item[meta_data][$j]->value);
						$order[$ind]["side"] = "Right";	
						$order[$ind]["order_id"] = $data["id"];
						$ind++;
						
					} /*
						elseif(!strcmp( $line_item[meta_data][$j]->key, "Link to Design Image") ) {
						$order[$ind]["fullpath"] = $line_item[meta_data][$j]->value;	
						$basename = basename($line_item[meta_data][$j]->value);
						$order[$ind]["filename"]  = substr($basename, 3, 18);
						$order[$ind]["order_id"] = $data["id"];
						$ind++;
					} 
					*/
				} // CLOSES FOR LOOP - METADATA
				//$ind++;
			} // CLOSES IF STATEMENT - STATUS
	    } // CLOSES IF STATEMENT - IS IT AN EARPHONE OR NOT
	} // END FOR LOOP THAT GOES THROUGH EVERY LINE ITEM OF AN ORDER LOOKING FOR MORE THAN ONE EARPHONE HAS BEEN PURCHASED 
} // END FOR LOOP THAT STEPS THROUGH EVERY ORDER

  $start = strtotime("+1day", $start);
  $startdayyr = date('Y', $start) . date('m', $start) . date('d', $start);
  //break;
} // END WHILE LOOP

		//echo "I is " . $i . " K is " . $k . " and J is " . $j . "<br/>";
		for($k = 0; $k < count($order); $k++) {
			copy('/var/www/html/otis/artworkscan/custom/' . $order[$k]["filename"], '/var/www/html/otis/artworkscan/custom_tf/' . $order[$k]["filename"]);
			//copy('/var/www/html/otis/artworkscan/saved/' . $order[$k]["filename"] . '.png', '/var/www/html/otis/artworkscan/saved_tf/' . $order[$k]["filename"] . '.png');
			//echo $order[$k]["filename"] .'.png' . " Order is " . $order[$k]["order_id"]  . " and ind is " . $k . "<br/>";
			/*if($k == 0) {
				break;
			}*/
			//exit;
		} // CLOSE FOR LOOP FOR EACH ORDER
		echo "Order count is " . count($order) . "<br>";
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