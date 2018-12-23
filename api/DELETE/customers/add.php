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
    $stmt = "";
	/*if(empty($_REQUEST["is_superuser"])) 
	 {
		 $_REQUEST["is_superuser"]=0;
	 }
	 else
	 {
		 $_REQUEST["is_superuser"]=1;
	 }
	 if(empty($_REQUEST["is_staff"])) 
	 {
		 $_REQUEST["is_staff"]=0;
	 }
	 else
	 {
		 $_REQUEST["is_staff"]=1;
	 }*/
	if(!empty($_REQUEST["password"]))
    {						
		$query = "Insert into customer
                  ( customer_name ) 
                  values
                  (:customer_name) RETURNING id";
		$params = array(
                        ":customer_name" => $_REQUEST["customer_name"],

                      );
        $stmt = pdo_query( $pdo, $query, $params );
   	}
	else
	{	
		$query = "Insert into customer
                 ( customer_name )
                 values
                 ( :customer_name) RETURNING id";
		$params = array(
                        ":customer_name" => $_REQUEST["customer_name"],

                     );
					
		$stmt = pdo_query( $pdo, $query, $params );
	}
    $result = pdo_fetch_array($stmt);

	$response['code']='success';
	$response["message"] =" Add Customer success!";
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