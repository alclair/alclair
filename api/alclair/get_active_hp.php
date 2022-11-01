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
  
 if(!empty($_REQUEST["SearchText"]) && strlen($_REQUEST["SearchText"]) > 1)
    {
	    if(is_numeric($_REQUEST["SearchText"])) {
		    $conditionSql .= " AND (t1.order_id = :SearchOrderID)";
		    $params[":SearchOrderID"] = $_REQUEST["SearchText"];
	    }
	    else {
		    $conditionSql .= " AND (t1.customer_name ilike :SearchText)";
			$params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
		}
		
        //$conditionSql .= " and (t1.ticket_number ilike :SearchText or twell.current_well_name ilike :SearchText or twell.file_number=:FileNumber)";
        /*$conditionSql .= " AND (t1.customer_name ilike :SearchText OR t1.order_id = :SearchOrderID)";
        $params[":SearchText"] = "%".$_REQUEST["SearchText"]."%";
        $params[":SearchOrderID"] = $_REQUEST["SearchText"];
        $params[":Order_ID"]=$_REQUEST["SearchText"];
        $response['testing']="%".$_REQUEST["SearchText"]."%";*/
    }

else {    
	
    if($_REQUEST['PASS_OR_FAIL'] != '0')
    {
	    if($_REQUEST['PASS_OR_FAIL'] ==  'PASS AND FAIL') {
		    $conditionSql .= " AND (t1.pass_or_fail = 'PASS' OR t1.pass_or_fail = 'FAIL')";
	    } else {
	        $conditionSql .= " AND (t1.pass_or_fail = :pass_or_fail)";
			$params[":pass_or_fail"] = $_REQUEST['PASS_OR_FAIL'];
		}
    }
    
    if($_REQUEST['MonitorID'] !=  0)
    {
        $conditionSql .= " AND (t1.monitor_id = :monitor_id)";
        $params[":monitor_id"] = $_REQUEST['MonitorID'];
    }
    if($_REQUEST['BuildTypeID'] !=  0)
    {
        $conditionSql .= " AND (t1.build_type_id = :build_type_id)";
        $params[":build_type_id"] = $_REQUEST['BuildTypeID'];
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
		$conditionSql.=" and (t1.qc_date>=:StartDate)";
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
		$conditionSql.=" and (t1.qc_date<=:EndDate)";
		$params[":EndDate"]=$TIME_END;
		//$params[":EndDate"]=$_REQUEST["EndDate"];
	}
	
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
    $query = "SELECT count(t1.id) FROM qc_form_active_hp AS t1
    WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
    
    //Get Total Passed
    $query = "SELECT count(t1.id) FROM qc_form_active_hp AS t1
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
    }
	
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    if( isset($_REQUEST['id']) )
    {        
        $query = "SELECT t1.*, to_char(t1.shipping_date,'MM/dd/yyyy') as shipping_date,
                  IEMs.name AS monitor_name, t3.first_name as first_name, t3.last_name as last_name, t4.type as build_type
                  FROM qc_form_active_hp AS t1
                  LEFT JOIN monitors AS IEMs ON t1.monitor_id = IEMs.id
                  LEFT JOIN auth_user AS t3 ON t1.qc_pass_by = t3.id 
                  LEFT JOIN new_or_repair AS t4 ON t1.build_type_id = t4.id
                  WHERE t1.id = :id";
                  $stmt = pdo_query($pdo, $query, array(":id"=>$_REQUEST['id'] ) );
    }
    else
    {
        $query = "SELECT t1.*, to_char(t1.shipping_date,'MM/dd/yyyy') as shipping_date,
                  IEMs.name AS monitor_name, t3.first_name as first_name, t3.last_name as last_name, t4.type as build_type
                  FROM qc_form_active_hp AS t1
                  LEFT JOIN monitors AS IEMs ON t1.monitor_id = IEMs.id
                  LEFT JOIN auth_user AS t3 ON t1.qc_pass_by = t3.id 
                  LEFT JOIN new_or_repair AS t4 ON t1.build_type_id = t4.id
                  WHERE active = TRUE $conditionSql $orderBySql LIMIT 25";
                  $stmt = pdo_query($pdo, $query, $params);
                  //WHERE active = TRUE $conditionSql $orderBySql $pagingSql";
    }    
  
    $result = pdo_fetch_all( $stmt );

    $rows_in_result = pdo_rows_affected($stmt);

    for ($i = 0; $i < $rows_in_result; $i++) {
    	if (date('I', time()))
		{
			$result[$i]["qc_date"] = date("m/d/Y",strtotime($result[$i]["qc_date"]) - 5 * 3600);
		}
		else
		{
			$result[$i]["qc_date"] = date("m/d/Y",strtotime($result[$i]["qc_date"]) - 6 * 3600);
		}
	}
    
     if( isset($_REQUEST['id']) )
    {  
    	$query = "SELECT id, filepath, to_char(date_uploaded,'MM/dd/yyyy') as date_uploaded, uploaded_by_id, qc_form_id
                       FROM qc_form_active_hp_indexupload 
                       WHERE qc_form_id = :id";
                       //$stmt = pdo_query( $pdo, $query, $params); 
                       $stmt = pdo_query($pdo, $query, array(":id"=>$_REQUEST['id']) );
	}
	else {
		$query = "SELECT id, filepath, to_char(date_uploaded,'MM/dd/yyyy') as date_uploaded, uploaded_by_id, qc_form_id
                       FROM qc_form_active_hp_indexupload";
                       $stmt = pdo_query( $pdo, $query, null); 
	}
	
	//$stmt = pdo_query( $pdo, $query, null); 
    $result2 = pdo_fetch_all( $stmt );
	
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