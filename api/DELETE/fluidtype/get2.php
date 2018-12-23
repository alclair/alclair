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

	$stmt = pdo_query( $pdo, 'SELECT fluid_id FROM tank_tracker WHERE tank_id = :id',
					array(":id"=>$_REQUEST["index"])
					);
	$result = pdo_fetch_all($stmt);
	$response['test']=json_encode($result);
	
	$query = "SELECT * FROM tank_tracker as t1
					LEFT JOIN ticket_tracker_fluidtype as t2 on t1.fluid_id=t2.id
					WHERE t1.tank_id=:id";
	$params[":id"]=$_REQUEST["index"];
	$stmt2 = pdo_query( $pdo, $query, $params);
	//$stmt2 = pdo_query( $pdo,
	//				   'SELECT * FROM ticket_tracker_fluidtype WHERE id = :id order by type',
	//					$params);	
	$result2 = pdo_fetch_all($stmt2);
	$response['test2'] = $result2;
	$response['test3'] = $params;
	
	$response['code']='success';
	$response['data'] = $result2;
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