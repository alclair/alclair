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
    $orderBySql = " ORDER BY order_id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t2.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }

    if(!empty($_REQUEST["SearchText"]))
    {
	    if(is_numeric($_REQUEST["SearchText"])) {
		    $conditionSql .= " AND (t2.order_id = :SearchOrderID)";
		    $params[":SearchOrderID"] = $_REQUEST["SearchText"];
	    }
	    else {
		    $conditionSql .= " AND (t2.designed_for ilike :SearchText)";
			$params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
		}
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        /*$conditionSql .= " AND (t1.customer_name ilike :SearchText OR t1.order_id = :SearchOrderID)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        $params[":SearchOrderID"] = $_REQUEST["SearchText"];
        $params[":Order_ID"]=$_REQUEST["SearchText"];
        $response['testing']="%".$_REQUEST["SearchText"]."%";*/
    }
    else if ($_REQUEST['RUSH_OR_NOT'] == 1) {
			$conditionSql .=" AND (t1.rush_process = 'Yes')";
			$conditionSql .= " AND (t1.order_status_id != 12)";
			//$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    }
    else {
		if($_REQUEST['PRINTED_OR_NOT'] != '0' && empty($_REQUEST['id'])) {
		    if($_REQUEST['PRINTED_OR_NOT'] ==  'TRUE') {
			    $conditionSql .= " AND (t1.printed = TRUE)";    
			} else {
	        	$conditionSql .= " AND (t1.printed = FALSE OR t1.printed IS NULL)";
				//$params[":printed"] = $_REQUEST['PRINTED_OR_NOT'];
			}
    	}
		if($_REQUEST['ORDER_STATUS_ID'] >= 1) {
		    //$response['message'] = 'Please ' . $_REQUEST['ORDER_STATUS_ID'];
			//echo json_encode($response);
			//exit;	
			$conditionSql .= " AND (t1.order_status_id = :OrderStatusID)";
			$params[":OrderStatusID"] = $_REQUEST['ORDER_STATUS_ID']; 
    	}  else {
	    
			if(!empty($_REQUEST["StartDate"])) {
				$conditionSql.=" and (t1.date>=:StartDate)";
				$params[":StartDate"]=$_REQUEST["StartDate"];
			}
			if(!empty($_REQUEST["EndDate"])) {
				$conditionSql.=" and (t1.date<=:EndDate)";
				$params[":EndDate"]=date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59'));
			}
		}
		
	} // END ELSE STATEMENT
	/*if(!empty($_REQUEST["StartDate"]))
	{
		if (date('I', time()))
		{	
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00') + 5 * 3600);
		}
		else
		{
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')+ 6 * 3600);
		}
		$conditionSql.=" and (t1.date>=:StartDate)";
		$params[":StartDate"]=$TIME_START;
		//$params[":StartDate"]=$_REQUEST["StartDate"];
	}
	
	if(!empty($_REQUEST["EndDate"]))
	{
		if (date('I', time()))
		{
			$TIME_END = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 5 * 3600);
		}
		else
		{
			$TIME_END = date("m/d/Y H:i:s",strtotime($_REQUEST["EndDate"] . '23:59:59') + 6 * 3600);
		}
		$conditionSql.=" and (t1.date<=:EndDate)";
		$params[":EndDate"]=$TIME_END;
		//$params[":EndDate"]=$_REQUEST["EndDate"];
	}*/
	
	
    /*if(!empty($_REQUEST["SearchDisposalWell"]))
    {
        $conditionSql .= " and (t1.disposal_well_id=:DisposalWellId)";
        
        $params[":DisposalWellId"]=$_REQUEST["SearchDisposalWell"];
    }*/
    
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
    //$response['TotalRecords'] = $row[0];
    
     if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }
    
    //Get Total Passed
    /*
    $query = "SELECT count(t1.id) FROM order_status_log AS t1
    	LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
		LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
		LEFT JOIN monitors AS t4 ON t2.model = t4.name
		WHERE t1.order_status_id = 12 AND t2.active = TRUE AND t4.name IS NOT NULL AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL)  $conditionSql";
	*/	
	$query = "SELECT count(t1.id) FROM order_status_log AS t1
    	LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
		LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
		LEFT JOIN monitors AS t4 ON t2.model = t4.name
		WHERE t1.order_status_id = 12 AND t2.active = TRUE AND t4.name IS NOT NULL 
		AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')
		AND (t2.model IS NOT NULL AND t2.model != 'MP' AND t2.model != 'Musicians Plugs' 
		AND t2.model != 'AHP' 
		AND t2.model != 'SHP' 
		AND t2.model != 'EXP PRO' 
		AND t2.model != 'Exp Pro' 
		AND t2.model != 'Security Ears' 
		AND t2.model != 'Sec Ears Silicone'
		AND t2.model != 'Sec Ears Acrylic'
		AND t2.model != 'Silicone Protection' 
		AND t2.model != 'Canal Fit HP' 
		AND t2.model != 'Acrylic HP' 
		AND t2.model != 'Full Ear HP' 
		AND t2.model != 'EXP CORE'
		AND t2.model != 'EXP CORE+'  
		AND t2.model != 'Venture'  
		AND t2.model != 'Cruise'  )  
		$conditionSql";
    //WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];

	
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    $response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];
    /*if( !empty($_REQUEST['orderID']) )
    {        
        $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date
                  FROM import_orders AS t1
                  WHERE 1=1 $conditionSql"; //t1.id = :id";
    }
    else
    {*/
    
    $response["test"] = $pagingSql;
    //echo json_encode($response);
    //exit;

        $query = "SELECT DISTINCT t1.import_orders_id, t2.designed_for, t3.status_of_order, t2.order_id AS order_id, to_char(t1.date, 'MM/dd/yyyy    HH24:MI') as date_done, t4.name AS model, t1.order_status_id, t1.notes,  t2.id
