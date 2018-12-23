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
    $condition = "";
    $lng_queens = $_REQUEST['lng_queens'];
    
    if(!empty($_REQUEST["StartDate"]))
	{
		$conditionSql.=" and (time_stamp>=:StartDate)";
		$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	if(!empty($_REQUEST["EndDate"]))
	{
		$conditionSql.=" and (time_stamp<=:EndDate)";
		$params[":EndDate"]=$_REQUEST["EndDate"];
	}
    

	if ($lng_queens == 1) {
			$query =   "SELECT tank_level as inox_q4, time_stamp, row_number() over() row_num_1 FROM inox_q4
								ORDER BY time_stamp DESC LIMIT 96";
	}
	elseif ($lng_queens == 2) {
			$query =   "SELECT tank_level as chart_q5, time_stamp, row_number() over() row_num_1 FROM chart_q5
								ORDER BY time_stamp DESC LIMIT 96";
	}
	elseif ($lng_queens == 3) {
			$query =   "SELECT tank_level  as chart_q2, time_stamp, row_number() over() row_num_1 FROM chart_q2
								ORDER BY time_stamp DESC LIMIT 96";
	}
	elseif ($lng_queens == 0) {
			$query =   "SELECT t1.tank_level as inox_q4, t2.tank_level  as chart_q5, t3.tank_level as chart_q2, t4.tank_level  as inox_q1, t5.tank_level  as inox_q3, t6.tank_level  as inox_q6, t1.time_stamp, row_number() over() row_num_1 FROM inox_q4 as t1
								LEFT JOIN chart_q5 as t2 on t2.id = t1.id
								LEFT JOIN chart_q2 as t3 on t3.id = t1.id
								LEFT JOIN inox_q1 as t4 on t4.id = t1.id
								LEFT JOIN inox_q3 as t5 on t5.id = t1.id
								LEFT JOIN inox_q6 as t6 on t6.id = t1.id
								ORDER BY t1.time_stamp DESC LIMIT 96";
	}
	elseif ($lng_queens == 4) {
			$query =   "SELECT tank_level  as inox_q1, time_stamp, row_number() over() row_num_1 FROM inox_q1
								ORDER BY time_stamp DESC LIMIT 96";
	}
		elseif ($lng_queens == 5) {
			$query =   "SELECT tank_level  as inox_q3, time_stamp, row_number() over() row_num_1 FROM inox_q3
								ORDER BY time_stamp DESC LIMIT 96";
	}
		elseif ($lng_queens == 6) {
			$query =   "SELECT tank_level  as inox_q6, time_stamp, row_number() over() row_num_1 FROM inox_q6
								ORDER BY time_stamp DESC LIMIT 96";
	}
	//SELECT t1.flow_rate as inox_q4, t2.flow_rate as chart_q5, time_stamp, row_number() over() row_num_1 FROM inox_q4 as t1
	//							LEFT JOIN chart_q5 as t2 on t2.id = t1.id
	//							ORDER BY time_stamp DESC LIMIT 96

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

    $response['data'] = $result;
    
    $stmt = pdo_query( $pdo, 'SELECT name as queen_name FROM queens ORDER BY queens_id',null);	
	$result2 = pdo_fetch_all($stmt);
	
	$response['code'] = 'success';
	$response['queens'] = $result2;
    
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>