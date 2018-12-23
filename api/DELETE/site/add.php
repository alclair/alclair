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
	$query = "Insert into django_site 
                ( domain,name )
                values
                ( :domain, :name) RETURNING id"; 
	$params = array(
                    ":domain" => $_POST["domain"],			
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