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
			$query =   "SELECT ROUND(t1.tank_level*2, 0)/2 as inox_q1, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as inox_q1_volume_gal, row_number() over() row_num_1 FROM inox_q1 as t1
								LEFT JOIN inox_lng_calc as t2 on t2.level_h2o = ROUND(t1.tank_level*2, 0)/2
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 2) {
			$query =   "SELECT tank_level as chart_q2, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_q2_volume_gal, row_number() over() row_num_1 FROM chart_q2 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 3) {
			$query =   "SELECT ROUND(t1.tank_level*2, 0)/2 as inox_q3, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as inox_q3_volume_gal, row_number() over() row_num_1 FROM inox_q3 as t1
								LEFT JOIN inox_lng_calc as t2 on t2.level_h2o = ROUND(t1.tank_level*2, 0)/2
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 0) {
			$query =   "SELECT t1.tank_level as inox_q4, t2.tank_level as chart_q5, t3.tank_level as chart_q2, t4.tank_level  as inox_q1, t5.tank_level as inox_q3, t6.tank_level as inox_q6, t13.tank_level as chart_q7, t14.tank_level as chart_q8, t15.tank_level as chart_q9, t19.tank_level as chart_q10, t21.tank_level as chart_pq33, t23.tank_level as chart_pq34, t25.tank_level as chart_pq66, t27.tank_level as chart_pq31, t29.tank_level as chart_pq39, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, 
			t7.volume_gal as inox_q1_volume_gal, 
			t8.volume_gal as chart_q2_volume_gal, 
			t9.volume_gal as inox_q3_volume_gal,  
			t10.volume_gal as inox_q4_volume_gal, 
			t11.volume_gal as chart_q5_volume_gal, 
			t12.volume_gal as inox_q6_volume_gal, 
			t16.volume_gal as chart_q7_volume_gal,
			t17.volume_gal as chart_q8_volume_gal,
			t18.volume_gal as chart_q9_volume_gal,
			t20.volume_gal as chart_q10_volume_gal,
			t22.volume_gal as chart_pq33_volume_gal,
			t24.volume_gal as chart_pq34_volume_gal,
			t26.volume_gal as chart_pq66_volume_gal,
			t28.volume_gal as chart_pq31_volume_gal,
			t30.volume_gal as chart_pq39_volume_gal,
			
			row_number() over() row_num_1 FROM inox_q4 as t1
								LEFT JOIN chart_q5 as t2 on t2.time_stamp = t1.time_stamp
								LEFT JOIN chart_q2 as t3 on t3.time_stamp = t1.time_stamp
								LEFT JOIN inox_q1 as t4 on t4.time_stamp = t1.time_stamp
								LEFT JOIN inox_q3 as t5 on t5.time_stamp = t1.time_stamp
								LEFT JOIN inox_q6 as t6 on t6.time_stamp = t1.time_stamp
								LEFT JOIN chart_q7 as t13 on t13.time_stamp = t1.time_stamp
								LEFT JOIN chart_q8 as t14 on t14.time_stamp = t1.time_stamp
								LEFT JOIN chart_q9 as t15 on t15.time_stamp = t1.time_stamp
								LEFT JOIN chart_q10 as t19 on t19.time_stamp = t1.time_stamp
								LEFT JOIN chart_pq33 as t21 on t21.time_stamp = t1.time_stamp
								LEFT JOIN chart_pq34 as t23 on t23.time_stamp = t1.time_stamp
								LEFT JOIN chart_pq66 as t25 on t25.time_stamp = t1.time_stamp
								LEFT JOIN chart_pq31 as t27 on t27.time_stamp = t1.time_stamp
								LEFT JOIN chart_pq39 as t29 on t29.time_stamp = t1.time_stamp
								
								LEFT JOIN inox_lng_calc as t7 on t7.level_h2o = ROUND(t4.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t8 on t8.level_h2o = ROUND(t3.tank_level*2, 0)/2
								LEFT JOIN inox_lng_calc as t9 on t9.level_h2o = ROUND(t5.tank_level*2, 0)/2
								LEFT JOIN inox_lng_calc as t10 on t10.level_h2o = ROUND(t1.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t11 on t11.level_h2o = ROUND(t2.tank_level*2, 0)/2
								LEFT JOIN inox_lng_calc as t12 on t12.level_h2o = ROUND(t6.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t16 on t16.level_h2o = ROUND(t13.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t17 on t17.level_h2o = ROUND(t14.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t18 on t18.level_h2o = ROUND(t15.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t20 on t20.level_h2o = ROUND(t19.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t22 on t22.level_h2o = ROUND(t21.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t24 on t24.level_h2o = ROUND(t23.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t26 on t26.level_h2o = ROUND(t25.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t28 on t28.level_h2o = ROUND(t27.tank_level*2, 0)/2
								LEFT JOIN chart_lng_calc as t30 on t30.level_h2o = ROUND(t29.tank_level*2, 0)/2
								
								WHERE 1 = 1 $conditionSql
								ORDER BY t1.time_stamp DESC";
	}
	elseif ($lng_queens == 4) {
			$query =   "SELECT ROUND(t1.tank_level*2, 0)/2 as inox_q4, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp,  t2.volume_gal as inox_q4_volume_gal, row_number() over() row_num_1 FROM inox_q4 as t1
								LEFT JOIN inox_lng_calc as t2 on t2.level_h2o = ROUND(t1.tank_level*2, 0)/2
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 5) {
			$query =   "SELECT t1.tank_level as chart_q5, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_q5_volume_gal, row_number() over() row_num_1 FROM chart_q5 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 6) {
			$query =   "SELECT ROUND(t1.tank_level*2, 0)/2 as inox_q6, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as inox_q6_volume_gal, row_number() over() row_num_1 FROM inox_q6 as t1
								LEFT JOIN inox_lng_calc as t2 on t2.level_h2o = ROUND(t1.tank_level*2, 0)/2
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 7) {
			$query =   "SELECT t1.tank_level as chart_q7, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_q7_volume_gal, row_number() over() row_num_1 FROM chart_q7 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 8) {
			$query =   "SELECT t1.tank_level as chart_q8, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_q8_volume_gal, row_number() over() row_num_1 FROM chart_q8 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 9) {
			$query =   "SELECT t1.tank_level as chart_q9, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_q9_volume_gal, row_number() over() row_num_1 FROM chart_q9 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 10) {
			$query =   "SELECT t1.tank_level as chart_q10, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_q10_volume_gal, row_number() over() row_num_1 FROM chart_q10 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 18) {
			$query =   "SELECT t1.tank_level as chart_pq33, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_pq33_volume_gal, row_number() over() row_num_1 FROM chart_pq33 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
		elseif ($lng_queens == 13) {
			$query =   "SELECT t1.tank_level as chart_pq34, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_pq34_volume_gal, row_number() over() row_num_1 FROM chart_pq34 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 16) {
			$query =   "SELECT t1.tank_level as chart_pq66, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_pq66_volume_gal, row_number() over() row_num_1 FROM chart_pq66 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 17) {
			$query =   "SELECT t1.tank_level as chart_pq31, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_pq31_volume_gal, row_number() over() row_num_1 FROM chart_pq31 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
								WHERE 1 = 1 $conditionSql
								ORDER BY time_stamp DESC";
	}
	elseif ($lng_queens == 15) {
			$query =   "SELECT t1.tank_level as chart_pq39, to_char(t1.time_stamp, 'yyyy-MM-dd HH24:MI') as time_stamp, t2.volume_gal as chart_pq39_volume_gal, row_number() over() row_num_1 FROM chart_pq39 as t1
								LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = t1.tank_level
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