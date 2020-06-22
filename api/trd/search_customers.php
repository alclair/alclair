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
    if(empty($_REQUEST["id"])) {
		$params=array(":search"=>$_REQUEST["q"]."%");       
		$stmt = pdo_query( $pdo, "SELECT *, COALESCE(customer) AS customer FROM trd_customer AS t1
                       WHERE (t1.customer::text ilike :search) AND t1.active = TRUE
                       ORDER BY t1.customer", $params);	

        while($row=pdo_fetch_array($stmt)) {
            $data=array();
            $data["id"]=$row["id"];
            $data["customer"]=$row["customer"];
            $response["data"][]=$data;   
        }
    }
	else {        

        $stmt = pdo_query( $pdo, 'SELECT * FROM trd_customer AS t1 WHERE t1.id=:id AND t1.active = TRUE', array(":id"=>$_REQUEST["id"]));	
        $row=pdo_fetch_array($stmt);
        $response["data"]["id"]=$row["id"];
        $response["data"]["firstname"]=$row["firstname"];
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