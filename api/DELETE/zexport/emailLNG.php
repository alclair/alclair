<?php
include_once "../../config.inc.php";
include_once "../../includes/PHPExcel/Classes/PHPExcel.php";
if(empty($_SESSION["UserId"])&&$_REQUEST["token"]!=$rootScope["SWDApiToken"])
{
    //return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	  
    $params = null;   
    
    $query = "SELECT time_stamp
    					FROM inox_q1
    					ORDER BY time_stamp
    					DESC LIMIT 1";
    
    $stmt = pdo_query( $pdo, $query, null ); 
    $result = pdo_fetch_all( $stmt );
    
    /*echo ($result["time_recorded"]); 
	print $item["time_recorded"]; 
	echo $result["time_recorded"] + 5*3600;
	echo strtotime($result["time_recorded"]);*/
	
	 if (date('I', time()))
				{
					$params[":time_stamp"] = date("Y/m/d H:i:s",strtotime($result["time_stamp"]) - 5 * 3600);		
				}
			else
				{
					$params[":time_stamp"]  = date("Y/m/d H:i:s",strtotime($result["time_stamp"]) - 6 * 3600);
				}
    					

	$query = "SELECT q1.line_pressure as q1_line_pressure, q1.tank_level as q1_tank_level, q1.flow_rate as q1_flow_rate, to_char(q1.time_stamp,'yyyy-MM-dd HH24:MI:SS') as time_recorded, q2.line_pressure as q2_line_pressure, q2.tank_level as q2_tank_level, q2.flow_rate as q2_flow_rate, q3.line_pressure as q3_line_pressure, q3.tank_level as q3_tank_level, q3.flow_rate as q3_flow_rate, q4.line_pressure as q4_line_pressure, q4.tank_level as q4_tank_level, q4.flow_rate as q4_flow_rate, q5.line_pressure as q5_line_pressure, q5.tank_level as q5_tank_level, q5.flow_rate as q5_flow_rate, q6.line_pressure as q6_line_pressure, q6.tank_level as q6_tank_level, q6.flow_rate as q6_flow_rate, 
	q7.line_pressure as q7_line_pressure, q7.tank_level as q7_tank_level, q7.flow_rate as q7_flow_rate, 
	q8.line_pressure as q8_line_pressure, q8.tank_level as q8_tank_level, q8.flow_rate as q8_flow_rate,
	q9.line_pressure as q9_line_pressure, q9.tank_level as q9_tank_level, q9.flow_rate as q9_flow_rate,
	q10.line_pressure as q10_line_pressure, q10.tank_level as q10_tank_level, q10.flow_rate as q10_flow_rate,
	pq31.line_pressure as pq31_line_pressure, pq31.tank_level as pq31_tank_level, pq31.flow_rate as pq31_flow_rate,
	pq33.line_pressure as pq33_line_pressure, pq33.tank_level as pq33_tank_level, pq33.flow_rate as pq33_flow_rate,
	pq34.line_pressure as pq34_line_pressure, pq34.tank_level as pq34_tank_level, pq34.flow_rate as pq34_flow_rate,
	pq39.line_pressure as pq39_line_pressure, pq39.tank_level as pq39_tank_level, pq39.flow_rate as pq39_flow_rate,
	pq66.line_pressure as pq66_line_pressure, pq66.tank_level as pq66_tank_level, pq66.flow_rate as pq66_flow_rate
						FROM inox_q1 as q1
						LEFT JOIN chart_q2 as q2 on q1.id = q2.id
						LEFT JOIN inox_q3 as q3 on q1.id = q3.id
						LEFT JOIN inox_q4 as q4 on q1.id = q4.id
						LEFT JOIN chart_q5 as q5 on q1.id = q5.id
						LEFT JOIN inox_q6 as q6 on q1.id = q6.id
						LEFT JOIN chart_q7 as q7 on q1.id = q7.id
						LEFT JOIN chart_q8 as q8 on q1.id = q8.id
						LEFT JOIN chart_q9 as q9 on q1.id = q9.id
						LEFT JOIN chart_q10 as q10 on q1.id = q10.id
						LEFT JOIN chart_pq31 as pq31 on q1.id = pq31.id
						LEFT JOIN chart_pq33 as pq33 on q1.id = pq33.id
						LEFT JOIN chart_pq34 as pq34 on q1.id = pq34.id
						LEFT JOIN chart_pq39 as pq39 on q1.id = pq39.id
						LEFT JOIN chart_pq66 as pq66 on q1.id = pq66.id
						ORDER BY q1.time_stamp 
						DESC LIMIT 1"; 

/* $query = "SELECT q1.line_pressure as q1_line_pressure, q1.tank_level as q1_tank_level, q1.flow_rate as q1_flow_rate, q1.time_stamp as time_recorded, q2.line_pressure as q2_line_pressure, q2.tank_level as q2_tank_level, q2.flow_rate as q2_flow_rate, q3.line_pressure as q3_line_pressure, q3.tank_level as q3_tank_level, q3.flow_rate as q3_flow_rate, q4.line_pressure as q4_line_pressure, q4.tank_level as q4_tank_level, q4.flow_rate as q4_flow_rate, q5.line_pressure as q5_line_pressure, q5.tank_level as q5_tank_level, q5.flow_rate as q5_flow_rate, q6.line_pressure as q6_line_pressure, q6.tank_level as q6_tank_level, q6.flow_rate as q6_flow_rate
						FROM inox_q1 as q1
						LEFT JOIN chart_q2 as q2 on q1.id = q2.id
						LEFT JOIN inox_q3 as q3 on q1.id = q3.id
						LEFT JOIN inox_q4 as q4 on q1.id = q4.id
						LEFT JOIN chart_q5 as q5 on q1.id = q5.id
						LEFT JOIN inox_q6 as q6 on q1.id = q6.id
						ORDER BY q1.time_stamp 
						DESC LIMIT 1"; */
		
    $stmt = pdo_query( $pdo, $query, null ); 
    $result = pdo_fetch_all( $stmt );		
    
$conditionSql = '';
$conditionSql .= " AND q.time_stamp BETWEEN :subtract_15_minutes AND :today_at_midnight";

$today_at_midnight = strtotime('today midnight');

if (date('I', time()))
	{
		$today_at_midnight = date("m/d/y G:i:s", $today_at_midnight  + 5 * 3600);	
	}
else
	{
		$today_at_midnight = date("m/d/y G:i:s", $today_at_midnight  + 6 * 3600);	
	}


//$today_at_midnight = date("m/d/y G:i:s", $today_at_midnight);	
$subtract_15_minutes = date("m/d/y G:i:s", strtotime('today midnight') - 0.25 * 3600);	    
$params = array();
$params[":today_at_midnight"] = $today_at_midnight;
$params[":subtract_15_minutes"] =$subtract_15_minutes;
    
$query = "SELECT q.line_pressure as q1_line_pressure, q.tank_level as q1_tank_level, q.flow_rate*60 as q1_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q1_time_recorded, t1.volume_gal as q1_volume_gal, q.totalizer as q1_totalizer
						FROM inox_q1 as q
						LEFT JOIN inox_lng_calc as t1 on t1.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q1 = pdo_fetch_all( $stmt );	
if($result_q1[0]["q1_totalizer"] === null) {
	$result_q1_totalizer = 0; }
else {
	$result_q1_totalizer = $result_q1[0]["q1_totalizer"] - $result_q2[1]["q1_totalizer"]; }
	
$query = "SELECT q.line_pressure as q2_line_pressure, q.tank_level as q2_tank_level, q.flow_rate as q2_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q2_time_recorded, t2.volume_gal as q2_volume_gal, q.totalizer as q2_totalizer
						FROM chart_q2 as q
						LEFT JOIN chart_lng_calc as t2 on t2.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params); 
