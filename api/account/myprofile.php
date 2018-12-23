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
	$stmt = pdo_query( $pdo,
					   'select * from auth_user where id=:Id',
						array(":Id"=>$_SESSION["UserId"])
					 );	
	$row = pdo_fetch_array($stmt);
	
	$response['code']='success';
	$response['data'] = $row;
	
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