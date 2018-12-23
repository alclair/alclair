<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = array();

try
{	
	
	$conditionSql  = $_REQUEST["source_well_id"];
	$query = "SELECT t1.*, t2.name AS source_well_operator_name
						FROM ticket_tracker_well as t1
						LEFT JOIN ticket_tracker_operator AS t2 ON t1.current_operator_id = t2.id
						WHERE t1.id = $conditionSql";
	//$_REQUEST['source_well_id']";
	//$_POST['source_well_id']
	$stmt = pdo_query($pdo,$query,null);
	
	$result=pdo_fetch_all($stmt);
	$response['data'] = $result;   
	$response['name'] = $result[0]["source_well_operator_name"];    
    $response['id'] = $_REQUEST["source_well_id"];  

	$response['code']='success';	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>