$result_q2 = pdo_fetch_all( $stmt );
$result_q2_totalizer = $result_q2[0]["q2_totalizer"] - $result_q2[1]["q2_totalizer"];

$query = "SELECT q.line_pressure as q3_line_pressure, q.tank_level as q3_tank_level, q.flow_rate*60 as q3_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q3_time_recorded, t3.volume_gal as q3_volume_gal, q.totalizer as q3_totalizer
						FROM inox_q3 as q
						LEFT JOIN inox_lng_calc as t3 on t3.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q3 = pdo_fetch_all( $stmt );
$result_q3_totalizer = $result_q3[0]["q3_totalizer"] - $result_q3[1]["q3_totalizer"];

$query = "SELECT q.line_pressure as q4_line_pressure, q.tank_level as q4_tank_level, q.flow_rate*60 as q4_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q4_time_recorded, t4.volume_gal as q4_volume_gal, q.totalizer as q4_totalizer
						FROM inox_q4 as q
						LEFT JOIN inox_lng_calc as t4 on t4.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q4 = pdo_fetch_all( $stmt );
$result_q4_totalizer = $result_q4[0]["q4_totalizer"] - $result_q4[1]["q4_totalizer"];

$query = "SELECT q.line_pressure as q5_line_pressure, q.tank_level as q5_tank_level, q.flow_rate as q5_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q5_time_recorded, t5.volume_gal as q5_volume_gal, q.totalizer as q5_totalizer
						FROM chart_q5 as q
						LEFT JOIN chart_lng_calc as t5 on t5.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q5 = pdo_fetch_all( $stmt );
