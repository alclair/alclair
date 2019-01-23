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
    $orderBySql = " ORDER BY t1.id $orderBySqlDirection";
    $params = array();
	
    if( !empty($_REQUEST['batch_log_id']) )
    {
        $conditionSql .= " AND t1.batch_id = :batch_id";
        $params[":batch_id"] = $_REQUEST['batch_log_id'];
    }
    
    if( !empty($_REQUEST['item_id']) )
    {
        $conditionSql .= " AND t1.id = :item_id";
        $params[":item_id"] = $_REQUEST['item_id'];
    }

    if( !empty($_REQUEST["PageIndex"]) && !empty($_REQUEST["PageSize"]) && intval($_REQUEST["PageIndex"]) > 0 && intval($_REQUEST["PageSize"]) > 0 )
    {
        $start = ( intval($_REQUEST["PageIndex"]) - 1 ) * intval( $_REQUEST["PageSize"] );        
       
        $pagingSql=" limit ".intval($_REQUEST["PageSize"])." offset $start";          
    }

    //Get Total Records
    $query = "SELECT count(t1.id) FROM batch_item_log AS t1
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
    
   	
   	$query = 'SELECT * FROM batches WHERE id = :batch_log_id';
   	$stmt = pdo_query( $pdo, $query, array(":batch_log_id"=>$_REQUEST["batch_log_id"])); 
    $result = pdo_fetch_all( $stmt );

	$response["Batch_Name"] = $result[0]["batch_name"];
	//$params[":session_userid"]=$_SESSION['UserId'];
    //Get One Page Records
    $response["test"] = $conditionSql;

        $query = "SELECT t1.*,  to_char(t1.impression_date, 'MM/dd/yyyy') AS impression_date, t2.steps, t2.id AS next_step_id FROM batch_item_log AS t1
        				  LEFT JOIN in_house_next_steps AS t2 ON t1.next_step_id = t2.id
						  WHERE 1=1 AND t1.active = TRUE $conditionSql $orderBySql";
		//$query = "SELECT * FROM batch_item_log WHERE 1=1 AND active = TRUE AND batch_id = 3 ORDER BY id ASC";

    //}    
    $stmt = pdo_query( $pdo, $query, $params); 
    $result = pdo_fetch_all( $stmt );
    $rows_in_result = pdo_rows_affected($stmt);
    
    //$response["THE_BATCH"] = $result[0]["batch_name"];
	//$response["THE_ID"] = $result[0]["id"];
	
	$response["test2"] = $result[0]["id"];
	//echo json_encode($response);
	//exit;
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