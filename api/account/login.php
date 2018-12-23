<?php
include_once "../../config.inc.php";
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	if( empty( $username ) == true || empty( $password ) == true )
	{
		$response['message'] = 'Please input username and password.';
		echo json_encode($response);
		exit;
	}
	
	$stmt = pdo_query( $pdo, 
					   'select * from auth_user where username = :username and password = :password',
					   array(':username'=>$username,':password'=>md5($password)) 
					 );
	$row = pdo_fetch_array( $stmt );
	if( empty($row["id"]) )
	{
		$response['message'] = 'Invalid username and password';
		echo json_encode($response);
		exit;
	}
	$_SESSION["UserId"]=$row["id"];
    $_SESSION["UserName"]=$row["username"];
    $_SESSION["IsAdmin"]=$row["is_superuser"];
    $_SESSION["IsManager"]=$row["is_staff"];
    $_SESSION["Email"]=$row["email"];
	$response['code'] = 'success';
	$response['data'] = array(
                                "username" => $result['username'],
                                "firstname" => $result['first_name'],
                                "lastname" => $result['last_name'],
                                'email' => $result['email']
                                
                             );
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>