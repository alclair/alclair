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
              LEFT JOIN status_type_order AS t2 ON 1 = t2.id
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day, type
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_impressions_in_day = pdo_fetch_all( $stmt );
   
   	$query = "SELECT to_char(shipping_date, 'dd') AS the_day, ( SELECT COUNT(to_char(shipping_date, 'dd') ) ) AS num_in_day, t2.type
              FROM qc_form
              LEFT JOIN status_type_order AS t2 ON 2 = t2.id
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
    $stmt = pdo_query( $pdo, $query, $params ); 
	 $num_in_day = pdo_fetch_all( $stmt );
	 
	$num_impressions = 0;
	$num_shipped = 0; 
	for($j=0; $j<count($num_in_day); $j++) {    
		if(stristr($num_in_day[$j]["type"], "# of Impressions") ) {
			$num_impressions = $num_impressions + $num_in_day[$j]["num_in_day"];		
		} elseif(stristr($num_in_day[$j]["type"], "# Shipped") ) {
			$num_shipped = $num_shipped + $num_in_day[$j]["num_in_day"];	
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
	$response["num_impressions"] = $num_impressions;
	$response["num_shipped"] = $num_shipped;
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