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
    /*if( empty( $_REQUEST["id"] ) )
        return;
	if(empty($_REQUEST["is_superuser"])) 
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
		$query = "Update customer set 
                 customer_name = :customer_name,
                 where id = :id";
				 
		$params = array(
                            ":custome_name" => $_REQUEST["customer_name"],

		                    ":id" => $_REQUEST["id"]
                            
                       );
        pdo_query( $pdo, $query, $params );
		   		
   	}
	else
	{	
	    $query = "Update customer set 
                  customer_name = :customer_name
                  where id = :id";
	    $params = array(
                        ":customer_name" => $_REQUEST["customer_name"],

		                ":id" => $_REQUEST["id"] 
                     );
        pdo_query( $pdo, $query, $params );
	}
		   			
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