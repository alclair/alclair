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
      
   	$query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_days
              FROM repair_form
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_impressions_in_day = pdo_fetch_all( $stmt );

$query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_in_day, t2.type
              FROM repair_form
              LEFT JOIN status_type_repairs AS t2 ON 1 = t2.id
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day, type
      UNION ALL
              SELECT to_char(date, 'dd') AS the_day, ( SELECT COUNT(to_char(date, 'dd') ) ) AS num_in_day, t2.type
              FROM repair_status_log
              LEFT JOIN status_type_repairs AS t2 ON 2 = t2.id
              WHERE to_char(date,'yyyy') = '$year' AND to_char(date,'MM') = '$month'  AND repair_status_id = 14
              GROUP BY the_day, type
              ORDER BY the_day ASC";

    $stmt = pdo_query( $pdo, $query, $params ); 
	$num_in_day = pdo_fetch_all( $stmt );

    $result = array();

	for($j=0; $j<count($num_of_impressions_in_day); $j++) {   

	}
	$response["test1"] = $num_of_impresions_in_day;
	
    $response['code'] = 'success';
    $response['data'] = $result;
    $response['data'] = $num_in_day;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>