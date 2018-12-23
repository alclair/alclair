<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response['data'] = array();

try
{	
    if(empty($_REQUEST["id"]))
    {
        $params=array(":search"=>$_REQUEST["q"]."%");
       
        $stmt = pdo_query( $pdo,
					   "SELECT DISTINCT * FROM import_orders
                       where (designed_for::text ilike :search AND active = TRUE) 
                       ORDER BY designed_for",
						$params
					 );	
        
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["designed_for"]=$row["designed_for"];
            $response["data"][]=$data;
        }        
    }
	else
    {        
        $stmt = pdo_query( $pdo,
					   'SELECT * FROM import_orders
                        WHERE id=:id AND active = TRUE',
						array(":id"=>$_REQUEST["id"])
					 );	
        $row=pdo_fetch_array($stmt);
        $response["data"]["id"]=$row["id"];
        $response["data"]["name"]=$row["designed_for"];
    }
	$response['code']='success';	
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