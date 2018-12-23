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
		$query = "Insert into auth_user 
                  ( username, first_name, last_name, email, password,is_superuser,is_staff, is_customer, customer_id ) 
                  values
                  (:username, :first_name, :last_name, :email, :password,:is_superuser,:is_staff, :is_customer, :customer_id) RETURNING id";
		$params = array(
                        ":username" => $_REQUEST["username"],
		                ":first_name" => $_REQUEST["first_name"],
		                ":last_name" => $_REQUEST["last_name"],
		                ":email" => $_REQUEST["email"],
						":is_superuser"=>$_REQUEST["is_superuser"],
							":is_staff"=>$_REQUEST["is_staff"],
							":is_customer"=>$_REQUEST["is_customer"],
							":customer_id"=>$_REQUEST["customer_id"],
                        ":password" => md5($_REQUEST["password"]) 
                      );
        $stmt = pdo_query( $pdo, $query, $params );
   	}
	else
	{	
		$query = "Insert into auth_user 
                 ( username, first_name, last_name, email,is_superuser,is_staff, is_customer,customer_id )
                 values
                 ( :username, :first_name, :last_name, :email,:is_superuser,:is_staff, :is_customer, :customer_id) RETURNING id";
		$params = array(
                        ":username" => $_REQUEST["username"],
			            ":first_name" => $_REQUEST["first_name"],
			            ":last_name" => $_REQUEST["last_name"],
			            ":email" => $_REQUEST["email"],
						":is_superuser"=>$_REQUEST["is_superuser"],
							":is_staff"=>$_REQUEST["is_staff"],
							":is_customer"=>$_REQUEST["is_customer"],
							":customer_id"=>$_REQUEST["customer_id"],
                     );
					
		$stmt = pdo_query( $pdo, $query, $params );
	}
    $result = pdo_fetch_array($stmt);

	$response['code']='success';
	$response["message"] =" Add User success!";
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