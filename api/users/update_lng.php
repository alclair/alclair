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
    if( empty( $_REQUEST["id"] ) )
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
	 }
	 if(empty($_REQUEST["is_customer"])) 
	 {
		$_REQUEST["is_customer"]=0;
		$_REQUEST["customer_id"]=0;
	 }
	 else
	 {
		 $_REQUEST["is_customer"]=1;
	 }
	 
	if(!empty($_REQUEST["password"]))
    {			       
		$query = "Update auth_user set 
                 username = :username, first_name = :first_name,
                 last_name = :last_name, email = :email,
                 customer_id = :customer_id,
                 password = :password,is_superuser=:is_superuser,is_staff=:is_staff, is_customer=:is_customer where id = :id";
				 
		$params = array(
                            ":username" => $_REQUEST["username"],
		                    ":first_name" => $_REQUEST["first_name"],
		                    ":last_name" => $_REQUEST["last_name"],
		                    ":email" => $_REQUEST["email"],
							":is_superuser"=>$_REQUEST["is_superuser"],
							":is_staff"=>$_REQUEST["is_staff"],
							":is_customer"=>$_REQUEST["is_customer"],
							":customer_id"=>$_REQUEST["customer_id"],
		                    ":id" => $_REQUEST["id"],
                            ":password" => md5($_REQUEST["password"])
                       );
        pdo_query( $pdo, $query, $params );
		   		
   	}
	else
	{	
	    $query = "Update auth_user set 
                  username = :username, first_name = :first_name,
                  last_name = :last_name, email = :email,is_superuser=:is_superuser,is_staff=:is_staff, is_customer=:is_customer, customer_id=:customer_id where id = :id";
	    $params = array(
                        ":username" => $_REQUEST["username"],
		                ":first_name" => $_REQUEST["first_name"],
		                ":last_name" => $_REQUEST["last_name"],
		                ":email" => $_REQUEST["email"],
						":is_superuser"=>$_REQUEST["is_superuser"],
						":is_staff"=>$_REQUEST["is_staff"],
						":is_customer"=>$_REQUEST["is_customer"],
						":customer_id"=>$_REQUEST["customer_id"],
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