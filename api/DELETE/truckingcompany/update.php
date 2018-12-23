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


	$query = "Update ticket_tracker_truckingcompany set 
                name = :name where id = :id";
	$params = array(
                    ":name" => $_POST["name"],		
		            ":id" => $_POST["id"] 
                    );
    pdo_query( $pdo, $query, $params );
	
		   			
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