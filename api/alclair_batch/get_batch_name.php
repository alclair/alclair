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
	
      	
   	$query = 'SELECT * FROM batches WHERE id = :batch_log_id';
   	$stmt = pdo_query( $pdo, $query, array(":batch_log_id"=>$_REQUEST["batch_log_id"])); 
    $result = pdo_fetch_all( $stmt );

	$response["Batch_Name"] = $result[0]["batch_name"];
	    
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