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
    $orderBySql = " ORDER BY order_id $orderBySqlDirection";
    $params = array();
    $params2 = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

   
    if ($_REQUEST['RUSH_OR_NOT'] == 1) {
			$conditionSql .=" AND (t1.rush_process = 'Yes')";
			$conditionSql .= " AND (t1.order_status_id != 12)";
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    if ($_REQUEST['REMOVE_HEARING_PROTECTION'] == 1) {
			//$conditionSql .=" AND (t1.hearing_protection != TRUE)";
			//$conditionSql .=" AND ( (t1.hearing_protection != TRUE AND t1.model IS NOT NULL) OR (t1.hearing_protection != TRUE AND t1.model IS NULL) )";
			$conditionSql .=" AND ( t1.model != 'AHP' AND t1.model != 'SHP' AND t1.model != 'EXP PRO' AND t1.model != 'MP' AND t1.model != 'Musicians Plugs')";
			$conditionSql .= "	AND (t1.model IS NOT NULL AND t1.model != 'MP' AND t1.model != 'Musicians Plugs' 
			AND t1.model != 'AHP' 
			AND t1.model != 'SHP' 
			AND t1.model != 'EXP PRO' 
			AND t1.model != 'Exp Pro' 
			AND t1.model != 'Security Ears' 
			AND t1.model != 'Silicone Protection' 
			AND t1.model != 'Canal Fit HP' 
			AND t1.model != 'Acrylic HP' 
			AND t1.model != 'Full Ear HP' 
			AND t1.model != 'EXP CORE'
			AND t1.model != 'EXP CORE+' 
			AND t1.model != 'Venture' 
			AND t1.model != 'Cruise'  ) ";
			
			$conditionSql .= " AND (t1.order_status_id != 12)";
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    if ($_REQUEST['monitor_id'] > 0 ) {
		$stmt = pdo_query( $pdo, "SELECT name FROM monitors WHERE id = :monitor_id", array(":monitor_id"=>$_REQUEST['monitor_id'] ));
		$name_of_monitor = pdo_fetch_array( $stmt );

		$conditionSql .= " AND t1.model = :name_of_monitor";
		$params[":name_of_monitor"] = $name_of_monitor["name"];
		
		//$response["test"] = $name_of_monitor["name"];
		//echo json_encode($response);
		//exit;
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
	if ($_REQUEST['order_status_id'] > 0 ) {
		//$stmt = pdo_query( $pdo, "SELECT name FROM monitors WHERE id = :monitor_id", array(":monitor_id"=>$_REQUEST['order_status_id'] ));
		//$order_status_id = pdo_fetch_array( $stmt );

		$conditionSql .= " AND t1.order_status_id = :order_status_id";
		$params[":order_status_id"] = $_REQUEST['order_status_id'];
		
    }
    
	    $today=getdate(date("U"));
		//$date_4_sql = $today['m'] . "/". $today['mday'] . "/" . $today['year'];
		$date_4_sql = $today['year'] . "/" . $today['m'] . "/". $today['mday'];
		//$today_4_sql = date("m/d/Y");
		$today_4_sql = date("Y/m/d");
		
		/*
		$tomorrow_4_sql = date('m/d/Y', strtotime('+1 days'));
		$next_3days_sql = date('m/d/Y', strtotime('+3 days'));
		$nextweek_4_sql = date('m/d/Y', strtotime('+7 days'));
		$next_14days_sql = date('m/d/Y', strtotime('+14 days'));
		$threeWeeks_4_sql = date('m/d/Y', strtotime('+21 days'));
		$sixWeeks_4_sql = date('m/d/Y', strtotime('+42 days'));
		*/
		$tomorrow_4_sql = date('Y/m/d', strtotime('+1 days'));
		$next_3days_sql = date('Y/m/d', strtotime('+3 days'));
		$nextweek_4_sql = date('Y/m/d', strtotime('+7 days'));
		$next_14days_sql = date('Y/m/d', strtotime('+14 days'));
		$threeWeeks_4_sql = date('Y/m/d', strtotime('+21 days'));
		$sixWeeks_4_sql = date('Y/m/d', strtotime('+42 days'));

		
		//if(!empty($_REQUEST["StartDate"])) {
		
		//SELECT * FROM import_orders WHERE (received_date + INTERVAL '1 day') = '07/17/2021'
		//if( strlen($_REQUEST['TODAY_OR_NEXT_WEEK']) == 17) {   // , 'Today and Tomorrow') ) {
		if( $_REQUEST['TODAY_OR_NEXT_WEEK'] == '1') {   // MEANS TODAY
			$conditionSql.=" and ( (t1.received_date + INTERVAL '14 days') = :Date) AND (t1.order_status_id != 12 AND  t1.order_status_id != 11 AND t1.order_status_id != 13 AND t1.order_status_id != 14 AND t1.order_status_id != 16 AND t1.order_status_id != 18 AND t1.order_status_id != 10)";
			$params[":Date"]=  $today_4_sql;
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '0') { // MEANS PAST DUE
			$conditionSql.=" and ((t1.received_date + INTERVAL '14 days') < :Date) AND (t1.order_status_id != 12 AND  t1.order_status_id != 11 AND t1.order_status_id != 13 AND t1.order_status_id != 14 AND t1.order_status_id != 16 AND t1.order_status_id != 18 AND t1.order_status_id != 10)";
			$params[":Date"]= $today_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '2') { // MEANS TOMORROW
			$conditionSql.=" and ((t1.received_date + INTERVAL '14 days') = :Date) AND (t1.order_status_id != 12 AND  t1.order_status_id != 11 AND t1.order_status_id != 13 AND t1.order_status_id != 14 AND t1.order_status_id != 16 AND t1.order_status_id != 18 AND t1.order_status_id != 10)";
			$params[":Date"]= $tomorrow_4_sql;	
			
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '8') { // MEANS NEXT 3 BUSINESS DAYS
			$conditionSql.=" and ((t1.received_date + INTERVAL '14 days') > :Today) AND ((t1.received_date + INTERVAL '14 days') <= :Date) AND (t1.order_status_id != 12 AND  t1.order_status_id != 11 AND t1.order_status_id != 13 AND t1.order_status_id != 14 AND t1.order_status_id != 16 AND t1.order_status_id != 18 AND t1.order_status_id != 10)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $next_3days_sql;		
			
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '3') { // MEANS NEXT 5 BUSINESS DAYS - 7 TOTAL DAYS
			$conditionSql.=" and ((t1.received_date + INTERVAL '14 days') > :Today) AND ((t1.received_date + INTERVAL '14 days') <= :Date) AND (t1.order_status_id != 12 AND  t1.order_status_id != 11 AND t1.order_status_id != 13 AND t1.order_status_id != 14 AND t1.order_status_id != 16 AND t1.order_status_id != 18 AND t1.order_status_id != 10)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $nextweek_4_sql;	
		} elseif ($_REQUEST['TODAY_OR_NEXT_WEEK'] == '4') { // MEANS NEXT 15 BUSINESS DAYS
			$conditionSql.=" and ((t1.received_date + INTERVAL '14 days') > :Today) AND ((t1.received_date + INTERVAL '14 days') <= :Date) AND (t1.order_status_id != 12 AND  t1.order_status_id != 11 AND t1.order_status_id != 13 AND t1.order_status_id != 14 AND t1.order_status_id != 16 AND t1.order_status_id != 18 AND t1.order_status_id != 10)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"]= $threeWeeks_4_sql;	
		} else {  // NEXT 30 BUSINESS DAYS
			$conditionSql.=" and ((t1.received_date + INTERVAL '14 days') > :Today) AND ((t1.received_date + INTERVAL '14 days') <= :Date) AND (t1.order_status_id != 12 AND  t1.order_status_id != 11 AND t1.order_status_id != 13 AND t1.order_status_id != 14 AND t1.order_status_id != 16 AND t1.order_status_id != 18 AND t1.order_status_id != 10)";
			$params[":Today"] = $today_4_sql;	
			$params[":Date"] = $sixWeeks_4_sql;	
		}
    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM import_orders AS t1
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
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE $conditionSql AND t1.printed = TRUE";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    

    $response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];

        $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, to_char( (t1.received_date + INTERVAL '14 days'), 'MM/dd/yyyy') as plus_2wks, IEMs.id AS monitor_id, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN monitors AS IEMs ON t1.model = IEMs.name
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE 1=1 AND t1.active = TRUE $conditionSql"; // $orderBySql $pagingSql";
                  
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $response["test"] = $rows_in_result . " and today is " . $today_4_sql . " and tomorrow is " . $nextweek_4_sql;
    //echo json_encode($response);
    //exit;
    
    // CALCULATE NUMBER OF DAYS PAST DUE
    // DETERMINE NUMBER OF QC FORMS AND STATUS OF INITIAL PASS
    $workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
	//$holidayDays = array('*-12-25', '*-01-01', '2013-12-23', '2021-11-25', '2021-11-26'); # variable and fixed holidays
    for ($i = 0; $i < $rows_in_result; $i++) {
	    		
	    	$to = $today_4_sql;
			//$from = $result[$i]["estimated_ship_date"];
			$from = $result[$i]["plus_2wks"];
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
			$stmt = pdo_query( $pdo, "SELECT *, to_char(t1.date, 'MM/dd/yy') AS date_of_last_scan FROM order_status_log AS t1
	       LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing 
	       WHERE import_orders_id = :import_orders_id ORDER BY date DESC", array(":import_orders_id"=>$result[$i]["id"]));
	    	$result3 = pdo_fetch_all($stmt);     
	    	$result[$i]["date_of_last_scan"] = $result3[0]["date_of_last_scan"];
			
			$query = pdo_query($pdo, "SELECT * FROM qc_form WHERE id_of_order = :id_of_order", array(":id_of_order"=>$result[$i]["id"]));
			$output = pdo_fetch_all($query);
			$result[$i]["num_of_qc_forms"] = pdo_rows_affected($query);
			
			for ($k = 0; $k < $result[$i]["num_of_qc_forms"]; $k++) {
				if($output[$k]["build_type_id"] == 1 ) {
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

    
    $query2 = "SELECT *, to_char(date, 'MM/dd/yyyy HH24:MI') as date_moved, t2.status_of_order, t3.first_name, t3.last_name
    					FROM order_status_log 
    					LEFT JOIN order_status_table AS t2 ON order_status_log.order_status_id = t2.order_in_manufacturing
    					LEFT JOIN auth_user AS t3 ON order_status_log.user_id = t3.id
    					WHERE import_orders_id = :import_orders_id
    					ORDER BY date_moved DESC";
    $params2[":import_orders_id"] = $_REQUEST['id'];
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