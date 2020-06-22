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
	$stmt = pdo_query( $pdo, "UPDATE trd_customer SET active = FALSE WHERE id = :id", array(":id"=>$_REQUEST['customer_id']));	
	$result = pdo_fetch_array($stmt);
	$response['code']='success';
	$response['data'] = $result;
	
	$response['test'] = "ID is " . $_REQUEST['customer_id'];	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>
