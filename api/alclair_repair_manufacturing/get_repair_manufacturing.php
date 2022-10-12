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
    $conditionSql_printed = "";
    $pagingSql = "";
    $orderBySqlDirection = "ASC";
    $orderBySql = " ORDER BY t1.received_date $orderBySqlDirection";
        
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

   if($_REQUEST['MONTH_RANGE'] != '0' && empty($_REQUEST['id'])) {
		
    }
	
	$start_date = new DateTime($_REQUEST["StartDate"]);
	$days_back = clone $start_date;
	$days_back->modify('-' . $_REQUEST['MONTH_RANGE'] . ' day');
	
	$start_date = $start_date->format('Y-m-d');
	$days_back = $days_back->format('Y-m-d');
	
	$conditionSql .= " AND (t1.received_date <= :start_date AND t3.date >= :days_back)";
	$params[":start_date"] = $start_date;
	$params[":days_back"] = $days_back;
	
	$conditionSql_2 = '';
	$params_2 = array();
	$conditionSql_2 .= " AND (t1.date <= :start_date AND t1.date >= :days_back)";
	$params_2[":start_date"] = $start_date;
	$params_2[":days_back"] = $days_back;
	
	$conditionSql_3 = '';
	$params_3 = array();
	$conditionSql_3 .= " AND (t3.date <= :start_date AND t3.date >= :days_back)";
	$params_3[":start_date"] = $start_date;
	$params_3[":days_back"] = $days_back;
	//echo json_encode($response);
	//exit;
    /*  NOT USING THIS METHOD ANYMORE - COMMENTED ON 02/17/2021
	    THIS METHOD COUNTS ORDER BY ORDER CREATION DATE - NOT THE DATE THE ORDER WAS MOVED TO DONE
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
    $response['TotalRecords2'] = $row[0];
    */
     if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
    
     //Get One Page Records
     
     $query_2 = "SELECT * FROM order_status_log AS t1
     						LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
     						WHERE t1.order_status_id = 12 AND t2.active = TRUE  
     						
     						AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')   
								AND (t2.model IS NOT NULL AND t2.model != 'MP' 
								AND t2.model != 'AHP' 
								AND t2.model != 'SHP' 
								AND t2.model != 'EXP PRO'
								AND t2.model != 'Security Ears' 
								AND t2.model != 'Sec Ears Silicone'
								AND t2.model != 'Sec Ears Acrylic'
								AND t2.model != 'Musicians Plugs' 
								AND t2.model != 'Silicone Protection' 
								AND t2.model != 'Canal Fit HP' 
								AND t2.model != 'Acrylic HP' 
								AND t2.model != 'Full Ear HP' 
								AND t2.model != 'EXP CORE'
								AND t2.model != 'EXP CORE+'
								AND t2.model != 'Venture'
								AND t2.model != 'Cruise')
     						
     						$conditionSql_2";
	$stmt_2 = pdo_query( $pdo, $query_2, $params_2); 
    $result_2 = pdo_fetch_all( $stmt_2 );
    $response['TotalRecords2'] = count($result_2);
    
    $query = "SELECT t1.id AS id_of_repair, t1.customer_name, t1.rma_number, to_char(t1.received_date, 'MM/dd/yyyy') AS rma_received, t2.designed_for, t2.id AS id_of_order, t3.order_status_id, to_char(t3.date, 'MM/dd/yyyy') AS date_done, t2.model AS model_name, t4.color AS impression_color
    						FROM repair_form AS t1
							LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
							LEFT JOIN order_status_log AS t3 ON t2.id = t3.import_orders_id
							LEFT JOIN impression_colors AS t4 ON t2.impression_color_id = t4.id
							
							WHERE t1.import_orders_id IS NOT NULL AND t3.order_status_id = 12 
								AND t2.active = TRUE 
								AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')   
								AND (t2.model IS NOT NULL AND t2.model != 'MP' 
								AND t2.model != 'AHP' 
								AND t2.model != 'SHP' 
								AND t2.model != 'EXP PRO'
								AND t2.model != 'Security Ears' 
								AND t2.model != 'Sec Ears Silicone'
								AND t2.model != 'Sec Ears Acrylic'
								AND t2.model != 'Musicians Plugs' 
								AND t2.model != 'Silicone Protection' 
								AND t2.model != 'Canal Fit HP' 
								AND t2.model != 'Acrylic HP' 
								AND t2.model != 'Full Ear HP' 
								AND t2.model != 'EXP CORE'
								AND t2.model != 'EXP CORE+')
								$conditionSql_3 $orderBySql";
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params_3); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    $response['TotalRecords'] = $rows_in_result;
    
    $final_result = $result;
    
    // FOR LOOP TO DETERMINE IF/WHEN THE REPAIR SHIPPED - 04/06/2022
    for ($i = 0; $i < $rows_in_result; $i++) {
		$query2 = "SELECT *, to_char(date, 'MM/dd/yyyy') AS rma_shipped
							FROM repair_status_log 
							WHERE repair_form_id = :id_of_rma AND repair_status_id = 14";
		$stmt2 = pdo_query( $pdo, $query2, array(":id_of_rma"=>$result[$i]['id_of_repair']));
		$returned = pdo_fetch_all( $stmt2 );
		$rows = pdo_rows_affected($stmt2);
		if($rows) {
			$final_result[$i]["rma_shipped"] = $returned[0]["rma_shipped"];
		} else{
			$final_result[$i]["rma_shipped"] = "Not Shipped";
		}
	}
   
    // FOR LOOP TO DETERMINE WHEN THE IMPRESSIONS WERE DETAILED - 04/06/2022
    for ($i = 0; $i < $rows_in_result; $i++) {
		$query2 = "SELECT *, to_char(date, 'MM/dd/yyyy') AS order_detailed
							FROM order_status_log 
							WHERE import_orders_id = :id_of_order 
							AND (order_status_id = 2 OR order_status_id = 15) 
							ORDER BY date DESC LIMIT 1";
		$stmt2 = pdo_query( $pdo, $query2, array(":id_of_order"=>$result[$i]['id_of_order']));
		$returned = pdo_fetch_all( $stmt2 );
		$rows = pdo_rows_affected($stmt2);
		if($rows) {
			$final_result[$i]["order_detailed"] = $returned[0]["order_detailed"];
		} else{
			$final_result[$i]["order_detailed"] = "No Date Found";
		}
	} 
	// FOR LOOP TO DETERMINE NUMBER OF TIMES THE ORDER HAS BEEN TRHOUGH THE RMA PROCESS - 04/06/2022
    for ($i = 0; $i < $rows_in_result; $i++) {
		$query2 = "SELECT COUNT(id) AS num_of_repairs_from_order_id 
							FROM repair_form
							WHERE import_orders_id = :id_of_order AND active = TRUE";
		$stmt2 = pdo_query( $pdo, $query2, array(":id_of_order"=>$result[$i]['id_of_order']));
		$returned = pdo_fetch_all( $stmt2 );
		$rows = pdo_rows_affected($stmt2);
		if($rows) {
			$final_result[$i]["num_of_repairs_from_order_id"] = $returned[0]["num_of_repairs_from_order_id"];
		} else{
			$final_result[$i]["num_of_repairs_from_order_id"] = "Weird Answer";
		}
	} 
    
    $sound = 0;
	$fit = 0;
	$design = 0;
	$customer = 0;
   for ($i = 0; $i < $rows_in_result; $i++) {
		$query2 = "SELECT * FROM rma_faults_log WHERE id_of_rma = :id_of_rma AND active = TRUE";
		$stmt2 = pdo_query( $pdo, $query2, array(":id_of_rma"=>$result[$i]['id_of_repair']));
		$faults = pdo_fetch_all( $stmt2 );
		$rows = pdo_rows_affected($stmt2);
	
		for ($j = 0; $j < $rows; $j++) {
				//$response['test'] = "J is " . $j . " and fault is " . $faults[$j]['classification'] ;
				//echo json_encode($response);
				//exit;
			if(!strcmp($faults[$j]['classification'], 'Sound') ) {
				$response['test'] = "J is " . $j . " and fault is " . $faults[$j]['classification'] ;
				$sound++;
				//echo json_encode($response);
				//exit;
				$final_result[$i]['sound']= 'X';
			} else if(!strcmp($faults[$j]['classification'] , 'Fit') ) {
				$response['test'] = "J is " . $j . " and fault is " . $faults[$j]['classification'] ;
				$fit++;
				//echo json_encode($response);
				//exit;
				$final_result[$i]['fit'] = 'X';
			} else if(!strcmp($faults[$j]['classification'], 'Design') ) {
				$design++;
				$final_result[$i]['design'] = 'X';
			} else if(!strcmp($faults[$j]['classification'], 'Customer') ) {
				$design++;
				$final_result[$i]['customer'] = 'X';
			}
		}
	}
	
	$fit2 = 0;
	for ($i = 0; $i < $rows_in_result; $i++) {
		$query2 = "SELECT * FROM rma_faults_log WHERE id_of_rma = :id_of_rma AND classification = 'Fit' Limit 1";
		$stmt2 = pdo_query( $pdo, $query2, array(":id_of_rma"=>$result[$i]['id_of_repair']));
		$faults = pdo_fetch_all( $stmt2 );
		$rows = pdo_rows_affected($stmt2);
		if($rows) {
			$fit2++;
		}
	}

	/*
	SELECT t1.id, t1.customer_name, to_char(t1.received_date, 'MM/dd/yyyy') AS rma_received, t2.designed_for, t3.order_status_id, 
to_char(t3.date, 'MM/dd/yyyy') AS date_done, t4.classification

FROM repair_form AS t1 
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
LEFT JOIN order_status_log AS t3 ON t2.id = t3.import_orders_id
LEFT JOIN rma_faults_log AS t4 ON t1.id = t4.id_of_rma
WHERE t1.import_orders_id IS NOT NULL AND t3.order_status_id = 12  AND (t1.received_date < '02/01/2019' AND t3.date > '01/01/2019')  ORDER BY t1.received_date ASC  limit 100 offset 0
*/
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $final_result;
    
    	
	if($_REQUEST['asc_or_desc'] == 0) {
		$asc_or_desc = constant('SORT_ASC');
	} else {
		$asc_or_desc = constant('SORT_DESC');
	}
	$response['test'] = "1st is " . $_REQUEST['asc_or_desc'] . " and 2nd is " . $asc_or_desc;
	//echo json_encode($response);
	//exit;
    if(!strcmp($_REQUEST['To_Sort_By'], "Designed For")) {
	    array_multisort(array_column($final_result, 'customer_name'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "RMA #")) {
		array_multisort(array_column($final_result, 'id_of_repair'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "Model")) {
		array_multisort(array_column($final_result, 'model_name'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "Number of RMAs")) {
		array_multisort(array_column($final_result, 'num_of_repairs_from_order_id'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "Impression Color")) {
		array_multisort(array_column($final_result, 'impression_color'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "Impressions Detailed")) {
		array_multisort(array_column($final_result, 'order_detailed'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "Manufactured Date")) {
		array_multisort(array_column($final_result, 'date_done'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "Repair Received")) {
		array_multisort(array_column($final_result, 'rma_received'), $asc_or_desc, $final_result);
	} elseif(!strcmp($_REQUEST['To_Sort_By'], "Repair Shipped")) {
		array_multisort(array_column($final_result, 'rma_shipped'), $asc_or_desc, $final_result);
    } else {
	    array_multisort(array_column($final_result, 'id_of_repair'), $asc_or_desc, $final_result);
    }
    
	// # OF ORDERS IS TOTALRECORDS2
	// # OF REPAIRS IS TOTALRECORDS
	// # OF FIT ISSUES IS OrdersWithFit
	$num_days_to_go_back_int = intval($_REQUEST['MONTH_RANGE']);
	$num_days_to_go_back_str = $_REQUEST['MONTH_RANGE'];
	$params_2 = array();
	$repairs_by_day = array();
	$selected_date_by_user = new DateTime($_REQUEST["StartDate"]); // DATE PICKER SELECTED BY USER
	for ($i = 1; $i <= $num_days_to_go_back_int; $i++) {
		$end_date_for_query = clone $selected_date_by_user; // CLONE DATE SELECTED BY USER
		$start_date_for_query = clone $selected_date_by_user; // CLONE DATE SELECTED BY USER
		$date_for_plot = clone $selected_date_by_user; // CLONE DATE SELECTED BY USER
		$end_date = strval($num_days_to_go_back_int - $i);
		$start_date = strval($num_days_to_go_back_int*2 - $i); 
		
		$end_date_for_query->modify('-' . $end_date . ' day');
		$start_date_for_query->modify('-' . $start_date . ' day');
		$date_for_plot->modify('-' . $start_date . ' day');
	
		$end_date_for_query = $end_date_for_query->format('m/d/Y');
		$start_date_for_query = $start_date_for_query->format('m/d/Y');
		$date_for_plot = $date_for_plot->format('md');
		
		//$response['test'] = "Start date is " . $start_date_for_query . " and end date is " . $end_date_for_query;
		//echo json_encode($response);
		//exit;
		
		$params_2[":end_date_for_query"] = $end_date_for_query;
		$params_2[":start_date_for_query"] = $start_date_for_query;
		
		$query_2 = 
			"SELECT COUNT(t1.id) AS num_of_orders FROM order_status_log AS t1
			 LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
			WHERE t1.order_status_id = 12 AND t2.active = TRUE  
			AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')   
			AND (t2.model IS NOT NULL AND t2.model != 'MP' 
			AND t2.model != 'AHP' 
			AND t2.model != 'SHP' 
			AND t2.model != 'EXP PRO'
			AND t2.model != 'Security Ears' 
			AND t2.model != 'Sec Ears Silicone'
			AND t2.model != 'Sec Ears Acrylic'
			AND t2.model != 'Musicians Plugs' 
			AND t2.model != 'Silicone Protection' 
			AND t2.model != 'Canal Fit HP' 
			AND t2.model != 'Acrylic HP' 
			AND t2.model != 'Full Ear HP' 
			AND t2.model != 'EXP CORE'
			AND t2.model != 'EXP CORE+'
			AND t2.model != 'CM3K') AND (t1.date >= :start_date_for_query AND t1.date <= :end_date_for_query)";
			$stmt_2 = pdo_query( $pdo, $query_2, $params_2); 
			$result_2 = pdo_fetch_all( $stmt_2 );
			//$response['TotalRecords2'] = count($result_2);
			$orders_by_day[$i-1] = $result_2[0]["num_of_orders"];
			$testing[$i-1]["label"] = strval($i);
			//$testing[$i-1]["label"] = ($date_for_plot);
			$testing[$i-1]["value"] = $result_2[0]["num_of_orders"];
			if ($i == 1) { // IF STATEMENT WAS USED TO SEE IF THE DATE (EITHER AS STRING OR INTEGER) COULD BE USED FOR X-AXIS
				$response['test'] = "I equals 1 is " . $date_for_plot;
			} elseif ($i == $num_days_to_go_back_int) {
				$response['test'] = $response['test'] . " and last is " . $date_for_plot;
			}
		
		$query = "SELECT t1.id AS id_of_rma
			FROM repair_form AS t1
			LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
			LEFT JOIN order_status_log AS t3 ON t2.id = t3.import_orders_id
			LEFT JOIN impression_colors AS t4 ON t2.impression_color_id = t4.id
			
			WHERE t1.import_orders_id IS NOT NULL AND t3.order_status_id = 12 
				AND t2.active = TRUE 
				AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')   
				AND (t2.model IS NOT NULL AND t2.model != 'MP' 
				AND t2.model != 'AHP' 
				AND t2.model != 'SHP' 
				AND t2.model != 'EXP PRO'
				AND t2.model != 'Security Ears' 
				AND t2.model != 'Sec Ears Silicone'
				AND t2.model != 'Sec Ears Acrylic'
				AND t2.model != 'Musicians Plugs' 
				AND t2.model != 'Silicone Protection' 
				AND t2.model != 'Canal Fit HP' 
				AND t2.model != 'Acrylic HP' 
				AND t2.model != 'Full Ear HP' 
				AND t2.model != 'EXP CORE'
				AND t2.model != 'EXP CORE+'
				AND t2.model != 'CM3K') AND (t3.date >= :start_date_for_query AND t3.date <= :end_date_for_query)";
				$stmt_2 = pdo_query( $pdo, $query, $params_2); 
				$result_2 = pdo_fetch_all( $stmt_2 );
				$rows_in_result = pdo_rows_affected($stmt);
				/*
				$repairs_by_day[$i-1] = $result_2[0]["num_of_repairs"];
				$testing[$i-1]["label"] = strval($i);
				$testing[$i-1]["value"] = $result_2[0]["num_of_repairs"];
				$response['test'] = $result_2[0]["num_of_repairs"];
				*/
				$fit_issues = 0;
				for ($j = 0; $j < $rows_in_result; $j++) {
					$query2 = "SELECT * FROM rma_faults_log WHERE id_of_rma = :id_of_rma AND classification = 'Fit' Limit 1";
					$stmt2 = pdo_query( $pdo, $query2, array(":id_of_rma"=>$result_2[$j]['id_of_rma']));
					$faults = pdo_fetch_all( $stmt2 );
					$rows = pdo_rows_affected($stmt2);
					if($rows) {
						$fit_issues++;
					}
				}
				
				$send_to_plot[$i-1]['label'] = strval($i);
				//$send_to_plot[$i-1]['value'] = round($repairs_by_day[$i-1] / $orders_by_day[$i-1] * 100, 1);
				$send_to_plot[$i-1]['value'] = round($fit_issues / $orders_by_day[$i-1] * 100, 1);
				//echo json_encode($response);
				//exit;
				if($i == 1) {
					$minimum = $send_to_plot[$i-1]['value'];
					$maximum = $send_to_plot[$i-1]['value'];
				} else {
					if($send_to_plot[$i-1]['value'] < $minimum) {
						$minimum = $send_to_plot[$i-1]['value'];
					}
					if($send_to_plot[$i-1]['value'] > $maximum) {
						$maximum = $send_to_plot[$i-1]['value'];
					}
				}
    }
    
	$response['testing'] = $testing;
	$response['send_to_plot'] = $send_to_plot;
	$response['minimum'] = $minimum;
	$response['maximum'] = $maximum;
	
    //$response['testing'] =  [{label: 10, value: 20},{label: 20, value: 50},{label: 30, value: 30},{label: 40, value: 10},{label: 50,value: 100},{label: 60, value: 60},{label: 70, value: 80},{label: 80, value: 50},{label: 100, value: 70}];
    //$response['test'] = "Num of Orders " . count($orders_by_day);
	//echo json_encode($response);
	//exit;
	$response['data'] = $final_result;
    //$response['test'] = $rows_in_result; 
    //$response['test'] = $query; 
    $response['data2'] = $result2;
    $response["TotalSound"] = $sound;
    $response["TotalFit"] = $fit;
    $response["TotalDesign"] = $design;
    $response["TotalCustomer"] = $customer;
    $response["OrdersWithFit"] = $fit2;

        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>