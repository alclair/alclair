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
	if($_REQUEST["id"] !='')
	{

		$stmt = pdo_query( $pdo,
					   'select id, type, priority from ticket_tracker_fluidtype WHERE id = :id order by priority',
						array(":id"=>$_REQUEST["id"])
					 );	
		$result = pdo_fetch_array($stmt);
		$response['test'] = "In Here";
		$response['test2'] = count($result);
	} else {
		
		$stmt = pdo_query( $pdo,
					   'select * from ticket_tracker_fluidtype order by priority',
						null
					 );	
		$result = pdo_fetch_all($stmt);
	}
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