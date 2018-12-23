<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

try
{	    
	$query = "Insert into ticket_tracker_truckingcompany 
                ( name )
                values
                ( :name) RETURNING id"; 
	$params = array(
                    ":name" => $_POST["name"],			
                    );
					    
    $stmt = pdo_query( $pdo, $query, $params );	
    $result = pdo_fetch_array($stmt);

	$response['code']='success';
	$response["message"] =" Add success!";
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