$result_q5_totalizer = $result_q5[0]["q5_totalizer"] - $result_q5[1]["q5_totalizer"];
	
$query = "SELECT q.line_pressure as q6_line_pressure, q.tank_level as q6_tank_level, q.flow_rate*60 as q6_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q6_time_recorded, t6.volume_gal as q6_volume_gal, q.totalizer as q6_totalizer
						FROM inox_q6 as q
						LEFT JOIN inox_lng_calc as t6 on t6.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q6 = pdo_fetch_all( $stmt );
$result_q6_totalizer = $result_q6[0]["q6_totalizer"] - $result_q6[1]["q6_totalizer"];

$query = "SELECT q.line_pressure as q7_line_pressure, q.tank_level as q7_tank_level, q.flow_rate as q7_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q7_time_recorded, t7.volume_gal as q7_volume_gal, q.totalizer as q7_totalizer
						FROM chart_q7 as q
						LEFT JOIN chart_lng_calc as t7 on t7.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q7 = pdo_fetch_all( $stmt );
$result_q7_totalizer = $result_q7[0]["q7_totalizer"] - $result_q7[1]["q7_totalizer"];

$query = "SELECT q.line_pressure as q8_line_pressure, q.tank_level as q8_tank_level, q.flow_rate as q8_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q8_time_recorded, t8.volume_gal as q8_volume_gal, q.totalizer as q8_totalizer
						FROM chart_q8 as q
						LEFT JOIN chart_lng_calc as t8 on t8.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params); 
$result_q8 = pdo_fetch_all( $stmt );
$result_q8_totalizer = $result_q8[0]["q8_totalizer"] - $result_q8[1]["q8_totalizer"];

$query = "SELECT q.line_pressure as q9_line_pressure, q.tank_level as q9_tank_level, q.flow_rate as q9_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q9_time_recorded, t9.volume_gal as q9_volume_gal, q.totalizer as q9_totalizer
						FROM chart_q9 as q
						LEFT JOIN chart_lng_calc as t9 on t9.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q9 = pdo_fetch_all( $stmt );
$result_q9_totalizer = $result_q9[0]["q9_totalizer"] - $result_q9[1]["q9_totalizer"];

$query = "SELECT q.line_pressure as q10_line_pressure, q.tank_level as q10_tank_level, q.flow_rate as q10_flow_rate, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as q10_time_recorded, t10.volume_gal as q10_volume_gal, q.totalizer as q10_totalizer
						FROM chart_q10 as q
						LEFT JOIN chart_lng_calc as t10 on t10.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_q10 = pdo_fetch_all( $stmt );
$result_q10_totalizer = $result_q10[0]["q10_totalizer"] - $result_q10[1]["q10_totalizer"];

