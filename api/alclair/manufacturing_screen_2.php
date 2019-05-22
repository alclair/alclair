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
    
    //Get Total Passed
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE AND t1.manufacturing_screen = TRUE";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['Orders'] = $row[0];

    $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date, to_char(t1.received_date,'MM/dd/yyyy') as received_date, t2.status_of_order
                  FROM import_orders AS t1
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE 1=1 AND t1.active = TRUE AND t1.manufacturing_screen = TRUE AND t1.order_status_id <> 12";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    
    //UNION ALL
    $query = "SELECT t1.*, to_char(t1.received_date,'MM/dd/yyyy') as date, t2.status_of_repair AS status
                  FROM repair_form AS t1
                  LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair
                  WHERE 1=1 AND t1.active = TRUE AND t1.manufacturing_screen = TRUE AND t1.repair_status_id <> 14";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    
    // THE ABOVE TWO QUERIES COMBINED
    $query = "SELECT t1.id, t1.designed_for AS customer, to_char(t1.date,'MM/dd/yyyy') as date, t2.status_of_order AS status, 1 AS type
                  FROM import_orders AS t1
                  LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.order_in_manufacturing
                  WHERE 1=1 AND t1.active = TRUE AND t1.manufacturing_screen = TRUE AND t1.order_status_id <> 12
              UNION ALL    
                  SELECT t1.id, t1.customer_name AS customer, to_char(t1.received_date,'MM/dd/yyyy') as date, t2.status_of_repair AS status, 2 AS type
                  FROM repair_form AS t1
                  LEFT JOIN repair_status_table AS t2 ON t1.repair_status_id = t2.order_in_repair
                  WHERE 1=1 AND t1.active = TRUE AND t1.manufacturing_screen = TRUE AND t1.repair_status_id <> 14";
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );

    $current_year = date("Y");
    $current_month = date("m");
    $current_date = date("m/d/Y");
    $minus_30_days = strtotime($current_date . '-30 days');
    $minus_30_days = date("m/d/Y", $minus_30_days);
    $last_year = $current_year - 1;
    $january_current_year = '01/01/' . $current_year;
    $december_current_year = '12/31/' . $current_year;
   
	$response['data'] = $result;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////// START TURN AROUND TIME FOR ORDERS  ////////////////////////////////////////////////////////////////////////
	$conditionSql = "";
    $conditionSql_printed = "";
    $pagingSql = "";
    $orderBySqlDirection = "ASC";
    $orderBySql = " ORDER BY order_id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

	
		//if(!empty($_REQUEST["StartDate"])) {
			$conditionSql.=" and (t1.date>=:StartDate)";
			//$params[":StartDate"]='05/01/2019';
			$params[":StartDate"]=$minus_30_days . ' 00:00:00';
		//}
		//if(!empty($_REQUEST["EndDate"])) {
			$conditionSql.=" and (t1.date<=:EndDate)";
			//$params[":EndDate"]='05/11/2019' .' 23:59:59';
			$params[":EndDate"]=$current_date . ' 23:59:59';
			
		//}
		
    //Get Total Records
    $query = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE AND t1.order_status_id = 12 $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    //Get Total Records Active 
    // Amanda request on 11/24/2018
    $query2 = "SELECT count(t1.id) FROM import_orders AS t1
    					WHERE 1=1 AND t1.active = TRUE AND (t1.order_status_id >= 1 AND t1.order_status_id <=11)"; // $conditionSql";
    //WHERE active = TRUE $conditionSql";
    //$stmt2 = pdo_query( $pdo, $query2, $params );
    $stmt2 = pdo_query( $pdo, $query2, null );
    $row2 = pdo_fetch_array( $stmt2 );
    $response['TotalRecordsActive'] = $row2[0];
   
    //Get One Page Records
    $response["test1"] = "asdfadsfadsf";//$conditionSql;
    $response["test2"] = $_REQUEST['id'];

