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
					   //'select id,name from ticket_tracker_county order by name',
					   'SELECT DISTINCT name from ticket_tracker_county order by name',
						null
					 );	
	//$result = pdo_fetch_all($stmt,PDO::FETCH_ASSOC);
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