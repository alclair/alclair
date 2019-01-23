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
    $orderBySql = " ORDER BY id $orderBySqlDirection";
    $params = array();

	$response["test"] = $_REQUEST['id'];
	//echo json_encode($response);
	//exit;
	
    if( !empty($_REQUEST['id']) )
    {
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
    
    $response["test"] = is_null($_REQUEST['group_type_id']) . " THEN " . is_null($_REQUEST['group_type_id']);
    
    //$response["test"] = $_REQUEST['group_type_id'] . " and " . is_null($_REQUEST['group_type_id']);
    if($_REQUEST['group_type_id'] === null || !strcmp($_REQUEST['group_type_id'], 'null') ) {
	    //$response["test"] = "NULL";
    } else {
	    //$response["test"] = "NOT NULL";
    }
    //echo json_encode($response);
	//exit;
    
    if( !empty($_REQUEST['group_type_id']) && strcmp($_REQUEST['group_type_id'], 'null')  )
    {
        $conditionSql .= " AND t1.batch_type_id = :batch_type_id";
        $params[":batch_type_id"] = $_REQUEST['group_type_id'];
    }

    if(!empty($_REQUEST["SearchText"]))
    {
	    if(is_numeric($_REQUEST["SearchText"])) {
		    $conditionSql .= " AND (t1.order_id = :SearchOrderID)";
		    $params[":SearchOrderID"] = $_REQUEST["SearchText"];
	    }
	    else {
		    $conditionSql .= " AND (t1.designed_for ilike :SearchText)";
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
	} // END ELSE STATEMENT
	    
    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM batches AS t1
    					WHERE 1=1 AND t1.active = TRUE AND t1.archive = FALSE $conditionSql";
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
    
   	
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    //$response["test"] = $conditionSql;
    $response["test2"] = $_REQUEST['id'];
    /*if( !empty($_REQUEST['orderID']) )
    {        
        $query = "SELECT t1.*, to_char(t1.date,'MM/dd/yyyy') as date
                  FROM batches AS t1
                  WHERE 1=1 $conditionSql"; //t1.id = :id";
    }
    else
    {*/
        $query = "SELECT t1.*, to_char(t1.created_date,'MM/dd/yyyy') as created_date, t2.first_name, t2.last_name, t3.types, t4.status, t5.first_name AS received_first_name, t5.last_name AS received_last_name, to_char(t1.received_date,'MM/dd/yyyy') as received_date, t6.types AS group
                          FROM batches AS t1
						  LEFT JOIN auth_user AS t2 ON t1.created_by_id = t2.id
						  LEFT JOIN batch_types AS t3 ON t1.batch_type_id = t3.id
						  LEFT JOIN batch_status AS t4 ON t1.batch_status_id = t4.id
						  LEFT JOIN auth_user AS t5 ON t1.received_by = t5.id
						  LEFT JOIN batch_types AS t6 ON t1.batch_type_id = t6.id
						  WHERE 1=1 AND t1.active = TRUE AND t1.archive = FALSE $conditionSql $orderBySql $pagingSql";
                  //active = TRUE $conditionSql $orderBySql $pagingSql";
    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    $response["THE_BATCH"] = $result[0]["batch_name"];
	$response["THE_ID"] = $result[0]["id"];
    //$response["test"] = $result["batch_name"];
    /*
    $query2 = "SELECT *, to_char(date, 'MM/dd/yyyy    HH24:MI') as date_moved, t2.status_of_order, t3.first_name, t3.last_name
    					FROM order_status_log 
    					LEFT JOIN order_status_table AS t2 ON order_status_log.order_status_id = t2.order_in_manufacturing
    					LEFT JOIN auth_user AS t3 ON order_status_log.user_id = t3.id
    					WHERE batches_id = :batches_id
    					ORDER BY date DESC";
    $params2[":batches_id"] = $_REQUEST['id'];
    $stmt2 = pdo_query( $pdo, $query2, $params2); 
	$result2 = pdo_fetch_all( $stmt2 );  
	$rows_in_result2 = pdo_rows_affected($stmt2);
	*/
    /*
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
	*/
    
    $response['code'] = 'success';
    $response["message"] = $query;
    $response['data'] = $result;
    //$response['data2'] = $result2;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>