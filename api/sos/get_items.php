<?php
include_once "../../config.inc.php";
if(empty($_SESSION["IsAdmin"]))
{
    return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{

	$stmt = pdo_query( $pdo, 'SELECT * FROM sos_items', null);	
	$result = pdo_fetch_all($stmt);
	$response['code']='success';
	$response['data'] = $result;
	
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