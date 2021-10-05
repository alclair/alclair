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
    $conditionSql = "";
    $conditionSql2 = "";
    $conditionSql_printed = "";
    $pagingSql = "";
    $orderBySqlDirection = "ASC";
    $orderBySql = " ORDER BY repair_id $orderBySqlDirection";
    $params = array();
    $params2 = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

   
    if ($_REQUEST['RUSH_OR_NOT'] == 1) {
			$conditionSql .=" AND (t1.rush_process = 'Yes')";
			$conditionSql .= " AND (t1.repair_status_id != 14)";
			//$params[":OrderStatusID"] = $_REQUEST['repair_status_id']; 
    }
    if ($_REQUEST['REMOVE_HEARING_PROTECTION'] == 1) {
			//$conditionSql .=" AND (t1.hearing_protection != TRUE)";
			$conditionSql .=" AND ( (t1.hearing_protection != TRUE AND t1.model IS NOT NULL) OR (t1.hearing_protection != TRUE AND t1.model IS NULL) )";
			$conditionSql .= " AND (t1.repair_status_id != 12)";
			//$params[":OrderStatusID"] = $_REQUEST['repair_status_id']; 
    }
    if ($_REQUEST['monitor_id'] > 0 ) {
		$stmt = pdo_query( $pdo, "SELECT name FROM monitors WHERE id = :monitor_id", array(":monitor_id"=>$_REQUEST['monitor_id'] ));
		$name_of_monitor = pdo_fetch_array( $stmt );
		
		$conditionSql .= " AND t1.monitor_id = :monitor_id";
		$params[":monitor_id"] = $_REQUEST['monitor_id'];
    }
    if ($_REQUEST['repair_status_id'] > 0 ) {
		//$stmt = pdo_query( $pdo, "SELECT name FROM monitors WHERE id = :monitor_id", array(":monitor_id"=>$_REQUEST['order_status_id'] ));
		//$order_status_id = pdo_fetch_array( $stmt );

		$conditionSql .= " AND t1.repair_status_id = :repair_status_id";
		$params[":repair_status_id"] = $_REQUEST['repair_status_id'];
		
    }

	    
	    $today=getdate(date("U"));
		$date_4_sql = $today['m'] . "/". $today['mday'] . "/" . $today['year'];
		$today_4_sql = date("m/d/Y");
		
		$tomorrow_4_sql = date('m/d/Y', strtotime('+1 days'));
		$nextWeek_4_sql_3_days = date('m/d/Y', strtotime('+3 days'));
		$nextWeek_4_sql = date('m/d/Y', strtotime('+7 days'));
		$threeWeeks_4_sql = date('m/d/Y', strtotime('+21 days'));
		$sixWeeks_4_sql = date('m/d/Y', strtotime('+42 days'));
		
		$twoWeeks_4_sql = date('m/d/Y', strtotime('+14 days'));
		$fourWeeks_4_sql = date('m/d/Y', strtotime('+28 days'));
		$fiveWeeks_4_sql = date('m/d/Y', strtotime('+35 days'));
		
		//if(!empty($_REQUEST["StartDate"])) {
		
