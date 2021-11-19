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
    $orderBySql = " ORDER BY repair_id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

	    
		if(!empty($_REQUEST["StartDate"])) {
			$conditionSql.=" and (t1.receiveddate>=:StartDate)";
			$params[":StartDate"]=$_REQUEST["StartDate"];
		}
		if(!empty($_REQUEST["EndDate"])) {
			$conditionSql.=" and (t1.receiveddate<=:EndDate)";
			$params[":EndDate"]=date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
		}
		

    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM repair_form AS t1
    					WHERE 1=1 AND t1.active = TRUE AND t1.repair_status_id = 14 $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    //Get Total Records Active 
    // Amanda request on 11/24/2018
    $query2 = "SELECT count(t1.id) FROM repair_form AS t1
    					WHERE 1=1 AND t1.active = TRUE AND (t1.repair_status_id >= 1 AND t1.repair_status_id <=11)"; // $conditionSql";
    //WHERE active = TRUE $conditionSql";
    //$stmt2 = pdo_query( $pdo, $query2, $params );
    $stmt2 = pdo_query( $pdo, $query2, null );
    $row2 = pdo_fetch_array( $stmt2 );
    $response['TotalRecordsActive'] = $row2[0];
    
     if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
       
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    $response["test1"] = "asdfadsfadsf";//$conditionSql;
    $response["test2"] = $_REQUEST['id'];

// ORDER IN MANUFACTURING
// 1 EQUALS START CART
// 12 EQUALS DONE

	// FIND ALL ORDERS BETWEEN START AND END DATE THAT ARE DONE
	//$query1 = pdo_query($pdo, "SELECT * FROM order_status_log WHERE order_status_id = 12 AND date >= :StartDate", array(":StartDate"=>$_REQUEST["StartDate"]));
	
	// ORIGINAL QUERY BEFORE REALIZING NEEDED TO NOT INCLUDE NON-ACTIVE ORDERS
	//$query2 = pdo_query($pdo, "SELECT *, to_char(date,'MM/dd/yyyy') as date FROM order_status_log WHERE order_status_id = 12 AND date >= :StartDate AND date <= :EndDate AND import_orders_id IS NOT NULL", array(":StartDate"=>$_REQUEST["StartDate"], ":EndDate"=>$_REQUEST["EndDate"]));
	
