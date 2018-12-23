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
	$export = array();
	$export["quantity"] = $_POST["quantity"];
	$export["category_id"] = $_POST["category_id"];
	$export["product_id"] = $_POST["product_id"];
	
	//$log_id = $_POST["log_id"];
	$log_id = $_REQUEST["log_id"];
	
	//$serial_numbers = explode("\n",$export["sn"]);
	//$num_of_sn = count($serial_numbers);
	//$response["test1"] = $export["sn"];
	//$response["test1"]=$log_id;
    
//////////////////////////////////////////////////////////////////////              EXPORT          //////////////////////////////////////////////////////////////////////////
	for ($i = 0; $i < $export["quantity"]; $i++) {
		
		//// INSERT INTO  PRODUCT REQUESTED 
		$stmt = pdo_query( $pdo, "INSERT INTO product_requested (quantity, category_id, product_id, request_id, date_requested) VALUES (:quantity, :category_id, :product_id, :request_id, now()) RETURNING id",
			array(':quantity'=>$export["quantity"], ':category_id'=>$export["category_id"], ':product_id' =>$export['product_id'], ':request_id'=>$log_id));
						
	}     
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$response["test"] = $new[1];
	//$response["test2"] = $count;
	$response["test1"] = "Woohoo";
	
	$result = pdo_fetch_all($stmt);
	$response['code']='success';
	$response['data'] = $result;
	echo json_encode($response);
	//$response['test'] = $export["new"];
	
	//var_export($result);
	
	//echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>