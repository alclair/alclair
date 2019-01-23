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
	
      	
   	$query = 'UPDATE batches SET batch_name = :batch_name WHERE id = :batch_id';
   	$stmt = pdo_query( $pdo, $query, array(":batch_name"=>$_POST["batch_name"], ":batch_id"=>$_REQUEST["batch_id"])); 
    $result = pdo_fetch_all( $stmt );

	    
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