FROM order_status_log AS t1
LEFT JOIN import_orders AS t2 ON t1.import_orders_id = t2.id
LEFT JOIN order_status_table AS t3 ON 12 = t3.order_in_manufacturing
LEFT JOIN monitors AS t4 ON t2.model = t4.name
WHERE t1.order_status_id = 12 AND t2.active = TRUE AND t4.name IS NOT NULL 
AND (t2.customer_type = 'Customer' OR t2.customer_type IS NULL OR t2.customer_type = '')   
AND (t2.model IS NOT NULL AND t2.model != 'MP' 
AND t2.model != 'AHP' 
AND t2.model != 'SHP' 
AND t2.model != 'EXP PRO'
AND t2.model != 'Exp Pro'
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
$conditionSql 
ORDER BY date_done ASC,  t1.import_orders_id ";//$pagingSql
//AND t1.date >= '08/01/2018' AND t1.date <= '08/28/2018'
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    
    $response["test"] = $result[0]["import_orders_id"];
    //echo json_encode($response);
    //exit;
    $rows_in_result = pdo_rows_affected($stmt);
    
     $inc = 0;	
	 $num_done = array();
     for ($i = 0; $i < $rows_in_result; $i++) {
	 	$query = "SELECT * FROM order_status_log WHERE import_orders_id = :import_orders_id AND order_status_id = 12";
	 	$stmt = pdo_query($pdo, $query, array(":import_orders_id"=>$result[$i]["import_orders_id"]));
	 	$result_test = pdo_fetch_all($stmt);
	 	$number_of_done = pdo_rows_affected($stmt);
	 	$num_done[$inc] = $number_of_done;
	 	$result[$i]["num_done"] = $number_of_done;
	 	$inc = $inc + 1;
	 }
    
    $query2 = "SELECT *, to_char(date, 'MM/dd/yyyy    HH24:MI') as date_done, t2.status_of_order, t3.first_name, t3.last_name
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
    $response['data3'] = $num_done;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>
