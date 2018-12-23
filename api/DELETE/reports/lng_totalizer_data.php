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
    $conditionSql= "";
    $lng_queens = $_REQUEST['lng_queens'];
    $new_StartDate = '';
    
    if(date("I",time()))
		{ $new_StartDate = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')  + 5 * 3600 ); 
		   $new_EndDate = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 5 * 3600 ); 
		   $answer = 5;
		}
	else
		{ $new_StartDate = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')  + 6 * 3600 ); 
			$new_EndDate = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 6 * 3600 ); 
			$answer = 6;
		}
    
    $response['start'] = $new_StartDate;
	$response['end'] = $new_EndDate;
	$response['answer'] = $answer;

    if(!empty($_REQUEST["StartDate"]))
	{
		$conditionSql.=" and (t1.time_stamp>=:StartDate)";
		$params[":StartDate"]=$new_StartDate;//$_REQUEST["StartDate"];
	}
	if(!empty($_REQUEST["EndDate"]))
	{
		$conditionSql.=" and (t1.time_stamp<=:EndDate)";
		$params[":EndDate"]=$new_EndDate;//$_REQUEST["EndDate"] . ' 23:59:59';
	}
    
	if ($lng_queens == 1) {
			$query =   "SELECT totalizer as inox_q1, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM inox_q1 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 2) {
			$query =   "SELECT totalizer as chart_q2, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM chart_q2 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 3) {
			$query =   "SELECT totalizer as inox_q3, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM inox_q3 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 0) {
			$query =   "SELECT t1.totalizer as inox_q4, t2.totalizer as chart_q5, t3.totalizer as chart_q2, t4.totalizer as inox_q1, t5.totalizer as inox_q3, t6.totalizer as inox_q6,  t7.totalizer as chart_q7,  t8.totalizer as chart_q8,  t9.totalizer as chart_q9,  t10.totalizer as chart_q10, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM inox_q4 as t1
								LEFT JOIN chart_q5 as t2 on t2.time_stamp = t1.time_stamp
								LEFT JOIN chart_q2 as t3 on t3.time_stamp = t1.time_stamp
								LEFT JOIN inox_q1 as t4 on t4.time_stamp = t1.time_stamp
								LEFT JOIN inox_q3 as t5 on t5.time_stamp = t1.time_stamp
								LEFT JOIN inox_q6 as t6 on t6.time_stamp = t1.time_stamp
								LEFT JOIN chart_q7 as t7 on t7.time_stamp = t1.time_stamp
								LEFT JOIN chart_q8 as t8 on t8.time_stamp = t1.time_stamp
								LEFT JOIN chart_q9 as t9 on t9.time_stamp = t1.time_stamp	
								LEFT JOIN chart_q10 as t10 on t10.time_stamp = t1.time_stamp																								
								WHERE 1 = 1 $conditionSql
								ORDER BY t1.time_stamp DESC";
	}
	elseif ($lng_queens == 4) {
			$query =   "SELECT totalizer  as inox_q4, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM inox_q4 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 5) {
			$query =   "SELECT t1.totalizer as chart_q5, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_q5_volume_gal, row_number() over() row_num_1 FROM chart_q5 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 6) {
			$query =   "SELECT totalizer as inox_q6, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM inox_q6 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 7) {
			$query =   "SELECT totalizer as chart_q7, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM chart_q7 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 8) {
			$query =   "SELECT totalizer as chart_q8, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM chart_q8 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 9) {
			$query =   "SELECT totalizer as chart_q9, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM chart_q9 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 10) {
			$query =   "SELECT totalizer as chart_q10, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, row_number() over() row_num_1 FROM chart_q10 as t1
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	//SELECT t1.flow_rate as inox_q4, t2.flow_rate as chart_q5, time_stamp, row_number() over() row_num_1 FROM inox_q4 as t1
	//							LEFT JOIN chart_q5 as t2 on t2.id = t1.id
	//							ORDER BY time_stamp DESC LIMIT 96

    $stmt = pdo_query( $pdo, $query, $params ); 
    $result = pdo_fetch_all( $stmt );

	$response['code'] = 'success';
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