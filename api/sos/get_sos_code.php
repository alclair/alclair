<?php
include_once "../../config.inc.php";

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{
	$stmt = pdo_query($pdo, "SELECT * FROM sos_code", null);	

    $result = pdo_fetch_all($stmt);
	$response['code']='success';
	
	$response['sos_code'] = $result[0]["code"];
	$response['date'] = $result[0]["date"];
	$response['test'] = "TEST";
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