$query2 = pdo_query($pdo, "SELECT *, to_char(t1.date,'MM/dd/yyyy') as date
						FROM order_status_log AS t1 
						LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
						WHERE t1.order_status_id = 12 AND t1.date >= :StartDate AND t1.date <= :EndDate AND t1.import_orders_id IS NOT NULL AND t2.active = TRUE", array(":StartDate"=>$params[":StartDate"], ":EndDate"=>$params[":EndDate"]));
	
	//$just_start_date = pdo_fetch_all( $query1 );
	$store_done_data = pdo_fetch_all( $query2 );
	
	$workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
    $holidayDays = array('*-12-25', '*-01-01', '2019-11-25'); # variable and fixed holidays
	// import_orders_id
	$store_start_data = array();
	$difference = array();
	$ind = 0;

	// LOOPS THROUGH THE OUTPUT FROM THE ABOVE SQL STATEMENT
	for ($i = 0; $i < count($store_done_data); $i++) {
	//for ($i = 0; $i < 1; $i++) {
		
		// QUERY FINDS THE START DATE FOR EACH ORDER THAT IS DONE
		$query = pdo_query($pdo, "SELECT *, to_char(received_date, 'MM/dd/yyyy') as start_date FROM import_orders WHERE id = :import_orders_id" , array(":import_orders_id"=>$store_done_data[$i]["import_orders_id"]));
		$store_start_data = pdo_fetch_all( $query );
		$rowcount = pdo_rows_affected( $query );
		
		if ($rowcount != 0 ) {
			//$store_start_data[$i] = $row[0];
		
			$from = $store_start_data[0]["start_date"];
			$to = $store_done_data[$i]["date"];
			$from = new DateTime($from);
			$from->modify('+1 day');
			$to = new DateTime($to);
			//$to->modify('+1 day');
			$interval = new DateInterval('P1D');
			$periods = new DatePeriod($from, $interval, $to);

			$days = 0;
			foreach ($periods as $period) {
				$days++;
			}	
				
			$difference[$ind] = $days;
			
			if($difference[$ind] == 0 ) {
				$response["test3"]  = $store_done_data[$i]["import_orders_id"];
			}
			$ind++;				
		}
	} 
	// CLOSE FOR LOOP
		
	$response["num_of_orders1"] = $ind;
	$response["num_of_orders2"] = count($difference);
	$response["min"] = min($difference);
	$response["max"] = max($difference);
	$response["avg"] = round(array_sum($difference)/count($difference));
////////////////////////////////////////////////////////////// END TURN AROUND TIME FOR ORDERS  //////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////// START TURN AROUND TIME FOR REPAIRS  ////////////////////////////////////////////////////////////////////////
$query2 = pdo_query($pdo, "SELECT *, to_char(t1.date,'MM/dd/yyyy') as date
						FROM repair_status_log AS t1 
						LEFT JOIN repair_form AS t2 ON t1.repair_form_id = t2.id
						WHERE t1.repair_status_id = 14 AND t1.date >= :StartDate AND t1.date <= :EndDate AND t1.repair_form_id IS NOT NULL AND t2.active = TRUE", array(":StartDate"=>$params[":StartDate"], ":EndDate"=>$params[":EndDate"]));
	
	
	//$just_start_date = pdo_fetch_all( $query1 );
	$store_done_data = pdo_fetch_all( $query2 );
	
	$workingDays = array(1, 2, 3, 4, 5); # date format = N (1 = Monday, ...)
    $holidayDays = array('*-12-25', '*-01-01', '2019-11-25'); # variable and fixed holidays
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
			
			
			/*if ($i == 1) {
				$testing = $store_start_data[0]['start_date'];
				$response["test3"] = "Not zero " . $row[0]["start_date"]. " and i is " . $i;
				echo json_encode($response);
				exit;
			}*/
		
			$from = $store_start_data[0]["start_date"];
			$to = $store_done_data[$i]["date"];
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
	$response["avg_repairs"] = round(array_sum($difference)/count($difference));
	
	$values = array_count_values($difference); 
	$response["mode"] = array_search(max($values), $values);
////////////////////////////////////////////////////////////// END TURN AROUND TIME FOR REPAIRS  //////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $response["test"] = $current_year;
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>