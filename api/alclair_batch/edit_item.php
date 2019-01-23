<?php
include_once "../../config.inc.php";
if(empty($_SESSION["UserId"]))
{
    return;
}
$response = array();
$response["code"] = "";
$response["message"] = "";
$response["data"] = null;


try
{	   
	$item = array();

	$item["batch_id"] = $_REQUEST["batch_id"];
	$item["designed_for"] = $_POST["designed_for"];
	//$item["ordered_by"] = $_POST["ordered_by"];		
	$item["impression_date"] = $_REQUEST["ImpressionDate"];
	$item["order_number"] = $_POST["order_number"];
	$item["same_name"] = $_POST["same_name"];
	$item["paid"] = $_POST["paid"];
	$item["customer_status"] = $_POST["customer_status"];
	$item["next_step_id"] = $_POST["next_step_id"];
	$item["notes"] = $_POST["notes"];		
	
	//if(!empty($_REQUEST["ShippedDate"])) {
	if($item["batch_status_id"] == 2) { // MEANS NOT SHIPPED/MAILED
		$item["shipped_date"] = $_REQUEST["ImpressionDate"];
		$item["shipped_by"] = $_SESSION["UserId"];
		//$params[":ShippedDate"]=$_REQUEST["ShippedDate"];
	} else {
		$item["shipped_date"] = NULL;
		$item["shipped_by"] = NULL;
	}
	
		
//////////////////////////////////////////////////////////////////////              BATCH          //////////////////////////////////////////////////////////////////////////
//for ($i = 0; $i < $num_of_sn; $i++) {
	
	//// INSERT INTO ORDER TRACKING THE SERIAL NUMBER AND LOG ID					
			/*		$stmt = pdo_query( $pdo, 
													"UPDATE batch_item_log SET designed_for=:designed_for, 
													ordered_by=:ordered_by, 
													impression_date=:impression_date, 
													order_number=:order_number, 
													paid=:paid, 
													same_name=:same_name, 
													customer_status=:customer_status, 
													next_step_id=:next_steps, 
													notes=:notes 
													WHERE id = :item_id",
			array(":designed_for"=>$item["designed_for"], ":ordered_by"=>$item["ordered_by"], 
					":impression_date"=>$item["impression_date"], ":order_number"=>$item["order_number"], ":paid"=>$item["paid"], ":same_name"=>$item["same_name"], ":customer_status"=>$item["customer_status"], ":next_steps"=>$item["next_step_id"], ":notes"=>$item["notes"], ":item_id"=>$_REQUEST["item_id"]));			
		*/			
$stmt = pdo_query( $pdo, 
													"UPDATE batch_item_log SET designed_for=:designed_for, 
													impression_date=:impression_date, 
													order_number=:order_number, 
													paid=:paid, 
													same_name=:same_name, 
													customer_status=:customer_status, 
													next_step_id=:next_steps, 
													notes=:notes 
													WHERE id = :item_id",
			array(":designed_for"=>$item["designed_for"],
					":impression_date"=>$item["impression_date"], ":order_number"=>$item["order_number"], ":paid"=>$item["paid"], ":same_name"=>$item["same_name"], ":customer_status"=>$item["customer_status"], ":next_steps"=>$item["next_step_id"], ":notes"=>$item["notes"], ":item_id"=>$_REQUEST["item_id"]));			
					

					
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//$response["test"] = $new[1];
	//$response["test2"] = $count;

	//$result = pdo_fetch_array($stmt);
	$response["code"]="success";
	//$response["data"] = $result;
	
	$response["test"] = "Type ID " . $item["designed_for"] . " DESIGNED  " . $_REQUEST["item_id"];
	//echo json_encode($response);
	//exit;//}     
	echo json_encode($response);
	//$response["test"] = $item["new"];
	
	//var_batch($result);
	
	//echo json_encode($response);
}
catch(PDOException $ex)
{
	$response["code"] = "error";
	$response["message"] = $ex->getMessage();
	echo json_encode($response);
}

?>