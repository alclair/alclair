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
           
        $stmt = pdo_query( $pdo,'SELECT * FROM repair_form_active_hp ORDER BY id DESC LIMIT 1', null);	
        $row=pdo_fetch_array($stmt);
        //$response["data"]["id"]=$row["id"];
        $response["next_id"] = $row["id"] +1;
        //$response["data"]["name"]=$row["customer_name"];
		$response["data"] = $row;
		
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