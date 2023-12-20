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
    if($IEM != 0)
    {
        $condition = "and t1.monitor_id = $IEM ";
    }
         	
   	$query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_in_day, t2.type
              FROM import_orders
              LEFT JOIN category_type AS t2 ON 1 = t2.id
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day, type
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_impressions_in_day = pdo_fetch_all( $stmt );
   
   	$query = "SELECT to_char(shipping_date, 'dd') AS the_day, ( SELECT COUNT(to_char(shipping_date, 'dd') ) ) AS num_in_day, t2.type
              FROM qc_form
              LEFT JOIN category_type AS t2 ON 2 = t2.id
              WHERE to_char(shipping_date,'yyyy') = '$year' AND to_char(shipping_date,'MM') = '$month' 
              
              GROUP BY the_day, type
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_shipped_in_day = pdo_fetch_all( $stmt );
   
 

// ON 01/18/2021 ADDED LINES OF CODE TO OBTAIN THE CORRECT NUMBER OF EARPHONES SHIPPED BY MONTH
// BORROWED CODE FROM /api/reports/manufacturing_screen_2 where it determines $num_in_day
	$query = "SELECT to_char(t1.date, 'dd') AS the_day, ( SELECT COUNT(to_char(t1.date, 'dd') ) ) AS num_in_day, t5.type
              FROM order_status_log as t1
			  LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
			  LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
			  LEFT JOIN monitors AS t4 ON t2.model = t4.name
              LEFT JOIN category_type AS t5 ON 1 = t5.id
              WHERE to_char(t1.date,'yyyy') = '$year' AND to_char(t1.date,'MM') = '$month'  AND t1.order_status_id = 12 AND t2.active=TRUE AND t4.name IS NOT NULL AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')
              AND (t2.model IS NOT NULL 
        AND t2.model != 'MP' 
		AND t2.model != 'AHP' 
		AND t2.model != 'SHP' 
		AND t2.model != 'EXP PRO' 
		AND t2.model != 'Exp Pro' 
		AND t2.model != 'Security Ears' 
		AND t2.model != 'Sec Ears Silicone'
		AND t2.model != 'Sec Ears Acrylic'
		AND t2.model != 'Musicians Plugs' 
		AND t2.model != 'Silicone Protection' 
		AND t2.model != 'Canal Fit HP' 
		AND t2.model != 'Acrylic HP' 
		AND t2.model != 'Full Ear HP' 
		AND t2.model != 'EXP CORE'
		AND t2.model != 'EXP CORE+'
		AND t2.model != 'Venture'
		AND t2.model != 'Cruise'
		AND t2.model != 'Moto Earplugs'
		AND t2.model != 'IFB Single'
		AND t2.model != 'IFB Duo') 
		
		GROUP BY the_day, type
    UNION ALL
    SELECT to_char(t1.date, 'dd') AS the_day, ( SELECT COUNT(to_char(t1.date, 'dd') ) ) AS num_in_day, t5.type
              FROM order_status_log as t1
			  LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
			  LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
			  LEFT JOIN monitors AS t4 ON t2.model = t4.name
          LEFT JOIN category_type AS t5 ON 2 = t5.id
              WHERE to_char(t1.date,'yyyy') = '$year' AND to_char(t1.date,'MM') = '$month'  AND t1.order_status_id = 12 AND t2.active=TRUE AND t4.name IS NOT NULL AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')
              AND t2.model IS NOT NULL 
        AND (t2.model = 'MP' 
		OR t2.model = 'AHP' 
		OR t2.model = 'SHP'  
		OR t2.model = 'Musicians Plugs' 
		OR t2.model = 'Silicone Protection' 
		OR t2.model = 'Canal Fit HP' 
		OR t2.model = 'Acrylic HP' 
		OR t2.model = 'Full Ear HP' 
		OR t2.model = 'Moto Earplugs') 
		
		GROUP BY the_day, type
	UNION ALL	  
	SELECT to_char(t1.date, 'dd') AS the_day, ( SELECT COUNT(to_char(t1.date, 'dd') ) ) AS num_in_day, t5.type
              FROM order_status_log as t1
			  LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
			  LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
			  LEFT JOIN monitors AS t4 ON t2.model = t4.name
          LEFT JOIN category_type AS t5 ON 3 = t5.id
              WHERE to_char(t1.date,'yyyy') = '$year' AND to_char(t1.date,'MM') = '$month'  AND t1.order_status_id = 12 AND t2.active=TRUE AND t4.name IS NOT NULL AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')
              AND t2.model IS NOT NULL 
		AND (t2.model = 'EXP PRO' 
		OR t2.model = 'Exp Pro' 
		OR t2.model = 'EXP CORE'
		OR t2.model = 'EXP CORE+') 
		
		GROUP BY the_day, type
		UNION ALL	  
	SELECT to_char(t1.date, 'dd') AS the_day, ( SELECT COUNT(to_char(t1.date, 'dd') ) ) AS num_in_day, t5.type
              FROM order_status_log as t1
			  LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
			  LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
			  LEFT JOIN monitors AS t4 ON t2.model = t4.name
          LEFT JOIN category_type AS t5 ON 4 = t5.id
              WHERE to_char(t1.date,'yyyy') = '$year' AND to_char(t1.date,'MM') = '$month'  AND t1.order_status_id = 12 AND t2.active=TRUE AND t4.name IS NOT NULL AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')
              AND t2.model IS NOT NULL 
		AND (t2.model = 'IFB Single'
		OR t2.model = 'IFB Duo'
		OR t2.model = 'Security Ears' 
		OR t2.model = 'Sec Ears Silicone'
		OR t2.model = 'Sec Ears Acrylic') 
		
		GROUP BY the_day, type
		UNION ALL	  
	SELECT to_char(t1.date, 'dd') AS the_day, ( SELECT COUNT(to_char(t1.date, 'dd') ) ) AS num_in_day, t5.type
              FROM order_status_log as t1
			  LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
			  LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
			  LEFT JOIN monitors AS t4 ON t2.model = t4.name
          LEFT JOIN category_type AS t5 ON 5 = t5.id
              WHERE to_char(t1.date,'yyyy') = '$year' AND to_char(t1.date,'MM') = '$month'  AND t1.order_status_id = 12 AND t2.active=TRUE AND t4.name IS NOT NULL AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')
              AND t2.model IS NOT NULL 
		AND (t2.model = 'Venture'
		OR t2.model = 'Cruise') 
		
		GROUP BY the_day, type

			  
		ORDER BY the_day ASC";

    $stmt = pdo_query( $pdo, $query, $params ); 
	$num_in_day = pdo_fetch_all( $stmt );
	 
	$num_ciem = 0;
	$num_hp = 0; 
	$num_outdoor = 0; 
	$num_ifb_sec = 0; 
	$num_moto = 0; 
	//for($j=0; $j<count($num_in_day); $j++) {    
	for($j=0; $j<($num_in_day); $j++) {    
		if(stristr($num_in_day[$j]["type"], "# CIEM") ) {
			$num_ciem = $num_ciem + $num_in_day[$j]["num_in_day"];		
		} elseif(stristr($num_in_day[$j]["type"], "# HP") ) {
			$num_hp = $num_hp + $num_in_day[$j]["num_in_day"];	
		} elseif(stristr($num_in_day[$j]["type"], "# Outdoor") ) {
			$num_outdoor = $num_outdoor + $num_in_day[$j]["num_in_day"];	
		} elseif(stristr($num_in_day[$j]["type"], "# IFB/Sec") ) {
			$num_ifb_sec = $num_ifb_sec + $num_in_day[$j]["num_in_day"];	
		} elseif(stristr($num_in_day[$j]["type"], "# Moto") ) {
			$num_moto = $num_moto + $num_in_day[$j]["num_in_day"];	
		}
	}
  
	//$response["test"] = $num_in_day;
	//$response["test"] = "Num imp is " . $num_impressions . " and num shipped is " . $num_shipped;
   $result = array();
   $result = array_merge($num_of_impressions_in_day, $num_of_shipped_in_day);

/*
	for($j=0; $j<count($num_of_impressions_in_day); $j++) {   
		$result[$the_day-1]["the_day"] = $the_day;
		$result[$the_day-1]["num_days"] = $num_days;
	}
	for($j=0; $j<count($num_of_shipped_in_day); $j++) {   
		$result[$the_day_shipped-1]["the_day_shipped"] = $the_day_shipped;
		$result[$the_day_shipped-1]["num_shipped"] = $num_shipped;
	}
	*/
	$response["num_ciem"] = $num_ciem;
	$response["num_hp"] = $num_hp;
	$response["num_outdoor"] = $num_outdoor;
	$response["num_ifb_sec"] = $num_ifb_sec;
	$response["num_moto"] = $num_moto;
    $response['code'] = 'success';
    $response['data'] = $num_in_day;
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