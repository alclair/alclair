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
    $pagingSql = "";
    $orderBySqlDirection = "DESC";
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();

    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
    
    if($_REQUEST['MonitorID'] !=  0)
    {
        $conditionSql .= " AND (t1.monitor_id = :monitor_id)";
        $params[":monitor_id"] = $_REQUEST['MonitorID'];
    }

    if(!empty($_REQUEST["SearchText"]))
    {
	     if(is_numeric($_REQUEST["SearchText"])) {
		 	$conditionSql .= " AND (t1.rma_number = :RMA_Number)";
		 	$params[":RMA_Number"]=$_REQUEST["SearchText"];
		 }
		 else {
			 $conditionSql .= " AND (t1.customer_name ilike :SearchText)";
			 $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
		 }
		 
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        /*$conditionSql .= " AND (t1.customer_name ilike :SearchText OR t1.rma_number = :RMA_Number)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        $params[":RMA_Number"]=$_REQUEST["SearchText"];
        $response["testing"]=$_REQUEST["SearchText"];*/
    }
    
    if($_REQUEST['REPAIR_STATUS_ID'] >= 1) {
		    //$response['message'] = 'Please ' . $_REQUEST['ORDER_STATUS_ID'];
			//echo json_encode($response);
			//exit;	
			$conditionSql .= " AND (t1.repair_status_id = :RepairStatusID)";
			$params[":RepairStatusID"] = $_REQUEST['REPAIR_STATUS_ID']; 
    }  

    
    if($_REQUEST['REPAIRED_OR_NOT'] != '0' && empty($_REQUEST['id']))
    {
	    if($_REQUEST['REPAIRED_OR_NOT'] ==  'TRUE') {
		    $conditionSql .= " AND (t1.rma_performed_date IS NOT NULL)";
		    
	    } else {
	        $conditionSql .= " AND (t1.rma_performed_date IS NULL)";
			//$params[":printed"] = $_REQUEST['PRINTED_OR_NOT'];
		}
    }

	if(!empty($_REQUEST["StartDate"]))
	{
		if (date('I', time()))
		{	
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00') + 5 * 3600);
		}
		else
		{
			$TIME_START = date("m/d/Y H:i:s",strtotime($_REQUEST["StartDate"] . '00:00:00')+ 6 * 3600);
		}
		$conditionSql.=" and (t1.received_date>=:StartDate)";
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
		$conditionSql.=" and (t1.received_date<=:EndDate)";
		$params[":EndDate"]=$TIME_END;
		//$params[":EndDate"]=$_REQUEST["EndDate"];
	}
	
	
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
    $query = "SELECT count(t1.id) FROM repair_form AS t1
    WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    //Get Total Passed
    /*$query = "SELECT count(t1.id) FROM qc_form AS t1
    WHERE active = TRUE AND pass_or_fail = 'PASS' $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['Passed'] = $row[0];

    if( !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageSize"]) > 0 )
    {
        $response["TotalPages"] = ceil( $row[0]/intval($_REQUEST["PageSize"]) );
    }
    else
    {
        $response["TotalPages"] = 1; 
    }*/
	
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    if( isset($_REQUEST['id']) )
    {        
        $query = "SELECT t1.*, to_char(t1.received_date,'MM/dd/yyyy') as received_date, to_char(t1.date_entered,'MM/dd/yyyy') as date_entered, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date,
                  IEMs.name AS monitor_name,
                  t3.first_name as first_name, t3.last_name as last_name, t4.status_of_repair
                  FROM repair_form AS t1
                  LEFT JOIN monitors AS IEMs ON t1.monitor_id = IEMs.id
                  LEFT JOIN auth_user AS t3 ON t1.entered_by = t3.id 
                  LEFT JOIN repair_status_table AS t4 ON t1.repair_status_id = t4.order_in_repair
                  WHERE t1.id = :id";
                  
                      $stmt = pdo_query( $pdo, $query, $params); 
					  $result = pdo_fetch_all( $stmt );
					  $rows_in_result = pdo_rows_affected($stmt);
                  
                  $query2 = "SELECT t1.*
                  FROM rma_faults_log AS t1
                  WHERE t1.id_of_rma = :id AND active = true";
                  
				  $stmt2 = pdo_query( $pdo, $query2, $params); 
				  $result_faults= pdo_fetch_all( $stmt2 );
				  $response['data_faults'] = $result_faults;
    }
    else
    {
        $query = "SELECT t1.*, to_char(t1.received_date,'MM/dd/yyyy') as received_date, to_char(t1.date_entered,'MM/dd/yyyy') as date_entered, to_char(t1.estimated_ship_date,'MM/dd/yyyy') as estimated_ship_date,
        		  to_char(t1.rma_performed_date,'MM/dd/yyyy') as rma_performed_date,        
                  IEMs.name AS monitor_name, t3.first_name as first_name, t3.last_name as last_name, t4.status_of_repair
                  FROM repair_form AS t1
                  LEFT JOIN monitors AS IEMs ON t1.monitor_id = IEMs.id
                  LEFT JOIN auth_user AS t3 ON t1.entered_by = t3.id 
				  LEFT JOIN repair_status_table AS t4 ON t1.repair_status_id = t4.order_in_repair
                  WHERE t1.active = TRUE $conditionSql $orderBySql $pagingSql";
                  
                  $stmt = pdo_query( $pdo, $query, $params); 
				  $result = pdo_fetch_all( $stmt );
				  $rows_in_result = pdo_rows_affected($stmt);
    }    

    
    /*for ($i = 0; $i < $rows_in_result; $i++) {
    	if (date('I', time()))
		{
			$result[$i]["received_date"] = date("m/d/Y",strtotime($result[$i]["received_date"]) - 5 * 3600);
			$result[$i]["date_entered"] = date("m/d/Y",strtotime($result[$i]["date_entered"]) - 5 * 3600);
			$result[$i]["estimated_ship_date"] = date("m/d/Y",strtotime($result[$i]["estimated_ship_date"]) - 5 * 3600);

		}
		else
		{
			$result[$i]["received_date"] = date("m/d/Y",strtotime($result[$i]["received_date"]) - 6 * 3600);
			$result[$i]["date_entered"] = date("m/d/Y",strtotime($result[$i]["date_entered"]) - 6 * 3600);
			$result[$i]["estimated_ship_date"] = date("m/d/Y",strtotime($result[$i]["estimated_ship_date"]) - 6 * 3600);
		}
	}*/
	
	 $query2 = "SELECT *, to_char(date, 'MM/dd/yyyy    HH24:MI') as date_moved, t2.status_of_repair, t3.first_name, t3.last_name
    					FROM repair_status_log 
    					LEFT JOIN repair_status_table AS t2 ON repair_status_log.repair_status_id = t2.order_in_repair
    					LEFT JOIN auth_user AS t3 ON repair_status_log.user_id = t3.id
    					WHERE repair_status_log.repair_form_id = :repair_form_id
    					ORDER BY date_moved DESC";
    $params2[":repair_form_id"] = $_REQUEST['id'];
    $stmt2 = pdo_query( $pdo, $query2, $params2); 
	$result2 = pdo_fetch_all( $stmt2 );  
	$rows_in_result2 = pdo_rows_affected($stmt2);
    for ($i = 0; $i < $rows_in_result2; $i++) {
    	if (date('I', time()))
		{
			$result2[$i]["date_to_show"] = date("m/d/Y  h:i A",strtotime($result2[$i]["date_moved"]) );
			$result2[$i]["date_to_show_date"] = date("m/d/Y",strtotime($result2[$i]["date_moved"]) );
			$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) );
		}
		else
		{
			$result2[$i]["date_to_show"] = date("m/d/Y  h:i A",strtotime($result2[$i]["date_moved"]) );
			$result2[$i]["date_to_show_date"] = date("m/d/Y ",strtotime($result2[$i]["date_moved"]) );
			$result2[$i]["date_to_show_hours"] = date("h:i A",strtotime($result2[$i]["date_moved"]) );
	
			
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