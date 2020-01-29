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
    
    $ID_is = $_REQUEST["id"];
    
    $query = "SELECT *, t1.id AS the_id FROM order_status_log AS t1 LEFT JOIN order_status_table AS t2 ON t1.order_status_id = t2.id WHERE t1.id = $ID_is";
    $stmt = pdo_query( $pdo, $query, null );
    $row = pdo_fetch_array( $stmt );
	
	$response["data"] = $row;
        
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}
?>