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
	
	$conditionSql  = $_REQUEST["rig_id"];
	$query = "SELECT company_man_name, company_man_number FROM rigs WHERE id =  $conditionSql";
	//$_REQUEST['source_well_id']";
	//$_POST['source_well_id']
	$stmt = pdo_query($pdo,$query,null);
	
	$result=pdo_fetch_all($stmt);
	$response['data'] = $result;   
	$response['company_man_name'] = $result[0]["company_man_name"];    
	$response['company_man_number'] = $result[0]["company_man_number"];    
    $response['id'] = $_REQUEST["id"];  

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