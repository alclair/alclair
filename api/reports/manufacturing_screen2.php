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
              LEFT JOIN status_type_orders AS t2 ON 1 = t2.id
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day, type
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_impressions_in_day = pdo_fetch_all( $stmt );
   
   	$query = "SELECT to_char(shipping_date, 'dd') AS the_day, ( SELECT COUNT(to_char(shipping_date, 'dd') ) ) AS num_in_day, t2.type
              FROM qc_form
              LEFT JOIN status_type_orders AS t2 ON 2 = t2.id
              WHERE to_char(shipping_date,'yyyy') = '$year' AND to_char(shipping_date,'MM') = '$month' 
              GROUP BY the_day, type
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_shipped_in_day = pdo_fetch_all( $stmt );
   
   // SAME AS ABOVE BUT A UNION WAS USED TO COMBINE THE TWO QUERIES TOGETHER
	//WHAT IS BELOW THIS COMMENTED TEXT IS WHAT IS USED FOR THE PLOT
   /*  
   $query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_in_day, t2.type
              FROM import_orders
              LEFT JOIN status_type_orders AS t2 ON 1 = t2.id
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
GROUP BY the_day, type

    UNION ALL
    SELECT to_char(shipping_date, 'dd') AS the_day, ( SELECT COUNT(to_char(shipping_date, 'dd') ) ) AS num_in_day, t2.type
              FROM qc_form
              LEFT JOIN status_type_orders AS t2 ON 2 = t2.id
              WHERE to_char(shipping_date,'yyyy') = '$year' AND to_char(shipping_date,'MM') = '$month' 
GROUP BY the_day, type
ORDER BY the_day ASC";
    $stmt = pdo_query( $pdo, $query, $params ); 
	 $num_in_day = pdo_fetch_all( $stmt );
	 */

	$query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_in_day, t2.type
              FROM import_orders
              LEFT JOIN status_type_orders AS t2 ON 1 = t2.id
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
GROUP BY the_day, type


    UNION ALL
    SELECT to_char(date, 'dd') AS the_day, ( SELECT COUNT(to_char(date, 'dd') ) ) AS num_in_day, t2.type
              FROM order_status_log
              LEFT JOIN status_type_orders AS t2 ON 2 = t2.id
              WHERE to_char(date,'yyyy') = '$year' AND to_char(date,'MM') = '$month'  AND order_status_id = 12
GROUP BY the_day, type
ORDER BY the_day ASC";

// THIS PORTION IS NOT LIVE
// GRABS 2018 AND 2019
    $query = "SELECT to_char(t1.date, 'MM') AS the_month,  to_char(t1.date, 'month') AS the_month_name, to_char(t1.date, 'yyyy') AS the_year, ( SELECT COUNT(to_char(t1.date, 'MM') ) ) AS num_in_month
FROM order_status_log AS t1
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
WHERE to_char(t1.date,'yyyy') = '2021' AND t1.order_status_id = 12 AND t2.active=TRUE
GROUP BY the_month, the_year, the_month_name

UNION ALL
    
SELECT to_char(t1.date, 'MM') AS the_month,  to_char(t1.date, 'month') AS the_month_name, to_char(t1.date, 'YYYY') AS the_year, ( SELECT COUNT(to_char(t1.date, 'MM') ) ) AS num_in_month
FROM order_status_log AS t1
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
WHERE to_char(t1.date,'yyyy') = '2020' AND t1.order_status_id = 12 AND t2.active=TRUE
GROUP BY the_month, the_year, the_month_name
ORDER BY the_month, the_year ASC";
    $stmt = pdo_query( $pdo, $query, $params ); 
	 $num_in_day = pdo_fetch_all( $stmt );
	

// THIS PORTION IS LIVE - GRABS ONLY 2020
 $current_year = date("Y");
 
 $current_month = date("m");
	     $query = "SELECT to_char(t1.date, 'MM') AS the_month,  to_char(t1.date, 'MON') AS the_month_name, to_char(t1.date, 'yyyy') AS the_year, ( SELECT COUNT(to_char(t1.date, 'MM') ) ) AS num_in_month
FROM order_status_log AS t1
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
LEFT JOIN monitors AS t4 ON t2.model = t4.name
WHERE to_char(t1.date,'yyyy') = '$current_year' AND t1.order_status_id = 12 AND t2.active=TRUE AND t4.name IS NOT NULL AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '') AND t2.use_for_estimated_ship_date = TRUE
GROUP BY the_month, the_year, the_month_name";

    $stmt = pdo_query( $pdo, $query, $params ); 
	 $num_in_day = pdo_fetch_all( $stmt );
	 	 
   $result = array();
   //$result = array_merge($num_of_impressions_in_day, $num_of_shipped_in_day);

	$response["test"] = $num_in_day;
	
    $response['code'] = 'success';
    $response['data'] = $num_in_day;
    
    //$response["the_month"] = array();
    $the_month = array();
    $the_month_name = array();
    $num_in_month = array();
    for($i=0; $i<count($num_in_day); $i++) {
	    //$response["the_month"] = $num_in_day[$i]["the_month"];	    
		$the_month[$i] = $num_in_day[$i]["the_month"];	
		$the_month_name[$i] = $num_in_day[$i]["the_month_name"];	    
		$num_in_month[$i] = $num_in_day[$i]["num_in_month"];	    
    }
    $response["the_month"] = $the_month;
    $response["the_month_name"] = $the_month_name;
    $response["num_in_month"] = $num_in_month;
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