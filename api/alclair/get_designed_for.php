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
       /*
        $stmt = pdo_query( $pdo,
					   "SELECT DISTINCT * FROM import_orders
                       where (designed_for::text ilike :search AND active = TRUE) 
                       ORDER BY designed_for",
						$params
					 );	
		*/
		$stmt = pdo_query( $pdo,
					   "SELECT DISTINCT * FROM import_orders
                       where (designed_for::text ilike :search OR billing_name::text ilike :search OR shipping_name::text ilike :search) AND active = TRUE
                       ORDER BY designed_for, billing_name, shipping_name",
						$params
					 );	
        
        
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            if(strlen($row["designed_for"]) > 1) {
	            $data["designed_for"]=$row["designed_for"];
	        } elseif(strlen($row["billing_name"]) > 1) {
		        $data["designed_for"]=$row["billing_name"];
	        } else {
		        $data["designed_for"]=$row["shipping_name"];
	        }
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