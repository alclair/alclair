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
        $conditionSql .= " AND t1.id = :id";
        $params[":id"] = $_REQUEST['id'];
    }
    
    //Get Total Records
    $query = "SELECT count(t1.id) FROM trd_files AS t1
    					WHERE t1.active = TRUE $conditionSql";
    //WHERE active = TRUE $conditionSql";
    $stmt = pdo_query( $pdo, $query, $params );
    $row = pdo_fetch_array( $stmt );
    $response['TotalRecords'] = $row[0];
       
    $query = "SELECT t1.*, to_char(date_uploaded, 'MM/dd/yyyy HH24:MI') as date_uploaded
    					FROM trd_files AS t1
    					LEFT JOIN trd_tickets AS t2 ON t1.ticket_id = t2.id
    					WHERE t1.active = TRUE ORDER BY t1.ticket_id DESC";
    					
    $params[":import_orders_id"] = $_REQUEST['id'];
    $stmt = pdo_query( $pdo, $query, null); 
	$result = pdo_fetch_all( $stmt );  
	$rows_in_result = pdo_rows_affected($stmt);
   
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