$query2 = pdo_query($pdo, "SELECT *, to_char(t1.date,'MM/dd/yyyy') as date
						FROM repair_status_log AS t1 
						LEFT JOIN repair_form AS t2 ON t1.repair_form_id = t2.id
						WHERE t1.repair_status_id = 14 AND t1.date >= :StartDate AND t1.date <= :EndDate AND t1.repair_form_id IS NOT NULL AND t2.active = TRUE", array(":StartDate"=>$params[":StartDate"], ":EndDate"=>$params[":EndDate"]));
	
	
	//$just_start_date = pdo_fetch_all( $query1 );
	$store_done_data = pdo_fetch_all( $query2 );
	
	$workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
    $holidayDays = array('*-12-25', '*-01-01', '2013-12-23'); # variable and fixed holidays
	// import_orders_id
	$store_start_data = array();
	$difference = array();
	$ind = 0;

	// LOOPS THROUGH THE OUTPUT FROM THE ABOVE SQL STATEMENT
	for ($i = 0; $i < count($store_done_data); $i++) {
	//for ($i = 0; $i < 1; $i++) {
		
		//$query = pdo_query($pdo, "SELECT *, to_char(date,'MM/dd/yyyy') as start_date FROM order_status_log WHERE order_status_id = 1 AND import_orders_id = :import_orders_id ORDER BY ID ASC LIMIT 1" , array(":import_orders_id"=>$store_done_data[$i]["import_orders_id"]));
		//$store_start_data = pdo_fetch_all( $query );
		//$rowcount = pdo_rows_affected( $query );
		
		// QUERY FINDS THE START DATE FOR EACH ORDER THAT IS DONE
		$query = pdo_query($pdo, "SELECT *, to_char(received_date, 'MM/dd/yyyy') as start_date FROM repair_form WHERE id = :repair_form_id" , array(":repair_form_id"=>$store_done_data[$i]["repair_form_id"]));
		$store_start_data = pdo_fetch_all( $query );
		$rowcount = pdo_rows_affected( $query );
		
		
		if ($rowcount != 0 ) {
			//$store_start_data[$i] = $row[0];
		
			$from = $store_start_data[0]["start_date"];
			
////////////////////////////////////////////////////////////			
			$query6 = pdo_query($pdo, "SELECT t1.id as t1_id, t2.id as t2_id, to_char(t1.date,'MM/dd/yyyy') as date
						FROM repair_status_log AS t1 
						LEFT JOIN repair_form AS t2 ON t1.repair_form_id = t2.id
						WHERE t1.repair_form_id = :repair_form_id AND (t1.repair_status_id = 16) AND t1.repair_form_id IS NOT NULL AND t2.active = TRUE ORDER BY t1.date ASC LIMIT 1", array(":StartDate"=>$params[":StartDate"], ":EndDate"=>$params[":EndDate"], ":repair_form_id"=>$store_done_data[$i]["repair_form_id"]));

			$store_holding_for_payment3 = pdo_fetch_all( $query6 );
			$rowcount6 = pdo_rows_affected( $query6 );


			if ($rowcount6) {
				$to = $store_holding_for_payment3[0]["date"];
				$//to = $store_done_data[$i]["date"];	
				//$J = $J + 1;
			} else {
				$to = $store_done_data[$i]["date"];	
				//$J = $J + 1;
			}
////////////////////////////////////////////////////////////
			//$to = $store_done_data[$i]["date"];
			$from = new DateTime($from);
			$from->modify('+1 day');
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
				
			$difference[$ind] = $days;
			
			if($difference[$ind] == 0 ) {
				$response["test3"]  = $store_done_data[$i]["repair_form_id"];
			}
			$ind++;				
		}
	} 
	// CLOSE FOR LOOP
		
	$response["num_of_repairs1"] = $ind;
	$response["num_of_repairs2"] = count($difference);
	$response["min"] = min($difference);
	$response["max"] = max($difference);
	$response["avg"] = round(array_sum($difference)/count($difference),1);
	
	$values = array_count_values($difference); 
	$response["mode"] = array_search(max($values), $values);
	
	$count = count($difference);
    sort($difference);
    $mid = floor($count/2);
    $response["median"] =  ($difference[$mid]+$difference[$mid+1-$count%2])/2;
    //$response["median"] =  count($difference);
   
	$counter = 0;
	for($j = 0; $j < count($difference); $j++) {
		if($difference[$j] == 0) {
			$counter++;
		}
	}
	
	$store_start_data2 = array();
	$difference2 = array();
	$difference_zero = array();
	$ids_to_keep  = array();
	$ids_to_keep_zero  = array();
	$ind = 0;
	$ind2 = 0;
	$ind3 = 0;
	$OrdersList = array();
	$OrdersList_zero = array();
	$stepper = array();
	
	/*
	$response["test1"] = $store_done_data;
	echo json_encode($response);
	exit;
	*/
	
	// THIS LOOP CALCULATES THE NUMBER OF DAYS THE ORDER TOOK TO COMPLETE
	for ($i = 0; $i < count($store_done_data); $i++) {

		$query2 = pdo_query($pdo, "SELECT *, to_char(received_date, 'MM/dd/yyyy') as start_date FROM repair_form WHERE id = :repair_form_id" , array(":repair_form_id"=>$store_done_data[$i]["repair_form_id"]));
		$store_start_data2 = pdo_fetch_all( $query2 );
		$rowcount2 = pdo_rows_affected( $query2 );
		
		if ($rowcount2 != 0 ) {
		
			$from = $store_start_data2[0]["start_date"];
			
			// QUERY FINDS IF THE REPAIR MOVED TO HOLDING FOR PAYMENT PRIOR TO BEING DONE
			// IF THE PAYMENT WAS HOLDING FOR PAYMENT THEN ITS DATE WILL REPLACE THE DONE DATE
/*
			$query5 = pdo_query($pdo, "SELECT t1.id as t1_id, t2.id as t2_id, to_char(t1.date,'MM/dd/yyyy') as date
						FROM repair_status_log AS t1 
						LEFT JOIN repair_form AS t2 ON t1.repair_form_id = t2.id
						WHERE t1.repair_form_id = :repair_form_id AND (t1.repair_status_id = 16) AND t1.date >= :StartDate AND t1.date <= :EndDate AND t1.repair_form_id IS NOT NULL AND t2.active = TRUE ORDER BY t1.date ASC LIMIT 1", array(":StartDate"=>$params[":StartDate"], ":EndDate"=>$params[":EndDate"], ":repair_form_id"=>$store_done_data[$i]["repair_form_id"]));
*/
			$query5 = pdo_query($pdo, "SELECT t1.id as t1_id, t2.id as t2_id, to_char(t1.date,'MM/dd/yyyy') as date
						FROM repair_status_log AS t1 
						LEFT JOIN repair_form AS t2 ON t1.repair_form_id = t2.id
						WHERE t1.repair_form_id = :repair_form_id AND (t1.repair_status_id = 16) AND t1.repair_form_id IS NOT NULL AND t2.active = TRUE ORDER BY t1.date ASC LIMIT 1", array(":StartDate"=>$params[":StartDate"], ":EndDate"=>$params[":EndDate"], ":repair_form_id"=>$store_done_data[$i]["repair_form_id"]));

			$store_holding_for_payment2 = pdo_fetch_all( $query5 );
			$rowcount5 = pdo_rows_affected( $query5 );

/*
			if($store_done_data[$i]["repair_form_id"] == 6231) { //6091 6231
				$response["test1"] = "The row count is " . $rowcount4 . " and ID is " . $store_done_data[$i]["repair_form_id"] . " and output is " . $store_holding_for_payment2[0]["t1_id"];
				//echo json_encode($response);
				//exit;
			}
*/

			if ($rowcount5) {
				$to = $store_holding_for_payment2[0]["date"];
				//$to = $store_done_data[$i]["date"];	
				//$J = $J + 1;
			} else {
				$to = $store_done_data[$i]["date"];	
				//$J = $J + 1;
			}

			//$to = $store_done_data[$i]["date"];
			$from = new DateTime($from);
			$from->modify('+1 day');
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
				
			$difference2[$ind2] = $days;	
			$difference_zero[$ind3] = $days;	
			
			if($i == 1) {
				$response["test1"]  = $difference2[$ind] . " avg is " . $response["avg"];
				//echo json_encode($response);
				//exit;	
			}
		
		
			// IF THE ORDER TOOK LONGER THAN THE AVERAGE THEN IT GETS SAVED FOR OUTPUT TO THE TURN AROUND TIME SCREEN
			if($difference2[$ind2] > $response["avg"]) { //$response["avg"] - $response["avg"]) {
				$ids_to_keep[$ind2] = $store_done_data[$i]["repair_form_id"];
				
				$query3 = pdo_query($pdo, "SELECT t1.*,  to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, IEMs.id AS monitor_id, t2.status_of_repair, IEMs.name AS model
                  FROM repair_form AS t1
                  LEFT JOIN monitors AS IEMs ON t1.monitor_id = IEMs.id
                  LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair
                  WHERE 1=1 AND t1.active = TRUE AND t1.id = :repair_form_id", array(":repair_form_id"=>$ids_to_keep[$ind2]));
                  $OrdersList[$ind2] = pdo_fetch_array( $query3 );
                  $OrdersList[$ind2]["difference"] = $difference2[$ind2];
                  $OrdersList[$ind2]["done_date"] = $store_done_data[$i]["date"];
				  $ind2++;
			}	
			
			
			
			if($difference_zero[$ind3] == 0) {
				$ids_to_keep_zero[$ind3] = $store_done_data[$i]["repair_form_id"];
				
				$query4 = pdo_query($pdo, "SELECT t1.*,  to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, IEMs.id AS monitor_id, t2.status_of_repair, IEMs.name AS model
                  FROM repair_form AS t1
                  LEFT JOIN monitors AS IEMs ON t1.monitor_id = IEMs.id
                  LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair
                  WHERE 1=1 AND t1.active = TRUE AND t1.id = :repair_form_id", array(":repair_form_id"=>$ids_to_keep_zero[$ind3]));
                  
                  //$response["test"] = pdo_rows_affected( $query4 ) . " and it is also " . count($OrdersList_zero[$ind3]);
				  if(pdo_rows_affected($query4) != 0) {
	                  $OrdersList_zero[$ind3] = pdo_fetch_array( $query4 );
	                  $OrdersList_zero[$ind3]["difference"] = $difference_zero[$ind3];
					  $OrdersList_zero[$ind3]["done_date"] = $store_done_data[$i]["date"];
					  $ind3++;
				  }
			}	
		}		
	} 
	
	
	
	$Sorted = array();
	$ind = 0;
	for ($i = 0; $i < count($ids_to_keep); $i++) {
		if($i == 0 ) {
			$Sorted[0] = $OrdersList[0];
		} elseif($i == 1) {
			if( is_null($OrdersList[$i]) ) {
				$Sorted[1] = $Sorted[0]; // NO MATTER WHAT THE ORIGINAL VALUE WAS IT MOVES TO THE SECOND SPOT IN THE ARRAY
				$Sorted[0] = $OrdersList[1]; // NULL VALUE GOES TO THE FRONT OF THE ARRAY
			} elseif( $OrdersList[1]["difference"] < $Sorted[0]["difference"] ) {
				$Sorted[1] = $OrdersList[1];
			} else {
				$Sorted[1] = $Sorted[0];
				$Sorted[0] = $OrdersList[1];
			}
					
		} else {
			for ($k = 0; $k < count($Sorted); $k++) {
				if( $OrdersList[$i]["difference"] > $Sorted[$k]["difference"]) {
					for ($m = $i; $m > $k; $m--) {
						$Sorted[$m] = $Sorted[$m-1];
					}	
					$Sorted[$k] = $OrdersList[$i];
					break;
				} elseif( $k == count($Sorted) - 1)  {
					$Sorted[$i] = $OrdersList[$i];
				}
			} // CLOSE FOR LOOP
		} // CLOSE IF STATEMENT
	}  // CLOSE FOR LOOPS
	
	//$ids_to_keep = $OrdersList[0]["received_date"];
	
	$response["test1"] = $store_done_data[1]["date"];
	//927
	$response["test2"] = $testing;
	$response["test3"] = $ids_to_keep;
	 $response['TotalRecords'] = count($ids_to_keep);
	//$response["test"] = count($store_start_data);
	$response["test4"] = $counter;	    
    $response['code'] = 'success';
    $response["message"] = $query;
    //$response['data'] = $result;
    $response['data'] = $OrdersList;
    $response['data'] = $Sorted;
    $response['data_zero'] = $OrdersList_zero;
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
