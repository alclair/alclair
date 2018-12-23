<?php
include_once "../../config.inc.php";
if(empty($_SESSION["IsAdmin"]))
{
    return;
}

$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = null;

try
{
	if( !empty($_REQUEST['id']) )
	{
		$stmt = pdo_query( $pdo,
					   'select id, username, first_name,last_name,email,is_superuser,is_staff, is_customer, customer_id from auth_user where id=:id',
						array(":id"=>$_REQUEST['id'])
					 );	
		$result = pdo_fetch_array($stmt);
		$response['code']='success';
	    $response['data'] = $result;
	    $response['test2'] = count($result);
	}
    else if(!empty($_REQUEST["SearchText"]))
    {
        $stmt = pdo_query( $pdo,
                           'select id, username, first_name,last_name,email,is_superuser,is_staff, is_custoemr, customer_id from auth_user
                           where (username ilike :SearchText or first_name ilike :SearchText or last_name ilike :SearchText or email ilike :SearchText )
                           order by first_name',
                            array(":SearchText"=>"%".$_REQUEST["SearchText"]."%")
                            //,1
                         );	
        $result = pdo_fetch_all($stmt);
        $response['code']='success';
        $response['data'] = $result;
    }
    else
    {
        $stmt = pdo_query( $pdo,
                           'SELECT t1.id, t1.username, t1.first_name, t1.last_name, t1.email, t1.is_superuser, t1.is_staff, t1.is_customer, t1.customer_id, t2.customer_name
						   FROM auth_user as t1                           
						   LEFT JOIN customer as t2 on t1.customer_id = t2.id
                           ORDER BY t1.first_name',
                            null
                         );	
        $result = pdo_fetch_all($stmt);
        
        $response['code']='success';
        $response['data'] = $result;
    }
	
	//var_export($result);
	
	echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>