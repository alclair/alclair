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
	$query = "INSERT INTO ticket_tracker_fluidtype
                ( type, priority )
                VALUES
                ( :type, :priority) RETURNING id"; 
	$params = array(
                    ":type" => $_POST["type"],
					":priority" => $_POST["priority"]		
                    );
					    
    $stmt = pdo_query( $pdo, $query, $params );	
    $result = pdo_fetch_array($stmt);
    
    $response['test1'] = $result;
	$response['test2'] = $_POST["priority"];

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