/*		
		//if( strlen($_REQUEST['TODAY_OR_NEXT_WEEK']) == 17) {   // , 'Today and Tomorrow') ) {
		if( $_REQUEST['TODAY_OR_NEXT_WEEK'] == '1') {   // MEANS TODAY
			$conditionSql.=" and (t1.estimated_ship_date = :Date) AND t1.repair_status_id != 12 ";
			$params[":Date"]=  $today_4_sql;
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '0') { // MEANS PAST DUE
			$conditionSql.=" and (t1.estimated_ship_date < :Date) AND t1.repair_status_id != 12 ";
			$params[":Date"]= $today_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '2') { // MEANS TOMORROW
			$conditionSql.=" and (t1.estimated_ship_date = :Date) AND t1.repair_status_id != 12 ";
			$params[":Date"]= $tomorrow_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '3') { // MEANS NEXT 7 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 12 ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $nextweek_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '4') { // MEANS NEXT 21 CALENDAR DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 12 ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $threeWeeks_4_sql;	
		} else {  // NEXT 30 BUSINESS DAYS
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 12 ";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $sixWeeks_4_sql;	
		}
*/

	//if( strlen($_REQUEST['TODAY_OR_NEXT_WEEK']) == 17) {   // , 'Today and Tomorrow') ) {
		if( $_REQUEST['TODAY_OR_NEXT_WEEK'] == '1') {   // MEANS TODAY
			//$conditionSql.=" and (t1.estimated_ship_date = :Date) AND t1.repair_status_id != 12 ";
			// EVERYTHING BEFORE CASING - CASING IS STATUS 5
			//$conditionSql.=" and (t1.estimated_ship_date = :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date = :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Date"]=  $today_4_sql;
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '0') { // MEANS PAST DUE
			//$conditionSql.=" and (t1.estimated_ship_date < :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date < :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Date"]= $today_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '2') { // MEANS TOMORROW
			//$conditionSql.=" and (t1.estimated_ship_date = :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date = :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Date"]= $tomorrow_4_sql;	
		
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '8') { // MEANS NEXT 3 CALENDAR DAYS
			//$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $nextWeek_4_sql_3_days;	
			
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '3') { // MEANS NEXT 7 CALENDAR DAYS
			//$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $nextWeek_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '4') { // MEANS NEXT 14 CALENDAR DAYS
			//$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $twoWeeks_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '5') { // MEANS NEXT 21 CALENDAR DAYS
			//$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $threeWeeks_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '6') { // MEANS NEXT 28 CALENDAR DAYS
			//$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $fourWeeks_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '7') { // MEANS NEXT 35 CALENDAR DAYS
			//$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Today"] = $today_4_sql;
			$params[":Date"]= $fiveWeeks_4_sql;	
		}  else {  // NEXT 42 CALENDAR DAYS
			//$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND t1.repair_status_id != 14";
			$conditionSql.=" and (t1.estimated_ship_date > :Today) AND (t1.estimated_ship_date <= :Date) AND (t1.repair_status_id != 14 AND t1.repair_status_id != 12 AND t1.repair_status_id != 13 AND t1.repair_status_id != 15 AND t1.repair_status_id != 16 AND t1.repair_status_id != 18)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $sixWeeks_4_sql;	
		}
    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM repair_form AS t1
    					WHERE 1=1 AND t1.active = TRUE $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
     if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
    
    //Get Total Passed
    $query = "SELECT count(t1.id) FROM repair_form AS t1
    					WHERE 1=1 AND t1.active = TRUE $conditionSql";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    

    $response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];

        $query = "SELECT t1.*, to_char(t1.date_entered,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, IEMs.id AS monitor_id, IEMs.name AS model, t2.status_of_repair
                  FROM repair_form AS t1
                  LEFT JOIN monitors AS IEMs ON t1.monitor_id = IEMs.id
                  LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair
                  WHERE 1=1 AND t1.active = TRUE $conditionSql ORDER BY t1.estimated_ship_date"; // $orderBySql $pagingSql";
                  
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $response["test"] = $rows_in_result;
    //echo json_encode($response);
    //exit;
    // CALCULATE NUMBER OF DAYS PAST DUE
    // DETERMINE NUMBER OF QC FORMS AND STATUS OF INITIAL PASS
    $workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
	$holidayDays = array('*-12-25', '*-01-01', '2013-12-23'); # variable and fixed holidays
    for ($i = 0; $i < $rows_in_result; $i++) {
	    		
	    	$to = $today_4_sql;
			$from = $result[$i]["estimated_ship_date"];
			$from = new DateTime($from);
			//$from->modify('+1 day');
			$to = new DateTime($to);
			//$to->modify('+1 day');
			$interval = new DateInterval('P1D');
			$periods = new DatePeriod($from, $interval, $to);

			$days = 0;
			foreach ($periods as $period) {
        		//if (!in_array($period->format('N'), $workingDays)) continue;
				//if (in_array($period->format('Y-m-d'), $holidayDays)) continue;
				//if (in_array($period->format('*-m-d'), $holidayDays)) continue;
				$days++;
			}				
			$result[$i]["days_past_due"] = $days;
			
			// 09-29-20 - FINDING DATE OF LAST SCAN
			$stmt = pdo_query( $pdo, "SELECT *, to_char(t1.date, 'MM/dd/yy') AS date_of_last_scan FROM repair_status_log AS t1
	       LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair 
	       WHERE repair_form_id = :repair_form_id ORDER BY date DESC", array(":repair_form_id"=>$result[$i]["id"]));
	    	$result3 = pdo_fetch_all($stmt);     
	    	$result[$i]["date_of_last_scan"] = $result3[0]["date_of_last_scan"];
			
			$query = pdo_query($pdo, "SELECT * FROM qc_form WHERE id_of_repair = :id_of_repair", array(":id_of_repair"=>$result[$i]["id"]));
			$output = pdo_fetch_all($query);
			$result[$i]["num_of_qc_forms"] = pdo_rows_affected($query);
			
			for ($k = 0; $k < $result[$i]["num_of_qc_forms"]; $k++) {
				if($output[$k]["build_type_id"] == 2 ) {
					//if($output[$k]["pass_or_fail"] != "PASS") {
						$result[$i]["pass_or_fail"] = $output[$k]["pass_or_fail"];
					//} else {
					//	$result[$i]["pass_or_fail"] = $output[$k]["pass_or_fail"]; 
					//}
				}
			}
			
	}
	
function array_orderby(&$array,$orderby = null,$order = 'desc',$children = false) {
  if($orderby == null)
    return $array;
  $key_value = $new_array = array();
  foreach($array as $k => $v) {
    $key_value[$k] = $v[$orderby];
  }
  if($order == 'asc') {
    asort($key_value);
  }
  else {
    arsort($key_value);
  }
  reset($key_value);
  foreach($key_value as $k => $v) {
    $new_array[$k] = $array[$k];
    // 如果有children
    if($children && isset($new_array[$k][$children])) {
      $new_array[$k][$children] = array_orderby($new_array[$k][$children],$orderby,$order,$children);
    }
  }
  $new_array = array_values($new_array); // 使键名为0,1,2,3...
  $array = $new_array;
  return $new_array;
}

$result = array_orderby($result, 'days_past_due', SORT_ASC);

//$response["test"] = $a;
//echo json_encode($response);
//exit;

    
    $query2 = "SELECT *, to_char(date, 'MM/dd/yyyy HH24:MI') as date_moved, t2.status_of_repair, t3.first_name, t3.last_name
    					FROM repair_status_log 
    					LEFT JOIN repair_status_table AS t2 ON repair_status_log.repair_status_id = t2.order_in_repair
    					LEFT JOIN auth_user AS t3 ON repair_status_log.user_id = t3.id
    					WHERE repair_form_id = :repair_form_id
    					ORDER BY date_moved DESC";
    $params2[":repair_form_id"] = $_REQUEST['id'];
    $stmt2 = pdo_query( $pdo, $query2, $params2); 
	$result2 = pdo_fetch_all( $stmt2 );  
	$rows_in_result2 = pdo_rows_affected($stmt2);

	
    for ($i = 0; $i < $rows_in_result2; $i++) {
    	if (date('I', time()))
		{	
			$result2[$i]["date_to_show"] = date("m/d/Y  h:i A",strtotime($result2[$i]["date_moved"]) - 5 * 3600);
			$result2[$i]["date_to_show_date"] = date("m/d/Y",strtotime($result2[$i]["date_moved"]) - 5 * 3600);
			$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) - 5 * 3600);
		}
		else
		{
			$result2[$i]["date_to_show"] = date("m/d/Y  h:i A",strtotime($result2[$i]["date_moved"]) - 6 * 3600);
			$result2[$i]["date_to_show_date"] = date("m/d/Y ",strtotime($result2[$i]["date_moved"]) - 6 * 3600);
			$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) - 6 * 3600);
		}
	}
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    $response['data2'] = $result2;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>