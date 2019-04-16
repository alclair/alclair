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
	
	$response['code'] = 'before everything';
	$traveler = array();
	$traveler['id'] = $_REQUEST['id'];
   $response["test"] = $traveler["id"];
			     	
	$stmt = pdo_query( $pdo, 'UPDATE repair_form SET manufacturing_screen=:manufacturing_screen WHERE id = :id',
					   array("manufacturing_screen"=>0, "id"=>$traveler["id"]));
		
	$response['code'] = 'success';
	$response['data'] = $traveler['id'];
	//$response['testing1'] = $traveler['ports_cleaned'] ;
	echo json_encode($response);
	
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>