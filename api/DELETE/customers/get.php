<?php
include_once "../../config.inc.php";
if(empty($_SESSION["IsAdmin"]) && empty($_SESSION["IsManager"]))
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
					   'select id, customer_name from customer where id=:id',
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
                           'select id, customer_name from customer
                           where (customer_name ilike :SearchText )
                           order by customer',
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
                           'select id, customer_name from customer order by customer_name',
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