$query = "SELECT q.line_pressure as pq31_line_pressure, q.tank_level as pq31_tank_level, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as pq31_time_recorded, t31.volume_gal as pq31_volume_gal
						FROM chart_pq31 as q
						LEFT JOIN chart_lng_calc as t31 on t31.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_pq31 = pdo_fetch_all( $stmt );
//$result_pq31_totalizer = $result_pq31[0]["pq31_totalizer"] - $result_pq31[1]["pq34_totalizer"];

$query = "SELECT q.line_pressure as pq33_line_pressure, q.tank_level as pq33_tank_level, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as pq33_time_recorded, t33.volume_gal as pq33_volume_gal
						FROM chart_pq33 as q
						LEFT JOIN chart_lng_calc as t33 on t33.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_pq33 = pdo_fetch_all( $stmt );
//$result_pq33_totalizer = $result_pq33[0]["pq33_totalizer"] - $result_pq33[1]["pq33_totalizer"];

$query = "SELECT q.line_pressure as pq34_line_pressure, q.tank_level as pq34_tank_level, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as pq34_time_recorded, t34.volume_gal as pq34_volume_gal
						FROM chart_pq34 as q
						LEFT JOIN chart_lng_calc as t34 on t34.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_pq34 = pdo_fetch_all( $stmt );
//$result_pq34_totalizer = $result_pq34[0]["pq34_totalizer"] - $result_pq34[1]["pq34_totalizer"];

$query = "SELECT q.line_pressure as pq39_line_pressure, q.tank_level as pq39_tank_level, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as pq39_time_recorded, t39.volume_gal as pq39_volume_gal
						FROM chart_pq39 as q
						LEFT JOIN chart_lng_calc as t39 on t39.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_pq39 = pdo_fetch_all( $stmt );
//$result_pq39_totalizer = $result_pq39[0]["pq39_totalizer"] - $result_pq39[1]["pq34_totalizer"];


$query = "SELECT * FROM queens ORDER BY queens_id";
$stmt = pdo_query($pdo, $query, null);
$result_queen = pdo_fetch_all($stmt);	

$query = "SELECT q.line_pressure as pq66_line_pressure, q.tank_level as pq66_tank_level, to_char(q.time_stamp,'yyyy-MM-dd HH24:MI:SS') as pq66_time_recorded, t66.volume_gal as pq66_volume_gal
						FROM chart_pq66 as q
						LEFT JOIN chart_lng_calc as t66 on t66.level_h2o = ROUND(q.tank_level*2, 0)/2
						WHERE 1=1 $conditionSql
						ORDER BY q.time_stamp 
						DESC LIMIT 2"; 
$stmt = pdo_query( $pdo, $query, $params ); 
$result_pq66 = pdo_fetch_all( $stmt );
//$result_pq66_totalizer = $result_pq66[0]["pq66_totalizer"] - $result_pq66[1]["pq66_totalizer"];

$query = "SELECT * FROM queens ORDER BY queens_id";
$stmt = pdo_query($pdo, $query, null);
$result_queen = pdo_fetch_all($stmt);	

