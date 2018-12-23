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


	$query = "Update django_site set 
                domain = :domain, name = :name where id = :id";
	$params = array(
                    ":domain" => $_POST["domain"],		
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