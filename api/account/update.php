<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{	   
    if(!empty($_POST["newpassword"]))
    {
        $password_sql=",password=:Password";
    }
	$stmt = "update auth_user set username=:username $password_sql,first_name=:FirstName,last_name=:LastName where id=:id";
    $params=array(":username"=>$_POST["username"],
        ":Password"=>md5($_POST["newpassword"]),
        ":FirstName"=>$_POST["first_name"],
        ":LastName"=>$_POST["last_name"],":id"=>$_POST["id"]);
	
	pdo_query($pdo,$stmt,$params);
	$response['code']='success';
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>