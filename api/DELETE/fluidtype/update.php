<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

try
{		
    if( empty( $_REQUEST["id"] ) )
        return;

		 $response['test3'] = $_REQUEST["id"];

	$query = "UPDATE ticket_tracker_fluidtype SET
                type = :type, priority = :priority WHERE id = :id";
	$params = array(
                    ":type" => $_REQUEST["type"],	
                    ":priority" => $_REQUEST["priority"],	
		            ":id" => $_REQUEST["id"] 
                    );
    pdo_query( $pdo, $query, $params );
	
    $response['test1'] = $_REQUEST["type"];
    $response['test2'] = $_REQUEST["priority"];
   
	
	$response['code']='success';
	$response["message"] =" Update success!";
	echo json_encode($response);		
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>