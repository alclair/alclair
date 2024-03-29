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
       $response["testing"] = "Request Q is " . $_REQUEST["q"];
	   //echo json_encode($response);
	   //exit;
        $stmt = pdo_query( $pdo,
					   "SELECT DISTINCT * FROM qc_form_active_hp
                       where (customer_name::text ilike :search) AND active = TRUE
                       ORDER BY customer_name",
						$params
					 );	

        
        while($row=pdo_fetch_array($stmt))
        {
            $data=array();
            $data["id"]=$row["id"];
            $data["customer_name"]=$row["customer_name"];
            $response["data"][]=$data;
        }        
    }
	else
    {        
        $stmt = pdo_query( $pdo,
					   'SELECT * FROM qc_form_active_hp
                        WHERE id=:id',
						array(":id"=>$_REQUEST["id"])
					 );	
        $row=pdo_fetch_array($stmt);
        $response["data"]["id"]=$row["id"];
        $response["data"]["name"]=$row["customer_name"];
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