if (date('I', time()))
	{
		$result_q1[0]["q1_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q1[0]["q1_time_recorded"]) - 5 * 3600);
		$result_q2[0]["q2_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q2[0]["q2_time_recorded"]) - 5 * 3600);
		$result_q3[0]["q3_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q3[0]["q3_time_recorded"]) - 5 * 3600);
		$result_q4[0]["q4_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q4[0]["q4_time_recorded"]) - 5 * 3600);
		$result_q5[0]["q5_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q5[0]["q5_time_recorded"]) - 5 * 3600);
		$result_q6[0]["q6_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q6[0]["q6_time_recorded"]) - 5 * 3600);
		$result_q7[0]["q7_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q7[0]["q7_time_recorded"]) - 5 * 3600);
		$result_q8[0]["q8_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q8[0]["q8_time_recorded"]) - 5 * 3600);
		$result_q9[0]["q9_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q9[0]["q9_time_recorded"]) - 5 * 3600);
		$result_q10[0]["q10_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q10[0]["q10_time_recorded"]) - 5 * 3600);
		$result_pq31[0]["pq31_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq31[0]["pq31_time_recorded"]) - 5 * 3600);
		$result_pq33[0]["pq33_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq33[0]["pq33_time_recorded"]) - 5 * 3600);
		$result_pq34[0]["pq34_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq34[0]["pq34_time_recorded"]) - 5 * 3600);
		$result_pq39[0]["pq39_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq39[0]["pq39_time_recorded"]) - 5 * 3600);
		$result_pq66[0]["pq66_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq66[0]["pq66_time_recorded"]) - 5 * 3600);
	}
else
	{
		$result_q1[0]["q1_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q1[0]["q1_time_recorded"]) - 6 * 3600);
		$result_q2[0]["q2_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q2[0]["q2_time_recorded"]) - 6 * 3600);
		$result_q3[0]["q3_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q3[0]["q3_time_recorded"]) - 6 * 3600);
		$result_q4[0]["q4_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q4[0]["q4_time_recorded"]) - 6 * 3600);
		$result_q5[0]["q5_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q5[0]["q5_time_recorded"]) - 6 * 3600);
		$result_q6[0]["q6_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q6[0]["q6_time_recorded"]) - 6 * 3600);
		$result_q7[0]["q7_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q7[0]["q7_time_recorded"]) - 6 * 3600);
		$result_q8[0]["q8_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q8[0]["q8_time_recorded"]) - 6 * 3600);
		$result_q9[0]["q9_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q9[0]["q9_time_recorded"]) - 6 * 3600);
		$result_q10[0]["q10_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_q10[0]["q10_time_recorded"]) - 6 * 3600);	
		$result_pq31[0]["pq31_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq31[0]["pq31_time_recorded"]) - 6 * 3600);	
		$result_pq33[0]["pq33_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq33[0]["pq33_time_recorded"]) - 6 * 3600);	
		$result_pq34[0]["pq34_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq34[0]["pq34_time_recorded"]) - 6 * 3600);	
		$result_pq39[0]["pq39_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq39[0]["pq39_time_recorded"]) - 6 * 3600);	
		$result_pq66[0]["pq66_time_recorded"] = date("Y/m/d H:i:s", strtotime($result_pq66[0]["pq66_time_recorded"]) - 6 * 3600);	}

//dailyops@northdakotalng.com
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0)->setTitle("Queens Report");
    $objPHPExcel->setActiveSheetIndex(0)
			    ->setCellValue("A1",$result_queen[0]["name"])    	
                //->setCellValue("A1","INOX Q1 - Not in field")    		
                ->setCellValue("A2","Last Reading")
                ->setCellValue("B2","Line Pressure")
                ->setCellValue("C2","Tank Level")   
                ->setCellValue("D2","Flow Rate")
                ->setCellValue("E2","Totalizer")
                                
                ->setCellValue("A4",$result_queen[1]["name"])
                //->setCellValue("A4","Chart Q2 - Nabors X-27")    		
                ->setCellValue("A5","Last Reading")
                ->setCellValue("B5","Line Pressure")
                ->setCellValue("C5","Tank Level")   
                ->setCellValue("D5","Flow Rate")                    
                ->setCellValue("E5","Totalizer")
                                
                ->setCellValue("A7",$result_queen[2]["name"])    	
                //->setCellValue("A7","INOX Q3 - Nabors B-18")    		
                ->setCellValue("A8","Last Reading")
                ->setCellValue("B8","Line Pressure")
                ->setCellValue("C8","Tank Level")   
                ->setCellValue("D8","Flow Rate")  
                ->setCellValue("E8","Totalizer")
                                
                ->setCellValue("A10",$result_queen[3]["name"])  
                //->setCellValue("A10","INOX Q4 - Patterson 286")    		
                ->setCellValue("A11","Last Reading")
                ->setCellValue("B11","Line Pressure")
                ->setCellValue("C11","Tank Level")   
                ->setCellValue("D11","Flow Rate")  
                ->setCellValue("E11","Totalizer")

                ->setCellValue("A13",$result_queen[4]["name"])  
                //->setCellValue("A13","Chart Q5 - H & P 640")    		
                ->setCellValue("A14","Last Reading")
                ->setCellValue("B14","Line Pressure")
                ->setCellValue("C14","Tank Level")   
                ->setCellValue("D14","Flow Rate")  
                ->setCellValue("E14","Totalizer")
                
                ->setCellValue("A16",$result_queen[5]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A17","Last Reading")
                ->setCellValue("B17","Line Pressure")
                ->setCellValue("C17","Tank Level")   
                ->setCellValue("D17","Flow Rate")      
                ->setCellValue("E17","Totalizer")
                
                ->setCellValue("A19",$result_queen[6]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A20","Last Reading")
                ->setCellValue("B20","Line Pressure")
                ->setCellValue("C20","Tank Level")   
                ->setCellValue("D20","Flow Rate")
                ->setCellValue("E20","Totalizer")
                
                ->setCellValue("A22",$result_queen[7]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A23","Last Reading")
                ->setCellValue("B23","Line Pressure")
                ->setCellValue("C23","Tank Level")   
                ->setCellValue("D23","Flow Rate") 
                ->setCellValue("E23","Totalizer")
                
                ->setCellValue("A25",$result_queen[8]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A26","Last Reading")
                ->setCellValue("B26","Line Pressure")
                ->setCellValue("C26","Tank Level")   
                ->setCellValue("D26","Flow Rate")
                ->setCellValue("E26","Totalizer")
                
                ->setCellValue("A28",$result_queen[9]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A29","Last Reading")
                ->setCellValue("B29","Line Pressure")
                ->setCellValue("C29","Tank Level")   
                ->setCellValue("D29","Flow Rate")
                ->setCellValue("E29","Totalizer")             
                
                ->setCellValue("A31",$result_queen[12]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A32","Last Reading")
                ->setCellValue("B32","Line Pressure")
                ->setCellValue("C32","Tank Level")   
                ->setCellValue("D32","Flow Rate")
                ->setCellValue("E32","Totalizer")             
                
                ->setCellValue("A34",$result_queen[15]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A35","Last Reading")
                ->setCellValue("B35","Line Pressure")
                ->setCellValue("C35","Tank Level")   
                ->setCellValue("D35","Flow Rate")
                ->setCellValue("E35","Totalizer")     
                
                 ->setCellValue("A37",$result_queen[16]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A38","Last Reading")
                ->setCellValue("B38","Line Pressure")
                ->setCellValue("C38","Tank Level")   
                ->setCellValue("D38","Flow Rate")
                ->setCellValue("E38","Totalizer")  
                
                 ->setCellValue("A40",$result_queen[17]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A41","Last Reading")
                ->setCellValue("B41","Line Pressure")
                ->setCellValue("C41","Tank Level")   
                ->setCellValue("D41","Flow Rate")
                ->setCellValue("E41","Totalizer")
                
                 ->setCellValue("A43",$result_queen[14]["name"])  
                //->setCellValue("A16","INOX Q6 - Nabors B-22")    		
                ->setCellValue("A44","Last Reading")
                ->setCellValue("B44","Line Pressure")
                ->setCellValue("C44","Tank Level")   
                ->setCellValue("D44","Flow Rate")
                ->setCellValue("E44","Totalizer");                                                                             
   
    $objPHPExcel->setActiveSheetIndex(0)->getStyle("A1:O50")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(35);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
	
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A7')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A10')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A13')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A16')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A19')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A25')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A28')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A31')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A34')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A37')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A40')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
	$objPHPExcel->getActiveSheet()->getStyle('A43')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('000000');
		
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A4')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A7')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A10')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A13')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A16')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A19')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A22')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A25')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A28')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A31')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A34')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A37')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A40')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');
	$objPHPExcel->getActiveSheet()->getStyle('A43')->getFont()->setBold(true)->getColor()->setRGB('FFFFFF');

		
	$objPHPExcel->getActiveSheet()->getStyle("A1:O50")->getFont()->setSize(16);
//dailyops@northdakotalng.com
//foreach($result_q1 as $item)
   {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q1["q1_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A3", $result_q1[0]["q1_time_recorded"])
            ->setCellValue("B3",$result_q1[0]["q1_line_pressure"].' psi')
            ->setCellValue("C3",$result_q1[0]["q1_volume_gal"].' LNGg')
            ->setCellValue("D3",$result_q1[0]["q1_flow_rate"].' scfh')
            ->setCellValue("E3",$result_q1_totalizer.' LNGg');
	}
//foreach($result_q2 as $item)
    {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q2["q2_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A6", $result_q2[0]["q2_time_recorded"]) 
            ->setCellValue("B6",$result_q2[0]["q2_line_pressure"].' psi')
            ->setCellValue("C6",$result_q2[0]["q2_volume_gal"].' LNGg')
            ->setCellValue("D6",$result_q2[0]["q2_flow_rate"].' scfh')
            ->setCellValue("E6",$result_q2_totalizer.' LNGg');
	}
//foreach($result_q3 as $item)
   {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q3["q3_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A9", $result_q3[0]["q3_time_recorded"]) 
            ->setCellValue("B9",$result_q3[0]["q3_line_pressure"].' psi')
            ->setCellValue("C9",$result_q3[0]["q3_volume_gal"].' LNGg')
            ->setCellValue("D9",$result_q3[0]["q3_flow_rate"].' scfh')
           ->setCellValue("E9",$result_q3_totalizer.' LNGg');
	}
//foreach($result_q4 as $item)
   {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q4["q4_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A12", $result_q4[0]["q4_time_recorded"]) 
            ->setCellValue("B12",$result_q4[0]["q4_line_pressure"].' psi')
            ->setCellValue("C12",$result_q4[0]["q4_volume_gal"].' LNGg')
            ->setCellValue("D12",$result_q4[0]["q4_flow_rate"].' scfh')
            ->setCellValue("E12",$result_q4_totalizer.' LNGg');
	}  
	//foreach($result_q5 as $item)
    {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q5["q5_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A15", $result_q5[0]["q5_time_recorded"]) 
            ->setCellValue("B15",$result_q5[0]["q5_line_pressure"].' psi')
            ->setCellValue("C15",$result_q5[0]["q5_volume_gal"].' LNGg')
            ->setCellValue("D15",$result_q5[0]["q5_flow_rate"].' scfh')
            ->setCellValue("E15",$result_q5_totalizer.' LNGg');
	}
	//foreach($result_q6 as $item)
    {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q6["q6_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A18", $result_q6[0]["q6_time_recorded"]) 
            ->setCellValue("B18",$result_q6[0]["q6_line_pressure"].' psi')
            ->setCellValue("C18",$result_q6[0]["q6_volume_gal"].' LNGg')
            ->setCellValue("D18",$result_q6[0]["q6_flow_rate"].' scfh')
            ->setCellValue("E18",$result_q6_totalizer.' LNGg');
	}
	//foreach($result_q7 as $item)
    {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q7["q7_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A21", $result_q7[0]["q7_time_recorded"]) 
            ->setCellValue("B21",$result_q7[0]["q7_line_pressure"].' psi')
            ->setCellValue("C21",$result_q7[0]["q7_volume_gal"].' LNGg')
            ->setCellValue("D21",$result_q7[0]["q7_flow_rate"].' scfh')
            ->setCellValue("E21",$result_q7_totalizer.' LNGg');
	}
	//foreach($result_q8 as $item)
    {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q8["q8_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A24", $result_q8[0]["q8_time_recorded"]) 
            ->setCellValue("B24",$result_q8[0]["q8_line_pressure"].' psi')
            ->setCellValue("C24",$result_q8[0]["q8_volume_gal"].' LNGg')
            ->setCellValue("D24",$result_q8[0]["q8_flow_rate"].' scfh')
            ->setCellValue("E24",$result_q8_totalizer.' LNGg');
	}
	//foreach($result_q9 as $item)
    {	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q9["q9_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A27", $result_q9[0]["q9_time_recorded"]) 
            ->setCellValue("B27",$result_q9[0]["q9_line_pressure"].' psi')
            ->setCellValue("C27",$result_q9[0]["q9_volume_gal"].' LNGg')
            ->setCellValue("D27",$result_q9[0]["q9_flow_rate"].' scfh')
            ->setCellValue("E27",$result_q9_totalizer.' LNGg');
	}
	
	{	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_q10["q10_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A30", $result_q10[0]["q10_time_recorded"]) 
            ->setCellValue("B30", $result_q10[0]["q10_line_pressure"].' psi')
            ->setCellValue("C30", $result_q10[0]["q10_volume_gal"].' LNGg')
            ->setCellValue("D30", $result_q10[0]["q10_flow_rate"].' scfh')
            ->setCellValue("E30", $result_q10_totalizer.' LNGg');
	}
	
	{	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_pq34["pq34_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A33", $result_pq34[0]["pq34_time_recorded"]) 
            ->setCellValue("B33", $result_pq34[0]["pq34_line_pressure"].' psi')
            ->setCellValue("C33", $result_pq34[0]["pq34_volume_gal"].' LNGg');
            //->setCellValue("D33", $result_pq34[0]["pq34_flow_rate"].' scfh');
            //->setCellValue("E33", $result_pq34_totalizer.' LNGg');
	}
	
	{	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_pq66["pq66_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A36", $result_pq66[0]["pq66_time_recorded"]) 
            ->setCellValue("B36", $result_pq66[0]["pq66_line_pressure"].' psi')
            ->setCellValue("C36", $result_pq66[0]["pq66_volume_gal"].' LNGg');
            //->setCellValue("D36", $result_pq66[0]["pq66_flow_rate"].' scfh');
            //->setCellValue("E36", $result_pq66_totalizer.' LNGg');
	}
	
	{	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_pq31["pq31_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A39", $result_pq31[0]["pq31_time_recorded"]) 
            ->setCellValue("B39", $result_pq31[0]["pq31_line_pressure"].' psi')
            ->setCellValue("C39", $result_pq31[0]["pq31_volume_gal"].' LNGg');
            //->setCellValue("D33", $result_pq34[0]["pq34_flow_rate"].' scfh');
            //->setCellValue("E33", $result_pq34_totalizer.' LNGg');
	}
	{	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_pq33["pq33_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A42", $result_pq33[0]["pq33_time_recorded"]) 
            ->setCellValue("B42", $result_pq33[0]["pq33_line_pressure"].' psi')
            ->setCellValue("C42", $result_pq33[0]["pq33_volume_gal"].' LNGg');
            //->setCellValue("D33", $result_pq34[0]["pq34_flow_rate"].' scfh');
            //->setCellValue("E33", $result_pq34_totalizer.' LNGg');
	}
	{	    
		$UTC = new DateTimeZone("UTC");
		$newTZ = new DateTimeZone("America/Chicago");
		$date = new DateTime($result_pq39["pq39_time_recorded"], $UTC );
		$date->setTimezone( $newTZ );
		
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue("A45", $result_pq39[0]["pq39_time_recorded"]) 
            ->setCellValue("B45", $result_pq39[0]["pq39_line_pressure"].' psi')
            ->setCellValue("C45", $result_pq39[0]["pq39_volume_gal"].' LNGg');
            //->setCellValue("D33", $result_pq34[0]["pq34_flow_rate"].' scfh');
            //->setCellValue("E33", $result_pq34_totalizer.' LNGg');
	}
	//dailyops@northdakotalng.com
        //$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel,"Excel2007");
    //$filename = "Export-Queen-Readings(".str_replace("/","-",$startdate).")-To(".str_replace("/","-",$enddate).")-".time().".xlsx";
    //$filename = "Export-Queen-Readings.xlsx";
    $filename = "Export-Queen-Readings-".date("m-d-Y").".xlsx";

    //$filename = "hello.xlsx";
    $objWriter->save("../../data/export/$filename");
	
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