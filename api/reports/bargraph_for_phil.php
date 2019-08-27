<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	  
    $params = null;
    $year = $_REQUEST['year'];
    $month = $_REQUEST['month'];
    $IEM = intval($_REQUEST['IEM']);
    
    $condition = "";	

// THIS PORTION IS LIVE - GRABS ONLY 2019
 $current_year = date("Y");
 $current_month = date("m");
	     $query = "SELECT to_char(t1.date, 'MM') AS the_month,  to_char(t1.date, 'MON') AS the_month_name, to_char(t1.date, 'yyyy') AS the_year, ( SELECT COUNT(to_char(t1.date, 'MM') ) ) AS num_in_month
FROM order_status_log AS t1
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
WHERE to_char(t1.date,'yyyy') = '$current_year' AND t1.order_status_id = 12 AND t2.active=TRUE
GROUP BY the_month, the_year, the_month_name";
    $stmt = pdo_query( $pdo, $query, $params ); 
	 $num_in_day = pdo_fetch_all( $stmt );
	 
$query = "SELECT to_char(t1.date, 'fmDD') as the_day, ( SELECT COUNT(to_char(t1.date, 'dd') ) ) AS num_in_day
					FROM order_status_log AS t1
					LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
					WHERE to_char(t1.date,'yyyy') = '$current_year' AND to_char(t1.date,'mm') = '$month' AND t1.order_status_id = 12 AND t2.active=TRUE
					GROUP BY the_day";
$stmt = pdo_query( $pdo, $query, $params ); 
$num_in_day = pdo_fetch_all( $stmt );

// SORTING BECAUSE REMOVING THE LEADING ZERO IN THE DAY CAUSED THE RESULT TO BE UNSORTED
$k = 0;
$store = [];
$number = cal_days_in_month(CAL_GREGORIAN, $month, $current_year); 
for($i=0; $i<$number; $i++) {
	for($m=0; $m<count($num_in_day); $m++) {
		if($i+1 == $num_in_day[$m]["the_day"]) {
			$store[$i]["the_day"] = (int)$num_in_day[$m]["the_day"];
			$store[$i]["num_in_day"] = (int)$num_in_day[$m]["num_in_day"];
			break;
		} elseif($m == count($num_in_day)-1) {
			$store[$i]["the_day"] = $i+1;
			$store[$i]["num_in_day"] = 0;
			break;
			//$k++;
		}
	}
}

$response["test"] = $num_in_day[0]["num_in_day"];
$response["test"] = $store[3]["the_day"] . " and number is " . $store[3]["num_in_day"] . " and I is " . $i;
//echo json_encode($response);
//exit;

   $result = array();
   //$result = array_merge($num_of_impressions_in_day, $num_of_shipped_in_day);

	$response["test"] = $num_in_day;
	
    $response['code'] = 'success';
    $response['data'] = $num_in_day;
    
    //$response["the_month"] = array();
    $the_month = array();
    $the_month_name = array();
    $num_in_month = array();
    // FILL IN THE DAYS WHERE NOTHING SHIPPED - LIKE WEEKENDS - WITH ZERO
    $number = cal_days_in_month(CAL_GREGORIAN, $month, $current_year); 
    
    /*
    $k = 0;
    $store = [];
    for($i=0; $i<$number+1; $i++) {
	    if($i+1 != $num_in_day[$k]["the_day"]) {
			$store[$i]["the_day_v2"] = $i+1;
			$store[$i]["num_in_day_v2"] = 0;	
		} else {
			$store[$i]["the_day_v2"] = $num_in_day[$k]["the_day"];
			$store[$i]["num_in_day_v2"] = $num_in_day[$k]["num_in_day"];	
			$k++;
		}
	 }*/
	$response["test"] = $num_in_day[$i]["the_day_v2"] . " and I is " . $k;
	    //echo json_encode($response);
		//exit;
    for($i=0; $i<count($store); $i++) {
	    //$response["the_month"] = $num_in_day[$i]["the_month"];	    
		//$the_month[$i] = $num_in_day[$i]["the_month"];	
		$the_day[$i] = $store[$i]["the_day"];	    
		$num_in_day[$i] = $store[$i]["num_in_day"];	    
    }
    //$response["the_month"] = $the_month;
    $response["the_day"] = $the_day;
    $response["num_in_day"] = $num_in_day;
    //$response['data'] = $num_of_impressions_in_day;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>