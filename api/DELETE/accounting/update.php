<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"])||empty($_SESSION["IsAdmin"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";

try
{	
	
	$stmt = pdo_query( $pdo,
						   'SELECT id, product_type_id FROM rate_sheet where id=:id',
							array(":id"=>$_REQUEST["account_code_id"])
						);	
		$result = pdo_fetch_array($stmt);
		$info=array();
        $info["product_type_id"]=$result["product_type_id"];

   /* if( check_accesstoken() == false )
    {
        $response['code'] = 'error';
        $response['message'] = 'Please specify access token';
        echo json_encode($response);
        exit;
    }
*/
	$pagingSql='';
    //if( $_REQUEST["id"] !='' && $_REQUEST["name"] !='')
    //{
	//    $parentid=null;
    //    if(!empty($_REQUEST["parentid"]))
    //    {
    //        $parentid=$_REQUEST["parentid"];
    //    }
    $query = "UPDATE customer_operator_account SET customer=:customer, operator_id=:operator_id, product_type_id=:product_type_id, account_code_id=:account_code_id where id=:id";
		//$query = "UPDATE customer_operator_account SET customer=:customer, operator_id=:operator_id, product_type_id=:product_type_id, account_code_id=:account_code_id where //id=:id";
		$params=array(":customer"=>$_REQUEST["customer"], ":operator_id"=>$_REQUEST["operator_id"], ":product_type_id"=>$info["product_type_id"], ":account_code_id"=>$_REQUEST["account_code_id"],
		   ":id"=>$_REQUEST["id"]);
		
		pdo_query($pdo,$query,$params);
		$response['code']='success';
		$response["message"] =" Update success!";
		$response["test"]=$result["product_type_id"];
		echo json_encode($response);
	//}
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>