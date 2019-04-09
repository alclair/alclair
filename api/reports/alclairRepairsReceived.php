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
      
   $query = "SELECT id, to_char(received_date, 'MM/dd/yyyy') AS full_date, to_char(received_date, 'dd') AS day_only 
   						FROM import_orders 
   						WHERE to_char(received_date, 'yyyy') = '$year' AND to_char(received_date, 'MM') = '$month' AND received_date IS NOT NULL
   						GROUP BY day_only, id, received_date
   						ORDER BY day_only ASC";
   	$stmt = pdo_query( $pdo, $query, $params ); 
    $result2 = pdo_fetch_all( $stmt );
   	
   	$query = "SELECT to_char(received_date, 'dd') AS the_day, ( SELECT COUNT(to_char(received_date, 'dd') ) ) AS num_days
              FROM repair_form
              WHERE to_char(received_date,'yyyy') = '$year' AND to_char(received_date,'MM') = '$month' 
              GROUP BY the_day
              ORDER BY the_day ASC";
   $stmt = pdo_query( $pdo, $query, $params ); 
   $num_of_impressions_in_day = pdo_fetch_all( $stmt );

   
    

    $result = array();

	for($j=0; $j<count($num_of_impressions_in_day); $j++) {   

	}
	$response["test1"] = $num_of_impresions_in_day;
	$response["test2"] = $count_sound;
	$response["test3"] = $count_shells;
	$response["test4"] = $count_jacks;
	$response["test5"] = $count_ports;
	$response["test6"] = $count_artwork;
	
    $response['code'] = 'success';
    $response['data'] = $result;
    $response['data'] = $num_of_impressions_in_day;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>